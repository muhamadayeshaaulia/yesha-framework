<?php

namespace App\Models;

use Inti\BaseModel; // <-- Panggil BaseModel

class Tugas extends BaseModel {
    // Karena nama tabel kita 'tugas' (bukan 'tugass')
    // kita perlu menentukannya secara manual di sini.
    protected $table = 'tugas';
}