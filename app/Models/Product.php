<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
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
        $initial = str_before($this->attributes['name'], ' -');
        $initial = str_before($initial, ' –');
        return Str::words(str_before($initial, ','), 10, '');
    }
}
