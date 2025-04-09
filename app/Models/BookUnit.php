<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookUnit extends Model
{
    protected $table = 'book_units'; // pastikan nama tabel benar
    protected $primaryKey = 'kode_unit';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = [
        'kode_unit',
        'id_buku',
        'status',
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku', 'id_buku');
    }

    public function borrowing()
    {
        return $this->hasOne(Borrowing::class, 'kode_unit', 'id')->whereNull('returned_at');
    }
    public function borrowings()
    {
        return $this->hasMany(Borrowing::class, 'kode_unit');
    }
}
