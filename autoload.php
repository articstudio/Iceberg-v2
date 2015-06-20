<?php
namespace Iceberg;

use Iceberg\Core\Exception;

/**************************************************************************
 * CONFIGURATION
 **************************************************************************/

/** Required configuration file */
require_once __DIR__ . DIRECTORY_SEPARATOR . 'config.php';


/**************************************************************************
 * AUTOLOAD
 **************************************************************************/

//\Iceberg\Autoloader::$NAMESPACE_DIRECTORY_MAP['Iceberg'] = ICEBERG_DIR_INCLUDES;
class Autoloader
{
    public static $NAMESPACE_DIRECTORY_MAP = [];
    
    public static function Loader($className)
    {
        $namespaceClassName = $className;
        $className = ltrim($className, '\\');
        $namespace = '';
        if ($lastNsPos = strrpos($className, '\\'))
        {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
        }
        if (isset(static::$NAMESPACE_DIRECTORY_MAP[$namespace]))
        {
            $fileName = static::$NAMESPACE_DIRECTORY_MAP[$namespace] . $className . '.php';
        }
        else if (strpos($namespace, 'Iceberg') === 0)
        {
            $fileName = str_replace('Iceberg', ICEBERG_DIR_INCLUDES . 'classes' . DIRECTORY_SEPARATOR, $namespace);
            $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $fileName) . DIRECTORY_SEPARATOR . str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
            $fileName = str_replace('//', DIRECTORY_SEPARATOR, str_replace('\\', DIRECTORY_SEPARATOR, $fileName));
        }
        if (!empty($fileName))
        {
            if (realpath($fileName))
            {
                require_once $fileName;
                if (class_exists($namespaceClassName, false))
                {
                    return true;
                }
            }
            throw new Exception('Iceberg Autoloader: Not found class "' . $namespace . '\\' . $className . '" file "' . $fileName . '"');
        }
    }
}

spl_autoload_register('\Iceberg\Autoloader::Loader');