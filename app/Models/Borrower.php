<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrower extends Model
{
    
    public $timestamps = false;
    protected $fillable = ['name', 'date_of_birth'];
}