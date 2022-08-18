<?php
require_once("includes/Header.php");

if(isset($_POST['actiuniangajati'])) 
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
	$departament = '';
	$action = $_POST['action'];
	if($action == 1 || $action == 3)
	{
		if(isset($_POST['departament'])){
			$departament = mysqli_real_escape_string($DB, $_POST['departament']);
			$qr_dep = 'CALL ID_Departament("' . $departament . '");';
			$res_dep = mysqli_query($DB, $qr_dep);
			mysqli_next_result($DB);
			if(mysqli_num_rows($res_dep) != 1){
				$msg = "Departamentul nu a fost gasit in baza de date!";
			}
		}
		else{
			$msg = "Trebuie sa introduci numele departamentului!";
		}
	}
	
	if(!isset($msg))
	{
		$query = 'CALL ActiuniAngajati("' . $email . '", "' . $departament . '", ' . $action . ');';
		mysqli_query($DB, $query);
	}	
}

?>

<style>

#angajati {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 70%;
  text-align:center;
  margin-top:20px;
}

#angajati td, #angajati th {
  border: 1px solid #ddd;
  padding: 8px;
}

#angajati tr:nth-child(even){background-color: #f2f2f2;}
#angajati tr:nth-child(odd){background-color: white;}

#angajati tr:hover {background-color: #ddd;}

#angajati th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #E0B686;
  color: white;
}


.actiuniangajati{
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

<font face="Trebuchet MS" size="7" color="white"><b><span class="fa fa-user"></span> CONTURI ANGAJATI</b></font><hr>

<div class="actiuniangajati">
	<font size="6" color="white" face="Trebuchet MS">MODIFICARE CONTURI ANGAJATI</font><hr>
<form method="post" action="">
	<input style="width:90%;" type="text" name="email" id="email" placeholder="Email angajat"><br>
	<input style="width:90%;" type="text" name="departament" id="departament" placeholder="Nume departament (pentru setarea departamentului)"><br>
    <font color="white" size="4" face="Trebuchet MS"><label for="action">Actiune:</label></font>
    <select id="action" name="action" style="width:240px; height: 40px;" onchange="Action">
      <option value="1">Seteaza manager departament</option>
      <option value="2">Exclude manager departament</option>
      <option value="3">Seteaza departamentul</option>
      <option value="4">Exclude din departament</option>
	  <option value="5">Sterge contul</option>
    </select>
	<br>
	
	
	<button id="auth" type="submit" name="actiuniangajati"  class="btn btn-block">Seteaza</button><br>
	<?php if(isset($msg)) echo '<div class="errorMsg">' . $msg . '</div>'; ?>
</form>
</div>

<hr>
<table id="angajati">
  <tr>
    <th style="text-align:center;">Nume</th>
    <th style="text-align:center;">Sex</th>
	<th style="text-align:center;">Email</th>
	<th style="text-align:center;">Telefon</th>
	<th style="text-align:center;">CNP</th>
	<th style="text-align:center;">Departament</th>
	<th style="text-align:center;">Manager</th>
  </tr>
<?php
$query = 'CALL SelectareAngajatiCuDep;';
$res = mysqli_query($DB, $query);
mysqli_next_result($DB);
while($ang = mysqli_fetch_array($res)){
?>
  <tr>
    <td><?php echo $ang['Nume'] . ' ' . $ang['Prenume'];?></td>
    <td><?php
		if($ang['Sex'] == 'M') echo 'Masculin';
		else echo 'Feminin';
	?></td>
	<td><?=$ang['Email'];?></td>
	<td><?=$ang['Telefon'];?></td>
	<td><?=$ang['CNP'];?></td>
	<td><?=$ang['NumeDepartament'];?></td>
    <td><?php
		if($ang['Manager'] == 0) echo 'NU';
		else echo 'DA';
	?></td>
  </tr>
<?php } ?>
<?php
$query = 'CALL SelectareAngajatiFaraDep;';
$res = mysqli_query($DB, $query);
mysqli_next_result($DB);
while($ang = mysqli_fetch_array($res)){
?>
  <tr>
    <td><?php echo $ang['Nume'] . ' ' . $ang['Prenume'];?></td>
    <td><?php
		if($ang['Sex'] == 'M') echo 'Masculin';
		else echo 'Feminin';
	?></td>
	<td><?=$ang['Email'];?></td>
	<td><?=$ang['Telefon'];?></td>
	<td><?=$ang['CNP'];?></td>
	<td><?=$ang['NumeDepartament'];?></td>
    <td><?php
		if($ang['Manager'] == 0) echo 'NU';
		else echo 'DA';
	?></td>
  </tr>
<?php } ?>

<script>
document.getElementById("action").addEventListener("change", Action);

function Action(){
	var act = document.getElementById("action").value;
	if(act == 1 || act == 3){
		document.getElementById("departament").style.display = "initial";  
	}
	else{
		document.getElementById("departament").style.display = "none"; 
	}
}
</script>