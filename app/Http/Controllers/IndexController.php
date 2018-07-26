<?php

namespace App\Http\Controllers;

use SEO;
use AmazonProduct;
use App\Models\Keyword;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use ApaiIO\Operations\Search;

class IndexController extends Controller
{
    public function index()
    {
        $categories = Category::whereNull('parent_id')->where('is_popular', 1)->get();

        $popular_categories = Category::where('parent_id', '!=', null)
                                      ->where('is_popular', 1)
                                      ->limit(15)
                                      ->get();

        return view('pages.main', compact('categories', 'popular_categories'));
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        
        if ($category->parent_id) {
            $products = Product::where('category_id', $category->id)->get();
            $step = $products->max('price') / 5;

            $bests_for_money = $products->take(6);
            $bests_for_money->shift();
            $best_for_money = $bests_for_money->sortBy('price')->firstWhere('price', '>', 0);

            $related_categories = Category::where('parent_id', $category->parent_id)
                                          ->where('id', '!=', $category->id)
                                          ->inRandomOrder()
                                          ->limit(4)
                                          ->get();

            SEO::setTitle('Top 10 '.str_plural($category->name).' ('.date('F Y').')');
            SEO::setDescription('Finderiko analyzes and compares all '.str_plural($category->name).' of '.date('Y').'. You can easily compare and choose from the 10 best '.str_plural($category->name).' for you.');

            return view('pages.category', compact('category', 'products', 'step', 'related_categories', 'best_for_money'));
        } else {
            SEO::setTitle(str_plural($category->name));
            SEO::setDescription('Finderiko analyzes and compares all '.str_plural($category->name).' of '.date('Y').'. You can easily compare and choose from the best '.str_plural($category->name));

            return view('pages.parent_category', compact('category'));
        }
    }

    public function deleteCategory($id)
    {
        // Category::find($id)->delete();
        // Product::where('category_id', $id)->delete();

        // echo "Deleted";
    }
}
