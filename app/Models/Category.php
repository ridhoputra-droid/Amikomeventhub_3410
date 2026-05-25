<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // Tetap seperti ini, sudah aman!
    protected $fillable = ['name', 'slug', 'image'];

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}