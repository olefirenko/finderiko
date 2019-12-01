<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use ApaiIO\Configuration\GenericConfiguration;
use ApaiIO\ApaiIO;
use App\Models\Category;
use App\Models\Deal;
use ApaiIO\Operations\Search;
use Revolution\Amazon\ProductAdvertising\Facades\AmazonProduct;
use Illuminate\Support\Facades\Log;
use App\Models\Brand;

class DealsParser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deals:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the deals from Amazon';

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
        Deal::truncate();
        
        $root_categories = Category::where('parent_id', null)->whereNotNull('search_index')->get();

        foreach ($root_categories as $category) {
            for ($i = 1; $i <= 3; $i++) {
                $this->searchCategory($category, $i);
                sleep(1);
            }
        }
    }

    protected function searchCategory(Category $category, $page = 1)
    {
        $keyword = $category->name;
            
        $search = new Search();
        $search->setCategory($category->search_index);
        $search->setBrowseNode($category->amazon_node_id);
        $search->setMinPercentageOff(33);
        $search->setCondition('New');
        $search->setPage($page);
        $search->setResponseGroup(['BrowseNodes', 'PromotionSummary', 'Images', 'ItemAttributes', 'Offers', 'SalesRank']);

        $results = AmazonProduct::run($search);

        if (array_get($results, 'Items.Request.IsValid')) {
            // if no results
            if (!array_get($results, 'Items.Item')) {
                Log::debug('No items for :'.$keyword);
                return false;
            }

            foreach (array_get($results, 'Items.Item') as $key => $product_data) {
                //dd($category->id);
                $this->parseAmazonDealArray($product_data, $category->id, $key + 1);
            }
        }
    }

    protected function parseAmazonDealArray(array $product_data = [], int $category_id, $position = null)
    {
        $product = new Deal();
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
        $product->amount_saved = array_get($product_data, 'Offers.Offer.OfferListing.AmountSaved.Amount');
        $product->percentage_saved = array_get($product_data, 'Offers.Offer.OfferListing.PercentageSaved');
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
}
