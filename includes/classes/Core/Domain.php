<?php
namespace Iceberg\Core;

class Domain extends \Iceberg\Abstracts\ObjectDatabase
{
    
    protected static $_DB_TABLE_NAME = 'domains';
    
    protected static $_DB_TABLE_FIELDS = [
        'pid' => [
            'name' => 'PARENT DOMAIN ID',
            'type' => 'BIGINT',
            'length' => '20',
            'flags' => [
                'NOT NULL'
            ],
            'index' => true
        ],
        'name' => [
            'name' => 'DOMAIN NAME',
            'type' => 'VARCHAR',
            'length' => '150',
            'flags' => [
                'NOT NULL'
            ],
            'index' => true
        ]
    ];
    
    private static $_ID;
    
    private $_id;
    private $_name;
    private $_parent;
    private $_childs;
    
    public static function Initialize()
    {
        
    }
    
    public static function SetID($domain_id)
    {
        static::$_ID = $domain_id;
    }
    
    public static function GetID()
    {
        return static::$_ID;
    }
    
    public static function ResetConfiguration($domain_id)
    {
        $actual_domain_id = static::GetID();
        static::SetID($domain_id);
        
        
        
        static::SetID($actual_domain_id);
    }
    
    public static function Insert($domain, $pid=null)
    {
        return static::DB_Insert($pid ? ['pid' => $pid, 'name' => $domain] : ['name' => $domain]);
    }
}

