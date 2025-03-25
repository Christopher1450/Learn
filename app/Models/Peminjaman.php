<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';
    protected $fillable = [
        'buku_id',
        'id',
        'tgl_pinjam',
        'tgl_kembali',
        'denda'
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id', 'id_buku');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }
}
