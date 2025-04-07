<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrower extends Model
{
    protected $table = 'borrowers';
    public $timestamps = false;
    protected $fillable = ['name', 'date_of_birth'];
}