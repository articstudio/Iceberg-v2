<?php
namespace Iceberg\Database\PDO;

use Iceberg\Database\PDO\Exception;
use Iceberg\Core\Extend;

interface IConnector
{
}

abstract class Connector extends \Iceberg\Database\Connector implements \Iceberg\Database\PDO\IConnector
{
    private $_pdo;
    private $_sth;
    private $_done;
    private $_affected_rows;
    private $_input_parameters;
    
    protected function _connect($dsn, $username='', $password='', $options=[])
    {
        try
        {
            $this->_pdo = new \PDO($dsn, $username, $password, $options);
            $this->_pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
        catch (\PDOException $e)
        {
            throw new Exception($e->getMessage());
        }
    }

	public function query($statement)
	{
        $this->_set_last_query($statement);
        try
        {
            $this->_done = $this->_pdo->query($statement);
        }
        catch (\PDOException $e)
        {
            throw new Exception($e->getMessage());
        }
        return $this;
	}

	public function exec($statement)
	{
        $this->_set_last_query($statement);
        try
        {
            $start = microtime(true);
            $this->_affected_rows = $this->_pdo->exec($statement);
            $time = microtime(true) - $start;
            $this->_done = true;
            Extend::DoAction('database_query_send', $this->get_last_query(), $time, $this->_pdo->lastInsertId(), $this->_sth->rowCount());
        }
        catch (\PDOException $e)
        {
            throw new Exception($statement . $e->getMessage());
        }
        return $this;
	}
    
    public function get_error_code()
    {
        return $this->_pdo->errorCode();
    }
    
    public function get_error()
    {
        return $this->_pdo->errorInfo();
    }
    
    public function prepare($statement, $driver_options=[])
    {
        try
        {
            $this->_sth = $this->_pdo->prepare($statement, $driver_options);
            $this->_set_last_query($statement);
        }
        catch (\PDOException $e)
        {
            throw new Exception($e->getMessage());
        }
        return $this;
    }
    
    public function bindParam($parameter, &$variable, $data_type=\PDO::PARAM_STR, $length=null, $driver_options=null)
    {
        try
        {
            $this->_sth->bindParam($parameter, $variable, $data_type, $length, $driver_options);
        }
        catch (\PDOException $e)
        {
            throw new Exception($e->getMessage());
        }
        return $this;
    }
    
    public function bindValue($parameter, $value, $data_type=\PDO::PARAM_STR)
    {
        try
        {
            $this->_sth->bindValue($parameter, $value, $data_type);
        }
        catch (\PDOException $e)
        {
            throw new Exception($e->getMessage());
        }
        return $this;
    }
    
    public function execute($input_parameters=null)
    {
        $this->_input_parameters = $input_parameters;
        try
        {
            $start = microtime(true);
            $this->_done = $this->_sth->execute($this->_input_parameters);
            $time = microtime(true) - $start;
            $this->_set_last_query($this->_debug_query(false));
            Extend::DoAction('database_query_send', $this->get_last_query(), $time, $this->_pdo->lastInsertId(), $this->_sth->rowCount());
        }
        catch (\PDOException $e)
        {
            throw new Exception($e->getMessage());
        }
        return $this;
    }
    
    public function done($affected_rows=false)
    {
        return $affected_rows ? $this->_affected_rows : $this->_done;
    }
    
    public function last_insert_id($name=null)
    {
        return $this->_pdo->lastInsertId($name);
    }
    
    public function fetchAll()
    {
        return $this->_sth->fetchAll();
    }
    
	public function quote($string)
	{
		return $this->_pdo->quote($string);
	}
    
    
    protected function _debug_query($replaced=true)
    {
        if (!$replaced && is_array($this->_input_parameters) && !empty($this->_input_parameters))
        {
            return preg_replace_callback('/:([0-9a-z_]+)/i', [$this, '_debug_replace'], $this->get_last_query());
        }
        return $this->get_last_query();
    }

    protected function _debug_replace($m)
    {
        $v = $this->_input_parameters[$m[1]];
        if ($v === null)
        {
            return 'NULL';
        }
        if (!is_numeric($v))
        {
            $v = str_replace("'", "''", $v);
        }
        return "'". $v ."'";
    }
}

