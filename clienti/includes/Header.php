<?php
require_once("includes/Config.php");
if(isset($_SESSION['ClientID'])){
			$query = 'CALL GetClientData(' . $_SESSION['ClientID'] . ');';
			$result = mysqli_query($DB, $query);
			$user = mysqli_fetch_array($result, MYSQLI_ASSOC);
			mysqli_next_result($DB);
}
else{
	Header('Location: login.php');
}
?>

<head>
    <meta charset="utf-8">
	
    <title>Firma Curierat - Panel Clienti</title>
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
			margin: 10px;
			padding-top:30px;
			font-family: Verdana;
			font-size:20;
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
	<a href="expediere.php"><span class="fa fa-paper-plane"></span> EXPEDIERE</a>
	<a href="colete.php"><span class="fa fa-archive"></span> COLETELE MELE</a>
	<a href="cont.php"><span class="fa fa-user"></span> CONTUL MEU</a>
	<a href="promotii.php"><span class="fa fa-star"></span> PROMOTII</a>
	</div>
	<div class="pagecontent"><center>
	
