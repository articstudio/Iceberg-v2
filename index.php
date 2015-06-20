<?php

/**
 * First require the autoload
 */
require_once __DIR__ . DIRECTORY_SEPARATOR . 'autoload.php' ;

/**
 * Execute Bootstrap
 */
\Iceberg\Core\Bootstrap::Initialize([
    'root'      => __DIR__,
    'debug'     => true
]);
