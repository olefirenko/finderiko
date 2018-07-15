<?php

namespace App\Http\Controllers;

use SEO;
use AmazonProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use ApaiIO\Operations\Search;
use App\Models\Category;

class IndexController extends Controller
{
    public function index()
    {
        //$response = AmazonProduct::browse('166588011');
        //$response = AmazonProduct::item('B073WJGD8V');

        //$this->parseAmazonNode(166588011, 1);
        // $this->searchAmazon('travel iron');
        // $this->searchAmazon('rc buggy');
        // $this->searchAmazon('coilovers');
        // $this->searchAmazon('combination square');
        // $this->searchAmazon('submersible well pump');

        $categories = Category::whereNull('parent_id')->get();
        $popular_categories = Category::where('parent_id', '!=', null)
                                      ->where('is_popular', 1)
                                      ->limit(12)
                                      ->get();

        return view('pages.main', compact('categories', 'popular_categories'));
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        
        if ($category->parent_id) {
            $products = Product::where('category_id', $category->id)->get();
            $step = $products->max('price') / 5;

            $related_categories = Category::where('parent_id', $category->parent_id)
                                          ->where('id', '!=', $category->id)
                                          ->inRandomOrder()
                                          ->limit(4)
                                          ->get();

            SEO::setTitle('Top 10 '.str_plural($category->name).' ('.date('F Y').')');
            SEO::setDescription('Finderiko analyzes and compares all '.str_plural($category->name).' of '.date('Y').'. You can easily compare and choose from the 10 best '.str_plural($category->name).' for you.');

            return view('pages.category', compact('category', 'products', 'step', 'related_categories'));
        } else {
            SEO::setTitle(str_plural($category->name));
            SEO::setDescription('Finderiko analyzes and compares all '.str_plural($category->name).' of '.date('Y').'. You can easily compare and choose from the best '.str_plural($category->name));

            return view('pages.parent_category', compact('category'));
        }
    }

    protected function searchAmazon($keyword, $department = 'All')
    {
        // search by keyword
        // add category with root parent_id from ancestors

        $category = Category::where('name', ucwords($keyword))->first();
        if ($category) {
            dd('Category already exists');
        }

        $search = new Search();
        $search->setCategory($department);
        $search->setKeywords($keyword);
        $search->setResponseGroup(['BrowseNodes', 'Images', 'ItemAttributes', 'Offers', 'SalesRank']);

        $results = AmazonProduct::run($search);

        if (array_get($results, 'Items.Request.IsValid')) {
            $category_id = 0;
            foreach (array_get($results, 'Items.Item') as $key => $product_data) {
                // add image and parent_id
                if ($key == 0) {
                    if (array_get($product_data, 'BrowseNodes.BrowseNode.0')) {
                        $node = array_get($product_data, 'BrowseNodes.BrowseNode.0');
                    } else {
                        $node = array_get($product_data, 'BrowseNodes.BrowseNode');
                    }
                    $parent = $this->findParentCategory($node);

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
                        $category->save();
                    }

                    $category_id = $category->id;
                }

                $this->parseAmazonProductArray($product_data, $category_id);
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
                $this->parseAmazonProductArray($product_data, $category_id);
            }
        }
    }

    protected function parseAmazonProductArray(array $product_data = [], int $category_id)
    {
        $product = new Product;
        $product->ASIN = array_get($product_data, 'ASIN');
        $product->amazon_link = array_get($product_data, 'DetailPageURL');
        $product->sales_rank = array_get($product_data, 'SalesRank');
        $product->image = array_get($product_data, 'LargeImage.URL');
        $product->brand = array_get($product_data, 'ItemAttributes.Brand');
        $product->name = array_get($product_data, 'ItemAttributes.Title');
        $product->minimum_age_month = array_get($product_data, 'ItemAttributes.ManufacturerMinimumAge');
        $product->weight = array_get($product_data, 'ItemAttributes.ItemDimensions.Weight');
        $product->dimensions = array_get($product_data, 'ItemAttributes.PackageDimensions.Length').' x '.array_get($product_data, 'ItemAttributes.PackageDimensions.Width').' x '.array_get($product_data, 'ItemAttributes.PackageDimensions.Height');
        $product->price = array_get($product_data, 'OfferSummary.LowestNewPrice.Amount');
        $product->category_id = $category_id;
        $product->save();
    }
}
