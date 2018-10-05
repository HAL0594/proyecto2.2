<?php
$errors = array();

	session_start();
	require 'funcs/conexion.php';
	include 'funcs/funcs.php';
	
	if(!isset($_SESSION["id_usuario"])){ //Si no ha iniciado sesión redirecciona a index.php
		header("Location: index.php");
	}
	
	$idUsuario = $_SESSION['id_usuario'];
	
	$sql = "SELECT id_usuario, nombre FROM usuarios WHERE id_usuario = '$idUsuario'";
	$result = $mysqli->query($sql);
	
	$row = $result->fetch_assoc();


	if (isset($_POST['Enviar'])) {

		$nombre = $_POST['nombre'];
		$dpi = $_POST['dpi'];
		$pin = $_POST['pin'];
		
		   if(RegistraCuenta($nombre, $dpi, $pin)){
				
				unset($nombre);
				unset($dpi);
				unset($pin);
				
				$SQLTransacList="SELECT max(no_cuenta) as cuenta FROM cuentas";
				$TransacList = $mysqli->query($SQLTransacList);
				$lista = $TransacList->fetch_assoc(); 

                $msg = $lista['cuenta'];
				$errors[] = "Numero de cuenta creado $msg";
				
		   } else {
			$errors[] = "Sucedio Algun Problema";
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
						<li> <a href='cliente.php' class='LogM'> <i class='glyphicon glyphicon-home'></i> MiBanca</a></li>
					</ul>
					<?php if($_SESSION['tipo_usuario']==1) { ?>
					<ul class='nav nav1 navbar-nav'>
						<li> <a href='#'> <i class='glyphicon glyphicon-pencil'></i> Administrar Usuarios</a></li>
					</ul>
					<?php } ?>
					<ul class='nav nav1 navbar-nav navbar-right'>
						<li> <a href='logout.php'> <i class='glyphicon glyphicon-off'></i> Cerrar Sesi&oacute;n</a></li>
					</ul>

					<ul class='nav nav1 navbar-nav navbar-right'>
						<li> <a> <i class='glyphicon glyphicon-user'></i>
								<?php echo ''.utf8_decode($row['nombre']); ?></a></li>
					</ul>
				</div>
			</div>
		</nav>


		<div class="container-fluid">



  <ul class="nav nav-tabs nav-justified" role="tablist">
    <li class="active"><a data-toggle="tab" href="#menu1">Creacion de Cuentas</a></li>
    <li><a data-toggle="tab" href="#menu2">Deposito</a></li>
    <li><a data-toggle="tab" href="#menu3">Retiros</a></li>
  </ul>

  <div class="tab-content">
    <div id="menu1" class="tab-pane fade in active">
      
	  
	  <h3>Ingresar la Informacion Solicitada</h3>
      
	  <p>

	  <form class="form-horizontal" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" autocomplete="off">
		<div class="form-group tab-pane col-sm-6">
		
		                    <div class="form-group">
								<label for="nombre" class="col-md-3 control-label">Nombre:</label>
								<div class="col-md-9">
									<input type="text" class="form-control" name="nombre" placeholder="Ingrese Nombre" value="<?php if (isset($nombre)) echo $nombre; ?>" required >
								</div>
							</div>
							
							<div class="form-group">
								<label for="dpi" class="col-md-3 control-label">DPI</label>
								<div class="col-md-9">
									<input type="text" class="form-control" name="dpi" placeholder="Ingrese el DPI" value="<?php if (isset($dpi)) echo $dpi; ?>" required>
								</div>
							</div>

							<div class="form-group">
								<label for="pin" class="col-md-3 control-label">PIN</label>
								<div class="col-md-9">
									<input type="text" class="form-control" name="pin" placeholder="Ingrese el pin de la cuenta" value="<?php if (isset($pin)) echo $pin; ?>" required>
								</div>
							</div>

							<div class="form-group">                             
								<div class="col-md-offset-3 col-md-9">
									<button id="btn-signup" type="submit" class="btn btn-primary btn-sm btn-block" name="Enviar"><i class="icon-hand-right"></i>Registrar</button> 
								</div>
							</div>
							<div class="form-group"><?php echo resultBlock($errors); ?></div>

		</div>
	</form>
	  
	  
	  </p>
    
	
	</div>
    <div id="menu2" class="tab-pane fade">
      
	  
	  <h3>Transferencias</h3>
      <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.</p>
    
	</div>
    <div id="menu3" class="tab-pane fade">
      
	  
	  <h3>Estado De Cuenta</h3>
      <p>Eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
    
	</div>
  </div>


			
		</div>
	</div>

</body>

</html>