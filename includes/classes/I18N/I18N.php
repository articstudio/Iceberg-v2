<?php
namespace Iceberg\I18N;

class I18N extends \Iceberg\Abstracts\ObjectConfiguration
{
    const DOMAIN = 'iceberg';
    
    public static $KEY = 'i18n_config';
    
    private static $_DEFAULT_LOCALES = [
        'English' => 'en_US',
        'Català' => 'ca',
        'Español' => 'es_ES'
    ];
    private static $_LOCALES = [];
    private static $_LOCALE;
    private static $_LANG;
    
    
    public static function Initialize()
    {
        static::$_LOCALES = static::$_DEFAULT_LOCALES;
        static::LoadDefault();
        static::BindTextDomain(static::DOMAIN, ICEBERG_DIR_CONTENT . 'languages' . DIRECTORY_SEPARATOR);  
        textdomain(static::DOMAIN);
        bind_textdomain_codeset(static::DOMAIN, 'UTF-8');
    }
    
    public static function LoadDefault()
    {
        $language = defined('ICEBERG_LANGUAGE') ? ICEBERG_LANGUAGE : reset(static::$_DEFAULT_LOCALES);
        return static::LoadLanguage($language);
    }
    
    public static function LoadLanguage($locale)
    {
        //http://stackoverflow.com/questions/8802093/how-to-run-or-load-po-mo-files-for-localization-in-php
        if (in_array($locale, static::$_LOCALES))
        {
            static::$_LOCALE = $locale;
            $lang = explode('_', $locale);
            static::$_LANG = $lang[0];
            putenv('LANG=' . $locale); 
            setlocale(LC_ALL, $locale);
            return true;
        }
        return false;
    }
    
    public static function GetDefaultLocales()
    {
        return static::$_DEFAULT_LOCALES;
    }
    
    public static function GetLocales()
    {
        return static::$_LOCALES;
    }
    
    public static function GetLocale()
    {
        return static::$_LOCALE;
    }
    
    public static function GetLang()
    {
        return static::$_LANG;
    }
    
    public static function BindTextDomain($domain, $directory)
    {
        return bindtextdomain($domain, $directory); 
    }
    
    public static function T($message, $domain=null)
    {
        
        if ($domain === null)
        {
            $rtext = gettext($message);
        }
        else
        {
            $rtext = dgettext($domain , $message);
        }
        return $rtext;
    }
    
    public static function E($message, $domain=null)
    {
        echo static::T($message, $domain);
    }
    
    public static function N($msgid1, $msgid2, $n, $domain=null)
    {
        
        if ($domain === null)
        {
            $rtext = ngettext($msgid1, $msgid2, $n);
        }
        else
        {
            $rtext = dngettext($domain , $msgid1, $msgid2, $n);
        }
        return $rtext;
    }
    
    public static function NE($msgid1, $msgid2, $n, $domain=null)
    {
        echo static::N($msgid1, $msgid2, $n, $domain);
    }
}