<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class DealsController extends Controller
{
    public function index()
    {
        $categories = Category::where('parent_id', null)->whereNotNull('search_index')->get();
        
        return view('pages.deals', [
            'categories' => $categories,
        ]);
    }
}
