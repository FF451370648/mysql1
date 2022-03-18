<?php

return [
    // 默认磁盘
    'default' => env('filesystem.driver', 'local'),
    // 磁盘列表
    'disks' => [
        'local' => [
            'type' => 'local',
            'root' => app()->getRuntimePath() . 'storage',
        ],
        'public' => [
            // 磁盘类型
            'type' => 'local',
            // 磁盘路径
            'root' => app()->getRootPath() . 'public/storage',
            // 磁盘路径对应的外部URL路径
            'url' => '/storage',
            // 可见性
            'visibility' => 'public',
        ],
        'cut_project' => [
            // 磁盘类型
            'type' => 'local',
            // 磁盘路径
            'root' => app()->getRootPath() . 'public/storage/cut',
            // 磁盘路径对应的外部URL路径
            'url' => '/storage',
            // 可见性
            'visibility' => 'public',
        ],
        'remote_project' => [
            // 磁盘类型
            'type' => 'local',
            // 磁盘路径
            'root' => app()->getRootPath() . 'public/storage/remote',
            // 磁盘路径对应的外部URL路径
            'url' => '/storage',
            // 可见性
            'visibility' => 'public',
        ],
        // 更多的磁盘配置信息
        'uploadGeometry' => [
            // 磁盘类型
            'type' => 'local',
            // 磁盘路径
            'root' => 'D:/upload/Geometry',
            // 可见性
            'visibility' => 'public',
        ],
        'cut_tif' => [
            // 磁盘类型
            'type' => 'local',
            // 磁盘路径
            'root' => 'D:/dynamic/cut',
            // 可见性
            'visibility' => 'public',
        ],
        'cut_tif' => [
            // 磁盘类型
            'type' => 'local',
            // 磁盘路径
            'root' => 'D:/dynamic/remote',
            // 可见性
            'visibility' => 'public',
        ],
        'en-programme' => [
            // 磁盘类型
            'type' => 'local',
            // 磁盘路径
            'root' => app()->getRootPath() . 'public/storage/programme',
            // 可见性
            'visibility' => 'public',
        ],
        'xyz' => [
            // 磁盘类型
            'type' => 'local',
            // 磁盘路径
            'root' => 'D:/upload/wydc3/xyz',
            // 可见性
            'visibility' => 'public',
        ],
        // 更多的磁盘配置信息
    ],
];
