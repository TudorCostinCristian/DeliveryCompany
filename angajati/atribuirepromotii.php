<?php
require_once("includes/Header.php");

if(isset($_POST['formPromotii'])) 
{
	if(!isset($_POST['numepromotie'])){
		$msg = "Trebuie sa introduci numele promotiei!";
	}
	
	if(!isset($_POST['sex'])){
		$sex = '-';
	}
	else $sex = $_POST['sex'];
	
	
	$numepromotie = mysqli_real_escape_string($DB, $_POST['numepromotie']); 
	$oras = mysqli_real_escape_string($DB, $_POST['oras']);
	$email = mysqli_real_escape_string($DB, $_POST['email']);
   
   
    if(!empty($email)){
		$qr = 'CALL ID_Client("' . $email . '");';
		$res = mysqli_query($DB, $qr);
		mysqli_next_result($DB);
		if(mysqli_num_rows($res) < 1)
		{
			$msg = 'Email-ul nu a fost gasit in baza de date!';
		}
	}
	
	if(empty($numepromotie)){
		$msg = 'Trebuie sa introduci un nume pentru promotie!';
	}
	
	if(!isset($msg))
	{
		$qr = 'SELECT AtribuirePromotie("' . $numepromotie . '", "' . $email . '", "' . $sex . '", "' . $oras . '", ' . time() . ');';
		$res = mysqli_query($DB, $qr);
		$mesaj = mysqli_fetch_array($res, MYSQLI_BOTH);
		if($mesaj[0] != 'OK')
		{
			$msg = $mesaj[0];
		}
	}
}


?>

<style>

#promotii {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 70%;
  text-align:center;
  margin-top:20px;
}

#promotii td, #promotii th {
  border: 1px solid #ddd;
  padding: 8px;
}

#promotii tr:nth-child(even){background-color: #f2f2f2;}
#promotii tr:nth-child(odd){background-color: white;}

#promotii tr:hover {background-color: #ddd;}

#promotii th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #E0B686;
  color: white;
}


.formPromotii{
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
	
	#genderselect{
		width:90%;
		border: 0;
		border-bottom: 2px solid grey;
		margin-left:5%;
		font-family:Trebuchet MS;
		color:white;
		float:left;
		height:55px;
		padding-top:18px;
	}
</style>

<font face="Trebuchet MS" size="7" color="white"><b><span class="fa fa-star"></span>ATRIBUIRE PROMOTII</b></font><hr>

<div class="formPromotii">
	<font size="6" color="white" face="Trebuchet MS">ATRIBUIE O PROMOTIE UNEI CATEGORII DE CLIENTI</font><hr>
<form method="post" action="">
	<input style="width:90%;" type="text" name="numepromotie" id="numepromotie" placeholder="*Nume promotie"><br>
	<input style="width:90%;" type="text" name="email" id="email" placeholder="Email client (daca nu este completat, promotia se atribuie clientilor cu orice email)"><br>
	<input style="width:90%;" type="text" name="oras" id="oras" placeholder="Oras(daca nu este completat, promotia se atribuie clientilor din orice oras)"><br>
		<div id="genderselect">
		Sex(daca nu este completat, promotia se atribuie clientilor indiferent de sexul acestora):<br>
		<input type="radio" id="masculin" name="sex" value="M">
		<label for="masculin">Masculin</label>
		<input type="radio" id="feminin" name="sex" value="F">
		<label for="feminin">Feminin</label>
	</div>
	<button id="auth" type="submit" name="formPromotii"  class="btn btn-block">Atribuie promotia</button><br>
	<?php if(isset($msg)) echo '<div class="errorMsg">' . $msg . '</div>'; ?>
</form>
</div>

<hr>
<table id="promotii">
  <tr>
    <th style="text-align:center;">Nume promotie</th>
    <th style="text-align:center;">Discount</th>
	<th style="text-align:center;">Valabilitate</th>
	<th style="text-align:center;">Nr. clienti</th>
  </tr>
<?php
$query = 'CALL SelectarePromotii;';
$res = mysqli_query($DB, $query);
mysqli_next_result($DB);
while($prom = mysqli_fetch_array($res)){
?>
  <tr>
    <td><?php echo $prom['NumePromotie'];?></td>
    <td><?php
		echo $prom['DiscountLei'] . ' LEI';
	?></td>
    <td><?php
		if($prom['Valabilitate'] < time()) echo '<font color="red"><b>PROMOTIE EXPIRATA</b></font>';
		else echo date("F j, Y, g:i a", $prom['Valabilitate']);
	?></td>
	<td><?php echo $prom['NrClienti'];?></td>
  </tr>
<?php } ?>


<table id="promotii">
  <tr>
    <th style="text-align:center;">Email client</th>
    <th style="text-align:center;">Oras</th>
	<th style="text-align:center;">Sex</th>
	<th style="text-align:center;">Promotie</th>
  </tr>
<?php
$query = 'CALL SelectarePromotiiClienti;';
$res = mysqli_query($DB, $query);
mysqli_next_result($DB);
while($pc = mysqli_fetch_array($res)){
?>
  <tr>
    <td><?php echo $pc['Email'];?></td>
    <td><?php echo $pc['Oras'];?></td>
    <td><?php
		if($pc['Sex'] == 'F') echo 'Feminin';
		else echo 'Masculin';
	?></td>
	<td><?php echo $pc['NumePromotie'];?></td>
  </tr>
<?php } ?>
<?php
