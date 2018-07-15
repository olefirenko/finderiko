<?php

namespace App\Http\Controllers;

use SEO;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function show($slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();

        SEO::setTitle($article->name);
        SEO::setDescription($article->description);

        return view('articles.show', compact('article'));
    }
}
