<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookUnit extends Model
{
    protected $table = 'book_units';
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
        return $this->hasOne(Borrowing::class, 'kode_unit', 'kode_unit')->whereNull('returned_at'); // Problem di penamaan <--
    }
    public function borrowings()
    {
        return $this->hasMany(Borrowing::class, 'kode_unit', 'kode_unit');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeUnavailable($query)
    {
        return $query->where('status', 'unavailable');
    }

    public function scopeLost($query)
    {
        return $query->where('status', 'lost');
    }
    public function scopeDamaged($query)
    {
        return $query->where('status', 'damaged');
    }
}
