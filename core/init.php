<?php
session_start();
// core of the project
$GLOBALS['config'] = array(
    // DB
    'mysql' => array(
        'host' => '127.0.0.1',
        'username' => 'root',
        'password' => '',
        'db' => 'swapi-app'
    ),
);

// includes all classes
spl_autoload_register(function ($class) {
    require_once 'classes/' . $class . '.php';
});