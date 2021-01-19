<?php

use Core\App;

require __DIR__ . '/../bootstrap.php';

try {
    $app = new App();
    return $app->find();
} catch (Exception $e) {
    throw new RuntimeException($e->getMessage());
}


