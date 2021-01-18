<?php

use Core\Connection;
use Core\Model;

error_reporting(E_ALL);
ini_set('display_errors', true);

try {
    require __DIR__ . '/vendor/autoload.php';
    require __DIR__ . '/routes/routes.php';
    $conn = Connection::getInstance('database');
    Model::setConnection($conn);
} catch(Exception $e){
    echo $e->getMessage();
}
