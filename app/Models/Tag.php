<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    protected $fillable = ['name','description'];
    protected $table = 'tags';

    public function article(){
        return $this->belongsToMany(Article::class);
    }

    public function getRouteKeyName(){
        return 'name';
    }
}
