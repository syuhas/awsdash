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



    // main views //

    public function home()
    {
        $buckets = $this->apiService->listBuckets();
        $bucketsTotalCost = 0;
        $bucketsTotalSize = 0;
        foreach ($buckets as $bucket) {
            $bucketsTotalCost += $bucket['costPerMonth'];
            $bucketsTotalSize += $bucket['totalSizeGb'];
        }
        usort($buckets, function($a, $b) {
            return $b['costPerMonth'] <=> $a['costPerMonth'];
        });
        return view('home', [
            'buckets' => $buckets,
            'bucketsTotalCost' => $bucketsTotalCost,
            'bucketsTotalSize' => $bucketsTotalSize
        ]);
    }

    public function costExplorer()
    {
        $buckets = $this->apiService->listBuckets();
        return view('cost', ['buckets' => $buckets]);
    }

    public function objectExplorer(Request $request)
    {
        $bucket = $request->get('bucket', '');
        $page = $request->get('page', 1);
        $objects = $bucket ? $this->apiService->listObjects($bucket, 1, 10) : [];
        return view('object', ['objects' => $objects, 'bucket' => $bucket]);
    }

    // public function objectDownloader(Request $request)
    // {
    //     $bucket = $request->get('bucket', '');
    //     $key = $request->get('key', '');
    //     $downloadUrl = $bucket && $key ? $this->apiService->downloadObject($bucket, $key) : '';
    //     return view('object-downloader', ['downloadUrl' => $downloadUrl]);
    // }

    // ajax handler //
    public function getBucketDetails(Request $request)
    {
        $bucketName = $request->query('bucket', '');

        if (!$bucketName) {
            return response()->json(['error' => 'Bucket name is required'], 400);
        }

        $bucketList = $this->apiService->listBuckets();
        $selectedBucket = collect($bucketList)->firstWhere('bucket', $bucketName);

        if (!$selectedBucket) {
            return response()->json(['error' => 'Bucket not found'], 404);
        }

        $listObjects = $this->apiService->listObjects($bucketName, 1, 100);

        return response()->json([
            'bucket' => $selectedBucket['bucket'],
            'cost' => $selectedBucket['costPerMonth'],
            'size' => $selectedBucket['totalSizeGb'],
            'objectNumber' => $listObjects['total_objects'],
            'objects' => $listObjects['objects'],
        ]);
    }
}
