<?php
namespace Iceberg\Taxonomy;

class Taxonomy extends \Iceberg\Abstracts\ObjectDatabaseRelation
{
    
    protected static $_DB_TABLE_NAME = 'taxonomies';
    
    protected static $_DB_TABLE_FIELDS = [
        'type' => [
            'name' => 'TAXONOMY TYPE',
            'type' => 'VARCHAR',
            'length' => '150',
            'flags' => [
                'NOT NULL'
            ],
            'index' => true
        ],
        'name' => [
            'name' => 'TAXONOMY NAME',
            'type' => 'VARCHAR',
            'length' => '150',
            'flags' => [
                'NOT NULL'
            ],
            'index' => true
        ],
        'value' => [
            'name' => 'TAXONOMY VALUE',
            'type' => 'LONGTEXT',
            'length' => null,
            'flags' => [
                'NOT NULL'
            ],
            'index' => false
        ],
        'autoload' => [
            'name' => 'TAXONOMY AUTOLOAD',
            'type' => 'TINYINT',
            'length' => '1',
            'flags' => [
                'NOT NULL',
                'DEFAULT \'0\''
            ],
            'index' => true
        ]
    ];
    
}