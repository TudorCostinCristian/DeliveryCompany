<?php

session_start();

$mysqli = array (
	'SQL_HOST' => 'localhost',
	'SQL_USER' => 'root',
	'SQL_PASS' => '',
	'SQL_DB'   => 'firma_curierat'
);

$DB = mysqli_connect($mysqli['SQL_HOST'], $mysqli['SQL_USER'], $mysqli['SQL_PASS'], $mysqli['SQL_DB']);
mysqli_query($DB, "SET NAMES utf8");

if(isset($_GET["logout"])) 
{
	unset($_SESSION['EmailAng']);
	unset($_SESSION['ParolaAng']);
	unset($_SESSION['AngajatID']);
    
	Header('Location: login.php');
}

?>