<?php
require_once("includes/Header.php");
$qrPromotie = 'CALL PromotiiClient(' . $_SESSION['ClientID'] . ');';
$resPromotie = mysqli_query($DB, $qrPromotie);
mysqli_next_result($DB);
$Promotie = mysqli_fetch_array($resPromotie, MYSQLI_ASSOC);

if(isset($_POST['expediere'])){
	if(!isset($_POST['email']) || !isset($_POST['mesaj']) || !isset($_POST['volum']) || !isset($_POST['nrobiecte']))
	{
		$msg = 'Trebuie sa completezi toate campurile!';
	}
	else
	{
		$emailDestinatar = mysqli_real_escape_string($DB, addslashes($_POST['email']));
		$mesaj = mysqli_real_escape_string($DB, addslashes($_POST['mesaj']));
		$volum = (int)$_POST['volum'];
		$pret = (int)$_POST['costfinal'];
		$nrobiecte = (int)$_POST['nrobiecte'];
		
		$qr_email = 'CALL DestinatarID("' . $emailDestinatar . '");';
		$res = mysqli_query($DB, $qr_email);
		mysqli_next_result($DB);
		
		if(mysqli_num_rows($res) == 0)
		{
			$msg = "Adresa de email nu a fost gasita in baza de date!";
		}
		else if($nrobiecte < 1 || $nrobiecte > 10)
		{
			$msg = 'Numarul de obiecte trebuie sa fie intre 1 si 10!';
		}
		else if($volum < 1 || $volum >= 1000000)
		{
			$msg = 'Volumul(cm3) coletului trebuie sa fie mai mare decat 0 si mai mic decat 1.000.000!';
		}
		else
		{
			for($i = 1; $i <= $nrobiecte; $i++)
			{
				$numeobj = 'numeObj' . $i;
				$NumeObj[$i] = $_POST[$numeobj];
				$fragilobj = 'fragilObj' . $i;
				if(empty($NumeObj[$i])){
					$msg = 'Trebuie sa completezi continutul coletului!';
				}
				if(isset($_POST[$fragilobj]))
					$FragilObj[$i] = $_POST[$fragilobj];
				else
					$FragilObj[$i] = 'N';
			}
		}
		
		if(!isset($msg)){
			$time = time();
			$qr = 'CALL AdaugaColet('. $_SESSION['ClientID'] .', "'. $emailDestinatar .'",' . $volum . ', "' . $mesaj . '", ' . $time . ', '. $pret .');';
			mysqli_query($DB, $qr);
			for($i = 1; $i <= $nrobiecte; $i++)
			{
				$qr2 = 'CALL AdaugaObiect_Colet(' . $_SESSION['ClientID'] . ', ' . $time . ', "' . $NumeObj[$i] . '", "' . $FragilObj[$i] . '");';
				mysqli_query($DB, $qr2);
				if(isset($Promotie)){
					$qr3 = 'CALL StergePromotieClient(' . $_SESSION['ClientID'] . ', ' . $Promotie['PromotieID'] . ');';
					mysqli_query($DB, $qr3);
				}
			}
			header('Location: colete.php');
			
		}
	}
}



?>

<style>
	.expediere{
		width:40%;
		min-height:320px;
		min-width:350px;
		position:absolute;
		top:25%;
		left:30%;
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
	
	#label_nrobiecte{
		font-family:Trebuchet MS;
		color: white;
		font-size: 20px;
	}
	
	#nrobiecte{
		width:100px;
		height:30px;
	}
	
	.obiecte{
		width:35%;
	}
	
	.divobiecte{
		padding:0px;
		margin:0px;
		color:white;
		font-family:Trebuchet MS;
		font-size:20px;
	}
	.cost{
		display: inline;
		color: orange;
		text
	}
</style>

<div class="expediere">
<font face="Trebuchet MS" size="5" color="white">INFORMATII GENERALE</font><hr>
<form id="formular_expediere" method="post" action="">
    <input type="text" name="email" id="email" placeholder="Introdu email-ul destinatarului"><br>
	<input type="text" name="volum" id="volum" placeholder="Volumul coletului (cm3)" onchange="Volum"><br>
	<input type="text" name="mesaj" id="mesaj" placeholder="Mesajul tau pentru destinatar"><br>
	<input type="number" name="costfinal" id="costfinal" value="0" style="display:none;">
	<br><br><font face="Trebuchet MS" size="5" color="white">CONTINUTUL COLETULUI</font><hr>
	<label id="label_nrobiecte" for="nrobiecte">Cate obiecte contine coletul?(min 1, max 10):</label>
	<select id="nrobiecte" name="nrobiecte" form="formular_expediere" onchange="Obiecte">
		<option value="1">1</option>
		<option value="2">2</option>
		<option value="3">3</option>
		<option value="4">4</option>
		<option value="5">5</option>
		<option value="6">6</option>
		<option value="7">7</option>
		<option value="8">8</option>
		<option value="9">9</option>
		<option value="10">10</option>
	</select><br>
	<?php
	for ($i = 1; $i <= 10; $i++) {
		if($i > 1)
			echo '<div style="display:none;" class="divobiecte" id="divObj' . $i . '">OBIECT ' . $i . ': <input class="obiecte" type="text" name="numeObj' . $i . '" placeholder="Nume obiect ' . $i . '">   
			<input style="width:30px" type="checkbox" name="fragilObj' . $i . '" value="D"> FRAGIL</div>';
		else
			echo '<div style="display:block;" class="divobiecte" id="divObj' . $i . '">OBIECT ' . $i . ': <input class="obiecte" type="text" name="numeObj' . $i . '" placeholder="Nume obiect ' . $i . '">   
			<input style="width:30px" type="checkbox" name="fragilObj' . $i . '" value="D"> FRAGIL</div>';
	}
	?>
	<hr>
	<font face="Trebuchet MS" color="white">Cost Livrare: <div class="cost" id="pret">-</div> RON<br>
	Discount: <div class="cost" id="discount">-</div> RON<br>
	Cost final: <div class="cost" id="pretfinal">-</div> RON<br></font>
	<button id="submitbutton" type="submit" name="expediere" ><span class="fa fa-paper-plane"></span><b> EXPEDIERE</b></button><br>
	<?php if(isset($msg)) echo '<div class="errorMsg">' . $msg . '</div>'; ?>
</form>

<script>
var discount = <?php if(isset($Promotie)) echo $Promotie['DiscountLei']; 
else echo 0;
?>;
var pret = 10;

document.getElementById("nrobiecte").addEventListener("change", Obiecte);
document.getElementById("volum").addEventListener("change", Volum);

function Volum(){
	var vol = document.getElementById("volum").value;
	var xvol = Math.round(vol*0.0003);
	if(xvol > 90) xvol = 90;
	pret = 10 + xvol;
	document.getElementById("pret").innerHTML = pret;
	var disc = discount;
	if(disc > pret) disc = pret;
	document.getElementById("discount").innerHTML = disc;
	var pretFinal = 0;
	if(disc >= pret) pretFinal = 0;
	else pretFinal = pret - disc;
	document.getElementById("pretfinal").innerHTML = pretFinal;
	document.getElementById("costfinal").value = pretFinal;
}

function Obiecte() {
  var nrObj = document.getElementById("nrobiecte").value;
  var i;
  for(i = 1; i <= 10; i++){
	  if(i <= nrObj){
		document.getElementById("divObj" + i).style.display = "block";  
	  }
	  else{
		document.getElementById("divObj" + i).style.display = "none";  
	  }
  }
}
</script>

</div>
