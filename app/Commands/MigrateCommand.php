<?php
namespace App\Commands;

use Inti\Database;
use PDO;

class MigrateCommand {
    public $nama = 'migrate';
    public $deskripsi = 'Menjalankan migrasi database yang belum dijalankan.';

    public function handle() {
        $db = Database::getInstance()->getConnection();
        $this->pastikanTabelMigrasiAda($db);

        $stmt = $db->query("SELECT migration FROM migrations");
        $migrasiSudahJalan = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $semuaFileMigrasi = glob(getcwd() . '/database/migrations/*.php');
        
        $batch = $this->getLatestBatch($db) + 1;
        $migrasiBaruDitemukan = false;

        foreach ($semuaFileMigrasi as $file) {
            $namaFile = basename($file);
            
            if (in_array($namaFile, $migrasiSudahJalan)) {
                continue;
            }

            $migrasiBaruDitemukan = true;

            echo "Migrating: {$namaFile}\n";
            require_once $file;
            
            // =============================================================
            // INI ADALAH BARIS YANG DIPERBAIKI
            // =============================================================
            $parts = explode('_', pathinfo($namaFile, PATHINFO_FILENAME));
            $namaKelas = implode('_', array_slice($parts, 4));
            
            if (class_exists($namaKelas)) {
                $migrasi = new $namaKelas();
                $migrasi->up();

                $stmt = $db->prepare("INSERT INTO migrations (migration, batch) VALUES (?, ?)");
                $stmt->execute([$namaFile, $batch]);
                
                echo "Migrated:  {$namaFile}\n";
            } else {
                echo "Error: Class '{$namaKelas}' tidak ditemukan di dalam file {$namaFile}\n";
            }
        }
        
        if (!$migrasiBaruDitemukan) {
            echo "Tidak ada migrasi baru untuk dijalankan.\n";
        } else {
            echo "Migrasi selesai.\n";
        }
    }

    private function getLatestBatch($db) {
        $stmt = $db->query("SELECT MAX(batch) FROM migrations");
        $result = $stmt->fetchColumn();
        return $result ? (int)$result : 0;
    }

    private function pastikanTabelMigrasiAda($db) {
        $db->exec("
            CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL,
                batch INT NOT NULL
            )
        ");
    }
}