<?php
namespace Iceberg\User;

class User extends \Iceberg\Abstracts\ObjectDatabaseRelation
{
    
    protected static $_DB_TABLE_NAME = 'users';
    
    protected static $_DB_TABLE_FIELDS = [
        'email' => [
            'name' => 'E-MAIL',
            'type' => 'VARCHAR',
            'length' => '250',
            'flags' => [
                'NOT NULL'
            ],
            'index' => true
        ],
        'username' => [
            'name' => 'USERNAME',
            'type' => 'VARCHAR',
            'length' => '50',
            'flags' => [
                'NOT NULL'
            ],
            'index' => true
        ],
        'password' => [
            'name' => 'PASSWORD',
            'type' => 'VARCHAR',
            'length' => '250',
            'flags' => [
                'NOT NULL'
            ],
            'index' => false
        ],
        'status' => [
            'name' => 'STATUS',
            'type' => 'TINYINT',
            'length' => '3',
            'flags' => [
                'NOT NULL',
                'DEFAULT \'1\''
            ],
            'index' => true
        ],
        'role' => [
            'name' => 'ROLE',
            'type' => 'BIGINT',
            'length' => '20',
            'flags' => [
                'NOT NULL'
            ],
            'index' => true
        ],
        'capabilities' => [
            'name' => 'CAPABILITIES',
            'type' => 'TEXT',
            'length' => null,
            'flags' => [
                'NOT NULL'
            ],
            'index' => false
        ],
        // http://stackoverflow.com/questions/4982701/best-way-to-store-ip-in-database
        // http://stackoverflow.com/questions/6427786/ip-address-storing-in-mysql-database
        // http://daipratt.co.uk/mysql-store-ip-address/
        'ip' => [
            'name' => 'LAST IP',
            'type' => 'INT',
            'length' => '11',
            'flags' => [
                'UNSIGNED'
            ],
            'index' => false
        ],
        'session' => [
            'name' => 'LAST SESSION',
            'type' => 'VARCHAR',
            'length' => '40',
            'flags' => [
                'NOT NULL'
            ],
            'index' => false
        ],
        'login' => [
            'name' => 'LAST LOGIN',
            'type' => 'TIMESTAMP',
            'length' => null,
            'flags' => [
                'NOT NULL'
            ],
            'index' => false
        ]
    ];
    
}