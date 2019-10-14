<?php

namespace App\models;

use Core\database\QueryBuilder;

class User extends QueryBuilder
{

    protected $tableName = 'users';

	public $username;
	
	public $password;
}