<?php

require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
$s3Client = new S3Client([
    'region' => 'us-east-1',
    'version' => 'latest'
]);

$bucketData = $s3Client->listBuckets();
$buckets = [];

foreach ($bucketData['Buckets'] as $bucket) {
    $bucketName = $bucket['Name'];
    $objects = $s3Client->listObjects(['Bucket' => $bucketName]);

    $totalSize = 0;
    $objectList = [];

    if (isset($objects['Contents'])) {
        foreach ($objects['Contents'] as $object) {
            $sizeKB = round($object['Size'], 2);
            $sizeMB = round($object['Size'] / (1024 * 1024), 2);
            $sizeGB = round($object['Size'] / (1024 * 1024 * 1024), 4);
            $totalSize += $object['Size'];
            $objectList[] = [
                'Key' => $object['Key'],
                'sizeKB' => $sizeKB,
                'sizeMB' => $sizeMB,
                'sizeGB' => $sizeGB
            ];


        }
    }

    $totalSizeKB = round($totalSize, 2);
    $totalSizeMB = round($totalSize / (1024 * 1024), 2);
    $totalSizeGB = round($totalSize / (1024 * 1024 * 1024), 4);

    $buckets[] = [
        'name' => $bucketName,
        'totalSizeKB' => $totalSizeKB,
        'totalSizeMB' => $totalSizeMB,
        'totalSizeGB' => $totalSizeGB,
        'objects' => $objectList,
    ];

}

// twig setup
$loader = new FilesystemLoader('templates');
$twig = new Environment($loader);

// render twig
echo $twig-> render('s3.twig', ['buckets' => $buckets]);

?>
