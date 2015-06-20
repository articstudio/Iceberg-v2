<?php
namespace Iceberg\Core;

/**
 * Description of Normalize
 *
 * @author Marc
 */
class Normalize
{
    public static function Merge($default, $override)
    {
        if ($default === null)
        {
            return $override;
        }
        else if ($override === null)
        {
            return $default;
        }
        else if (is_array($default) && is_array($override))
        {
            return array_merge($default, $override);
        }
        else if (is_object($default) && is_object($default))
        {
            return (object) array_merge((array)$default, (array)$override);
        }
        return $override;
    }
    
    public static function Encode($value)
    {
        if (is_object($value))
        {
            return serialize($value);
        }
        else if (is_array($value))
        {
            return json_encode($value);
        }
        return $value;
    }
    
    public static function Decode($value=null)
    {
        $data = @unserialize($value);
        if($data !== false || $value === 'b:0;')
        {
            return $data;
        }
        $data = @json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE)
        {
            return $data;
        }
        return $value;
    }
}
