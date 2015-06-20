<?php
namespace Iceberg\Theme;

use Iceberg\Core\Bootstrap;
use Iceberg\Install\Request;
use Iceberg\Template\Template;

interface ITheme
{
    public static function Initialize();
}

abstract class Theme
{
    protected static $_DIR;
    protected static $_URL;
    protected static $_VENDOR_DIR;
    protected static $_VENDOR_URL;
    protected static $_UPLOADS_DIR;
    protected static $_UPLOADS_URL;
    protected static $_TMP_DIR;
    protected static $_TMP_URL;
    
    public static function Initialize()
    {
        
        static::$_DIR = Bootstrap::GetValue('root');
        static::$_URL = Request::GetBaseURL();
        static::$_VENDOR_DIR = ICEBERG_DIR_INCLUDES . 'vendor/';
        static::$_VENDOR_URL = str_replace(Bootstrap::GetValue('root'), Request::GetBaseURL(), static::$_VENDOR_DIR);
        static::$_UPLOADS_DIR = ICEBERG_DIR_CONTENT . 'uploads/';
        static::$_UPLOADS_URL = str_replace(Bootstrap::GetValue('root'), Request::GetBaseURL(), static::$_UPLOADS_DIR);
        static::$_TMP_DIR = ICEBERG_DIR_CONTENT . 'tmp/';
        static::$_TMP_URL = str_replace(Bootstrap::GetValue('root'), Request::GetBaseURL(), static::$_TMP_DIR);
    }
    
    public static function GetDIR()
    {
        return static::$_DIR;
    }
    
    public static function GetURL()
    {
        return static::$_URL;
    }
    
    public static function GetVendorDIR()
    {
        return static::$_VENDOR_DIR;
    }
    
    public static function GetVendorURL()
    {
        return static::$_VENDOR_URL;
    }
    
    public static function GetUploadsDIR()
    {
        return static::$_UPLOADS_DIR;
    }
    
    public static function GetUploadsURL()
    {
        return static::$_UPLOADS_URL;
    }
    
    public static function GetTmpDIR()
    {
        return static::$_TMP_DIR;
    }
    
    public static function GetTmpURL()
    {
        return static::$_TMP_URL;
    }
    
    public static function Template($file, $print=true, $callback=null)
    {
        $t = new Template(static::$_DIR, $file, $callback);
        if ($print)
        {
            $t->generate_content()->print_content();
        }
        else
        {
            return $t->generate_content()->get_content();
        }
    }
}