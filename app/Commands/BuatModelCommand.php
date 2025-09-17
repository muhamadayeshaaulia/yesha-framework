<?php
namespace App\Commands;

class BuatModelCommand {
    public $nama = 'buat:model';
    public $deskripsi = 'Membuat file Model baru di app/Models.';

    public function handle($argumen) {
        // Ambil nama Model dari argumen pertama
        $namaModel = $argumen[0] ?? null;

        // Validasi jika nama Model tidak diberikan
        if (!$namaModel) {
            die("Error: Mohon sertakan nama Model. Contoh: php yesha buat:model Produk\n");
        }

        // Tentukan path dan nama file baru
        $path = getcwd() . "/app/Models/{$namaModel}.php";

        // Cek apakah file Model sudah ada
        if (file_exists($path)) {
            die("Error: Model {$namaModel} sudah ada.\n");
        }

        // Buat folder Models jika belum ada
        if (!is_dir(getcwd() . "/app/Models")) {
            mkdir(getcwd() . "/app/Models", 0755, true);
        }

        // Ini adalah template (stub) untuk file Model baru
        $template = <<<EOT
<?php

namespace App\Models;

use Inti\BaseModel;

class {$namaModel} extends BaseModel {
    /**
     * Nama tabel database yang terhubung dengan Model ini.
     *
     * @var string
     */
    // protected \$table = 'nama_tabel_kustom';
}
EOT;

        // Tulis template ke dalam file baru
        file_put_contents($path, $template);

        echo "✅ Model berhasil dibuat di: {$path}\n";
    }
}