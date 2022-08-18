<?php
require_once("../includes/Config.php");
if(isset($_POST['codetoset']) && isset($_SESSION['ClientID']) && !empty($_POST['codetoset']))
{
	$codetoset = mysqli_real_escape_string($DB, $_POST['codetoset']);
	$clientID = mysqli_real_escape_string($DB, $_SESSION['ClientID']);
	
	if(strlen($codetoset) < 4)
	{
		echo 1;
		return;
	}
	if(strlen($codetoset) > 15)
	{
		echo 2;
		return;
	}
	
	if (preg_match('/[^A-Za-z0-9]/', $codetoset))
	{
		echo 3;
		return;
	}
	
	
	$query = 'SELECT SeteazaCodAfiliat(' . $clientID . ', "' . $codetoset . '");';
	$result = mysqli_query($DB, $query);
	mysqli_next_result($DB);
	$response = mysqli_fetch_array($result, MYSQLI_BOTH);
	echo $response[0];
	return;
}
else echo -1;
?>