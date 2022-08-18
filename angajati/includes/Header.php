<?php
require_once("includes/Config.php");
if(isset($_SESSION['AngajatID'])){
			$query = 'CALL GetAngajatData(' . $_SESSION['AngajatID'] . ');';
			$result = mysqli_query($DB, $query);
			mysqli_next_result($DB);
			$user = mysqli_fetch_array($result, MYSQLI_ASSOC);
}
else{
	Header('Location: login.php');
}
?>

<head>
    <meta charset="utf-8">
	
    <title>Firma Curierat - Panel Angajati</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
	<style>
		.menu{
			z-index:100;
			overflow: hidden;
			background-color: #222;
			border-left: 1px solid #111;
			border-right: 1px solid #111;
			position: fixed;
			top: 0;
			width: -moz-calc(100% - 640px);
			width: -webkit-calc(100% - 640px);
			width: -o-calc(100% - 640px);
			width: calc(100% - 640px);
			right:340px;
			height:55px;
			white-space: nowrap;
			padding-top:25px;
		}
		.menu a{
			margin: 8px;
			padding-top:35px;
			font-family: Verdana;
			font-size:16px;
			transition:0.6s;
			margin-left:15px;
		}
		
		.menu a:hover{
			color:#E0B686;
			font-size:24px;
		}
		
		.banner{
			background-color:#222;
			height: 80px;
			overflow: hidden;
			position: fixed;
			top: 0px;
			left:0;
			z-index:100;
			width: 300px;
			cursor: pointer;
		}
		
		.userinfo{
			background-color:#333;
			height: 80px;
			overflow: hidden;
			position: fixed;
			top: 0px;
			right:0;
			z-index:100;
			width: 340px;
			text-align:left;
		}
		
		.userinfoContent{
			padding:10px;
			font-family: Trebuchet MS;
			font-size:15;
			color:white;
			margin-top:15px;
		}
		
		.pagecontent{
			margin-top:80px;
			background: url('images/background.jpg') no-repeat center;
			background-size: cover;
			background-attachment: fixed;
			min-height:100%;
			padding:30px;
		}
		
		.logoutdiv{
			background-color: indianred;
			width: 110px;
			float:right;
			padding:7px;
			cursor:pointer;
			text-align:center;
			position:fixed;
			top:25px;
			right:7px;
			font-family: Trebuchet MS;
			border-radius:5px;
			border:1px solid #333;
			transition:0.6s;
		}
		
		.logoutdiv:hover{
			background-color: red;
		}

		body{
			margin: 0;
			padding: 0;
		}
		
		a{
			text-decoration:none;
			color: white;
		}
	</style>
</head>

<body>
	<div class="banner">
	<img src="images/logo.png" alt="LOGO">
	</div>
	<div class="userinfo">
		<div class="userinfoContent">
			<?php 
				echo $user['Nume'] . ' ' . $user['Prenume'] . '<br>'; 
				echo $user['Email'] . '<br>';
			?>
		</div>
		<div class="logoutdiv">
             <a href="?logout">
                  <span class="fa fa-sign-out" style="display:inline;">  </span>Deconectare
              </a>
		</div>
	</div>
	<div class="menu">
	<a href="statistici.php"><span class="fa fa-signal"></span> STATISTICI</a>
	<?php
		$qr = 'SELECT EsteManager(' . $_SESSION['AngajatID'] . ');';
		$qr_res = mysqli_query($DB, $qr);
		$manager = mysqli_fetch_array($qr_res, MYSQLI_BOTH);
		if($manager[0] == 'DA'){
	?>
		<a href="manager.php"><font color="red"><span class="fa fa-users"></span> DEPARTAMENT</font></a>
	<?php } 
		if($user['DepartamentID'] == 1){ ?>
	<a href="adaugaangajat.php"><span class="fa fa-plus"></span> ADAUGA UN ANGAJAT</a>
	<a href="angajati.php"><span class="fa fa-cogs"></span> CONTURI ANGAJATI</a>
	<a href="clienti.php"><span class="fa fa-cogs"></span> CONTURI CLIENTI</a>
	<a href="orase.php"><span class="fa fa-cogs"></span> ORASE</a>
	<?php }else if($user['DepartamentID'] == 2){ ?>
		<a href="crearepromotii.php"><span class="fa fa-star"></span> CREARE PROMOTII</a>
		<a href="atribuirepromotii.php"><span class="fa fa-star"></span> ATRIBUIRE PROMOTII</a>
	<?php }else if($user['DepartamentID'] == 3){ ?>
		<a href="preluarecomenzi.php"><span class="fa fa-archive"></span> PRELUARE COMENZI</a>
		<a href="coletepreluate.php"><span class="fa fa-archive"></span> COLETE PRELUATE</a>
	<?php }else if($user['DepartamentID'] == 4){ ?>
		<a href="ridicarecolet.php"><span class="fa fa-archive"></span> RIDICARE COLETE </a>
		<a href="coleteridicate.php"><span class="fa fa-archive"></span> COLETE RIDICATE</a>
	<?php } ?>
	</div>
	<div class="pagecontent"><center>
	
