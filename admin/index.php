<?php

/**
 * First require the autoload
 */
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'autoload.php' ;

/**
 * Execute Bootstrap
 */
\Iceberg\Core\Bootstrap::Initialize([
    'env'      => 'Backend',
    'root'      => dirname(__DIR__),
    'debug'     => true
]);
