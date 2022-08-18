<?php
require_once("includes/Header.php");

if(isset($_POST['register'])) 
{
	if(!isset($_POST['nume']) || !isset($_POST['prenume']) || !isset($_POST['sex']) || 
	!isset($_POST['email']) || !isset($_POST['telefon']) || !isset($_POST['parola']) || !isset($_POST['CNP']))
	{
		$msg = "Campurile marcate cu * sunt obligatorii!";
	}
	else{
		
	$nume = mysqli_real_escape_string($DB, $_POST['nume']);
	$prenume = mysqli_real_escape_string($DB, $_POST['prenume']);
	
	$sex = $_POST['sex'];
	if($sex != 'M' && $sex != 'F')
	{
		$msg = "Sexul trebuie sa fie Masculin/Feminin!";
	}

	$email = mysqli_real_escape_string($DB, $_POST['email']);
	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
	{
		$msg = "Te rugam sa introduci un email valid!";
	}

	$telefon = mysqli_real_escape_string($DB, $_POST['telefon']);
	$CNP = mysqli_real_escape_string($DB, $_POST['CNP']);
	
	$parola = mysqli_real_escape_string($DB, $_POST['parola']);
	$hash1 = hash( 'whirlpool', $parola);
	$hash = strtoupper($hash1);
	
	if(!isset($msg))
	{
		$query = 'SELECT AdaugaAngajat("' . $email . '", "' . $telefon . '", "' . $CNP . '", "' . $hash . '", "' . $nume . '","' . $prenume . '", "' . $sex . '");';
		$result = mysqli_query($DB, $query);
		$arr = mysqli_fetch_array($result, MYSQLI_BOTH);
		if($arr[0] == 'OK')
		{
			Header("Location: angajati.php");
		}
		else{
			$msg = $arr[0];
		}
	}
	
	}
		
}
?>


<html>

<head>
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <title>Firma Curierat - Inregistrare</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<style>
	body{
	    background: url('images/background.jpg') no-repeat center;
		background-size: cover;
		background-attachment: fixed;
	}
	.register{
		height:50%;
		width:40%;
		min-height:320px;
		min-width:350px;
		position:absolute;
		top:10%;
		left:30%;
		background-color:rgba(34, 34, 34, 0.97);
		border: 1px solid black;
		border-radius:20px;
		padding:25px;
		text-align:center;
	}
	
	input{
		padding: 12px 20px;
		margin: 8px 0;
		display: inline-block;
		border: 1px solid #ccc;
		border-radius: 4px;
		box-sizing: border-box;
		transition:0.5s;
	}
	
	input:focus{
		outline: none;
	}
	
	#feminin{
		margin-left:30px;
	}
	#masculin{
		margin-left:30px;
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
	
	
	
	#auth:hover{
		cursor:pointer;
	}
	
	#genderselect{
		width:90%;
		border: 0;
		border-bottom: 2px solid grey;
		margin-left:5%;
		font-family:Trebuchet MS;
		color:grey;
		float:left;
		height:42px;
		padding-top:18px;
	}
	
	
	</style>
</head>

<body>

<div class="register">
	<font size="6" color="white" face="Trebuchet MS">ADAUGA UN ANGAJAT IN BAZA DE DATE</font><hr>
<form method="post" action="">
	<input style="width:45%;" type="text" name="nume" id="nume" placeholder="*Nume">
	<input style="width:45%;" type="text" name="prenume" id="prenume" placeholder="*Prenume"><br>
	<div id="genderselect">
		*Sex:
		<input type="radio" id="masculin" name="sex" value="M">
		<label for="masculin">Masculin</label>
		<input type="radio" id="feminin" name="sex" value="F">
		<label for="feminin">Feminin</label>
	</div>
	<input style="width:90%;" type="text" name="CNP" id="CNP" placeholder="*CNP"><br>
    <input style="width:90%;" type="text" name="email" id="email" placeholder="*Email"><br>
	<input style="width:90%;" type="text" name="telefon" id="telefon" placeholder="*Nr. Telefon"><br>
	<input style="width:90%;" type="password" name="parola" id="parola" placeholder="*Parola"><br>
	<button id="auth" type="submit" name="register"  class="btn btn-block">Inregistrare</button><br>
	<?php if(isset($msg)) echo '<div class="errorMsg">' . $msg . '</div>'; ?>
</form>
</div>



</body>

</html>



