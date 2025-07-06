<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

