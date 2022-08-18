<?php
require_once("../includes/Config.php");
if(isset($_POST['friendcode']) && isset($_SESSION['ClientID']) && !empty($_POST['friendcode'])){
	$friendcode = mysqli_real_escape_string($DB, $_POST['friendcode']);
	$clientID = mysqli_real_escape_string($DB, $_SESSION['ClientID']);
	
	
	$query = 'SELECT ActiveazaCodAfiliat(' . $clientID . ', "' . $friendcode . '");';
	$result = mysqli_query($DB, $query);
	mysqli_next_result($DB);
	$response = mysqli_fetch_array($result, MYSQLI_BOTH);
	echo $response[0];
	return;
}
else echo -1;
?>