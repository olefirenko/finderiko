<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Deal extends Model
{
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getPriceRange($step)
    {
        $range = intval(max(floor($this->price) / $step, 1));

        $range_price = Str::repeat('$', $range);

        if ($range < 5) {
            $range_price .= '<span class="text-grey-100">'.Str::repeat('$', 5 - $range).'</span>';
        }

        return $range_price;
    }

    public function getShortNameAttribute()
    {
        $initial = Str::before($this->attributes['name'], ' -');
        $initial = Str::before($initial, ' –');
        return Str::words(Str::before($initial, ','), 10, '');
    }
}
