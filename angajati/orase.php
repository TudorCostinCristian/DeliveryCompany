<?php
require_once("includes/Header.php");

if(isset($_POST['angajatioras'])) 
{
	if(!isset($_POST['email'])){
		$msg = "Trebuie sa introduci email-ul angajatului!";
	}
	
	$email = mysqli_real_escape_string($DB, $_POST['email']);
	$qr_email = 'CALL ID_Angajat("' . $email . '");';
	$res = mysqli_query($DB, $qr_email);
	mysqli_next_result($DB);
	if(mysqli_num_rows($res) < 1)
	{
		$msg = "Adresa de email nu a fost gasita in baza de date!";
	}
	
	$action = $_POST['action'];
	$oras = mysqli_real_escape_string($DB, $_POST['oras']);
	
	$qr_oras = 'CALL ID_Oras("' . $oras . '");';
	$res = mysqli_query($DB, $qr_oras);
	mysqli_next_result($DB);
	if(mysqli_num_rows($res) < 1)
	{
		$msg = "Orasul nu a fost gasit in baza de date!";
	}
	
	if(!isset($msg))
	{
		$query = 'CALL ActiuniOrase("' . $email . '", "' . $oras . '", ' . $action . ');';
		mysqli_query($DB, $query);
	}	
}


?>

<style>
#orase {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 70%;
  text-align:center;
  margin-top:20px;
}

#orase td, #orase th {
  border: 1px solid #ddd;
  padding: 8px;
}

#orase tr:nth-child(even){background-color: #f2f2f2;}
#orase tr:nth-child(odd){background-color: white;}

#orase tr:hover {background-color: #ddd;}

#orase th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #E0B686;
  color: white;
}


.angajatioras{
	width:40%;
	min-height:20px;
	min-width:350px;
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

#auth{
	margin:15px;
	width:160px;
	height:40px;
}
	
#auth:hover{
	cursor:pointer;
}

	.errorMsg{
		background-color:red;
		border-radius:5px;
		margin: 8px 0;
		color:white;
		padding: 12px 20px;
		right:10%;
		font-family:Verdana;
	}
</style>


<div class="angajatioras">
	<font size="6" color="white" face="Trebuchet MS">ORASE - ANGAJATI</font><hr>
<form method="post" action="">
	<input style="width:90%;" type="text" name="email" id="email" placeholder="Email angajat"><br>
	<input style="width:90%;" type="text" name="oras" id="oras" placeholder="Oras"><br>
    <font color="white" size="4" face="Trebuchet MS"><label for="action">Actiune:</label></font>
    <select id="action" name="action" style="width:240px; height: 40px;" onchange="Action">
      <option value="1">Lucreaza in acest oras</option>
      <option value="2">Nu mai lucreaza in acest oras</option>
    </select>
	<br>
	
	
	<button id="auth" type="submit" name="angajatioras"  class="btn btn-block">Confirmare</button><br>
	<?php if(isset($msg)) echo '<div class="errorMsg">' . $msg . '</div>'; ?>
</form>
</div>

<hr>
<font face="Trebuchet MS" size="6" color="white"><b><span class="fa fa-star"></span> ORASE </b></font><br>
<table id="orase">
  <tr>
    <th style="text-align:center;">Oras</th>
    <th style="text-align:center;">Nr. angajati</th>
  </tr>
<?php
$query = 'CALL SelectareOrase;';
$res = mysqli_query($DB, $query);
mysqli_next_result($DB);
while($oras = mysqli_fetch_array($res)){
?>
  <tr>
    <td><?php echo $oras['NumeOras'];?></td>
	 <td><?php echo $oras['NrAngajati'];?></td>
  </tr>
<?php } ?>

<table id="orase">
  <tr>
    <th style="text-align:center;">Nume angajat</th>
    <th style="text-align:center;">Nume oras</th>
  </tr>
<?php
$query = 'CALL SelectareOraseAngajati;';
$res = mysqli_query($DB, $query);
mysqli_next_result($DB);
while($oras = mysqli_fetch_array($res)){
?>
  <tr>
    <td><?php echo $oras['Nume'] . ' ' . $oras['Prenume'];?></td>
	 <td><?php echo $oras['NumeOras'];?></td>
  </tr>
<?php } ?>