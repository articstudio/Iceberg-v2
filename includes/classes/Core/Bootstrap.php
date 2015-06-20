<?php
namespace Iceberg\Core;

use Iceberg\Core\Exception;
use Iceberg\Core\Debug;
use Iceberg\Core\Extend;
use Iceberg\Core\Core;

/**
 * Iceberg Bootstrap
 * 
 * Boot the system
 *  
 * @package Bootstrap
 * @author Marc Mascort Bou marc@marcmascort.com
 * @version 1.0
 * @since 0
 */
abstract class Bootstrap
{
    /**
     * Bootstrap status: Not initialized
     */
    const STATUS_NULL = 0;
    
    /**
     * Bootstrap status: Initialized
     */
    const STATUS_BOOTSTRAP = 1;
    
    /**
     * Bootstrap status: Initialized + ICEBERG
     */
    const STATUS_ICEBERG = 2;

    /**
     * Default options
     * @var array
     */
    private static $_OPTIONS = [
        'env' => 'Frontend',
        'initialized' => false,
        'root' => '/'
    ];
    
    private static $_STATUS = 0;
    
    public static $_VERSION = '2.0';
    
    public static $_PHP_VERSION_REQUIRED = '5.5.0';

    /**
     * Initialize Bootstrap
     * 
     * @param array Arguments for bootstrap initialization
     * @throws \Iceberg\Exception Bootstrap is initialized, can't be reinitialized
     */
    public static function Initialize($args=[])
    {
        if (!static::IsInitialized())
        {
            static::_Config($args);
            static::_Load();
            Core::Initialize();
            Debug::PrintLog();
        }
        else
        {
            throw new Exception('ICEBERG BOOTSTRAP ERROR: Bootstrap is initialized, can\'t be reinitialized.');
        }
    }
    
    public static function SetStatus($status)
    {
        static::$_STATUS = $status;
    }
    
    public static function GetStatus()
    {
        return static::$_STATUS;
    }

    /**
     * Return if Bootstrap is initialized
     * 
     * @return bool 
     */
    public static function IsInitialized()
    {
        return (static::$_STATUS > static::STATUS_NULL);
    }

    /**
     * Return a bootstrap configuration value for key
     * 
     * @global array $__ICEBERG_BOOTSTRAP
     * @param string Index of value
     * @return mixed 
     */
    public static function GetValue( $key )
    {
        return (array_key_exists($key, static::$_OPTIONS)) ? static::$_OPTIONS[$key] : false;
    }

    /**
     * Configuration of Bootstrap
     * 
     * @global bool $__ICEBERG_INITIALIZED
     * @global bool $__ICEBERG_ADMIN
     * @global bool $__ICEBERG_API
     * @global array $__ICEBERG_BOOTSTRAP
     * @see Bootstrap::$default_options
     * @param array Arguments for bootstrap configuration
     */
    private static function _Config( $args )
    {
        static::SetStatus(static::STATUS_BOOTSTRAP);
        static::$_OPTIONS = array_merge(static::$_OPTIONS , $args);
        if (substr(static::$_OPTIONS['root'], -1, 1) !== DIRECTORY_SEPARATOR)
        {
            static::$_OPTIONS['root'] .= DIRECTORY_SEPARATOR;
        }
    }

    /**
     * Loading basic libraries of system
     */
    private static function _Load()
    {
        static::_CheckCompatibility();
        Debug::Initialize(static::GetValue('debug'));
        /**
         * Basic functions file
         */
        //require_once ICEBERG_DIR_INCLUDES . 'functions.php';
        
        /**
         * Extend functions file
         */
        Extend::Initialize();
    }

    /**
     * Checks if PHP version is compatible with Iceberg version requeriments
     * 
     * @throws IcebergException 
     */
    private static function _CheckCompatibility()
    {
        if (strnatcmp(phpversion(), static::$_PHP_VERSION_REQUIRED) < 0)
        {
            throw new Exception(sprintf('Your server is running version %1$s to PHP, but Iceberg v%2$s requires at least version %3$s.', phpversion(), ICEBERG_VERSION, ICEBERG_PHP_VERSION_REQUIRED));
        }
    }
    
}
