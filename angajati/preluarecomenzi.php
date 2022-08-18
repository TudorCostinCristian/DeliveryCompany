<?php
require_once("includes/Header.php");

?>

<style>

#comenzi {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 70%;
  text-align:center;
  margin-top:20px;
}

#comenzi td, #comenzi th {
  border: 1px solid #ddd;
  padding: 8px;
}

#comenzi tr:nth-child(even){background-color: #f2f2f2;}
#comenzi tr:nth-child(odd){background-color: white;}

#comenzi tr:hover {background-color: #ddd;}

#comenzi th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #E0B686;
  color: white;
}
</style>

<font face="Trebuchet MS" size="7" color="white"><b><span class="fa fa-archive"></span> COMENZI CARE NU AU FOST INCA PRELUATE DE CATRE UN COLECTOR</b></font><hr>
<table id="comenzi">
  <tr>
    <th style="text-align:center;">ID Colet</th>
	<th style="text-align:center;">Volum</th>
	<th style="text-align:center;">Fragil</th>
	<th style="text-align:center;">Email expeditor</th>
	<th style="text-align:center;">Email destinatar</th>
	<th style="text-align:center;">Nume expeditor</th>
	<th style="text-align:center;">Telefon expeditor</th>
	<th style="text-align:center;">Oras</th>
	<th style="text-align:center;">Judet</th>
	<th style="text-align:center;">Strada</th>
	<th style="text-align:center;">Numar</th>
	<th style="text-align:center;">Bloc</th>
	<th style="text-align:center;">Scara</th>
	<th style="text-align:center;">Etaj</th>
	<th style="text-align:center;">Apartament</th>
	<th style="text-align:center;">Actiuni</th>
  </tr>
<?php
$query = 'CALL PreluareComenzi_Colectori(' . $_SESSION['AngajatID'] . ');';
$res = mysqli_query($DB, $query);
mysqli_next_result($DB);
while($user = mysqli_fetch_array($res)){
?>
  <tr>
	<td><?=$user['ColetID'];?></td>
	<td><?=$user['Volum'];?></td>
	<td><?php
		if($user['Fragil'] > 0) echo "DA";
		else echo "NU";?></td>
	<td><?=$user['EmailExpeditor'];?></td>
	<td><?=$user['EmailDestinatar'];?></td>
    <td><?php echo $user['Nume'] . ' ' . $user['Prenume'];?></td>
    <td><?=$user['Telefon'];?></td>
	<td><?=$user['Oras'];?></td>
	<td><?=$user['Judet'];?></td>
	<td><?=$user['Strada'];?></td>
	<td><?=$user['Numar'];?></td>
    <td><?=$user['Bloc'];?></td>
    <td><?=$user['Scara'];?></td>
    <td><?=$user['Etaj'];?></td>
    <td><?=$user['Apartament'];?></td>
	<td>
	<?php echo '<a href="actiunicolectori.php?action=1&angajatid='. $_SESSION['AngajatID'] .'&coletid=' . $user['ColetID'] . '"><b><font color="green" size="4"><span class="fa fa-check"></span> Accepta comanda</font></b></a><br>'; ?>
	</td>
  </tr>
<?php } ?>