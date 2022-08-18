<?php
require_once("includes/Config.php");
if(isset($_SESSION['AngajatID'])){
		$data = $_GET;
		if(!isset($data['action']))
		{
			Header("Location: clienti.php");
		}
		if(!isset($data['clientid']))
		{
			Header("Location: clienti.php");
		}
		$query = 'CALL ActiuniClienti(' . $data['clientid'] . ', ' . $data['action'] . ');';
		mysqli_query($DB, $query);
		echo 'OK!';
		Header("Location: clienti.php");
}
else{
	Header('Location: login.php');
}

?>