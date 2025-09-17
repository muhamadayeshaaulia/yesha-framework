<?php

namespace App\Commands;

class BuatControllerCommand {
    public $nama = 'buat:controller';
    public $deskripsi = 'Membuat file Controller baru di app/Controllers.';

    public function handle($argumen) {
        $namaController = $argumen[0] ?? null;

        if (!$namaController) {
            die("Error: Mohon sertakan nama Controller. Contoh: php yesha buat:controller NamaController\n");
        }
        
        $path = getcwd() . "/app/Controllers/{$namaController}.php";
        if (file_exists($path)) {
            die("Error: Controller {$namaController} sudah ada.\n");
        }
        
        $template = <<<EOT
<?php
namespace App\Controllers;
use Inti\Pemandangan;
class {$namaController} {
    public function index() {
        return "Controller {$namaController} berhasil dibuat.";
    }
}
EOT;
        
        file_put_contents($path, $template);
        echo "✅ Controller berhasil dibuat di: {$path}\n";
    }
}