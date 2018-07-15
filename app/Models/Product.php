<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function getPriceRange($step)
    {
        $range = intval(max(floor($this->price) / $step, 1));

        $range_price = str_repeat('$', $range);

        if ($range < 5) {
            $range_price .= '<span class="text-grey-100">'.str_repeat('$', 5 - $range).'</span';
        }

        return $range_price;
    }
}
