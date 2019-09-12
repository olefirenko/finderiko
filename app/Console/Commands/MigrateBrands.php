<?php

namespace App\Console\Commands;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Console\Command;

class MigrateBrands extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'brands:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate brands';

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
        // Product::where('brand_id', 0)->chunk(100, function ($products) {
        //     foreach ($products as $key => $product) {
        //         if (empty($product->brand_name)) {
        //             continue;
        //         }

        //         $brand = Brand::firstOrCreate([
        //             'name' => $product->brand_name,
        //         ]);

        //         $product->brand_id = $brand->id;
        //         $product->save();
        //     }
        // });

        Brand::where('count_products', '>=', 10)->chunk(100, function ($brands) {
            foreach ($brands as $key => $brand) {
                // $count_products = $brand->products()
                //           ->with('category')
                //           ->groupBy('name')
                //           ->get()
                //           ->count();
                // $brand->count_products = $count_products;
                // $brand->save();
                
                $sales_rank_total = $brand->products()
                          ->with('category')
                          ->groupBy('name')
                          ->get()
                          ->sum('sales_rank');

                $brand->sales_rank_total = $sales_rank_total / $brand->count_products;
                $brand->save();
            }
        });
    }
}
