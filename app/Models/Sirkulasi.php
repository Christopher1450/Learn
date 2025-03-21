<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sirkulasi extends Model
{
    use HasFactory;

    protected $table = 'sirkulasis';  
    protected $primaryKey = 'id_sirkulasi';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = true;
    protected $fillable = [
        'id_buku',
        'anggota_id', 
        'tgl_pinjam', 
        'tgl_kembali', 
        'status'
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku', 'id_buku'); // foreign key , owner key(local key)
    }
    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'id_anggota', 'id_anggota'); // foreign key , owner key(local key)
    }
}