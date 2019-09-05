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
                                      //->where('is_popular', 1)
                                      ->latest()
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

            $related_categories = Category::findSimiliar($category->name, $category->id, 5, $category->parent_id);
            if (!$related_categories->count()) {
                $related_categories = Category::where('parent_id', $category->parent_id)->inRandomOrder()->limit(5)->get();
            }

            SEO::setTitle('Top 10 '.str_plural($category->name).' ('.date('F Y').')');
            SEO::setDescription('Finderiko analyzes and compares all '.str_plural($category->name).' of '.date('Y').'. You can easily compare and choose from the 10 best '.str_plural($category->name).' for you.');

            return view('pages.category', compact('category', 'products', 'step', 'related_categories', 'best_for_money'));
        } else {
            SEO::setTitle(str_plural($category->name));
            SEO::setDescription('Finderiko analyzes and compares all '.str_plural($category->name).' of '.date('Y').'. You can easily compare and choose from the best '.str_plural($category->name));

            return view('pages.parent_category', compact('category'));
        }
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
                            stripos($name, str_singular($part)) !== false || 
                            stripos($name, str_plural($part)) !== false
                        ) {
                            $name = trim(str_ireplace([$part, str_singular($part), str_plural($part), 'for', 's'], '', $name));
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
