<?php

namespace Inti;

use PDO;

abstract class BaseModel {
    protected $db;
    protected $table;
    
    // Properti untuk menyimpan data (misal: 'nama_tugas', 'selesai')
    protected $attributes = [];

    public function __construct($attributes = []) {
        $this->db = Database::getInstance()->getConnection();
        $this->fill($attributes);
        
        if (!$this->table) {
            $className = get_class($this);
            $parts = explode('\\', $className);
            $modelName = end($parts);
            $this->table = strtolower($modelName) . 's';
        }
    }
    
    // --- FUNGSI BARU UNTUK CRUD ---

    /**
     * Menyimpan data model ke database (INSERT atau UPDATE).
     */
    public function save() {
        if (isset($this->attributes['id'])) {
            return $this->update();
        } else {
            return $this->insert();
        }
    }

    /**
     * Menghapus data model dari database.
     */
    public function delete() {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$this->attributes['id']]);
    }

    /**
     * Membuat record baru dengan cepat (static method).
     */
    public static function create($attributes) {
        $instance = new static($attributes);
        $instance->save();
        return $instance;
    }

    // --- FUNGSI LAMA YANG SUDAH ADA ---
    
    public static function all() {
        $instance = new static();
        $stmt = $instance->db->prepare("SELECT * FROM {$instance->table}");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id) {
        $instance = new static();
        $stmt = $instance->db->prepare("SELECT * FROM {$instance->table} WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Jika data ditemukan, kembalikan sebagai objek Model, bukan array
        return $data ? new static($data) : null;
    }
    
    // --- FUNGSI PEMBANTU (HELPER) ---

    protected function insert() {
        $columns = implode(', ', array_keys($this->attributes));
        $placeholders = implode(', ', array_fill(0, count($this->attributes), '?'));
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array_values($this->attributes));
        
        // Ambil ID yang baru dibuat dan simpan ke model
        $this->attributes['id'] = $this->db->lastInsertId();
        return true;
    }

    protected function update() {
        $setClauses = [];
        foreach ($this->attributes as $key => $value) {
            if ($key !== 'id') {
                $setClauses[] = "{$key} = ?";
            }
        }
        $setSql = implode(', ', $setClauses);
        
        $sql = "UPDATE {$this->table} SET {$setSql} WHERE id = ?";
        
        $values = array_values(array_filter($this->attributes, fn($key) => $key !== 'id', ARRAY_FILTER_USE_KEY));
        $values[] = $this->attributes['id'];

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }
    
    public function fill($attributes) {
        $this->attributes = $attributes;
    }

    // Magic method untuk mengakses properti (misal: $tugas->nama_tugas)
    public function __get($key) {
        return $this->attributes[$key] ?? null;
    }

    // Magic method untuk mengatur properti (misal: $tugas->nama_tugas = 'Baru')
    public function __set($key, $value) {
        $this->attributes[$key] = $value;
    }
}