<?php
require_once("includes/Header.php");

?>

<style>

#clienti {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 70%;
  text-align:center;
  margin-top:100px;
}

#clienti td, #clienti th {
  border: 1px solid #ddd;
  padding: 8px;
}

#clienti tr:nth-child(even){background-color: #f2f2f2;}
#clienti tr:nth-child(odd){background-color: white;}

#clienti tr:hover {background-color: #ddd;}

#clienti th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #E0B686;
  color: white;
}
</style>

<font face="Trebuchet MS" size="7" color="white"><b><span class="fa fa-user"></span> CONTURI CLIENTI</b></font><hr>
<table id="clienti">
  <tr>
    <th style="text-align:center;">Nume</th>
    <th style="text-align:center;">Sex</th>
	<th style="text-align:center;">Email</th>
	<th style="text-align:center;">Telefon</th>
	<th style="text-align:center;">Oras</th>
	<th style="text-align:center;">Data Nasterii</th>
	<th style="text-align:center;">Cont activat</th>
	<th style="text-align:center;">Nr. colete trimise</th>
	<th style="text-align:center;">Nr. colete primite</th>
	<th style="text-align:center;">Actiuni</th>
  </tr>
<?php
$query = 'CALL SelectareClienti;';
$res = mysqli_query($DB, $query);
while($user = mysqli_fetch_array($res)){
?>
  <tr>
    <td><?php echo $user['Nume'] . ' ' . $user['Prenume'];?></td>
    <td><?php
		if($user['Sex'] == 'M') echo 'Masculin';
		else echo 'Feminin';
	?></td>
	<td><?=$user['Email'];?></td>
	<td><?=$user['Telefon'];?></td>
	<td><?=$user['Oras'];?></td>
	<td><?=$user['DataNasterii'];?></td>
    <td><?php
		if($user['ContActivat'] == 0) echo 'NU';
		else echo 'DA';
	?></td>
	<td><?=$user['NrColeteTrimise'];?></td>
	<td><?=$user['NrColetePrimite'];?></td>
	<td><?php
		if($user['ContActivat'] == 0) echo '<a href="tehnicclienti.php?action=1&clientid='. $user['ClientID'] .'"><b><font color="green" size="4"><span class="fa fa-check"></span> Activeaza contul</font></b></a><br><br><br>';
		else echo '<a href="tehnicclienti.php?action=2&clientid=' . $user['ClientID'] .'"><b><font color="orange" size="4"><span class="fa fa-ban"></span> Blocheaza contul</font></b></a><br><br><br>';
		echo '<a href="tehnicclienti.php?action=3&clientid=' . $user['ClientID'] .'"><b><font color="red" size="4"><span class="fa fa-trash"></span> Sterge contul</font></b></a>';

	?></td>
  </tr>
<?php } ?>