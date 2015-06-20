<?php
namespace Iceberg\Core;
// http://culttt.com/2013/02/04/how-to-save-php-sessions-to-a-database/

class Session extends \Iceberg\Abstracts\ObjectConfiguration
{
    private static $_ID = null;
    
    public static function Start($name, $life_time=3600)
    {
        ini_set('session.gc_maxlifetime', $life_time);
        ini_set('session.gc_divisor', 10000);
        ini_set('session.gc_probability', 1);
        ini_set('session.cookie_lifetime', 0);
        session_name(Security::Session($name));
        if (session_start())
        {
            static::$_ID = session_id();
        }
        return false;
    }

    /**
     * Set a session value for a key, if value is null unset the key
     * 
     * @param string $key
     * @param mixed $value
     * @return boolean 
     */
    public static function SetValue($key, $value=null)
    {
        if ($value === null)
        {
            $_SESSION[$key] = null;
            unset($_SESSION[$key]);
        }
        else
        {
            $_SESSION[$key] = $value;
        }
        return true;
    }

    /**
     * Unset a session value for a key
     * 
     * @param string $key
     * @return boolean 
     */
    public static function UnsetValue($key) {
        return static::SetValue($key);
    }
    
    /**
     * Get value of session
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed 
     */
    public static function GetValue($key, $default=null)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }

    /**
     * Get is set key in SESSION
     * 
     * @uses action_event() for 'request_isset_s'
     * @param string $key
     * @return bool 
     */
    public static function IssetKey($key)
    {
        return isset($_SESSION[$key]);
    }
}

