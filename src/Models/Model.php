<?php

namespace NewsAggregator\Models;

use NewsAggregator\Database\Database;
use PDO;

abstract class Model
{
    protected $db;
    protected $table;
    protected $primaryKey = 'id';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function all()
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll();
    }

    public function create(array $data)
    {
        $columns = implode(', ', array_keys($data));
        $values = implode(', ', array_fill(0, count($data), '?'));
        
        $stmt = $this->db->prepare("INSERT INTO {$this->table} ({$columns}) VALUES ({$values})");
        $stmt->execute(array_values($data));
        
        return $this->db->lastInsertId();
    }

    public function update($id, array $data)
    {
        $setClause = implode('=?, ', array_keys($data)) . '=?';
        $stmt = $this->db->prepare("UPDATE {$this->table} SET {$setClause} WHERE {$this->primaryKey} = ?");
        
        $values = array_values($data);
        $values[] = $id;
        
        return $stmt->execute($values);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?");
        return $stmt->execute([$id]);
    }
} 