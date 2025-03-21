<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;

    protected $table = 'anggota';
    protected $primaryKey = 'id_anggota';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = 
    [
        'id_anggota',
        'nama',
        'kelas',
        'no_hp'
    ];
    public function sirkulasi()
    {
        return $this->hasMany(Sirkulasi::class, 'id_anggota', 'id_anggota'); // foreign key , owner key(local key)
    }
}