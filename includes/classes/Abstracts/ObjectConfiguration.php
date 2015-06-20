<?php
namespace Iceberg\Abstracts;

use Iceberg\Configuration\Configuration;
use Iceberg\Core\Normalize;
use Iceberg\Core\Extend;
use Iceberg\I18N\I18N;

abstract class ObjectConfiguration
{
    protected static $USE_LANGUAGE = false;
    protected static $KEY = 'object_config';
    protected static $DEFAULTS = [];
    
    public static function GetUseLanguage()
    {
        return static::$USE_LANGUAGE;
    }
    
    public static function GetKey()
    {
        return static::$KEY;
    }
    
    public static function GetDefaults()
    {
        return static::$DEFAULTS;
    }
    
    public static function SetConfig($value=null)
    {
        return Configuration::Set(static::$KEY, $value);
    }
    
    public static function UnsetConfig()
    {
        return static::SetConfig();
    }
    
    public static function SaveConfig($value=null, $locale=null)
    {
        $value = static::Normalize(Extend::ApplyFilters('object_save_config', $value, get_called_class(), $locale));
        if (static::$CONFIG_USE_LANGUAGE)
        {
            $locale = $locale === null ? I18N::GetLocale() : $locale;
        }
        else
        {
            $locale = null;
        }
        return Configuration::Save(static::$KEY, $value, $locale);
    }
    
    public static function Normalize($config)
    {
        return $config===null ? $config : Normalize::Merge(static::$DEFAULTS, $config);
    }
}