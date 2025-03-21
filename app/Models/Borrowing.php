<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Borrowing extends Model
{
    use HasFactory;

    protected $table = 'borrowings';
    protected $primaryKey = 'id_borrowing';
    public $incrementing = false; 
    protected $keyType = 'string'; 

    protected $fillable = [
        'id_borrowing',
        'id',
        'id_buku',
        'borrow_date',
        'return_date',
        'returned_at'
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku', 'id_buku');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }
}
