<?php
require_once("includes/Header.php");
?>

<style>

#promotii {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 70%;
  text-align:center;
  margin-top:10px;
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

.afiliati{
	width:40%;
	min-height:320px;
	min-width:350px;
	background-color:rgba(34, 34, 34, 0.97);
	border: 1px solid black;
	border-radius:20px;
	padding:25px;
	text-align:center;
}
	
input{
	width: 80%;
	padding: 12px 20px;
	margin: 8px 0;
	display: inline-block;
	border: 1px solid #ccc;
	border-radius: 4px;
	box-sizing: border-box;
}

.errorMsg{
	position:absolute;
	width:72%;
	background-color:red;
	bottom: 1px;
	left:12%;
	border-radius:5px;
	margin: 8px 0;
	color:white;
	padding: 12px 20px;
	right:10%;
	font-family:Verdana;
}
	
	
#submitbutton{
	margin:15px;
	width:160px;
	height:40px;
}
	
	
#submitbutton:hover{
	cursor:pointer;
}
</style>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://code.jquery.com/jquery-3.4.1.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="js/promotii.js?version=1.20"></script>

<font face="Trebuchet MS" size="7" color="white"><b><span class="fa fa-star"></span> PROMOTII ACTIVE</b></font><hr>
<table id="promotii">
  <tr>
	<th style="text-align:center;">#</th>
    <th style="text-align:center;">Nume promotie</th>
    <th style="text-align:center;">Discount</th>
	<th style="text-align:center;">Valabilitate</th>
  </tr>
<?php
$query = 'CALL PromotiiClient(' . $_SESSION['ClientID'] . ');';
$result = mysqli_query($DB, $query);
mysqli_next_result($DB);
if(mysqli_num_rows($result)){
while($pr = mysqli_fetch_array($result)){
?>
  <tr>
    <td><?=$pr['PromotieID'];?></td>
    <td><?=$pr['NumePromotie'];?></td>
	<td><?php echo $pr['DiscountLei'] . ' RON';?></td>
	<td><?php if($pr['Valabilitate'] < time()) echo '<font color="red"><b>PROMOTIE EXPIRATA</b></font>';
    else echo date("F j, Y, g:i a", $pr['Valabilitate']);?></td>
  </tr>
<?php } 
}else{?>
  <tr>
    <td>-</td>
    <td>-</td>
	<td>-</td>
	<td>-</td>
  </tr>
<?php } ?>  
  
</table>


<br><br><br><br>


<font face="Trebuchet MS" size="6" color="white"><b><span class="fa fa-star"></span> INVITA-TI PRIETENII SI CASTIGA 15 LEI DISCOUNT LA URMATOAREA LIVRARE</b></font><hr>
<div class="afiliati">
<input type="text" id="friendcode">
<input type="submit" id="redeemcode" value="Activeaza codul unui prieten">
<hr>
<input type="text" id="codetoset" value="<?=$user['CodAfiliat'];?>">
<input type="submit" id="setcode" value="Seteaza un cod de afiliat">

</div>