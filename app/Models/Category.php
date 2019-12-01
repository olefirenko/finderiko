<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Category extends Model
{
    use Sluggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
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

    /**
     * Parent Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function subcategories()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function deals()
    {
        return $this->hasMany(Deal::class)->whereNotNull('image')->groupBy('brand_id')->orderByDesc('percentage_saved')->take(10);
    }

    public static function findSimiliar($name, $exclude_id = null, $limit = 5, $parent_id = null)
    {
         $query = self::selectRaw('id, name, slug, image, MATCH (name) AGAINST (\''.str_singular(str_replace("'", '', $name)).'*\' IN BOOLEAN MODE) as score')
                    ->where('id', '!=', $exclude_id);

        if ($parent_id) {
            $query->where('parent_id', '=', $parent_id);
        }
        
        return $query->whereRaw('MATCH (name) AGAINST (\''.str_singular(str_replace("'", '', $name)).'*\' IN BOOLEAN MODE)')
            ->orderByDesc('score')
            ->limit($limit)
            ->get();
    }

    public function getTotalResultsAttribute($value)
    {
        if ($value > 1000) {
            return rand(50, 999);
        }

        return $value;
    }

    public function getShortTitleAttribute()
    {
        return str_replace('Best ', '', $this->title);
    }
}
