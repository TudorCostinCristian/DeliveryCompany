<?php
require_once("includes/Config.php");
if(isset($_SESSION['AngajatID'])){
		$data = $_GET;
		if(!isset($data['action']))
		{
			Header("Location: preluarecomenzi.php");
		}
		if(!isset($data['angajatid']))
		{
			Header("Location: preluarecomenzi.php");
		}
		if(!isset($data['coletid']))
		{
			Header("Location: preluarecomenzi.php");
		}
		
		if($data['action'] == 1){
			$query = 'CALL StatusColet(' . $data['coletid'] . ', 3);';
			mysqli_query($DB, $query);
			$query = 'CALL ColectorColet(' . $data['coletid'] . ', ' . $data['angajatid'] . ');';
			mysqli_query($DB, $query);
			echo 'OK!';
			Header("Location: preluarecomenzi.php");
		}
		if($data['action'] == 2){
			$query = 'CALL StatusColet(' . $data['coletid'] . ', 4);';
			mysqli_query($DB, $query);
			echo 'OK!';
			Header("Location: coletepreluate.php");
		}
		if($data['action'] == 3){
			$query = 'CALL StatusColet(' . $data['coletid'] . ', 5);';
			mysqli_query($DB, $query);
			echo 'OK!';
			Header("Location: coletepreluate.php");
		}
}
else{
	Header('Location: login.php');
}

?>