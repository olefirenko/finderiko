<?php

namespace App\Console\Commands;

use Log;
use Parser;
use Exception;
use App\Models\Keyword;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Console\Command;
use Revolution\Amazon\ProductAdvertising\Facades\AmazonProduct;
use App\Models\Brand;
use App\Models\ProductInfo;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use GuzzleHttp\Exception\RequestException;


class AmazonParser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'amazon:nodes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse amazon nodes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        // $node_id = $this->ask('What is the node id you are going to fetch?');

        // $this->parseNode($node_id);
        //$this->searchAmazon(Keyword::first());
        $is_update_categories = $this->ask('Update categories?');

        if ($is_update_categories) {
            Category::doesntHave('products')->chunk(200, function ($categories) {
                foreach ($categories as $category) {
                    // delete products
                    $this->searchCategory($category);
                    sleep(1);
                }
            });
        } else {
            $keywords = Keyword::all();
            foreach ($keywords as $keyword) {
                try {
                    $this->searchAmazon($keyword);
                } catch (Exception $exception) {
                    dd($exception->getMessage());
                }
                $keyword->delete($keyword);
                sleep(3);
            }
        }
    }

    protected function searchCategory(Category $category, $department = 'All')
    {
        $keyword = $category->name;

        $results = AmazonProduct::search($department, $keyword, 1);

        if ($results && isset($results["SearchResult"]) && count($results["SearchResult"]["Items"]) > 0) {
            // if no results
            // if (!Arr::get($results, 'Items.Item')) {
            //     Log::debug('No items for :'.$keyword);
            //     return false;
            // }

            foreach ($results["SearchResult"]["Items"] as $key => $product_data) {
                //dd($category->id);
                $this->parseAmazonProductArray($product_data, $category->id, $key + 1);
            }
        }
    }

    protected function searchAmazon(Keyword $keyword_object, $department = 'All')
    {
        $keyword = trim(str_replace('best', '', $keyword_object->name));
        // search by keyword
        // add category with root parent_id from ancestors

        $category = Category::where('name', ucwords($keyword))
            ->orWhere('name', 'like', '%' . $keyword . '%')
            ->orWhere('name', Str::plural(ucwords($keyword)))
            ->first();

        if ($category) {
            Log::debug('Category already exists: ' . $keyword);
            return false;
        }

        $results = AmazonProduct::search($department, $keyword, 1);

        if (!$results || !isset($results["SearchResult"]) || count($results["SearchResult"]["Items"]) === 0) {
            Log::debug('No items for :' . $keyword);
            return;
        }

        $category_id = 0;

        foreach ($results["SearchResult"]["Items"] as $key => $product_data) {
            // add image and parent_id
            if ($key == 0) {
                
                if (Arr::get($product_data, 'BrowseNodeInfo.BrowseNodes.0')) {
                    $node = Arr::get($product_data, 'BrowseNodeInfo.BrowseNodes.0');
                } else {
                    $node = Arr::get($product_data, 'BrowseNodeInfo.BrowseNode');
                }

                if (is_null($node)) {
                    Log::debug('No parent name for :' . $keyword);
                    return false;
                }

                $parent = $this->findParentCategory($node);

                if (!isset($parent['DisplayName']) || is_null($node)) {
                    Log::debug('No parent name for :' . $keyword);
                    return false;
                }

                $parent_category = Category::firstOrCreate([
                    'name' => $parent['DisplayName'],
                ]);

                if (!$category) {
                    // insert new category
                    $category = new Category;
                    $category->name = ucwords($keyword); // add Best if needed and make all first letters capital
                    $category->title = 'Best ' . ucwords($keyword);
                    $category->image = Arr::get($product_data, 'Images.Primary.Large.URL');
                    $category->parent_id = $parent_category->id;
                    //$category->total_results = Arr::get($results, 'Items.TotalResults');
                    //$category->ahrefs_difficulty = $keyword_object->difficulty;
                    //$category->ahrefs_volume = $keyword_object->volume;
                    $category->save();
                }

                $category_id = $category->id;
            }

            $this->parseAmazonProductArray($product_data, $category_id, $key + 1);
        }
    }

    protected function findParentCategory(array $node)
    {
        $browser_node = $node['Ancestor'];

        while (isset($browser_node['Ancestor'])) {
            $browser_node = $browser_node['Ancestor'];
        }

        return $browser_node;
    }

    protected function parseAmazonNode($node_id, $category_id, $department = 'Toys')
    {

        $response = AmazonProduct::browse($node_id);
        $nodes = Arr::get($response, 'BrowseNodes');
        $items = Arr::get($nodes, 'BrowseNode.TopSellers.TopSeller');
        $asins = array_pluck($items, 'ASIN');
        $results = AmazonProduct::items($asins);

        if (Arr::get($results, 'Items.Request.IsValid')) {
            foreach (Arr::get($results, 'Items.Item') as $key => $product_data) {
                $this->parseAmazonProductArray($product_data, $category_id, $key + 1);
            }
        }
    }

    protected function parseAmazonProductArray(array $product_data = [], int $category_id, $position = null)
    {
        $product = new Product;
        $product->ASIN = Arr::get($product_data, 'ASIN');
        $product->amazon_link = Arr::get($product_data, 'DetailPageURL');
        $product->sales_rank = Arr::get($product_data, 'BrowseNodeInfo.WebsiteSalesRank.SalesRank');
        $product->image = Arr::get($product_data, 'Images.Primary.Large.URL');
        $product->brand_name = Arr::get($product_data, 'ItemInfo.ByLineInfo.Brand.DisplayValue');
        $product->name = Arr::get($product_data, 'ItemInfo.Title.DisplayValue');
        // $product->minimum_age_month = Arr::get($product_data, 'ItemAttributes.ManufacturerMinimumAge');
        // $product->weight = Arr::get($product_data, 'ItemAttributes.ItemDimensions.Weight');
        // $product->dimensions = Arr::get($product_data, 'ItemAttributes.PackageDimensions.Length').' x '.Arr::get($product_data, 'ItemAttributes.PackageDimensions.Width').' x '.Arr::get($product_data, 'ItemAttributes.PackageDimensions.Height');
        $product->price = Arr::get($product_data, 'Offers.Summaries.0.LowestPrice.Amount');
        $product->position = $position;

        if ($product->brand_name) {
            $brand = Brand::firstOrCreate([
                'name' => $product->brand_name,
            ]);

            $product->brand_id = $brand->id;
        }

        $features = Arr::get($product_data, 'ItemInfo.Features.DisplayValues');
        $features_text = '';
        if (is_array($features)) {
            $features_text = '<ul>';
            foreach ($features as $key => $feature) {
                $features_text .= '<li>' . $feature . '</li>';
            }
            $features_text .= '</ul>';
        }

        $product->description = $features_text;
        $product->category_id = $category_id;
        $product->save();

        $product_infos = Arr::get($product_data, 'ItemInfo.ProductInfo');

        if (is_array($product_infos)) {
            foreach ($product_infos as $key => $product_info) {
                if (isset($product_info['Label']) && isset($product_info['DisplayValue'])) {
                    $product->product_infos()->create([
                        'label' => $product_info['Label'], 
                        'value' => Arr::get($product_info, 'DisplayValue'),
                        'locale' => Arr::get($product_info, 'Locale'),
                        'unit' => Arr::get($product_info, 'Unit'),
                    ]);
                } else {
                    foreach ($product_info as $key => $nested_product_info) {
                        if (isset($nested_product_info['Label']) && isset($nested_product_info['DisplayValue'])) {
                            $product->product_infos()->create([
                                'label' => $nested_product_info['Label'], 
                                'value' => Arr::get($nested_product_info, 'DisplayValue'),
                                'locale' => Arr::get($nested_product_info, 'Locale'),
                                'unit' => Arr::get($nested_product_info, 'Unit'),
                            ]);
                        }
                    }
                }
            }
        }

        $product_infos = Arr::get($product_data, 'ItemInfo.ManufactureInfo');
        if (is_array($product_infos)) {
            foreach ($product_infos as $key => $product_info) {
                if (isset($product_info['Label']) && isset($product_info['DisplayValue'])) {
                    $product->product_infos()->create([
                        'label' => $product_info['Label'], 
                        'value' => Arr::get($product_info, 'DisplayValue'),
                        'locale' => Arr::get($product_info, 'Locale'),
                        'unit' => Arr::get($product_info, 'Unit'),
                    ]);
                } else {
                    foreach ($product_info as $key => $nested_product_info) {
                        if (isset($nested_product_info['Label']) && isset($nested_product_info['DisplayValue'])) {
                            $product->product_infos()->create([
                                'label' => $nested_product_info['Label'], 
                                'value' => Arr::get($nested_product_info, 'DisplayValue'),
                                'locale' => Arr::get($nested_product_info, 'Locale'),
                                'unit' => Arr::get($nested_product_info, 'Unit'),
                            ]);
                        }
                    }
                }
            }
        }
    }

    public function parseNode($node_id)
    {
        $browseNodeLookup = new BrowseNodeLookup();
        $browseNodeLookup->setNodeId($node_id);

        try {
            $response = $this->apaiIO->runOperation($browseNodeLookup);

            $result = Parser::xml($response);

            $root = Category::firstOrCreate([
                'amazon_node_id' => $node_id,
                'name' => $result['BrowseNodes']['BrowseNode']['Name'],
            ]);

            if (!isset($result['BrowseNodes']['BrowseNode']['Children'])) {
                return;
            }

            foreach ($result['BrowseNodes']['BrowseNode']['Children']['BrowseNode'] as $key => $value) {
                if (Category::where('amazon_node_id', $value['BrowseNodeId'])->exists()) {
                    continue;
                }

                $root->children()->create([
                    'name' => $value['Name'],
                    'amazon_node_id' => $value['BrowseNodeId'],
                ]);
                sleep(1);
                $this->parseNode($value['BrowseNodeId']);
            }
        } catch (Exception $e) {
            Log::error($e . '. Node: ' . $node_id);
        }
    }
}
