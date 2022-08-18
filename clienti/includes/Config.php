<?php

session_start();

$mysqli = array (
	'SQL_HOST' => 'localhost',
	'SQL_USER' => 'root',
	'SQL_PASS' => '',
	'SQL_DB'   => 'firma_curierat'
);

$DB = mysqli_connect($mysqli['SQL_HOST'], $mysqli['SQL_USER'], $mysqli['SQL_PASS'], $mysqli['SQL_DB']) or header('Location: /temaBD/clienti/mentenanta.php');
mysqli_query($DB, "SET NAMES utf8");

if(isset($_GET["logout"])) 
{
	unset($_SESSION['Email']);
	unset($_SESSION['Parola']);
	unset($_SESSION['ClientID']);
    
	Header('Location: login.php');
}

?>