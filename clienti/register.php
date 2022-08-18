<?php
require_once("includes/Config.php");
if(isset($_SESSION['ClientID']) && isset($_SESSION['Parola'])){
	Header("Location: index.php");
	return 1;
}

if(isset($_POST['register'])) 
{
	if(!isset($_POST['nume']) || !isset($_POST['prenume']) || !isset($_POST['sex']) || !isset($_POST['datanasterii']) ||
	!isset($_POST['email']) || !isset($_POST['telefon']) || !isset($_POST['parola']) || !isset($_POST['oras']) ||
	!isset($_POST['judet']) || !isset($_POST['strada']))
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

	$datanasterii = mysqli_real_escape_string($DB, $_POST['datanasterii']);
	$email = mysqli_real_escape_string($DB, $_POST['email']);
	$telefon = mysqli_real_escape_string($DB, $_POST['telefon']);
	if (!filter_var($email, FILTER_VALIDATE_EMAIL))
	{
		$msg = "Te rugam sa introduci un email valid!";
	}



	$qr_verificare = 'SELECT VerificareCont("' . $email . '", "' . $telefon . '");';
	$res = mysqli_query($DB, $qr_verificare);
	$res_msg = mysqli_fetch_array($res, MYSQLI_BOTH);
	if($res_msg[0] != 'OK')
	{
		$msg = $res_msg[0];
	}
	
	$parola = mysqli_real_escape_string($DB, $_POST['parola']);
	$hash1 = hash( 'whirlpool', $parola);
	$hash = strtoupper($hash1);
	
	$oras = mysqli_real_escape_string($DB, $_POST['oras']);
	$judet = mysqli_real_escape_string($DB, $_POST['judet']);
	$strada = mysqli_real_escape_string($DB, $_POST['strada']);
	
	if(isset($_POST['etaj'])) 
	{
		$etaj = (int)$_POST['etaj'];
		if($etaj == '')
		{
			$etaj = 'NULL';
		}
	}
	else $etaj = 'NULL';
	
	if(isset($_POST['numar'])) 
	{
		$numar = (int)$_POST['numar'];
		if($numar == '')
		{
			$numar = 'NULL';
		}
	}
	else $numar = 'NULL';
	
	if(isset($_POST['apartament'])) 
	{
		$apartament = (int)$_POST['apartament'];
		if($apartament == '')
		{
			$apartament = 'NULL';
		}
	}
	else $apartament = 'NULL';
	
	if(isset($_POST['bloc']))
	{
		$bloc = mysqli_real_escape_string($DB, trim($_POST['bloc']));
		if($bloc == '')
		{
			$bloc = 'NULL';
		}
		else
		{
			$bloc = '"' . $bloc . '"';
		}
	}
	else $bloc = 'NULL';
	
	if(isset($_POST['scara']))
	{
		$scara = mysqli_real_escape_string($DB, trim($_POST['scara']));
		if($scara == '')
		{
			$scara = 'NULL';
		}
		else
		{
			$scara = '"' . $scara . '"';
		}
	}
	else $scara = 'NULL';
	
	if(isset($_POST['scara']))
	{
		$scara = mysqli_real_escape_string($DB, trim($_POST['scara']));
		if($scara == '')
		{
			$scara = 'NULL';
		}
		else
		{
			$scara = '"' . $scara . '"';
		}
	}
	else $scara = 'NULL';
	
	if(isset($_POST['codpostal']))
	{
		$codpostal = mysqli_real_escape_string($DB, trim($_POST['codpostal']));
		if($codpostal == '')
		{
			$codpostal = 'NULL';
		}
		else
		{
			$codpostal = '"' . $codpostal . '"';
		}
	}
	else $codpostal = 'NULL';

	if(!isset($msg))
	{
		$query = 'CALL Inregistrare("' . $email . '", "' . $hash . '", "' . 
		$telefon . '", "' . $nume . '", "' . $prenume . '", "' . $sex. '", "' . $datanasterii. '", "' . $oras. '", "' . $judet . 
		'", "' . $strada . '", ' . $numar . ', ' . $bloc . ', ' . $scara . ', ' . $etaj . ', ' . $apartament . ', ' . $codpostal .
		');';
		$status = mysqli_query($DB, $query);
		if($status == false)
		{
			$msg = "O eroare a aparut la inregistrarea contului! Te rugam sa mai incerci!";
		}
		else{
			$query = 'CALL Autentificare("' . $email . '", "' . $hash . '");';
			$result = mysqli_query($DB, $query);
			$user = mysqli_fetch_array($result, MYSQLI_ASSOC);
			$_SESSION['Email'] = $email;
			$_SESSION['Parola'] = $hash;
			$_SESSION['ClientID'] = $user['ClientID'];
			header('Location: /temaBD/clienti/index.php'); 
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
		height:70%;
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
	
	a{
		color:darkorange;
		text-decoration: none;
	}
	
	
	#auth:hover{
		cursor:pointer;
	}
	
	#genderselect{
		width:44%;
		border: 0;
		border-bottom: 2px solid grey;
		margin-left:5%;
		font-family:Trebuchet MS;
		color:grey;
		float:left;
		height:42px;
		padding-top:18px;
	}
	
	#divdatanasterii{
		border: 0;
		border-bottom: 2px solid grey;
		color:grey;
		width:44%;
		font-family:Trebuchet MS;
		float:left;
		height:60px;
		margin-left:15px;
	}
	
	
	
	</style>
</head>

<body>

<div class="register">
	<img src="images/logo.png" alt="LOGO"><hr>
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
	<div id="divdatanasterii">
		*Data nasterii: <input style="width:50%;" type="date" value="2017-06-01" id="datanasterii" name="datanasterii">
	</div>
    <input style="width:90%;" type="text" name="email" id="email" placeholder="*Email"><br>
	<input style="width:90%;" type="text" name="telefon" id="telefon" placeholder="*Nr. Telefon"><br>
	<input style="width:90%;" type="password" name="parola" id="parola" placeholder="*Parola"><br>
	<input style="width:45%;background-color:transparent;border: 0;border-bottom: 2px solid grey;border-radius:0px;color:white;" type="text" name="oras" id="oras" placeholder="*Oras">
	<input style="width:45%;background-color:transparent;border: 0;border-bottom: 2px solid grey;border-radius:0px;color:white;" type="text" name="judet" id="judet" placeholder="*Judet/Sector"><br>
	<input style="width:67%;background-color:transparent;border: 0;border-bottom: 2px solid grey;border-radius:0px;color:white;" type="text" name="strada" id="strada" placeholder="*Strada">
	<input style="width:23%;background-color:transparent;border: 0;border-bottom: 2px solid grey;border-radius:0px;color:white;" type="text" name="numar" id="numar" placeholder="Numar"><br>
	<input style="width:18%;background-color:transparent;border: 0;border-bottom: 2px solid grey;border-radius:0px;color:white;" type="text" name="bloc" id="bloc" placeholder="Bloc">
	<input style="width:18%;background-color:transparent;border: 0;border-bottom: 2px solid grey;border-radius:0px;color:white;" type="text" name="scara" id="scara" placeholder="Scara">
	<input style="width:18%;background-color:transparent;border: 0;border-bottom: 2px solid grey;border-radius:0px;color:white;" type="text" name="etaj" id="etaj" placeholder="Etaj">
	<input style="width:18%;background-color:transparent;border: 0;border-bottom: 2px solid grey;border-radius:0px;color:white;" type="text" name="apartament" id="apartament" placeholder="Apartament">
	<input style="width:17%;background-color:transparent;border: 0;border-bottom: 2px solid grey;border-radius:0px;color:white;" type="text" name="codpostal" id="codpostal" placeholder="Cod Postal">
	<button id="auth" type="submit" name="register"  class="btn btn-block">Inregistrare</button><br>
	<div id="register"> Ai deja un cont? <a href="login.php"><i>Mergi la autentificare!</i></a></div>
	<?php if(isset($msg)) echo '<div class="errorMsg">' . $msg . '</div>'; ?>
</form>
</div>



</body>

</html>

