<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Borrowing extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'borrowings';
    protected $primaryKey = 'id_borrowing';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_borrowing',
        'id',
        'id_buku',
        'borrower_name',
        'borrower_dob',
        'borrow_date',
        'borrower_id',
        'return_date',
        'returned_at',
    ];
    

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku', 'id_buku');
    }


    // problem
    public function borrower()
    {
        return $this->belongsTo(Borrower::class, 'borrower_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }
    protected static function boot()
{
    parent::boot();

    static::creating(function ($model) {
        $last = self::orderBy('id_borrowing', 'desc')->first();

        if (!$last) {
            $model->id_borrowing = 'A001';
            return;
        }

        $lastId = $last->id_borrowing;

        // Pisah huruf & angka
        preg_match('/^([A-Z]+)(\d{3})$/', $lastId, $parts);

        $prefix = $parts[1];
        $number = (int) $parts[2];

        if ($number >= 999) {
            // Tambah prefix huruf
            $prefix = self::incrementLetters($prefix);
            $number = 1;
        } else {
            $number += 1;
        }

        $newId = $prefix . str_pad($number, 3, '0', STR_PAD_LEFT);
        $model->id_borrowing = $newId;
    });
}

    // increment A → B, Z → AA, AZ → BA
    protected static function incrementLetters($letters)
    {
        $length = strlen($letters);
        $result = '';
        $carry = true;

        for ($i = $length - 1; $i >= 0; $i--) {
            $char = $letters[$i];
            if ($carry) {
                if ($char === 'Z') {
                    $char = 'A';
                } else {
                    $char = chr(ord($char) + 1);
                    $carry = false;
                }
            }
            $result = $char . $result;
        }

        if ($carry) {
            $result = 'A' . $result;
        }

        return $result;
    }

    public function isReturned()
    {
        return !is_null($this->returned_at);
    }
}
