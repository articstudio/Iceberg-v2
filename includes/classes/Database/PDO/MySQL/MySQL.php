<?php
namespace Iceberg\Database\PDO\MySQL;

//use Iceberg\Database\MySQL\Error;

class MySQL extends \Iceberg\Database\PDO\Connector
{
   
    function __construct($host, $port, $user, $password, $database)
    {
        $dsn = 'mysql:host=' . $host . ';port=' . $port . ';dbname=' . $database;
        $this->_connect($dsn, $user, $password);
        //$this->exec('SET SQL_MODE=ANSI_QUOTES');
    }
    
    public function table_exists($table_name)
    {
        $tables = $this->prepare('SHOW TABLES LIKE :table_name')
            ->execute(['table_name' => $table_name])
            ->fetchAll();
        return count($tables) === 1;
    }
    
    public function create_table($table_name, $fields)
    {
        $indexs = [];
        $sql = ' CREATE TABLE ' . $table_name . ' (';
        foreach ($fields AS $field => $attrs)
        {
            $sql .= $field . ' ' . $attrs['type'] . ($attrs['length']===null ? ' ' : '( ' . $attrs['length'] . ' ) ') . implode(' ', $attrs['flags']) . ', ';
            if ($attrs['index'])
            {
                $indexs[] = $field;
            }
        }
        $sql = (empty($indexs) ? substr($sql, 0, -1) : $sql . ' INDEX(' . implode('), INDEX(', $indexs) . ')') . ')';
        return $this->exec($sql)
            ->done();
    }
    
    public function drop_table($table_name)
    {
        return $this->exec('DROP TABLE IF EXISTS ' . $table_name)
            ->done();
    }
    
    public function insert($table_name, $fields, $values)
    {
        $sql = "INSERT INTO `" . $table_name . "` (`" . implode("`,`", $fields) . "`) VALUES (:" . implode(" , :", $fields) . ")";
        //$input_parameters = $this->_build_execute_input_parameters($fields, $values);
        $input_parameters = array_combine($fields, $values);
        return $this->prepare($sql)
            ->execute($input_parameters)
            ->last_insert_id();
    }
    
    public function delete($table_name, $where, $apply_table='')
    {
        return false;
    }
    
    protected function _build_execute_input_parameters($fields, $values)
    {
        $input_parameters = [];
        if (count($fields) === count($values))
        {
            foreach($fields AS $k => $field)
            {
                $input_parameters['' . $field] = $values[$k];
            }
        }
        return $input_parameters;
    }
}

