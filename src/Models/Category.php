<?php

namespace NewsAggregator\Models;

class Category extends Model
{
    protected $table = 'categories';

    public function getWithArticleCount()
    {
        $stmt = $this->db->query(
            "SELECT c.*, COUNT(a.id) as article_count 
            FROM {$this->table} c 
            LEFT JOIN articles a ON c.id = a.category_id 
            GROUP BY c.id"
        );
        return $stmt->fetchAll();
    }
} 