<?php
namespace Iceberg\Core;

class Request extends \Iceberg\Abstracts\ObjectConfiguration
{
    const KEY_ACTION = 'action';
    const KEY_ENVIRONMENT = 'environment';
    const KEY_DOMAIN = 'domain';
    const KEY_LANGUAGE = 'lang';
    
    protected static $_DEFAULT_VARS = [
       'action' => null,
       'environment' => null,
       'domain' => null,
       'lang' => ICEBERG_LANGUAGE
    ];
    
    protected static $_VARS = [];
    
    
    public static function Initialize()
    {
        if (get_magic_quotes_gpc())
        {
            $_GET    = static::StripSlashes($_GET);
            $_POST   = static::StripSlashes($_POST);
            $_COOKIE = static::StripSlashes($_COOKIE);
        }
        $_REQUEST = array_merge($_GET, $_POST);
        static::Parse();
    }
    
    public static function IsRequestClass($class=null)
    {
        if ($class === null)
        {
            $class = self;
        }
        return ($class instanceof self);
    }
    
    public static function Get($default=false)
    {
        return $default ? static::$_DEFAULT_VARS : static::$_VARS;
    }
    
    public static function GetAll($default=false)
    {
        $request = static::Get($default);
        if (static::IsRequestClass(get_parent_class()))
        {
            $request = array_merge(parent::Get($default), $request);
        }
        return $request;
    }
    
    public static function Parse()
    {
        $request = static::Get(true);
        foreach ($request AS $k => $v)
        {
            $request[$k] = Request::GetValueSGP($k, $v, true);
        }
        static::$_VARS = array_merge(static::GetAll(), static::Get(), $request);
    }
    
    public static function GetVar($key, $default=null)
    {
        return isset(static::$_VARS[$key]) ? static::$_VARS[$key] : $default;
    }
    
    public static function SetVar($key, $value=null)
    {
        static::$_VARS[$key] = $value;
        if (!isset(static::$_DEFAULT_VARS[$key]) && static::IsRequestClass(parent))
        {
            parent::SetVar($key, $value);
        }
    }
    
    public static function UnsetVar($key)
    {
        static::SetVar($key);
    }
    
    
    public static function GetProtocol()
    {
        $protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : '';
        $protocol = explode(DIRECTORY_SEPARATOR, $_SERVER['SERVER_PROTOCOL']);
        return strtolower($protocol[0]);
    }
    
    public static function GetQuery($separator=false)
    {
        $query = (isset($_SERVER['QUERY_STRING']) && !empty ($_SERVER['QUERY_STRING'])) ? $_SERVER['QUERY_STRING'] : '';
        return (($separator && !empty($query)) ? '?' : '') . $query;
    }
    
    static public function GetURI($relative=true)
    {
        $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
        $script = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '';
        $script = substr($script, 0, strrpos($script, DIRECTORY_SEPARATOR));
        $uri = $relative ? str_replace($script, '', $uri) : $uri;
        $uri = (substr($uri, 0, 1) === DIRECTORY_SEPARATOR) ? substr($uri, 1) : $uri;
        $query = static::GetQuery(true);
        return str_replace($query, '', $uri);
    }
    
    static public function GetHost($protocol=true)
    {
        return ($protocol ? static::GetProtocol() . '://' : '') . $_SERVER['HTTP_HOST'];
    }
    
    static public function GetURL($protocol=true, $uri=true)
    {
        $uri = static::GetURI(false);
        return static::GetHost($protocol) . ($uri ? (DIRECTORY_SEPARATOR . $uri) : '');
    }
    
    public static function GetBaseURL($protocol=true)
    {
        $url = static::GetURL($protocol);
        /*if (isset($_SERVER['REDIRECT_URL'])) {
            $url = str_replace($_SERVER['REDIRECT_URL'], '' , $url) . '/';
        }*/
        $uri = static::GetURI(false);
        $url = str_replace($uri, '', $url);
        $admin_uri = str_replace(ICEBERG_DIR, '', ICEBERG_DIR_ADMIN);
        $api_uri = str_replace(ICEBERG_DIR, '', ICEBERG_DIR_API);
        if ( substr($url, (-1 * strlen($admin_uri)) ) === $admin_uri ) {
            $url = substr( $url, 0, (-1 * strlen($admin_uri)) );
        }
        else if ( substr($url, (-1 * strlen($api_uri)) ) === $api_uri ) {
            $url = substr( $url, 0, (-1 * strlen($api_uri) ) );
        }
        if (substr($url, -1, 1) !== DIRECTORY_SEPARATOR)
        {
            $url .= DIRECTORY_SEPARATOR;
        }
        return $url;
    }

    /**
     * Strip slashes of values
     * 
     * @uses action_event() for 'request_stripslashes'
     * @param mixed $var
     * @return mixed 
     */
    public static function StripSlashes($var)
    {
        if (is_array($var))
        {
            foreach ($var AS $key=>$value) $var[$key] = static::StripSlashes($value);
        }
        else if (is_object($var))
        {
            $ovars = get_object_vars($var);
            foreach ($ovars as $key=>$value) $value->{$key} = static::StripSlashes($value);
        }
        else if (!is_null($var) && !is_bool($var))
        {
            $var = stripcslashes(stripslashes($var));
        }
        return $var;
    }

    /**
     * ADD magic quotes of values
     * 
     * @uses action_event() for 'request_addmagicquotes'
     * @param mixed $var
     * @return mixed 
     */
    public static function AddMagicQuotes($var)
    {
        if (is_array($var))
        {
            foreach ($var AS $key=>$value) $var[$key]=static::AddMagicQuotes($value);
        }
        else if (is_object($var))
        {
            $ovars = get_object_vars( $var );
            foreach ($ovars as $key=>$value) $value->{$key} = static::AddMagicQuotes($value);
        }
        else
        {
            $var = addslashes($var);
        }
        return $var;
    }

    /**
     * Get value of GET
     * 
     * @uses action_event() for 'request_get_value_g'
     * @param string $key
     * @param mixed $default
     * @param bool $stripSlashes
     * @return mixed 
     */
    public static function GetValueG($key, $default=null, $stripSlashes=false)
    {
        $value = isset($_GET[$key]) ? $_GET[$key] : $default;
        $value = $stripSlashes ? static::StripSlashes($value) : $value;
        return $value;
    }

    /**
     * Get value of POST
     * 
     * @uses action_event() for 'request_get_value_p'
     * @param string $key
     * @param mixed $default
     * @param bool $stripSlashes
     * @return mixed 
     */
    public static function GetValueP($key, $default=null, $stripSlashes=false)
    {
        $value = isset($_POST[$key]) ? $_POST[$key] : $default;
        $value = $stripSlashes ? static::StripSlashes($value) : $value;
        return $value;
    }

    /**
     * Get value of GET > POST
     * 
     * @uses action_event() for 'request_get_value_gp'
     * @param string $key
     * @param mixed $default
     * @param bool $stripSlashes
     * @return mixed 
     */
    public static function GetValueGP($key, $default=null, $stripSlashes=false)
    {
        $value = static::GetValueG($key, $default, $stripSlashes);
        $value = static::GetValueP($key, $value, $stripSlashes);
        return $value;
    }

    /**
     * Get value of SESSION > GET > POST
     * 
     * @uses action_event() for 'request_get_value_sgp'
     * @param string $key
     * @param mixed $default
     * @param bool $stripSlashes
     * @return mixed 
     */
    public static function GetValueSGP($key, $default=null, $stripSlashes=false)
    {
        $value = Session::GetValue($key, $default);
        $value = static::GetValueG($key, $value, $stripSlashes);
        $value = static::GetValueP($key, $value, $stripSlashes);
        return $value;
    }

    /**
     * Check is set key in SESSION
     * 
     * @uses action_event() for 'request_isset_s'
     * @param string $key
     * @return bool 
     */
    public static function IssetKeyS($key)
    {
        return Session::IssetKey($key);
    }

    /**
     * Check is set key in GET
     * 
     * @uses action_event() for 'request_isset_g'
     * @param string $key
     * @return bool 
     */
    public static function IssetKeyG($key)
    {
        $isset = isset($_GET[$key]) ? true : false;
        return $isset;
    }

    /**
     * Check is set key in POST
     * 
     * @uses action_event() for 'request_isset_gp'
     * @param string $key
     * @return bool 
     */
    public static function IssetKeyP($key)
    {
        $isset = isset($_POST[$key]) ? true : false;
        return $isset;
    }

    /**
     * Check is set key in GET > POST
     * 
     * @uses action_event() for 'request_isset_gp'
     * @param string $key
     * @return bool 
     */
    public static function IssetKeyGP($key)
    {
        $isset = (static::IssetKeyG($key) || static::IssetKeyP($key)) ? true : false;
        return $isset;
    }

    /**
     * Check is set key in SESSION > GET > POST
     * 
     * @uses action_event() for 'request_isset_sgp'
     * @param string $key
     * @return bool 
     */
    public static function IssetKeySGP($key)
    {
        $isset = (Session::IssetKey($key) || static::IssetKeyG($key) || static::IssetKeyP($key)) ? true : false;
        return $isset;
    }
    
    /**
     * Set GET value for a key
     * 
     * @param string $key
     * @param mixed $value
     * @return boolean 
     */
    public static function SetValueG($key, $value=false)
    {
        if ($value === false) {
            $_GET[$key] = null;
            unset($_GET[$key]);
            return true;
        }
        return $_GET[$key] = $value;
    }
    
    /**
     * Set POST value for a key
     * 
     * @param string $key
     * @param mixed $value
     * @return boolean 
     */
    public static function SetValueP($key, $value=false)
    {
        if ($value === false) {
            $_POST[$key] = null;
            unset($_POST[$key]);
            return true;
        }
        return $_POST[$key] = $value;
    }
    
    /**
     * Get value of Cookie
     * @param String $name
     * @param String $default
     * @return mixed
     */
    public static function GetCookie($name, $default=null)
    {
        if (isset($_COOKIE) && isset($_COOKIE[$name]))
        {
            return $_COOKIE[$name];
        }
        return $default;
    }
    
    /**
     * Check if is set key in Cookies
     * @param String $name
     * @return Boolean
     */
    public static function IssetCookie($name)
    {
        return (isset($_COOKIE) && isset($_COOKIE[$name]));
    }
    
    /**
     * Set a Cookie value for a key
     * @param String $name
     * @param mixed $value
     * @param Int $duration
     * @return Boolean
     */
    public static function SetCookie($name, $value, $duration=86400)
    {
        return setcookie($name, $value, time()+$duration);
    }
    
    /**
     * Delete cookie
     * @param String $name
     * @return Boolean
     */
    public static function DeleteCookie($name)
    {
        return setcookie($name, '', time()-3600);
    }
}