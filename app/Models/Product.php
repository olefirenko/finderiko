<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function product_infos()
    {
        return $this->hasMany(ProductInfo::class);
    }

    public function getPriceRange($step)
    {
        $range = intval(max(floor($this->price) / $step, 1));

        $range_price = str_repeat('$', $range);

        if ($range < 5) {
            $range_price .= '<span class="text-grey-100">'.str_repeat('$', 5 - $range).'</span>';
        }

        return $range_price;
    }

    public function getShortNameAttribute()
    {
        $initial = Str::before($this->attributes['name'], ' -');
        $initial = Str::before($initial, ' –');
        return Str::words(Str::before($initial, ','), 10, '');
    }

    public function getLinkAttribute()
    {
        return "/redirect?rf_item=".$this->id."?rf_list_id=".$this->category_id."&amp;rf_source=amazon&amp;url=".sha1($this->name);
    }
}
