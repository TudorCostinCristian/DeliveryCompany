<?php
require_once("includes/Config.php");
if(isset($_SESSION['AngajatID'])){
		$data = $_GET;
		if(!isset($data['promotieid']))
		{
			Header("Location: crearepromotii.php");
		}
		if(!isset($data['action']))
		{
			Header("Location: crearepromotii.php");
		}
		$query = 'CALL ActiuniPromotii(' . $data['action'] . ', ' . $data['promotieid'] . ');';
		mysqli_query($DB, $query);
		echo 'OK!';
		Header("Location: crearepromotii.php");
}
else{
	Header('Location: login.php');
}

?>