<?php
namespace Iceberg\Database;

//use Iceberg\Core\Exception;

interface IConnector
{
    function __construct($host, $port, $user, $password, $database);
    function query($query);
    function get_error_code();
    function get_error();
    
    function table_exists($table_name);
    function create_table($table_name, $fields);
    function drop_table($table_name);
    function insert($table_name, $fields, $values);
    function delete($table_name, $where, $apply_table='');
}

abstract class Connector implements IConnector
{
    private $_query_string = '';
    
    protected function _connect($host, $username='', $password='', $options=[])
    {}

	public function get_last_query()
	{
		return $this->_query_string;
	}
    
    protected function _set_last_query($query)
    {
        $this->_query_string = $query;
    }
}

