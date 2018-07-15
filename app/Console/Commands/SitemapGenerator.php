<?php

namespace App\Console\Commands;

use SitemapPHP\Sitemap;
use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class SitemapGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Sitemap XML';

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
        ini_set('memory_limit', '-1');

        $categories = Category::all();

        $sitemap = new Sitemap('');
        $sitemap->setPath(public_path('/'));
        $sitemap->setFilename('sitemap');

        foreach ($categories as $key => $category) {
            $sitemap->addItem(route('category', $category->slug));
        }

        $sitemap->createSitemapIndex(config('app.url'));
    }
}
