<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Author extends Model
{
    use SoftDeletes;

    public function categories()
    {
        return $this->hasMany(Category::class, 'author_id');
    }

    public function getGravatarHashAttribute()
    {
        return md5(strtolower(trim($this->email)));
    }
}
