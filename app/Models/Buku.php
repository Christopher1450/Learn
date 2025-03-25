<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';
    protected $primaryKey = 'id_buku'; 
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;
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
                $model->id_buku = (string) Str::uuid();
            });
        }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'id_buku');
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'buku_category', 'id_buku', 'category_id');
    }
}