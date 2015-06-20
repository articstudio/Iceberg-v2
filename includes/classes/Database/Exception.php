<?php
namespace Iceberg\Database;

class Exception extends \Iceberg\Core\Exception
{
    
    
    /*
    public static function Report($msg='')
    {
        $debug = static::GetDebugInfo();
        echo $msg;
        //error_log();
        exit;
    }
    
    protected static function GetDebugInfo()
    {
        $debugInfoArray = debug_backtrace();
        $i = 0;
        while((isset($debugInfoArray[$i]["file"])) && (realPath($debugInfoArray[$i]["file"]) != realPath($_SERVER["SCRIPT_FILENAME"])) && ($i<sizeOf($debugInfoArray))) ++$i;
        return $debugInfoArray[$i];
    }
    */
}

