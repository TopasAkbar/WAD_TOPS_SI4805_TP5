<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $table = 'books'; // Define the table name
    protected $fillable = [
        'id',
        'title',
        'author',
        'published_year',
        'is_available',
        'created_at',
        'updated_at',

    ];
}
