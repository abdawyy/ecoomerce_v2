<?php

$pdo = new PDO('mysql:host=127.0.0.1;dbname=truelabelegyp;charset=utf8mb4', 'root', '');
$dir = dirname(__DIR__).DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'seeders'.DIRECTORY_SEPARATOR.'data';
if (! is_dir($dir)) {
    mkdir($dir, 0777, true);
}

$tables = ['type', 'categories', 'cities', 'products', 'product_images', 'product_items', 'site_settings', 'admins', 'discount_codes'];

foreach ($tables as $table) {
    $rows = $pdo->query('SELECT * FROM `'.$table.'`')->fetchAll(PDO::FETCH_ASSOC);
    file_put_contents($dir.DIRECTORY_SEPARATOR.$table.'.json', json_encode($rows, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    echo $table.': '.count($rows).PHP_EOL;
}
