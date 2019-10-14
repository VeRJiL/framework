<?php

namespace App\Http\Controllers;

use App\models\Article;

class PagesController extends Controller
{
    /**
     * @param Article $article
     * @return mixed
     */
    public function index(Article $article)
	{
	    $articles = $article->all();

	    dd($articles);

		return view("index");
	}
}