<?php
namespace Iceberg\Install;

use Iceberg\Core\Bootstrap;
use Iceberg\Install\Request;

class Theme extends \Iceberg\Theme\Theme
{
    
    
    public static function Initialize()
    {
        parent::Initialize();
        static::$_DIR = ICEBERG_DIR_ADMIN . 'install/';
        static::$_URL = str_replace(Bootstrap::GetValue('root'), Request::GetBaseURL(), static::$_DIR);
    }
}