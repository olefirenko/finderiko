<?php

namespace App\Console\Commands;

use Log;
use Parser;
use Exception;
use ApaiIO\ApaiIO;
use App\Models\Keyword;
use App\Models\Product;
use App\Models\Category;
use ApaiIO\Operations\Search;
use Illuminate\Console\Command;
use ApaiIO\Operations\BrowseNodeLookup;
use ApaiIO\Configuration\GenericConfiguration;
use Revolution\Amazon\ProductAdvertising\Facades\AmazonProduct;
use App\Models\Brand;

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

        $conf = new GenericConfiguration();
        $client = new \GuzzleHttp\Client();
        $request = new \ApaiIO\Request\GuzzleRequest($client);

        $conf
            ->setCountry('com')
            ->setAccessKey(env('AWS_API_KEY'))
            ->setSecretKey(env('AWS_API_SECRET_KEY'))
            ->setAssociateTag(env('AWS_ASSOCIATE_TAG'))
            ->setRequest($request);
        $this->apaiIO = new ApaiIO($conf);
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
                $this->searchAmazon($keyword);
                $keyword->delete($keyword);
                sleep(3);
            }
        }
    }

    protected function searchCategory(Category $category, $department = 'All')
    {
        $keyword = $category->name;
        
        $search = new Search();
        $search->setCategory($department);
        $search->setKeywords($keyword);
        $search->setResponseGroup(['BrowseNodes', 'Images', 'ItemAttributes', 'Offers', 'SalesRank']);

        $results = AmazonProduct::run($search);
        
        if (array_get($results, 'Items.Request.IsValid')) {
            // if no results
            if (!array_get($results, 'Items.Item')) {
                Log::debug('No items for :'.$keyword);
                return false;
            }

            foreach (array_get($results, 'Items.Item') as $key => $product_data) {
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
                            ->orWhere('name', 'like', '%'.$keyword.'%')
                            ->orWhere('name', str_plural(ucwords($keyword)))
                            ->first();

        if ($category) {
            Log::debug('Category already exists: '.$keyword);
            return false;
        }

        $search = new Search();
        $search->setCategory($department);
        $search->setKeywords($keyword);
        $search->setResponseGroup(['BrowseNodes', 'Images', 'ItemAttributes', 'Offers', 'SalesRank']);

        $results = AmazonProduct::run($search);
        
        if (array_get($results, 'Items.Request.IsValid')) {
            $category_id = 0;

            // if no results
            if (!array_get($results, 'Items.Item')) {
                Log::debug('No items for :'.$keyword);
                return false;
            }

            foreach (array_get($results, 'Items.Item') as $key => $product_data) {
                // add image and parent_id
                if ($key == 0) {
                    if (array_get($product_data, 'BrowseNodes.BrowseNode.0')) {
                        $node = array_get($product_data, 'BrowseNodes.BrowseNode.0');
                    } else {
                        $node = array_get($product_data, 'BrowseNodes.BrowseNode');
                    }

                    if (is_null($node)) {
                        Log::debug('No parent name for :'.$keyword);
                        return false;
                    }

                    $parent = $this->findParentCategory($node);

                    if (!isset($parent['Name']) || is_null($node)) {
                        Log::debug('No parent name for :'.$keyword);
                        return false;
                    }

                    $parent_category = Category::firstOrCreate([
                        'name' => $parent['Name'],
                    ]);

                    if (!$category) {
                        // insert new category
                        $category = new Category;
                        $category->name = ucwords($keyword); // add Best if needed and make all first letters capital
                        $category->title = 'Best '.ucwords($keyword);
                        $category->image = array_get($product_data, 'LargeImage.URL');
                        $category->parent_id = $parent_category->id;
                        $category->total_results = array_get($results, 'Items.TotalResults');
                        $category->ahrefs_difficulty = $keyword_object->difficulty;
                        $category->ahrefs_volume = $keyword_object->volume;
                        $category->save();
                    }

                    $category_id = $category->id;
                }

                $this->parseAmazonProductArray($product_data, $category_id, $key + 1);
            }
        }
    }

    protected function findParentCategory(array $node)
    {
        $browser_node = $node['Ancestors']['BrowseNode'];

        while (isset($browser_node['Ancestors'])) {
            $browser_node = $browser_node['Ancestors']['BrowseNode'];
        }

        return $browser_node;
    }

    protected function parseAmazonNode($node_id, $category_id, $department = 'Toys')
    {

        $response = AmazonProduct::browse($node_id);
        $nodes = array_get($response, 'BrowseNodes');
        $items = array_get($nodes, 'BrowseNode.TopSellers.TopSeller');
        $asins = array_pluck($items, 'ASIN');
        $results = AmazonProduct::items($asins);

        if (array_get($results, 'Items.Request.IsValid')) {
            foreach (array_get($results, 'Items.Item') as $key => $product_data) {
                $this->parseAmazonProductArray($product_data, $category_id, $key + 1);
            }
        }
    }

    protected function parseAmazonProductArray(array $product_data = [], int $category_id, $position = null)
    {
        $product = new Product;
        $product->ASIN = array_get($product_data, 'ASIN');
        $product->amazon_link = array_get($product_data, 'DetailPageURL');
        $product->sales_rank = array_get($product_data, 'SalesRank');
        $product->image = array_get($product_data, 'LargeImage.URL');
        $product->brand_name = array_get($product_data, 'ItemAttributes.Brand');
        $product->name = array_get($product_data, 'ItemAttributes.Title');
        $product->minimum_age_month = array_get($product_data, 'ItemAttributes.ManufacturerMinimumAge');
        $product->weight = array_get($product_data, 'ItemAttributes.ItemDimensions.Weight');
        $product->dimensions = array_get($product_data, 'ItemAttributes.PackageDimensions.Length').' x '.array_get($product_data, 'ItemAttributes.PackageDimensions.Width').' x '.array_get($product_data, 'ItemAttributes.PackageDimensions.Height');
        $product->price = array_get($product_data, 'OfferSummary.LowestNewPrice.Amount');
        $product->position = $position;

        if ($product->brand_name) {
            $brand = Brand::firstOrCreate([
                'name' => $product->brand_name,
            ]);
    
            $product->brand_id = $brand->id;
        }
        
        $features = array_get($product_data, 'ItemAttributes.Feature');
        $features_text = '';
        if (is_array($features)) {
            $features_text = '<ul>';
            foreach ($features as $key => $feature) {
                $features_text .= '<li>'.$feature.'</li>';
            }
            $features_text .= '</ul>';
        }

        $product->description = $features_text;
        $product->category_id = $category_id;
        $product->save();
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

            if( !isset($result['BrowseNodes']['BrowseNode']['Children'])) {
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
            Log::error($e.'. Node: '.$node_id);
        }

    }
}
