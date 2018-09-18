<?php


	session_start();
	require 'funcs/conexion.php';
	include 'funcs/funcs.php';
	
	if(!isset($_SESSION["id_usuario"])){ //Si no ha iniciado sesión redirecciona a index.php
		header("Location: index.php");
	}
	
	$idUsuario = $_SESSION['id_usuario'];
	
	$sql = "SELECT id, nombre FROM usuarios WHERE id = '$idUsuario'";
	$result = $mysqli->query($sql);
	
	$row = $result->fetch_assoc();
?>

<html>

<head>
	<title>Welcome</title>
	<link rel="stylesheet" href="css/bootstrap-theme.min.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

	<style>
		body {
			padding-top: 20px;
			padding-bottom: 20px;
			}

			#navbar, .nav {
				background-color: #1874cd;
			}

			.navbar li a, .navbar .navbar-brand {
      color: #fff !important;
  }

    .jumbotron {
      background-color: #3385ff;
      color: #fff;
      padding: 100px 25px;
      font-family: Montserrat, sans-serif;
  }

  .tab {
    float: left;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
    width: 30%;
    height: 300px;
}

/* Style the buttons inside the tab */
.tabutton {
    display: block;
    background-color: inherit;
    color: black;
    padding: 22px 16px;
    width: 100%;
    border: none;
    outline: none;
    text-align: left;
    cursor: pointer;
    transition: 0.3s;
    font-size: 17px;
}

/* Change background color of buttons on hover */
.tabutton:hover {
    background-color: #ddd;
}

/* Create an active/current "tab button" class */
.tabutton:active {
    background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
    float: left;
    padding: 0px 12px;
    border: 1px solid #ccc;
    width: 70%;
    border-left: none;
    height: 300px;
}
		</style>
</head>

<body>
	<div class="container-fluid">

		<nav class='navbar navbar-default'>
			<div class='nav container-fluid'>
				<div class='navbar-header'>
					<button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#navbar' aria-expanded='false'
					 aria-controls='navbar'>
						<span class='sr-only'>Men&uacute;</span>
						<span class='icon-bar'></span>
						<span class='icon-bar'></span>
						<span class='icon-bar'></span>
					</button>
				</div>

				<div id='navbar' class='navbar-collapse collapse '>
					<ul class='nav navbar-nav '>
						<li><a href='cliente.php'> <i class='glyphicon glyphicon-home'></i> Inicio</a></li>
					</ul>
					<?php if($_SESSION['tipo_usuario']==1) { ?>
					<ul class='nav navbar-nav'>
						<li><a href='#'><i class='glyphicon glyphicon-pencil'></i> Administrar Usuarios</a></li>
					</ul>
					<?php } ?>
					<ul class='nav navbar-nav navbar-right'>
						<li><a href='logout.php'><i class='glyphicon glyphicon-off'></i> Cerrar Sesi&oacute;n</a></li>
					</ul>

					<ul class='nav navbar-nav navbar-right'>
						<li><a><i class='glyphicon glyphicon-user'></i>
								<?php echo ''.utf8_decode($row['nombre']); ?></a></li>
					</ul>
				</div>
			</div>
		</nav>


		<div class="container-fluid">

			<ul class="tab">
				<li class="tabutton"><a data-toggle="tab" href="#1">londres</a></li>
				<li class="tabutton"><a data-toggle="tab" href="#2">paris</a></li>
			</ul>

			<div class="tabcontent tab-content">
				<div id="1" class="tab-pane fade">
					<h3>p</h3>
					<p>Eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
				</div>

				<div id="2" class="tab-pane fade">
					<h3>l</h3>
					<p>Eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
				</div>
			</div>
		</div>
	</div>

</body>

</html>