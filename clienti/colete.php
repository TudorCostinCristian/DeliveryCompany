<?php
require_once("includes/Header.php");

?>

<style>

#tabel {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 70%;
  text-align:center;
  margin-top:10px;
}

#tabel td, #tabel th {
  border: 1px solid #ddd;
  padding: 8px;
}

#tabel tr:nth-child(even){background-color: #f2f2f2;}
#tabel tr:nth-child(odd){background-color: white;}

#tabel tr:hover {background-color: #ddd;}

#tabel th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #E0B686;
  color: white;
}
</style>

<font face="Trebuchet MS" size="7" color="white"><b><span class="fa fa-paper-plane"></span> COLETE TRIMISE</b></font><hr>
<table id="tabel">
  <tr>
    <th style="text-align:center;">ID</th>
    <th style="text-align:center;">Email Destinatar</th>
    <th style="text-align:center;">Volum</th>
	<th style="text-align:center;">Status</th>
	<th style="text-align:center;">Data Trimiterii</th>
	<th style="text-align:center;">Continut</th>
  </tr>
<?php
$query = 'CALL ColeteClient(' . $_SESSION['ClientID'] . ', "TRIMISE");';
$result = mysqli_query($DB, $query);
mysqli_next_result($DB);
if(mysqli_num_rows($result)){
while($col = mysqli_fetch_array($result)){
?>
  <tr>
    <td><?php echo '#' . $col['IDColet'];?></td>
    <td><?=$col['EmailDestinatar'];?></td>
	<td><?=$col['VolumColet'];?></td>
    <td>
	<?php
		$sts = $col['StatusColet'];
		if($sts == 0) echo '<font color="red"><b>Coletul a fost refuzat de catre destinatar!</b></font>';
		else if($sts == 1) echo '<font color="orange"><b>Se asteapta confirmarea din partea destinatarului!</b></font';
		else if($sts == 2) echo '<font color="orange"><b>Coletul a fost confirmat de catre destinatar.<br> Un colector va accepta coletul in scurt timp!</b></font';
		else if($sts == 3) echo '<font color="orange"><b>Comanda a fost preluata de catre un colector.<br> Acesta va ridica coletul de la domiciliul expeditorului in scurt timp.</b></font';
		else if($sts == 4) echo '<font color="orange"><b>Coletul a fost ridicat de catre colector.</b></font';
		else if($sts == 5) echo '<font color="orange"><b>Coletul a ajuns la sediul din orasul expeditorului.</b></font';
		else if($sts == 6) echo '<font color="orange"><b>Coletul a fost ridicat de catre un livrator.</b></font';
		else if($sts == 7) echo '<font color="green"><b>Coletul a ajuns la domiciliul destinatarului.<br> Comanda a fost finalizata.</b></font';
	?>
	</td>
	<td><?=date("F j, Y, g:i a", $col['TimestampColet']);?></td>
	<td>
	<?php
		$query = 'CALL ContinutColet(' . $col['IDColet'] . ');';
		$ob_result = mysqli_query($DB, $query);
		mysqli_next_result($DB);
		while($obj = mysqli_fetch_array($ob_result)){
			echo $obj['NumeObiect'] . '<br>';
		}
	?>
	</td>
  </tr>
<?php } 
}
else{ ?>
  <tr>
    <td>-</td>
    <td>-</td>
	<td>-</td>
    <td>-</td>
	<td>-</td>
	<td>-</td>
  </tr>

<?php }

?>
</table>




<br><br><br><br>



<font face="Trebuchet MS" size="7" color="white"><b><span class="fa fa-archive"></span> COLETE PRIMITE</b></font><hr>
<table id="tabel">
  <tr>
    <th style="text-align:center;">ID</th>
    <th style="text-align:center;">Email Expeditor</th>
    <th style="text-align:center;">Volum</th>
	<th style="text-align:center;">Status</th>
	<th style="text-align:center;">Data Trimiterii</th>
	<th style="text-align:center;">Continut</th>
	<th style="text-align:center;">Optiuni</th>
  </tr>
<?php
$query = 'CALL ColeteClient(' . $_SESSION['ClientID'] . ', "PRIMITE");';
$result = mysqli_query($DB, $query);
mysqli_next_result($DB);
if(mysqli_num_rows($result)){
while($col = mysqli_fetch_array($result)){
?>
  <tr>
    <td><?php echo '#' . $col['IDColet'];?></td>
    <td><?=$col['EmailExpeditor'];?></td>
	<td><?=$col['VolumColet'];?></td>
    <td>
	<?php
		$sts = $col['StatusColet'];
		if($sts == 0) echo '<font color="red"><b>Coletul a fost refuzat de catre destinatar!</b></font>';
		else if($sts == 1) echo '<font color="orange"><b>Se asteapta confirmarea din partea destinatarului!</b></font';
		else if($sts == 2) echo '<font color="orange"><b>Coletul a fost confirmat de catre destinatar.<br> Un colector va accepta coletul in scurt timp!</b></font';
		else if($sts == 3) echo '<font color="orange"><b>Comanda a fost preluata de catre un colector.<br> Acesta va ridica coletul de la domiciliul expeditorului in scurt timp.</b></font';
		else if($sts == 4) echo '<font color="orange"><b>Coletul a fost ridicat de catre colector.</b></font';
		else if($sts == 5) echo '<font color="orange"><b>Coletul a ajuns la sediul din orasul expeditorului.</b></font';
		else if($sts == 6) echo '<font color="orange"><b>Coletul a fost ridicat de catre un livrator.</b></font';
		else if($sts == 7) echo '<font color="green"><b>Coletul a ajuns la domiciliul destinatarului.<br> Comanda a fost finalizata.</b></font';
	?>
	</td>
	<td><?=date("F j, Y, g:i a", $col['TimestampColet']);?></td>
	<td>
	<?php
		$query = 'CALL ContinutColet(' . $col['IDColet'] . ');';
		$ob_result = mysqli_query($DB, $query);
		mysqli_next_result($DB);
		while($obj = mysqli_fetch_array($ob_result)){
			echo $obj['NumeObiect'] . '<br>';
		}
	?>
	</td>
	<td>
	<?php
		$sts = $col['StatusColet'];
		if($sts == 1) echo '<a href="modificare.php?action=1&coletid=' . $col['IDColet'] .'"><b><font color="green" size="4"><span class="fa fa-check"></span> CONFIRMA COLETUL</font></b></a><br><br><br>
		<a href="modificare.php?action=2&coletid=' . $col['IDColet'] .'"><b><font color="red" size="4"><span class="fa fa-times"></span> REFUZA COLETUL</font></b></a>';
		else echo '-';
	?>
	</td>
  </tr>
<?php } 
}
else{?>
  <tr>
    <td>-</td>
    <td>-</td>
	<td>-</td>
    <td>-</td>
	<td>-</td>
	<td>-</td>
	<td>-</td>
  </tr>
<?php }
?>

</table>