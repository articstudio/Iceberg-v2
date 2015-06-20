<?php
namespace Iceberg\Database;

use Iceberg\Database\Exception;

class Database
{
    private static $_CONNECTOR;
    
    public static function GetConnector()
    {
        return static::$_CONNECTOR;
    }
    
    public static function GetConnectorType()
    {
        return ICEBERG_DB_CONNECTOR;
    }
    
    public static function GetConnectorNamespace()
    {
        return '\\' . __NAMESPACE__ . '\\' . static::GetConnectorType();
    }
    
    public static function GetConnectorClassname($class=null, $namespace=true)
    {
        $class_name = $class===null ? static::GetConnectorType() : $class;
        if (strpos($class_name, '\\'))
        {
            $class_name = explode('\\', $class_name);
            $class_name = array_pop($class_name);
        }
        return ($namespace ? static::GetConnectorNamespace() . '\\' : '') . $class_name;
    }
    
    public static function Initialize()
    {
        if (defined('ICEBERG_DB_PREFIX') && defined('ICEBERG_DB_HOST') && defined('ICEBERG_DB_PORT') && defined('ICEBERG_DB_USER') && defined('ICEBERG_DB_PASSWORD') && defined('ICEBERG_DB_NAME') && defined('ICEBERG_DB_CHARSET') && defined('ICEBERG_DB_COLLATE') && defined('ICEBERG_DB_CONNECTOR'))
        {
            static::_Connect();
        }
        else
        {
            throw new Exception('ICEBERG DATABASE ERROR: Database connection isn\'t defined.');
        }
    }
    
    private static function _Connect()
    {
        $class_name = static::GetConnectorClassname();
        if (class_exists($class_name) && is_subclass_of($class_name, '\Iceberg\Database\Connector'))
        {
            static::$_CONNECTOR = new $class_name(ICEBERG_DB_HOST, ICEBERG_DB_PORT, ICEBERG_DB_USER, ICEBERG_DB_PASSWORD, ICEBERG_DB_NAME);
        }
        else
        {
            throw new Exception('ICEBERG DATABASE ERROR: Database connector not found.');
        }
    }
}