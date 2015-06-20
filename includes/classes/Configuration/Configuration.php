<?php
namespace Iceberg\Configuration;

use Iceberg\I18N\I18N;

class Configuration extends \Iceberg\Abstracts\ObjectDatabaseRelation
{
    
    protected static $_DB_TABLE_NAME = 'configuration';
    
    protected static $_DB_TABLE_FIELDS = [
        'name' => [
            'name' => 'CONFIGURATION NAME',
            'type' => 'VARCHAR',
            'length' => '150',
            'flags' => [
                'NOT NULL'
            ],
            'index' => true
        ],
        'value' => [
            'name' => 'CONFIGURATION VALUE',
            'type' => 'LONGTEXT',
            'length' => null,
            'flags' => [
                'NOT NULL'
            ],
            'index' => false
        ],
        'language' => [
            'name' => 'CONFIGURATION LANGUAGE',
            'type' => 'VARCHAR',
            'length' => '150',
            'flags' => [
                'NOT NULL'
            ],
            'index' => true
        ],
        'autoload' => [
            'name' => 'CONFIGURATION AUTOLOAD',
            'type' => 'TINYINT',
            'length' => '1',
            'flags' => [
                'NOT NULL',
                'DEFAULT \'0\''
            ],
            'index' => true
        ]
    ];
    
    private static $_CONFIG = [];
    
    public static function Set($key, $value=null)
    {
        if ($value === null)
        {
            static::$_CONFIG[$key] = null;
            unset(static::$_CONFIG[$key]);
        }
        else
        {
            static::$_CONFIG[$key] = $value;
        }
        return true;
    }
    
    public static function Save($key, $value=null, $locale=null)
    {
        $done = ($locale === null || $locale === I18N::GetLocale()) ? static::Set($key, $value) : true;
        if ($done)
        {
            if ($value === null)
            {
                
            }
            else
            {
                
            }
        }
        return $done;
    }
    
}