<?php

namespace Core;

class Request 
{
	public static function uri()
	{
		$uri = trim(
			parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'
			);

		if (empty($uri)) {
		    return '/';
        } else {
		    return $uri;
        }
	}	

	public static function requestMethod()
	{
		return $_SERVER['REQUEST_METHOD'];
	}

	public static function all()
	{
		$post = $_POST;
		unset($post['submit']);
		return $post;
	}
}