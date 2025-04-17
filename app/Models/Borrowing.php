<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Borrowing extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'borrowings';
    protected $primaryKey = 'id_borrowing';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'id_buku',
        'kode_unit',
        'borrower_name',
        'borrower_id',
        'borrow_date',
        'return_date',
        'jenis_jaminan',
        'jumlah_jaminan',
        'bukti_jaminan',
        'bukti_pengembalian',
        'bukti_pembayaran',
        'fee',
        'penalty',
        'pengembalian_jaminan',
    ];
    
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku', 'id_buku');
    }
    public function unit()
    {
        return $this->belongsTo(\App\Models\BookUnit::class, 'kode_unit','kode_unit');
    }

    public function borrowing()
    {
        return $this->hasOne(Borrowing::class, 'kode_unit', 'kode_unit');
    }
    public function hitungFee()
    {
        return Carbon::parse($this->borrow_date)->diffInDays($this->returned_at ?? now()) * 10000;
    }

    public function hitungDenda()
    {
        $selisih = Carbon::parse($this->return_date)->diffInDays($this->returned_at ?? now(), false);
        return $selisih > 0 ? $selisih * 5000 : 0;
    }

    public function hitungUangKembali()
    {
        if ($this->jenis_jaminan !== 'uang') return 0;

        $total = $this->hitungFee() + $this->hitungDenda();
        return max(0, $this->jumlah_jaminan - $total);
    }

    public function hitungPengembalianJaminan()
    {
        if ($this->jenis_jaminan !== 'uang') {
            return 0;
        }

        $fee = $this->fee ?? 0;
        $penalty = $this->penalty ?? 0;
        $totalPotongan = $fee + $penalty;
        $sisa = $this->jumlah_jaminan - $totalPotongan;

        return max($sisa, 0);
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
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $last = self::orderBy('id_borrowing', 'desc')->first();
            // buat def mulai dr A001
            if (!$last) {
                $model->id_borrowing = 'A001';
                return;
            }

            $lastId = $last->id_borrowing;

            // Pisah huruf & angka
            preg_match('/^([A-Z]+)(\d{3})$/', $lastId, $parts);

            $prefix = $parts[1];
            $number = (int) $parts[2];
                // Hitungan 3 digit
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

    // increment A → B, Z → AA, AZ → BA ga gt kepake (buat cosmetic doang sih)
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
