<?php

require 'vendor/autoload.php';

/*
 * Connexion à la base de données
*/
define('HOST', 'localhost');
define('USER', 'root');
define('PASS', '');
define('DB', 'cookie');

$options_db = array(
	PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
	PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
	PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
);

$db = new PDO("mysql:host=".HOST.";dbname=".DB, USER, PASS, $options_db);

session_start();

$url = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$url = preg_replace('/\?.*/', '', $url);
?>