<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;

    // Tambahkan baris ini untuk mengizinkan mass assignment pada kolom slug dan title
    protected $fillable = [
        'slug',
        'title'
    ];
}