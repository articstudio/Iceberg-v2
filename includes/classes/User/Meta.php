<?php
namespace Iceberg\User;

class Meta extends \Iceberg\Abstracts\ObjectDatabaseRelation
{
    
    protected static $_DB_TABLE_NAME = 'users_metas';
    
    protected static $_DB_TABLE_FIELDS = [
        'name' => [
            'name' => 'META NAME',
            'type' => 'VARCHAR',
            'length' => '150',
            'flags' => [
                'NOT NULL'
            ],
            'index' => true
        ],
        'value' => [
            'name' => 'META VALUE',
            'type' => 'LONGTEXT',
            'length' => null,
            'flags' => [
                'NOT NULL'
            ],
            'index' => false
        ]
    ];
    
}