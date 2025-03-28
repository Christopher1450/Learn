<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('generateBorrowingId')) {
    function generateBorrowingId()
    {
        $last = DB::table('borrowings')->orderBy('id_borrowing', 'desc')->first();

        if (!$last) return 'A001';

        $prefix = substr($last->id_borrowing, 0, 1);
        $number = intval(substr($last->id_borrowing, 1)) + 1;

        if ($number > 999) {
            $prefix++;
            $number = 1;
        }

        return $prefix . str_pad($number, 3, '0', STR_PAD_LEFT);
    }
}
