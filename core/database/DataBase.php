<?php

namespace Core\database;

use PDO;
use PDOException;

/**
* This is a single-ton class for providing the connection link to database
**/
class DataBase
{
	private static $instance;

	private $connection;
    private $dBUserName;
    private $dBPassword;
    private $dBName;
    private $dBPort;

    private function __construct()
	{
	    $this->findInitiativeData();

	    $this->resolveConnection();
	}

	private function resolveConnection()
    {
        try {
            $this->connection = new PDO("mysql:host={$this->dBPort};dbname={$this->dBName}", $this->dBUserName, $this->dBPassword);
            $this->connection->exec("SET CHARACTER SET utf8");
        } catch(PDOException $e) {
            die($e->getMessage());
        }
    }

	private function findInitiativeData()
    {
        $this->findPort();
        $this->findDBName();
        $this->findUserName();
        $this->findPassword();
    }

    /**
     * ccalculates the "PORT" from config file located at config/database.php
     */
    private function findPort()
    {
        $this->dBPort = config('database.db_port');
    }

    /**
     * calculates the "DB_NAME" from config file located at config/database.php
     */
    private function findDBName()
    {
        $this->dBName = config('database.db_name');
    }

    /**
     * calculates the "User_Name" from config file located at config/database.php
     */
    private function findUserName()
    {
        $this->dBUserName = config('database.db_user_name');
    }

    /**
     * calculates the "DB_PASSWORD" from config file located at config/database.php
     */
    private function findPassword()
    {
        $this->dBPassword = config('database.db_password') ? config('database.db_password') : '';
    }

    /**
     * @return $this
     */
    public static function getInstance()
	{
		if (! isset(static::$instance)) {
			static::$instance = new self();
		}

		return static::$instance;
	}

    /**
     * returns the connection of database which stored in the connection property
     */
	public function connect()
	{
		return $this->connection;
	}
}