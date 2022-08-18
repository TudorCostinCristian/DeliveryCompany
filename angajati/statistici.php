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

<font face="Trebuchet MS" size="7" color="white"><b><span class="fa fa-signal"></span> STATISTICI</b></font><hr>
<table id="comenzi">
	<?php
		for($i = 0; $i < 10; $i++){
			$query = 'CALL Statistici(' . $i . ');';
			$res = mysqli_query($DB, $query);
			mysqli_next_result($DB);
			$stat = mysqli_fetch_array($res, MYSQLI_ASSOC);
			echo '<tr>';
			echo '<td><i>' . $stat['StatName'] . '</i></td>';
			echo '<td>' . $stat['Name'] . ' (' . $stat['Val'] . ')</td>';
			echo '</tr>';
		}
	?>
</table>
