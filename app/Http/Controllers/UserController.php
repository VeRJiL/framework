<?php

namespace App\Http\Controllers;

use Core\database\QueryBuilder;
use Core\Request;

class UserController extends Controller
{

	public function index()
	{
		$users = new QueryBuilder('users');

		$users = $users->all();

		view("user", compact('users'));
	}


	public function store()
	{
		$DBObj = new QueryBuilder('users');	

		$DBObj->insert(Request::all());

		redirect('/users');
	}
}