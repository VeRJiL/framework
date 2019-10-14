<?php

namespace App\models;

use Core\database\QueryBuilder;

class Article extends QueryBuilder
{
    protected $tableName = 'articles';
}