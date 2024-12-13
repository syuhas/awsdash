<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;

class BucketController extends Controller
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function home()
    {
        $buckets = $this->apiService->listBuckets();
        return view('home', ['buckets' => $buckets]);
    }

    public function costExplorer()
    {
        $buckets = $this->apiService->listBuckets();
        return view('cost-explorer', ['buckets' => $buckets]);
    }

    public function objectExplorer(Request $request)
    {
        $bucket = $request->get('bucket', '');
        $page = $request->get('page', 1);
        $objects = $bucket ? $this->apiService->listObjects($bucket, $page) : [];
        return view('object-explorer', ['objects' => $objects, 'bucket' => $bucket]);
    }

    public function objectDownloader(Request $request)
    {
        $bucket = $request->get('bucket', '');
        $key = $request->get('key', '');
        $downloadUrl = $bucket && $key ? $this->apiService->downloadObject($bucket, $key) : '';
        return view('object-downloader', ['downloadUrl' => $downloadUrl]);
    }
}
