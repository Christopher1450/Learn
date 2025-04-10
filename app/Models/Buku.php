<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\BookUnit;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';
    protected $primaryKey = 'id_buku'; 
    public $incrementing = false;
    protected $keyType = 'int';
    public $timestamps = false;
    public function sirkulasi()
{
    return $this->hasMany(Sirkulasi::class, 'id_buku');
}

    protected $fillable = [
        'kode_unit',
        'borrower_name',
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
                $model->id_buku = (string) Str::random(10);
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
        return $this->hasMany(Peminjaman::class, 'id_buku');
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'buku_category', 'id_buku', 'category_id');
    }
    public function units() {
        return $this->hasMany(BookUnit::class, 'id_buku', 'id_buku');
    }
    
}