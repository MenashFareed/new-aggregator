<?php

namespace NewsAggregator\Services;

use NewsAggregator\Models\Article;
use NewsAggregator\Models\Category;

class NewsApiService
{
    private $apiKey;
    private $baseUrl = 'https://newsapi.org/v2';
    private $article;
    private $category;

    public function __construct()
    {
        $config = require __DIR__ . '/../../config/config.php';
        $this->apiKey = $config['api']['news_api_key'];
        $this->article = new Article();
        $this->category = new Category();
    }

    public function fetchLatestNews($category = null)
    {
        $endpoint = '/top-headlines';
        $params = [
            'country' => 'us',
            'pageSize' => 100,
            'apiKey' => $this->apiKey
        ];

        if ($category) {
            $params['category'] = $category;
        }

        $response = $this->makeRequest($endpoint, $params);
        return $this->saveArticles($response['articles']);
    }

    private function makeRequest($endpoint, $params)
    {
        $url = $this->baseUrl . $endpoint . '?' . http_build_query($params);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    private function saveArticles($articles)
    {
        $saved = [];
        foreach ($articles as $article) {
            // Check if article already exists
            $existing = $this->article->findByUrl($article['url']);
            if ($existing) {
                continue;
            }

            // Prepare article data
            $articleData = [
                'title' => $article['title'],
                'description' => $article['description'],
                'content' => $article['content'],
                'source' => $article['source']['name'],
                'url' => $article['url'],
                'image_url' => $article['urlToImage'] ?? null,
                'published_at' => date('Y-m-d H:i:s', strtotime($article['publishedAt'])),
            ];

            $saved[] = $this->article->create($articleData);
        }
        return $saved;
    }
} 