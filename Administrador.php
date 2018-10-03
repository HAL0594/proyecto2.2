<?php
session_start();
require 'funcs/conexion.php';
include 'funcs/funcs.php';

if (!isset($_SESSION["id_usuario"])) { //Si no ha iniciado sesión redirecciona a index.php
	header("Location: index.php");
}

$idUsuario = $_SESSION['id_usuario'];

$sql = "SELECT id_usuario, nombre FROM usuarios WHERE id_usuario = '$idUsuario'";
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
			}

.bordered-tab-contents , .tab-content  {
    border-left: 1px solid #ddd;
    border-right: 1px solid #ddd;
	border-bottom: 1px solid #ddd;
    border-radius: 0px 0px 5px 5px;
    padding: 10px;
}

	</style>
</head>

<body>
	<div class="container-fluid">

		<nav class='navbar navbar-default'>
			<div class='nav nav1 container-fluid'>
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
					<ul class='nav nav1 navbar-nav '>
						<li> <a href='Administrador.php' class='LogM'> <i class='glyphicon glyphicon-home'></i> MiBanca</a></li>
					</ul>
					<?php if ($_SESSION['tipo_usuario'] == 1) { ?>
					<ul class='nav nav1 navbar-nav'>
						<li> <a href='#'> <i class='glyphicon glyphicon-pencil'></i> Panel de Administrar</a></li>
					</ul>
					<?php 
					} ?>
					<ul class='nav nav1 navbar-nav navbar-right'>
						<li> <a href='logout.php'> <i class='glyphicon glyphicon-off'></i> Cerrar Sesi&oacute;n</a></li>
					</ul>

					<ul class='nav nav1 navbar-nav navbar-right'>
						<li> <a> <i class='glyphicon glyphicon-user'></i>
								<?php echo '' . utf8_decode($row['nombre']); ?></a></li>
					</ul>
				</div>
			</div>
		</nav>


		<div class="container-fluid">

  <ul class="nav nav-tabs nav-justified" role="tablist">
    <li class="active"><a data-toggle="tab" href="#menu1">Agregar Cajeros</a></li>
    <li><a data-toggle="tab" href="#menu2">Lista de Cajeros</a></li>
  </ul>

  <div class="tab-content">
    <div id="menu1" class="tab-pane fade in active">
      <h3>Ingrese la Informacion</h3>
      <div class="panel-body" >
						<form id="signupform" class="form-horizontal" role="form" action="IngCajero.php" method="POST" autocomplete="off">
							
							<div id="signupalert" style="display:none" class="alert alert-danger">
								<p>Error:</p>
								<span></span>
							</div>
							
							<div class="form-group">
								<label for="nombre" class="col-md-3 control-label">Nombre:</label>
								<div class="col-md-9">
									<input type="text" class="form-control" name="nombre" placeholder="Nombre" value="<?php if (isset($nombre)) echo $nombre; ?>" required >
								</div>
							</div>
							
							<div class="form-group">
								<label for="usuario" class="col-md-3 control-label">Usuario</label>
								<div class="col-md-9">
									<input type="text" class="form-control" name="usuario" placeholder="Usuario" value="<?php if (isset($usuario)) echo $usuario; ?>" required>
								</div>
							</div>

							<div class="form-group">
								<label for="Cuenta" class="col-md-3 control-label">Cuenta</label>
								<div class="col-md-9">
									<input type="text" class="form-control" name="Cuenta" placeholder="Ingrese cuenta de trabajador" value="<?php if (isset($Cuenta)) echo $Cuenta; ?>" required>
								</div>
							</div>


							<div class="form-group">
								<label for="email" class="col-md-3 control-label">Email</label>
								<div class="col-md-9">
									<input type="email" class="form-control" name="email" placeholder="Email" value="<?php if(isset($email)) echo $email; ?>" required>
								</div>
							</div>
							
							<div class="form-group">
								<label for="telefono" class="col-md-3 control-label">Telefono</label>
								<div class="col-md-9">
									<input type="text" class="form-control" name="telefono" placeholder="Telefono" value="<?php if (isset($telefono)) echo $telefono; ?>" required>
								</div>
							</div>

							<div class="form-group">
								<label for="password" class="col-md-3 control-label">Password</label>
								<div class="col-md-9">
									<input type="password" class="form-control" name="password" placeholder="Password" required>
								</div>
							</div>
							
							<div class="form-group">
								<label for="con_password" class="col-md-3 control-label">Confirmar Password</label>
								<div class="col-md-9">
									<input type="password" class="form-control" name="con_password" placeholder="Confirmar Password" required>
								</div>
							</div>
													
							<div class="form-group">
								<label for="captcha" class="col-md-3 control-label"></label>
								<div class="g-recaptcha col-md-9" data-sitekey="6Lc8yW8UAAAAAIcxLTkix1A_xX4YtHtVddqoJNHP"></div> <!-- clave recaptcha -->
							</div>
							
							<div class="form-group">                             
								<div class="col-md-offset-3 col-md-9">
									<button id="btn-signup" type="submit" class="btn btn-primary btn-sm btn-block" name="Enviar"><i class="icon-hand-right"></i>Registrar</button> 
								</div>
							</div>
						</form>
	</div>
	
    <div id="menu2" class="tab-pane fade">
      <h3>Transferencias</h3>
      <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.</p>
	</div>
	
	</div>


			
	</div>
	</div>

</body>

</html>