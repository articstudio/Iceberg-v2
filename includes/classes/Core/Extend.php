<?php
namespace Iceberg\Core;

class Extend
{
    
    private static $__ACTIONS = [];
    private static $__FILTERS = [];
    
    public static function Initialize()
    {}
    
    public static function AddAction($event, $function, $priority=10, $accepted_arguments=1)
    {
        if (is_string($event) && !empty($event) && !empty($function) && is_int($priority) && is_int($accepted_arguments))
        {
            if (!isset(static::$__ACTIONS[$event]) || !is_array(static::$__ACTIONS[$event]))
            {
                static::$__ACTIONS[$event] = [];
            }
            if (!isset(static::$__ACTIONS[$event][$priority]) || !is_array(static::$__ACTIONS[$event][$priority]))
            {
                static::$__ACTIONS[$event][$priority] = [];
            }
            $function_hash = static::_BuildFunctionId($function);
            static::$__ACTIONS[$event][$priority][$function_hash] = [$function, $accepted_arguments];
            return true;
        }
        return false;
    }
    
    public static function RemoveAction($event, $function, $priority=10)
    {
        if (is_string($event) && !empty($event) && !empty($function) && is_int($priority) && isset(static::$__ACTIONS[$event]) && isset(static::$__ACTIONS[$event][$priority]))
        {
            $function_hash = static::_BuildFunctionId($function);
            if (isset(static::$__ACTIONS[$event][$priority][$function_hash]))
            {
                unset( static::$__ACTIONS[$event][$priority][$function_hash] );
                return true;
            }
        }
        return false;
    }
    
    public static function HasAction($event, $function)
    {
        if (is_string($event) && !empty($event) && !empty($function) && isset(static::$__ACTIONS[$event]) && !empty(static::$__ACTIONS[$event]))
        {
            $function_hash = static::_BuildFunctionId($function);
            foreach (static::$__ACTIONS[$event] AS $priority => $functions)
            {
                if (is_array($functions) && !empty($functions) && isset($functions[$function_hash]))
                {
                    return $priority;
                }
            }
        }
        return false;
    }
    
    public static function GetHooks($event)
    {
        if (isset(static::$__ACTIONS[$event]) && !empty(static::$__ACTIONS[$event]))
        {
            return static::$__ACTIONS[$event];
        }
        return [];
    }
    
    public static function DoAction($event)
    {
        if (is_string($event) && !empty($event) && isset(static::$__ACTIONS[$event]) && is_array(static::$__ACTIONS[$event]) && !empty(static::$__ACTIONS[$event]))
        {
            ksort(static::$__ACTIONS[$event]);
            $all_args = func_get_args();
            $args = array_slice($all_args, 1);
            foreach (static::$__ACTIONS[$event] AS $functions)
            {
                static::_ExecFunctions($functions, $args);
            }
        }
    }
    
    public static function AddFilter($event, $function, $priority=10, $accepted_arguments=1)
    {
        if (is_string($event) && !empty($event) && is_string($function) && !empty($function) && is_int($priority) && is_int($accepted_arguments))
        {
            if (!isset(static::$__FILTERS[$event]) || !is_array(static::$__FILTERS[$event]))
            {
                static::$__FILTERS[$event] = [];
            }
            if (!isset(static::$__FILTERS[$event][$priority]) || !is_array(static::$__FILTERS[$event][$priority]))
            {
                static::$__FILTERS[$event][$priority] = [];
            }
            $function_hash = static::_BuildFunctionId($function);
            static::$__FILTERS[$event][$priority][$function_hash] = [$function, $accepted_arguments];
            return true;
        }
        return false;
    }
    
    public static function RemoveFilter($event, $function, $priority=10)
    {
        if (is_string($event) && !empty($event) && !empty($function) && is_int($priority) && isset(static::$__FILTERS[$event]) && isset(static::$__FILTERS[$event][$priority]))
        {
            $function_hash = static::_BuildFunctionId($function);
            if (isset(static::$__FILTERS[$event][$priority][$function_hash]))
            {
                unset( static::$__FILTERS[$event][$priority][$function_hash] );
                return true;
            }
        }
        return false;
    }
    
    public static function HasFilter($event, $function)
    {
        if (is_string($event) && !empty($event) && !empty($function) && isset(static::$__FILTERS[$event]) && !empty(static::$__FILTERS[$event]))
        {
            $function_hash = static::_BuildFunctionId($function);
            foreach (static::$__FILTERS[$event] AS $priority => $functions)
            {
                if (is_array($functions) && !empty($functions) && isset($functions[$function_hash]))
                {
                    return $priority;
                }
            }
        }
        return false;
    }
    
    public static function GetFilters($event)
    {
        if (isset(static::$__FILTERS[$event]) && !empty(static::$__FILTERS[$event]))
        {
            return static::$__FILTERS[$event];
        }
        return [];
    }
    
    public static function ApplyFilters($event, $value)
    {
        if (is_string($event) && !empty($event) && isset(static::$__FILTERS[$event]) && is_array(static::$__FILTERS[$event]) && !empty(static::$__FILTERS[$event]))
        {
            ksort(static::$__FILTERS[$event]);
            $all_args = func_get_args();
            $args = array_slice($all_args, 2, (int)count($all_args));
            foreach (static::$__FILTERS[$event] AS $functions)
            {
                $value = static::_ExecFunctions($functions, $args, $value);
            }
        }
        return $value;
    }
    
    protected static function _BuildFunctionId($function)
    {
        if (is_string($function))
        {
            return $function;
        }
        $function = is_object($function) ? [$function, ''] : (array)$function;
        if (is_object($function[0]))
        {
            return spl_object_hash($function[0]) . $function[1];
        }
        else if (is_string($function[0]))
        {
            return $function[0] . '::' . $function[1];
        }
        return false;
    }
    
    protected static function _ExecFunctions($functions, $args, $value=null)
    {
        if (is_array($functions) && !empty($functions))
        {
            foreach ($functions AS $function_args)
            {
                $input_args = $value ? array_merge([$value], array_slice($args, 0, (int)$function_args[1]-1)) : array_slice($args, 0, (int)$function_args[1]);
                $value = call_user_func_array($function_args[0], $input_args);
            }
        }
        return $value;
    }
}