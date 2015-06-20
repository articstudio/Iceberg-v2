<?php
namespace Iceberg\Core;

use Iceberg\Core\Exception;
use Iceberg\Core\Bootstrap;
use Iceberg\I18N\I18N;
use Iceberg\Date\TimeZone;
use Iceberg\Core\Request;
use Iceberg\Database\Database;
use Iceberg\Install\Install;

abstract class Core
{
    private static $_TIME_START = 0;
    private static $_TIME_END = 0;
    
    public static function Initialize()
    {
        if (!static::IsInitialized())
        {
            static::$_TIME_START = microtime(true);
            Bootstrap::SetStatus(Bootstrap::STATUS_ICEBERG);
            Database::Initialize();
            I18N::Initialize();
            TimeZone::Initialize();
            Request::Initialize();
            if (!Install::Initialize())
            {
                $env = Bootstrap::GetValue('env');
            }
            static::$_TIME_END = microtime(true);
        }
        else
        {
            throw new Exception('ICEBERG CORE ERROR: Core is initialized, can\'t be reinitialized.');
        }
    }
    
    public static function IsInitialized()
    {
        return Bootstrap::GetStatus() > Bootstrap::STATUS_BOOTSTRAP;
    }
    
    public static function GetTime()
    {
        if (static::$_TIME_START > 0 && static::$_TIME_END > 0 && static::$_TIME_END > static::$_TIME_START)
        {
            return static::$_TIME_END - static::$_TIME_START;
        }
        return 0;
    }
    
}