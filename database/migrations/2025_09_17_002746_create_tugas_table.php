<?php
use Inti\Database;

class create_tugas_table {
    public function up() {
        $db = Database::getInstance()->getConnection();
        $sql = "
            CREATE TABLE tugas (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nama_tugas VARCHAR(255) NOT NULL,
                selesai BOOLEAN DEFAULT false
            )
        ";
        $db->exec($sql);
    }

    public function down() {
        $db = Database::getInstance()->getConnection();
        $sql = "
            DROP TABLE IF EXISTS tugas;
        ";
        $db->exec($sql);
    }
}