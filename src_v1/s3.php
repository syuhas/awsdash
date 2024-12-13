<?php

require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Aws\Ec2\Ec2Client;
use Aws\Credentials\CredentialProvider;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// if ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '127.0.0.1' || $_SERVER['SERVER_NAME'] == '0.0.0.0') {
//     $provider = CredentialProvider::ini('Stephen', './src/credentials');
//     $provider = CredentialProvider::memoize($provider);

//     $s3Client = new S3Client([
//         'region' => 'us-east-1',
//         'version' => 'latest',
//         'credentials' => $provider
//     ]);

//     $ec2Client = new Ec2Client([
//         'region' => 'us-east-1',
//         'version' => 'latest',
//         'credentials' => $provider
//     ]);
// } else {
//     $s3Client = new S3Client([
//         'region' => 'us-east-1',
//         'version' => 'latest'
//     ]);

//     $ec2Client = new Ec2Client([
//         'region' => 'us-east-1',
//         'version' => 'latest'
//     ]);
// }


// $bucketData = $s3Client->listBuckets();
// $buckets = [];
// $totalCostPerMonth = 0;

// foreach ($bucketData['Buckets'] as $bucket) {
//     $bucketName = $bucket['Name'];
//     $objects = $s3Client->listObjects(['Bucket' => $bucketName]);

//     $totalSize = 0;
//     $objectList = [];

//     if (isset($objects['Contents'])) {
//         foreach ($objects['Contents'] as $object) {
//             $sizeKB = round($object['Size'], 2);
//             $sizeMB = round($object['Size'] / (1024 * 1024), 2);
//             $sizeGB = round($object['Size'] / (1024 * 1024 * 1024), 4);
//             $totalSize += $object['Size'];
//             $objectList[] = [
//                 'Key' => $object['Key'],
//                 'sizeKB' => $sizeKB,
//                 'sizeMB' => $sizeMB,
//                 'sizeGB' => $sizeGB
//             ];


//         }
//     }

//     $totalSizeKB = round($totalSize, 2);
//     $totalSizeMB = round($totalSize / (1024 * 1024), 2);
//     $totalSizeGB = round($totalSize / (1024 * 1024 * 1024), 4);
//     $costPerMonth = round($totalSizeGB * 0.023, 6);

//     $buckets[] = [
//         'name' => $bucketName,
//         'totalSizeKB' => $totalSizeKB,
//         'totalSizeMB' => $totalSizeMB,
//         'totalSizeGB' => $totalSizeGB,
//         'objects' => $objectList,
//         'costPerMonth' => $costPerMonth
//     ];

//     $totalCostPerMonth += $costPerMonth;

// }

// // twig setup
// if ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '127.0.0.1' || $_SERVER['SERVER_NAME'] == '0.0.0.0') {
//     $loader = new FilesystemLoader('src/templates');
// } else {
//     $loader = new FilesystemLoader('templates');
// }
// $twig = new Environment($loader);

// // render twig
// echo $twig-> render('s3.twig', [
//     'buckets' => $buckets,
//     'totalCostPerMonth' => $totalCostPerMonth
// ]);


function listBuckets() {
    $buckets = [];
    $url = "http://localhost:8000/s3/list_buckets";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    $bucketMetadata = json_decode($response, true);
    foreach ($bucketMetadata['Buckets'] as $bucket) {
        if (isset($bucket['Name'])) {
            $buckets[] = [
                'Name' => $bucket['Name']
            ];
        }
    }
    return $buckets;
}

function listObjects($bucketName) {
    $objectList = [];
    $url = "http://localhost:8000/s3/list_objects?bucket_name=$bucketName";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    $objectsMetadata = json_decode($response, true);
    foreach ($objectsMetadata['Contents'] as $object) {
        if (isset($objectsMetadata['Contents'])) {
            $objectList[] = [
                'Key' => $object['Key'],
                'Size' => $object['Size']
            ];
        }
    }
    return $objectList;
}

$buckets = listBuckets();
foreach ($buckets as $bucket) {
    echo $bucket['Name'];
    echo "<br><br>";
}

$objects = listObjects('localdownloads');
$total = 0;
foreach ($objects as $object) {
    // echo $object['Key'];
    // echo "<br><br>";
    // echo $object['Size'];
    // echo "<br><br>";
    $total += $object['Size'];
}

echo ((string) round($total / (1024 * 1024 * 1024), 6)) . ' GB';
echo '<br><br>';
echo ((string) round($total / (1024 * 1024), 2)) . ' MB';



?>
