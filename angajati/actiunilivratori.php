<?php
require_once("includes/Config.php");
if(isset($_SESSION['AngajatID'])){
		$data = $_GET;
		if(!isset($data['action']))
		{
			Header("Location: ridicarecolet.php");
		}
		if(!isset($data['angajatid']))
		{
			Header("Location: ridicarecolet.php");
		}
		if(!isset($data['coletid']))
		{
			Header("Location: ridicarecolet.php");
		}
		
		if($data['action'] == 1){
			$query = 'CALL StatusColet(' . $data['coletid'] . ', 6);';
			mysqli_query($DB, $query);
			$query = 'CALL LivratorColet(' . $data['coletid'] . ', ' . $data['angajatid'] . ');';
			mysqli_query($DB, $query);
			echo 'OK!';
			Header("Location: ridicarecolet.php");
		}
		if($data['action'] == 2){
			$query = 'CALL StatusColet(' . $data['coletid'] . ', 7);';
			mysqli_query($DB, $query);
			echo 'OK!';
			Header("Location: coleteridicate.php");
		}
}
else{
	Header('Location: login.php');
}

?>