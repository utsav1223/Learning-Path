<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'description',
        'difficulty_level',
        'estimated_hours',
        'thumbnail',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
