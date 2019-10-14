<?php


/**
 * Die and Dump the given value.
 *
 * @param  $value
 */
function dd($value)
{
    view('dd', compact('value'));
    die();
}


/**
 * Get the evaluated view contents for the given view.
 *
 * @param  string $view
 * @param  array $data
 * @return mixed
 */
function view($view = null, $data = [])
{
    extract($data);
    return require __DIR__ . "/../resources/views/{$view}.view.php";
}


function redirect($path)
{
    header("Location: {$path}");
}

function config($configString)
{
    $configStringArray = explode('.', $configString);

    // There must be Exception throw for situations which file doesn't exist
    $configArray =  require (__DIR__ . "/../config/" . $configStringArray[0] . '.php');

    return $configArray[$configStringArray[1]];
}
