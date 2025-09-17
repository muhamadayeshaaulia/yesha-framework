<?php
namespace App\Commands;

use Inti\Database;
use PDO;

class MigrateResetCommand {
    public $nama = 'migrate:reset';
    public $deskripsi = 'Membatalkan (rollback) semua migrasi database.';

    public function handle() {
        $db = Database::getInstance()->getConnection();

        // Ambil semua migrasi yang sudah dijalankan, urutkan dari TERBARU
        $stmt = $db->query("SELECT migration FROM migrations ORDER BY id DESC");
        $semuaMigrasi = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if (empty($semuaMigrasi)) {
            echo "Tidak ada migrasi untuk di-reset.\n";
            return;
        }

        foreach ($semuaMigrasi as $namaFile) {
            echo "Rolling back: {$namaFile}\n";

            $pathFile = getcwd() . "/database/migrations/{$namaFile}";
            if (file_exists($pathFile)) {
                require_once $pathFile;
                $namaKelas = preg_replace('/^[0-9]+_[0-9]+_/', '', pathinfo($namaFile, PATHINFO_FILENAME));

                if (class_exists($namaKelas)) {
                    $migrasi = new $namaKelas();
                    $migrasi->down(); // <-- Memanggil method down()
                } else {
                    echo "Warning: Class '{$namaKelas}' tidak ditemukan.\n";
                }
            } else {
                echo "Warning: File migrasi '{$namaFile}' tidak ditemukan.\n";
            }
        }

        // Setelah semua dibatalkan, kosongkan tabel migrasi
        $db->exec("TRUNCATE TABLE migrations");

        echo "Semua migrasi berhasil di-reset.\n";
    }
}