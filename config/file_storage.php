<?php
/*use Burzum\FileStorage\Storage\Listener\BaseListener;
use Burzum\FileStorage\Storage\StorageUtils;
use Burzum\FileStorage\Storage\StorageManager;
use Cake\Core\Configure;
use Cake\Event\EventManager;
use Cake\Core\Plugin;

Plugin::load('Burzum/FileStorage');

StorageManager::config('Local', [
        'adapterOptions' => [ROOT . DS . 'files', true],
        'adapterClass' => '\Gaufrette\Adapter\Local',
        'class' => '\Gaufrette\Filesystem'
    ]);

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
        'UserImage' => [
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
\Burzum\FileStorage\Lib\FileStorageUtils::generateHashes();*/

use Burzum\FileStorage\Event\ImageProcessingListener;
use Burzum\FileStorage\Event\LocalFileStorageListener;
use Burzum\FileStorage\Lib\FileStorageUtils;
use Burzum\FileStorage\Lib\StorageManager;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Event\EventManager;

Plugin::load('Burzum/FileStorage');
StorageManager::config('Local', [
        'adapterOptions' => [ROOT . DS . 'files', true],
        'adapterClass' => '\Gaufrette\Adapter\Local',
        'class' => '\Gaufrette\Filesystem'
    ]);

Configure::write('FileStorage', [
        // Configure image versions on a per model base
        'imageSizes' => [
            'file_storage' => [
                'large' => [
                    'thumbnail' => [
                        'mode' => 'inbound',
                        'width' => 400,
                        'height' => 400
                    ]
                ],
                'medium' => [
                    'thumbnail' => [
                        'mode' => 'inbound',
                        'width' => 263,
                        'height' => 263
                    ]
                ],
                'medium-small' => [
                    'thumbnail' => [
                        'mode' => 'inbound',
                        'width' => 248,
                        'height' => 156
                    ]
                ],
                'small' => [
                    'thumbnail' => [
                        'mode' => 'inbound',
                        'width' => 60,
                        'height' => 60
                    ]
                ],
            ],
        ],
    ]);

FileStorageUtils::generateHashes();

$listener = new ImageProcessingListener(['preserveFilename' => true]);
EventManager::instance()->on($listener);

$listener = new LocalFileStorageListener();
EventManager::instance()->on($listener);
