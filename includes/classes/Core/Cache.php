<?php
namespace Iceberg\Core;

class Cache extends Iceberg\Abstracts\ObjectConfiguration
{
    protected static $KEY = 'cache_config';
    
    protected static $DEFAULTS = [
        'objects' => true
    ];
    
}