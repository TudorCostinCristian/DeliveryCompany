<?php
require_once("includes/Header.php");

if(isset($_POST['formPromotii'])) 
{
	if(!isset($_POST['numepromotie'])){
		$msg = "Trebuie sa introduci numele promotiei!";
	}
	if(!isset($_POST['discount'])){
		$msg = "Trebuie sa introduci discount-ul promotiei!";
	}
	
	if(!isset($_POST['valabilitate'])){
		$msg = "Trebuie sa introduci valabilitatea promotiei!";
	}
	
	$numepromotie = mysqli_real_escape_string($DB, $_POST['numepromotie']);
	$discount = (int)$_POST['discount'];
	$valabilitate = (int)$_POST['valabilitate'];
	
	if($discount < 1){
		$msg = "Discount-ul trebuie sa fie de minim 1 RON!";
	}
	
	if($valabilitate < 1){
		$msg = "Valabilitatea trebuie sa fie de minim 1 zi!";
	}
	
	if(empty($numepromotie)){
		$msg = 'Trebuie sa introduci un nume pentru promotie!';
	}
	
	if(!isset($msg))
	{
		$val = time() + $valabilitate*86400;
		$query = 'CALL PromotieNoua("' . $numepromotie . '", ' . $discount . ', ' . $val . ');';
		mysqli_query($DB, $query);
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
</style>

<font face="Trebuchet MS" size="7" color="white"><b><span class="fa fa-star"></span> PROMOTII</b></font><hr>

<div class="formPromotii">
	<font size="6" color="white" face="Trebuchet MS">CREEAZA O PROMOTIE NOUA</font><hr>
<form method="post" action="">
	<input style="width:90%;" type="text" name="numepromotie" id="numepromotie" placeholder="Nume promotie"><br>
	<input style="width:90%;" type="text" name="discount" id="discount" placeholder="Discount (RON)"><br>
	<input style="width:90%;" type="text" name="valabilitate" id="valabilitate" placeholder="Valabilitate (zile)"><br>
	
	<button id="auth" type="submit" name="formPromotii"  class="btn btn-block">Creeaza</button><br>
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
	<th style="text-align:center;">Actiuni</th>
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
	<td>
	<?php
		echo '<a href="actiunipromotii.php?action=1&promotieid='. $prom['PromotieID'] .'"><b><font color="red" size="4"><span class="fa fa-times"></span> Sterge promotia</font></b></a>';
		if($prom['Valabilitate'] > time()){
			echo '<br><br><a href="actiunipromotii.php?action=2&promotieid='. $prom['PromotieID'] .'"><b><font color="orange" size="4"><span class="fa fa-ban"></span> Opreste promotia</font></b></a><br><br>';
			echo '<a href="actiunipromotii.php?action=3&promotieid='. $prom['PromotieID'] .'"><b><font color="green" size="4"><span class="fa fa-check"></span> Extinde promotia(100 zile)</font></b></a>';
		}
	?>
	</td>
  </tr>
<?php } ?>
