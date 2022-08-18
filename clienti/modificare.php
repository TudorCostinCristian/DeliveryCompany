<?php
require_once("includes/Config.php");
if(isset($_SESSION['ClientID'])){
		$data = $_GET;
		if(!isset($data['action']))
		{
			Header("Location: colete.php");
		}
		if(!isset($data['coletid']))
		{
			Header("Location: colete.php");
		}
		if($data['action'] == 1){
			$query = 'CALL StatusColet(' . $data['coletid'] . ', 2);';
			mysqli_query($DB, $query);
			Header("Location: colete.php");
		}
		else if($data['action'] == 2){
			$query = 'CALL StatusColet(' . $data['coletid'] . ', 0);';
			mysqli_query($DB, $query);
			Header("Location: colete.php");
		}
}
else{
	Header('Location: login.php');
}

?>