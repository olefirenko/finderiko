<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductInfo extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'label',
        'value',
        'locale',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
