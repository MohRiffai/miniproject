<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Article extends Model
{
    use HasFactory;
    use Sluggable;
    protected $fillable = ['tittle','slug', 'category_id', 'content'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    protected $table = 'articles';

    public function getRouteKeyName(){
        return 'slug';
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
}
