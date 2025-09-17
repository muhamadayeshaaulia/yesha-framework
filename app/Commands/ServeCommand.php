<?php

namespace App\Commands;

class ServeCommand {
    // Nama perintah yang akan dipanggil dari terminal
    public $nama = 'serve';
    
    // Deskripsi singkat
    public $deskripsi = 'Menjalankan server pengembangan PHP untuk Yesha.';

    // Logika utama perintah
    public function handle() {
        $host = 'localhost';
        $port = 8000;
        echo "🚀 Yesha development server dimulai pada http://{$host}:{$port}\n";
        echo "Tekan Ctrl+C untuk menghentikan server.\n";
        exec("php -S {$host}:{$port} -t public");
    }
}