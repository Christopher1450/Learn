<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;


class Book extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'author', 'release_at', 'category_id', 'status'];
}