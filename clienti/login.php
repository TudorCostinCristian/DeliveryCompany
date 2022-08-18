<?php
require_once("includes/Config.php");
if(isset($_SESSION['ClientID']) && isset($_SESSION['Parola'])){
	Header("Location: index.php");
	return 1;
}

if(isset($_POST['login'])) 
{
	$email = mysqli_real_escape_string($DB, addslashes($_POST['email']));
	$parola = mysqli_real_escape_string($DB, addslashes($_POST['parola']));
	$hash1 = hash( 'whirlpool', $parola);
	$hash = strtoupper($hash1);
	$query = 'CALL Autentificare("' . $email . '", "' . $hash . '");';
	$result = mysqli_query($DB, $query);
	mysqli_next_result($DB);
	$user = mysqli_fetch_array($result, MYSQLI_ASSOC);
	
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$msg = "Te rugam sa introduci un email valid!";
	}
	else if($user['ClientID'] == false)
	{
		$msg = "Datele introduse sunt gresite!";
	}
	else if($user['ContActivat'] == 0)
	{
		$msg = "Contul tau nu este activat! Te rugam sa il activezi folosind adresa de email!";
	}
	else
	{
		$_SESSION['Email'] = $email;
		$_SESSION['Parola'] = $hash;
		$_SESSION['ClientID'] = $user['ClientID'];
		header('Location: /temaBD/clienti/index.php'); 
	}
}
?>


<html>

<head>
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <title>Firma Curierat - Autentificare</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<style>
	body{
	    background: url('images/background.jpg') no-repeat center;
		background-size: cover;
		background-attachment: fixed;
	}
	.login{
		height:40%;
		width:40%;
		min-height:320px;
		min-width:350px;
		position:absolute;
		top:25%;
		left:30%;
		background-color:rgba(34, 34, 34, 0.97);
		border: 1px solid black;
		border-radius:20px;
		padding:25px;
		text-align:center;
	}
	
	input{
		width: 80%;
		padding: 12px 20px;
		margin: 8px 0;
		display: inline-block;
		border: 1px solid #ccc;
		border-radius: 4px;
		box-sizing: border-box;
	}
	
	.errorMsg{
		position:absolute;
		width:72%;
		background-color:red;
		bottom: 15px;
		left:12%;
		border-radius:5px;
		margin: 8px 0;
		color:white;
		padding: 12px 20px;
		right:10%;
		font-family:Verdana;
	}
	
	#register{
		color:white;
		font-family: Verdana;
	}
	
	#auth{
		margin:15px;
		width:160px;
		height:40px;
	}
	
	a{
		color:darkorange;
		text-decoration: none;
	}
	
	
	#auth:hover{
		cursor:pointer;
	}
	</style>
</head>

<body>

<div class="login">
<img src="images/logo.png" alt="LOGO"><br><font size="4" face="Trebuchet MS" color="blue">PANEL CLIENTI</font><hr>
<form method="post" action="">
    <input type="text" name="email" id="email" placeholder="Email"><br>
	<input type="password" name="parola" id="parola" placeholder="Parola"><br>
	<button id="auth" type="submit" name="login" >Autentificare</button><br>
	<div id="register"> Nu ai inca un cont? <a href="register.php"><i>Inregistreaza-te!</i></a></div>
	<?php if(isset($msg)) echo '<div class="errorMsg">' . $msg . '</div>'; ?>
</form>
</div>



</body>

</html>

