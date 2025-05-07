<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Pixabay\PixabayClient;

class Search extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Search',
            'results' => [],
        ];
        
        $query = $this->request->getGet('query');
        $type = $this->request->getGet('type') ?? 'images'; 
        
        if ($query) {
            $pixabayClient = new PixabayClient([
                'key' => env('PIXABAY_API_KEY'), 
            ]);
            
            $params = [
                'q' => $query,
                'per_page' => 20,
            ];
            
            if ($type === 'images') {
                $results = $pixabayClient->getImages($params, true);
                $data['results'] = $results['hits'] ?? [];
            } else {
                $results = $pixabayClient->getVideos($params, true);
                $data['results'] = $results['hits'] ?? [];
            }
            
            $data['query'] = $query;
            $data['type'] = $type;
        }
        
        return view('search/index', $data);
    }
}
