<?php

session_start();

$GLOBALS['config'] = array(
    // database connect configuration
    'mysql' => array(
        'host' => '127.0.0.1',
        'username' => 'root',
        'password' => '',
        'db' => 'finalproject'
    ),
    // store the session
    'session' => array(
        'session_name' => 'user',
        'token_name' => 'token'
    )
);

// auto loading the files from classes folder
spl_autoload_register(function($class){
    require_once('classes/' . $class . '.php');
});

// removes special characters
function escape($string){
    return htmlentities($string, ENT_QUOTES, 'UTF-8');
}