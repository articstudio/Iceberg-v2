<?php
namespace Iceberg\Abstracts;

use Iceberg\Database\Database;
//use Iceberg\Core\Normalize;

abstract class ObjectDatabase
{
    
    protected static $_DB_TABLE_NAME = '';
    
    protected static $_DB_DEFAULT_FIELD = [
        'name' => '',
        'type' => 'INT',
        'length' => '0',
        'flags' => [],
        'index' => false
    ];
    
    protected static $_DB_DEFAULT_TABLE_FIELDS = [
        'id' => [
            'name' => 'ID',
            'type' => 'BIGINT',
            'length' => '20',
            'flags' => [
                'NOT NULL',
                'AUTO_INCREMENT',
                'PRIMARY KEY'
            ],
            'index' => false
        ]
    ];
    
    protected static $_DB_TABLE_FIELDS = [];
    
    public static function DB_GetTableName()
    {
        return ICEBERG_DB_PREFIX . static::$_DB_TABLE_NAME;
    }
    
    public static function DB_GetFields()
    {
        return static::_DB_NormalizeFields(
            array_merge(static::$_DB_DEFAULT_TABLE_FIELDS, static::$_DB_TABLE_FIELDS)
        );
    }
    
    public static function DB_TableExists()
    {
        return Database::GetConnector()->table_exists(static::DB_GetTableName());
    }
    
    public static function DB_CreateTable()
    {
        return Database::GetConnector()->create_table(static::DB_GetTableName(), static::DB_GetFields());
    }
    
    public static function DB_DropTable()
    {
        return Database::GetConnector()->drop_table(static::DB_GetTableName());
    }
    
    public static function DB_Insert($args)
    {
        $t = static::DB_GetTableName();
        $fields = static::_DB_FilterFields($args);
        return Database::GetConnector()->insert(
            $t,
            array_keys($fields),
            array_map(['\Iceberg\Core\Normalize', 'Encode'], array_values($fields))
        );
    }
    
    public static function DB_Delete($args)
    {
        $t = static::DB_GetTableName();
    }
    
    
    protected static function _DB_IsField($field)
    {
        $o_fields = static::DB_GetFields();
        return (is_string($field) && isset($o_fields[$field]));
    }
    
    protected static function _DB_FilterFields($fields)
    {
        foreach ($fields AS $k => $v)
        {
            if (!static::_DB_IsField($k))
            {
                $fields[$k] = null;
                unset($fields[$k]);
            }
        }
        return $fields;
    }
    
    protected static function _DB_NormalizeField($field)
    {
        return array_merge(static::$_DB_DEFAULT_FIELD, (!is_array($field) ? [] : $field));
    }
    
    protected static function _DB_NormalizeFields($fields)
    {
        return array_map([get_called_class(), '_DB_NormalizeField'], (!is_array($fields) ? [] : $fields));
    }
}