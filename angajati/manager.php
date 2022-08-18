<?php
require_once("includes/Header.php");

$qr = 'CALL NumeDepartament(' . $user['DepartamentID'] . ');';
$qr_res = mysqli_query($DB, $qr);
mysqli_next_result($DB);
$dep = mysqli_fetch_array($qr_res, MYSQLI_ASSOC);

if(isset($_POST['actiuniangajati'])) 
{
	if(!isset($_POST['email'])){
		$msg = "Trebuie sa introduci email-ul angajatului!";
	}
	
	$email = mysqli_real_escape_string($DB, $_POST['email']);


	$action = $_POST['action'];
	
	if(!isset($msg))
	{
		$query = 'SELECT ActiuniManager("' . $email . '", ' . $user['DepartamentID'] . ', ' . $action . ');';
		$result = mysqli_query($DB, $query);
		$mesaj = mysqli_fetch_array($result, MYSQLI_BOTH);
		if($mesaj[0] != 'OK')
		{
			$msg = $mesaj[0];
		}
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


<div class="actiuniangajati">
	<font size="6" color="white" face="Trebuchet MS">ADAUGA SAU ELIMINA ANGAJATI DIN DEPARTAMENT</font><hr>
<form method="post" action="">
	<input style="width:90%;" type="text" name="email" id="email" placeholder="Email angajat"><br>
    <font color="white" size="4" face="Trebuchet MS"><label for="action">Actiune:</label></font>
    <select id="action" name="action" style="width:240px; height: 40px;" onchange="Action">
      <option value="1">Adauga in departament</option>
      <option value="2">Exclude din departament</option>
    </select>
	<br>
	
	
	<button id="auth" type="submit" name="actiuniangajati"  class="btn btn-block">Confirmare</button><br>
	<?php if(isset($msg)) echo '<div class="errorMsg">' . $msg . '</div>'; ?>
</form>
</div>

<hr>
<font face="Trebuchet MS" size="6" color="white"><b><span class="fa fa-user"></span> ANGAJATI DIN DEPARTAMENTUL <?php echo strtoupper($dep['NumeDepartament']); ?></b></font><br>
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
$query = 'CALL SelectareAngajatiDepartament(' . $user['DepartamentID'] . ');';
$res = mysqli_query($DB, $query);
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