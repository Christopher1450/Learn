<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories'; // Nama tabel di database
    protected $primaryKey = 'id'; // id utama

    protected $fillable = ['name'];

    // 🔹 Relasi ke Buku (Satu kategori bisa punya banyak buku)
    public function books()
    {
        // return $this->hasMany(Buku::class, 'category_id');
        return $this->belongsToMany(Buku::class, 'buku_category', 'category_id', 'buku_id');
    }
}
