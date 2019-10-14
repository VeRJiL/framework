<?php

namespace Core\database;

use Exception;
use PDO;

class QueryBuilder
{
	protected $tableName;

    private $connection;
    private $instance;
    private $sql;

    /**
     * QueryBuilder constructor.
     * @param array $data
     */
    public function __construct($data = [])
	{
		$this->getTableName();

		$this->instance = DataBase::getInstance();

		$this->connection = $this->instance->connect();
	}

    /**
     * @return array
     */
    public function all()
	{
		$this->sql = sprintf("SELECT * FROM %s", $this->tableName);

		$statement = $this->connection->prepare($this->sql);

		$statement->execute();

		$collection = $statement->fetchAll(PDO::FETCH_CLASS);

		return $collection;
	}

    /**
     * @param $data
     */
    public function insert($data)
	{
        $this->sql = 	sprintf(
			"INSERT INTO %s (%s) values (%s)",
			$this->tableName,
			implode(', ', array_keys($data)),
			':' . implode(', :', array_keys($data))
		);

		try {
			
		$statement = $this->connection->prepare($this->sql);

		$statement->execute($data);

		} catch (Exception $e) {
			die('Whoops, Something went wrong');
		}
	}

    /**
     *
     */
    private function getTableName()
	{
	    if ($this->tableName === null) {
            $classNameArray = explode('\\', get_called_class());

            $lastIndex = count($classNameArray) - 1;

            $this->tableName = strtolower($classNameArray[$lastIndex]) . 's';

            return $this->tableName;
        }
    }
}