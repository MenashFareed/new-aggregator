<?php

namespace NewsAggregator\Models;

class Article extends Model
{
    protected $table = 'articles';

    public function findByCategory($categoryId, $limit = 20, $offset = 0)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} 
            WHERE category_id = ? 
            ORDER BY published_at DESC 
            LIMIT ? OFFSET ?"
        );
        $stmt->execute([$categoryId, $limit, $offset]);
        return $stmt->fetchAll();
    }

    public function search($query, $limit = 20, $offset = 0)
    {
        $searchTerm = "%{$query}%";
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} 
            WHERE title LIKE ? 
            OR description LIKE ? 
            OR content LIKE ? 
            ORDER BY published_at DESC 
            LIMIT ? OFFSET ?"
        );
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $limit, $offset]);
        return $stmt->fetchAll();
    }

    public function findByUrl($url)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE url = ?");
        $stmt->execute([$url]);
        return $stmt->fetch();
    }
} 