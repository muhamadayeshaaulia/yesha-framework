<?php

namespace App\Controllers;

use Inti\Pemandangan; // Penting untuk memanggil kelas Pemandangan

class BerandaController {
    public function index() {
        $data = [
            'judul' => 'Framework Yesha Berhasil Terinstal!',
            'deskripsi' => 'Anda berhasil menjalankan halaman ini menggunakan komponen buatan Anda sendiri.'
        ];

        Pemandangan::tampilkan('beranda', $data);
    }
}