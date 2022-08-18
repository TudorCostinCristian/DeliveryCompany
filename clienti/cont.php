<?php
require_once("includes/Header.php");

?>

<style>

#contulMeu {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 70%;
  text-align:center;
  margin-top:100px;
}

#contulMeu td{
  border: 1px solid #ddd;
  padding: 8px;
}

#contulMeu tr:nth-child(even){background-color: #f2f2f2;}
#contulMeu tr:nth-child(odd){background-color: white;}

#contulMeu tr:hover {background-color: #ddd;}
</style>

<font face="Trebuchet MS" size="7" color="white"><b><span class="fa fa-user"></span> CONTUL MEU</b></font><hr>
<table id="contulMeu">
  <tr>
    <td><b>Nume</b></td>
    <td><i><?=$user['Nume'];?></i></td>
  </tr>
  <tr>
    <td><b>Prenume</b></td>
    <td><i><?=$user['Prenume'];?></i></td>
  </tr>
  <tr>
    <td><b>Telefon</b></td>
    <td><i><?=$user['Telefon'];?></i></td>
  </tr>
  <tr>
	<td><b>Sex</b></td>
    <td><i><?php
		if($user['Sex'] == 'M') echo 'Masculin';
		else echo 'Feminin';
	?></i></td>
  </tr>
  <tr>
    <td><b>Data nasterii</b></td>
    <td><i><?=$user['DataNasterii'];?></i></td>
  </tr>
  <tr>
    <td><b>Oras</b></td>
    <td><i><?=$user['Oras'];?></i></td>
  </tr>
  <tr>
    <td><b>Judet</b></td>
    <td><i><?=$user['Judet'];?></i></td>
  </tr>
  <tr>
    <td><b>Strada</b></td>
    <td><i><?=$user['Strada'];?></i></td>
  </tr>
  <tr>
    <td><b>Numar</b></td>
    <td><i><?php
		if($user['Numar'] == NULL) echo '-';
		else echo $user['Numar'];
	?></i></td>
  </tr>
  <tr>
    <td><b>Bloc</b></td>
    <td><i><?php
		if($user['Bloc'] == NULL) echo '-';
		else echo $user['Bloc'];
	?></i></td>
  </tr>
  <tr>
    <td><b>Scara</b></td>
    <td><i><?php
		if($user['Scara'] == NULL) echo '-';
		else echo $user['Scara'];
	?></i></td>
  </tr>
  <tr>
    <td><b>Etaj</b></td>
    <td><i><?php
		if($user['Etaj'] == NULL) echo '-';
		else echo $user['Etaj'];
	?></i></td>
  </tr>
  <tr>
    <td><b>Apartament</b></td>
    <td><i><?php
		if($user['Apartament'] == NULL) echo '-';
		else echo $user['Apartament'];
	?></i></td>
  </tr>
  <tr>
    <td><b>CodPostal</b></td>
    <td><i><?php
		if($user['CodPostal'] == NULL) echo '-';
		else echo $user['CodPostal'];
	?></i></td>
  </tr>
  

</table>