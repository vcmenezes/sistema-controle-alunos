<?php

use Core\App;

require __DIR__ . '/../bootstrap.php';

$request = request();
$app = new App($request);
try {
    $app->find()->send();
} catch (Exception $e) {
    throw new RuntimeException($e->getMessage());
}


