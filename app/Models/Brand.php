<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Brand extends Model
{
    use Sluggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function products()
    {
        return $this->hasMany(Product::class)
                    // ->whereNotNull('sales_rank')
                    ->whereHas('category', function ($query) {
                        $query->whereNotNull('parent_id');
                    })
                    ->orderBy('sales_rank');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'products')->groupBy('name');
    }

    public function parent_categories()
    {
        return $this->belongsToMany(Category::class, 'products')->whereNull('parent_id');
    }

    public function scopeShouldBeShown(Builder $builder)
    {
        $builder->where('count_products', '>=', 10);
    }
}
