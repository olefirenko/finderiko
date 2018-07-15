<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function getPriceRange($step)
    {
        $range = max(floor($this->price) / $step, 1);

        return str_repeat('$', $range);
    }
}
