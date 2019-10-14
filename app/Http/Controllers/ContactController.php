<?php

namespace App\Http\Controllers;

class ContactController extends Controller
{
	public function index()
	{
		view("contact");
	}

	public function add()
	{
		var_dump($_POST);
	}
}