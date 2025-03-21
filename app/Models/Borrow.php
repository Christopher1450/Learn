<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\belongsTo;
use Illuminate\Database\Eloquent\Model;
use App\Models\Book;

class Borrow extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'book_id', 'borrow_date', 'return_date'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}