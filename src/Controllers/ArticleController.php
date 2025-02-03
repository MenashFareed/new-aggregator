<?php

namespace NewsAggregator\Controllers;

use NewsAggregator\Models\Article;
use NewsAggregator\Services\NewsApiService;

class ArticleController
{
    private $article;
    private $newsApi;

    public function __construct()
    {
        $this->article = new Article();
        $this->newsApi = new NewsApiService();
    }

    public function index($page = 1, $limit = 20)
    {
        $offset = ($page - 1) * $limit;
        return [
            'status' => 'success',
            'data' => $this->article->all($limit, $offset)
        ];
    }

    public function show($id)
    {
        $article = $this->article->find($id);
        if (!$article) {
            return [
                'status' => 'error',
                'message' => 'Article not found'
            ];
        }
        return [
            'status' => 'success',
            'data' => $article
        ];
    }

    public function search($query, $page = 1, $limit = 20)
    {
        $offset = ($page - 1) * $limit;
        return [
            'status' => 'success',
            'data' => $this->article->search($query, $limit, $offset)
        ];
    }

    public function fetchLatest()
    {
        try {
            $articles = $this->newsApi->fetchLatestNews();
            return [
                'status' => 'success',
                'message' => count($articles) . ' new articles fetched',
                'data' => $articles
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Failed to fetch articles: ' . $e->getMessage()
            ];
        }
    }
} 