<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku'; // Pastikan tabel sesuai dengan database
    protected $primaryKey = 'id'; // id Prim
    public $incrementing = true;  // id tidak auto-increment (karena tipe string)
    protected $keyType = 'int'; // Pastikan kunci utama adalah string
    public $timestamps = true;
    public function sirkulasi()
{
    return $this->hasMany(Sirkulasi::class, 'id_buku');
}

    protected $fillable = [
        'judul_buku',
        'pengarang',
        'penerbit',
        'th_terbit',
        'stock',
        'category_id',
    ];

        protected static function boot()
        {
            parent::boot();
            static::creating(function ($model) {
                $model->id_buku = (string) Str::id();
            });
        }

    // protected static function boot()
    // {
    //     parent::boot();
    //     static::creating(function ($model) {
    //         $model->id_buku = Str::id();
    //     });
    // }
    // Jika ada relasi ke kategori atau peminjaman, tambahkan di sini:
    // public function category()
    // {
    //     return $this->belongsTo(Category::class, 'category_id'); // Sesuaikan nama kolom FK
    // }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'id_buku'); // Sesuaikan jika ada tabel peminjaman
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'buku_category', 'buku_id', 'category_id');
    }
    // public function category()
// {
//     return $this->belongsTo(Category::class, 'buku_category', 'buku_id', 'category_id');
// }
}
