<?php
$errors = array();
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

if (isset($_POST['Enviar'])) {

    $nombre = $_POST['nombre'];
    $usuario = $_POST['usuario'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $password = $_POST['password'];
    $con_password = $_POST['con_password'];
    $no_cuenta = $_POST['no_cuenta'];
    

    if ($password == $con_password) {

        
       if(registraCajero($usuario,$password,$nombre,$email,1,'0',3,$telefono,$no_cuenta)){
			
			unset($nombre);
			unset($usuario);
			unset($email);
			unset($telefono);
			unset($password);
			unset($con_password);
			unset($no_cuenta);	
			$errors[] = "Cajero ingresado con EXITO";
			
       }

    } else {
        $errors[] = "Error El Password no coincide";
    }
}
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
	<form class="form-horizontal" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" autocomplete="off">
		<div class="form-group tab-pane col-sm-6">
		
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
								<label for="no_cuenta" class="col-md-3 control-label">Cuenta</label>
								<div class="col-md-9">
									<input type="text" class="form-control" name="no_cuenta" placeholder="Ingrese cuenta de trabajador" value="<?php if (isset($no_cuenta)) echo $no_cuenta; ?>" required>
								</div>
							</div>


							<div class="form-group">
								<label for="email" class="col-md-3 control-label">Email</label>
								<div class="col-md-9">
									<input type="email" class="form-control" name="email" placeholder="Email" value="<?php if (isset($email)) echo $email; ?>" required>
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
							<div class="form-group"><?php echo resultBlock($errors); ?></div>

		</div>
	</form>
		
</div>

<div id="menu2" class="tab-pane fade">
	<form class="form-horizontal" action="TransacClientes" method="POST" autocomplete="off">
		<div class="form-group tab-pane col-sm-6">
			<h3>Contactos</h3>
			<div class="box tab-pane">
				<select name="estado">
					<?php 
			 if ($resultC->num_rows > 0)
			 {

			 while ($row = $resultC->fetch_array(MYSQLI_ASSOC)) 
			 {
			 echo " <option value='".$row['no_cuenta']."'>".$row['no_cuenta']."</option>"; 
			 }
			 }
			 else
			 {
			 echo " <option value='Sin Contactos'>Sin Contactos</option>"; 
			 }
			 ?>
				</select>
			</div>
		</div>
		<div class="form-group tab-pane col-sm-6">
			<div class="form-group">
				<label for="no_cuenta">Catidad del deposito:</label>
				<input type="no_cuenta" name="no_cuenta" class="form-control" id="no_cuenta">
			</div>
			<div class="form-group">
				<button type="submit" class="btn btn-default">Confirmar</button>
			</div>
		</div>
	</form>
</div>


</div>
</div>

</div>

</body>

</html>