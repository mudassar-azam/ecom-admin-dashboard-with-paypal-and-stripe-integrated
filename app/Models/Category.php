<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'image',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
