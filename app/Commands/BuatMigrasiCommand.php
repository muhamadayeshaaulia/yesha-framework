<?php
namespace App\Commands;

class BuatMigrasiCommand {
    public $nama = 'buat:migrasi';
    public $deskripsi = 'Membuat file migrasi database baru.';

    public function handle($argumen) {
        $namaMigrasi = $argumen[0] ?? null;
        if (!$namaMigrasi) {
            die("Error: Berikan nama untuk file migrasi. Contoh: create_tugas_table\n");
        }

        $timestamp = date('Y_m_d_His');
        $namaFile = "{$timestamp}_{$namaMigrasi}.php";
        $path = getcwd() . "/database/migrations/{$namaFile}";

        // Template untuk file migrasi
        $template = <<<EOT
<?php
use Inti\Database;

class {$namaMigrasi} {
    public function up() {
        \$db = Database::getInstance()->getConnection();
        \$sql = "
            -- Tulis query SQL untuk CREATE TABLE di sini
        ";
        \$db->exec(\$sql);
    }

    public function down() {
        \$db = Database::getInstance()->getConnection();
        \$sql = "
            -- Tulis query SQL untuk DROP TABLE di sini
        ";
        \$db->exec(\$sql);
    }
}
EOT;
        file_put_contents($path, $template);
        echo "✅ Migrasi berhasil dibuat di: {$path}\n";
    }
}