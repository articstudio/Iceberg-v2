<?php
namespace Iceberg\Core;

use Iceberg\Core\Extend;
use Iceberg\Core\Core;

class Debug
{
    const COLLECTION_DATABASE = 'DATABASE';
    const COLLECTION_CACHE = 'CACHE';
    const COLLECTION_CACHE_MEMCACHED = 'CACHE_MEMCACHED';
    
    private static $_IS_DEBUGGING = false;
    private static $_COLLECTIONS = [];
    
    public static function Initialize($debug=false)
    {
        if (static::IsDebugging($debug))
        {
            error_reporting(-1);
            ini_set('display_errors', 1);
            
            Extend::AddAction('database_query_send', ['\Iceberg\Core\Debug', 'TrackDatabase'], 5, 4);
            //add_action('mysql_query_send', 'mysql_query_send_debug', 5, 5);
            //add_action('iceberg_cache_action', 'iceberg_cache_action_debug', 5, 5);
            //add_action('iceberg_cache_action_memcached', 'iceberg_cache_action_memcached_debug', 5, 3);
        }
        else
        {
            error_reporting(0);
            ini_set('display_errors', 0);
        }
    }
    
    public static function IsDebugging($debug=null)
    {
        if ($debug !== null)
        {
            static::$_IS_DEBUGGING = boolval($debug);
        }
        return static::$_IS_DEBUGGING;
    }
    
    public static function GetLog($collection)
    {
        return (!isset(static::$_COLLECTIONS[$collection]) || !is_array(static::$_COLLECTIONS[$collection])) ? [] : static::$_COLLECTIONS[$collection];
    }
    
    public static function GetLogDatabase()
    {
        $log = static::GetLog(static::COLLECTION_DATABASE);
        $time_total = 0;
        $buffer = '';
        $n = count($log);
        foreach ($log AS $query)
        {
            $time_total += $query[1];
            $buffer .= 'Query time: ' . $query[1] . " seconds\n";
            $buffer .= 'Query results: ' . $query[3] . "\n";
            $buffer .= 'Query: ' . $query[0] . "\n\n";
        }
        $average = $n>0 ? $time_total / $n : 0;
        $buffer = "MySQL time: " . $time_total . " seconds\n MySQL time average: " . $average . " seconds\n MySQL queries: " . $n . "\n\n\n" . $buffer;
        return $buffer;
    }
    
    public static function Track($collection, $value)
    {
        if (static::IsDebugging())
        {
            if (!isset(static::$_COLLECTIONS[$collection]) || !is_array(static::$_COLLECTIONS[$collection]))
            {
                static::$_COLLECTIONS[$collection] = [];
            }
            return static::$_COLLECTIONS[$collection][] = $value;
        }
        return false;
    }
    
    public static function TrackDatabase($query, $time, $lastInsertId=-1, $rowCount=0)
    {
        static::Track(static::COLLECTION_DATABASE, [$query, $time, $lastInsertId, $rowCount]);
    }
    
    public static function PrintLog()
    {
        if (static::IsDebugging())
        {
            $time = Core::GetTime();
            if ($time > 0)
            {
                $generated = 'Page generated in ' . $time . ' seconds' . "\n";
                static::PrintHTMLComment('TIME', $generated);
            }
            static::PrintHTMLComment('DATABASE', static::GetLogDatabase());
            Extend::DoAction('iceberg_debug_print_log');
        }
    }
    
    public static function PrintHTMLComment($name, $content)
    {
        $html = "\n<!--#LOG:" . '%1$s' . "#--\n\n " . '%2$s' . " \n/#LOG:" . '%1$s' . "#-->\n";
        printf($html, $name, $content);
    }
}

