<?php
use Aws\S3\S3Client;
use Burzum\FileStorage\Storage\Listener\BaseListener;
use Burzum\FileStorage\Storage\StorageUtils;
use Burzum\FileStorage\Storage\StorageManager;
use Cake\Core\Configure;
use Cake\Event\EventManager;

// Instantiate a storage event listener
$listener = new BaseListener([
    'imageProcessing' => true, // Required if you want image processing!
    'pathBuilderOptions' => [
        // Preserves the original filename in the storage backend.
        // Otherwise it would use a UUID as filename by default.
        'preserveFilename' => true
    ]
]);
// Attach the BaseListener to the global EventManager
EventManager::instance()->on($listener);

Configure::write('FileStorage', [
// Configure image versions on a per model base
    'imageSizes' => [
        'ProductImage' => [
            'large' => [
                'thumbnail' => [
                    'mode' => 'inbound',
                    'width' => 800,
                    'height' => 800
                ]
            ],
            'medium' => [
                'thumbnail' => [
                    'mode' => 'inbound',
                    'width' => 200,
                    'height' => 200
                ]
            ],
            'small' => [
                'thumbnail' => [
                    'mode' => 'inbound',
                    'width' => 80,
                    'height' => 80
                ]
            ]
        ]
    ]
]);

// This is very important! The hashes are needed to calculate the image versions!
StorageUtils::generateHashes();

// Lets use the Amazon S3 adapter here instead of the default `Local` config.
// We need to pass a S3Client instance to this adapter to make it work
//$S3Client = new S3Client([
//    'version' => 'latest',
//    'region'  => 'eu-central-1',
//    'credentials' => [
//        'key' => 'YOUR-AWS-S3-KEY-HERE',
//        'secret' => 'YOUR-SECRET-HERE'
//    ]
//]);

// Configure the S3 adapter instance through the StorageManager
//StorageManager::config('S3', [
//   'adapterOptions' => array(
//        $S3Client,
//        'YOUR-BUCKET-NAME-HERE', // Bucket
//        [],
//        true
//    ),
//    'adapterClass' => '\Gaufrette\Adapter\AwsS3',
//    'class' => '\Gaufrette\Filesystem'
//]);