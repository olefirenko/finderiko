<?php

namespace App\Http\Controllers;

use SEO;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Str;
use Revolution\Amazon\ProductAdvertising\Facades\AmazonProduct;

class IndexController extends Controller
{
    public function index()
    {
        $results = AmazonProduct::search("All", "Toys & Games" , 1);
        dd($results);
        $categories = Category::whereNull('parent_id')->where('is_popular', 1)->get();

        $brands = Brand::whereNotNull('sales_rank_total')
                        ->has('category')
                        ->where('logo', '!=', '')
                        ->orderBy('sales_rank_total')
                        ->limit(10)
                        ->get();

        $popular_categories = Category::where('parent_id', '!=', null)
                                      //->where('is_popular', 1)
                                      ->latest()
                                      ->limit(15)
                                      ->get();

        return view('pages.main', compact('categories', 'popular_categories', 'brands'));
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        if ($category->parent_id) {
            $products = Product::with('brand')->where('category_id', $category->id)->get();
            $step = $products->max('price') / 5;

            $premium = $products->whereNotIn('id', [$products->first()->id])->sortByDesc('price')->firstWhere('price', '>', 0);

            $bests_for_money = $products->take(6);
            $bests_for_money->shift();
            $best_for_money = $bests_for_money->sortBy('price')->firstWhere('price', '>', 0);

            // best unders
            $under_prices = [100000, 50000, 20000, 15000, 10000, 5000]; // in cents
            $min = $products->min('price');
            $max = $products->max('price');
            $under_products = [];

            foreach ($under_prices as $key => $item) {
                if (
                    $min <= $item && 
                    $item <= $max &&
                    $products
                            ->whereNotIn('id', [$best_for_money->id, $products->first()->id, $premium->id])
                            ->where('price', '<=', $item)
                            ->where('price', '!=', null)
                            ->first()
                ) {
                    $under_products[
                        $products
                            ->whereNotIn('id', [$best_for_money->id, $products->first()->id, $premium->id])
                            ->where('price', '<=', $item)
                            ->where('price', '!=', null)
                            ->first()
                            ->id
                    ] = $item / 100;
                 }
            }

            $related_categories = Category::findSimiliar($category->name, $category->id, 5, $category->parent_id);
            if (!$related_categories->count()) {
                $related_categories = Category::where('parent_id', $category->parent_id)->inRandomOrder()->limit(5)->get();
            }

            SEO::setTitle('Top 10 '.Str::plural($category->name).' ('.date('F Y').')');
            SEO::setDescription('Finderiko analyzes and compares all '.Str::plural($category->name).' of '.date('Y').'. You can easily compare and choose from the 10 best '.Str::plural($category->name).' for you.');

            return view('pages.category', compact('category', 'products', 'step', 'related_categories', 'best_for_money', 'under_products', 'premium'));
        } else {
            SEO::setTitle(Str::plural($category->name));
            SEO::setDescription('Finderiko analyzes and compares all '.Str::plural($category->name).' of '.date('Y').'. You can easily compare and choose from the best '.Str::plural($category->name));

            return view('pages.parent_category', compact('category'));
        }
    }

    public function brands()
    {
        $brands = Brand::whereNotNull('sales_rank_total')
                        ->has('category')
                        ->orderBy('sales_rank_total')
                        ->limit(100)
                        ->get();

        SEO::setTitle('Top 100 Best Brands ('.date('F Y').')');
        SEO::setDescription('Finderiko analyzes and compares all brands to determine which one are the best');

        return view('pages.brands', compact('brands'));
    }

    public function brand($slug)
    {
        $brand = Brand::shouldBeShown()->where('slug', $slug)->firstOrFail();
        $products = $brand->products()
                          ->with('category')
                          ->limit(48)
                          ->groupBy('name')
                          ->get();

        $categories = $brand->categories()->limit(6)->get();

        SEO::setTitle('Best '.$brand->name.' Products ('.date('F Y').')');
        SEO::setDescription('Finderiko analyzes and compares all '.$brand->name.' products of '.date('Y').'. You can easily compare and choose from the best '.$brand->name.' products.');

        return view('pages.brand', compact('brand', 'products', 'categories'));
    }

    public function search()
    {
        $categories = Category::findSimiliar(request('query'), null, 100);

        return view('pages.search', compact('categories'));
    }

    public function deleteCategory($id)
    {
        Category::find($id)->delete();
        Product::where('category_id', $id)->delete();

        echo "Deleted $id";
    }

    public function check_duplicates()
    {
        // ini_set('memory_limit', '-1');
        $categories = Category::where('id', '>', 0)->limit(2000)->get();
        $duplicates = [];

        foreach ($categories as $key => $category) {
            $similiar = Category::findSimiliar($category->name, $category->id, 5);
            
            if ($similiar->count()) {
                echo '<h3>#'.$category->id.' '.$category->name.' <a href="/delete/'.$category->id.'" target="_blank">delete</a></h3>';
                foreach ($similiar as $item) {
                    $parts = explode(' ', $category->name);
                    $name = $item->name;
                    foreach ($parts as $part) {
                        if (
                            stripos($name, $part) !== false || 
                            stripos($name, Str::singular($part)) !== false || 
                            stripos($name, Str::plural($part)) !== false
                        ) {
                            $name = trim(Str::ireplace([$part, Str::singular($part), Str::plural($part), 'for', 's'], '', $name));
                        }
                    }
                    if (empty($name) && count($parts) == count(explode(' ', $item->name))) {
                        echo $category->id.' deletedddd<br/>';
                        //$this->deleteCategory($category->id);
                        //break;
                    }

                        echo '<i style="color: '.($item->id > 16932 ? 'green' : 'red').'">#'.$item->id.' '.
                        $name
                        .' '.$item->score.'</i> <a href="/delete/'.$item->id.'" target="_blank">delete</a>'.
                        $item->name.'<br/>'; 
                }
            }
            
        }
    }
}
