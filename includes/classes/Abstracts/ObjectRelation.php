<?php
namespace Iceberg\Abstracts;

abstract class ObjectRelation extends \Iceberg\Abstracts\ObjectDatabase
{
    
    protected static $_DB_TABLE_NAME = 'relations';
    
    protected static $_DB_TABLE_FIELDS = [
        'pid' => [
            'name' => 'PARENT ID',
            'type' => 'BIGINT',
            'length' => '20',
            'flags' => [
                'NOT NULL'
            ],
            'index' => true
        ],
        'cid' => [
            'name' => 'CHILD ID',
            'type' => 'BIGINT',
            'length' => '20',
            'flags' => [
                'NOT NULL'
            ],
            'index' => true
        ],
        'name' => [
            'name' => 'RELATION NAME',
            'type' => 'VARCHAR',
            'length' => '150',
            'flags' => [
                'NOT NULL'
            ],
            'index' => true
        ],
        'attribute' => [
            'name' => 'RELATION ATTRIBUTE',
            'type' => 'VARCHAR',
            'length' => '150',
            'flags' => [
                'NOT NULL'
            ],
            'index' => true
        ],
        'language' => [
            'name' => 'RELATION LANGUAGE',
            'type' => 'VARCHAR',
            'length' => '150',
            'flags' => [
                'NOT NULL'
            ],
            'index' => true
        ],
        'count' => [
            'name' => 'RELATION ORDER',
            'type' => 'BIGINT',
            'length' => '20',
            'flags' => [
                'NOT NULL',
                'DEFAULT \'1\''
            ],
            'index' => true
        ]
    ];
    
    public static $DB_PARENT_FIELD = 'pid';
    
    public static $DB_CHILD_FIELD = 'cid';
    
    public static $DB_NAME_FIELD = 'name';
    
    public static $DB_ATTRIBUTE_FIELD = 'attribute';
    
    public static $DB_LANGUAGE_FIELD = 'language';
    
    public static $DB_COUNT_FIELD = 'count';
    
}

