<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class ApiService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('app.api_base_url');
    }

    public function listBuckets()
    {
        return Http::get("{$this->baseUrl}/s3/list_buckets")->json();
    }

    public function listObjects($bucket, $page = 1, $pageSize = 10)
    {
        return Http::get("{$this->baseUrl}/s3/list_objects", [
            'bucket' => $bucket,
            'page' => $page,
            'page_size' => $pageSize,
        ])->json();
    }

    public function listObject($bucket, $key)
    {
        return Http::get("{$this->baseUrl}/s3/list_object", [
            'bucket' => $bucket,
            'key' => $key,
        ])->json();
    }

    public function searchObject($key)
    {
        return Http::get("{$this->baseUrl}/s3/search_objects", [
            'key' => $key,
        ])->json();
    }

    public function downloadObject($bucket, $key)
    {
        return "{$this->baseUrl}/s3/download_object?bucket={$bucket}&key={$key}";
    }
}
