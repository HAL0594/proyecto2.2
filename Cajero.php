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

//******************************** Creacion de cuentas **********************************************************

if (isset($_POST['Enviar'])) {

	$nombre = $_POST['nombre'];
	$dpi = $_POST['dpi'];
	$pin = $_POST['pin'];

	if (RegistraCuenta($nombre, $dpi, $pin)) {

		unset($nombre);
		unset($dpi);
		unset($pin);

		$SQLTransacList = "SELECT max(no_cuenta) as cuenta FROM cuentas";
		$TransacList = $mysqli->query($SQLTransacList);
		$lista = $TransacList->fetch_assoc();

		$msg = $lista['cuenta'];
		$errors[] = "Numero de cuenta creado $msg";

	} else {
		$errors[] = "Sucedio Algun Problema";
	}
}
	
//******************************** Consulta Cuentas **********************************************************

if (isset($_POST['Enviar1'])) {

	$cuenta = $_POST['cuenta'];


	$SQLCuenta = "SELECT `no_cuenta`,`NombreCuenta`,`DPI`,`saldo`, case when `estado`= 1 then 'Activo' else 'Inactiva' end as est  FROM cuentas WHERE no_cuenta = '$cuenta'";
	$CuentaLista = $mysqli->query($SQLCuenta);
	$len = $CuentaLista->num_rows;

	if ($len > 0) {
		$lista = $CuentaLista->fetch_assoc();
	} else
		$errors[] = "la cuenta no es valida";


}
	
//******************************** Deposito **********************************************************

if (isset($_POST['EnviarDeposito'])) {

	$CantTran = $_POST['monto'];
	$CuentaDeposito = $_POST['cuenta'];

	
	$DescTrans = "Deposito";
	$RTransac = RealizaDepRet($DescTrans, $CuentaDeposito, $CantTran);
	if ($RTransac > 0) {
		echo "la transaccion se realiza correctamente";
	}

	$SQLCuenta = "SELECT `no_cuenta`,`NombreCuenta`,`DPI`,`saldo`, case when `estado`= 1 then 'Activo' else 'Inactiva' end as est  FROM cuentas WHERE no_cuenta = '$CuentaDeposito'";
	$CuentaLista = $mysqli->query($SQLCuenta);
	$len = $CuentaLista->num_rows;

	if ($len > 0) {
		$lista = $CuentaLista->fetch_assoc();
	} else
		$errors[] = "la cuenta no es valida";


}

//******************************** Retiro **********************************************************

if (isset($_POST['EnviaRetiro'])) {

	$cuenta = $_POST['monto'];


	$SQLCuenta = "SELECT `no_cuenta`,`NombreCuenta`,`DPI`,`saldo`, case when `estado`= 1 then 'Activo' else 'Inactiva' end as est  FROM cuentas WHERE no_cuenta = '$cuenta'";
	$CuentaLista = $mysqli->query($SQLCuenta);
	$len = $CuentaLista->num_rows;

	if ($len > 0) {
		$lista = $CuentaLista->fetch_assoc();
	} else
		$errors[] = "la cuenta no es valida";


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
						<li> <a href='cajero.php' class='LogM'> <i class='glyphicon glyphicon-home'></i> MiBanca</a></li>
					</ul>
					<?php if ($_SESSION['tipo_usuario'] == 3) { ?>
					<ul class='nav nav1 navbar-nav'>
						<li> <a href='#'> <i class='glyphicon glyphicon-pencil'></i> Panel de Cajero</a></li>
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
    <li class="active"><a data-toggle="tab" href="#menu1">Creacion de Cuentas</a></li>
    <li><a data-toggle="tab" href="#menu2">Transacciones</a></li>
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
      
	  
	  <h3>Ingrese la informacion para el Deposito</h3>
      <p>
	  
	  <form class="form-horizontal" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" autocomplete="off">
		<div class="form-group tab-pane col-sm-6">
		
		                    <div class="form-group">
								<label for="cuenta" class="col-md-3 control-label">Cuenta:</label>
								<div class="col-md-9">
									<input type="text" class="form-control" name="cuenta" placeholder="Ingrese el numero de cuenta" value="<?php if (isset($cuenta)) echo $cuenta; ?>" required >
									
								</div>
							</div>
							

							<div class="form-group">                             
								<div class="col-md-offset-3 col-md-9">
									<button id="btn-signup" type="submit" class="btn btn-primary btn-sm btn-block" name="Enviar1"><i class="icon-hand-right"></i>Consulta Cuenta</button> 
								</div>
							</div>

							<div class="form-group">
								<label for="monto" class="col-md-3 control-label">Ingrese el Monto</label>
								<div class="col-md-9">
									<input type="text" class="form-control" name="monto" placeholder="Ingrese el monto" value="<?php if (isset($monto)) echo $monto; ?>" >
									
								</div>
							</div>
							

							<div class="form-group">                             
								<div class="col-md-offset-3 col-md-3">
									<button id="btn-signup" type="submit" class="btn btn-primary btn-sm btn-block" name="EnviarDeposito"><i class="icon-hand-right"></i>Deposito Monetario</button> 
								</div>
							</div>

							<div class="form-group">                             
								<div class="col-md-offset-3 col-md-3">
									<button id="btn-signup" type="submit" class="btn btn-primary btn-sm btn-block" name="EnviarRetiro"><i class="icon-hand-right"></i>Retiro Monetario</button> 
								</div>
							</div>




							<div class="form-group"><?php echo resultBlock($errors); ?></div>

		</div>
		<div class="form-group tab-pane col-sm-5">
							<h3>Informacion de la Cuenta</h3>
							<div class="panel panel-default">
								<div class="panel-body">
									<table class="table table-fixed">
										<thead>
											<tr>
												<th>Id:</th>
												<th>No cuenta:</th>
												<th>Nombre Cuenta:</th>
												<th>DPI:</th>
												<th>Saldo:</th>
												<th>Estado:</th>
											</tr>
										</thead>
										<tbody>
											 <?php

											for ($i = 0; $i < $len; $i++) {
												echo "<tr><td width: 5%>" . $i . "</td><td>" . $lista['no_cuenta'] . "</td><td>" . $lista['NombreCuenta'] . "</td><td>" . $lista['DPI'] . "</td><td>Q." . $lista['saldo'] . "</td></tr>" . $lista['est'] . "</td></tr>";
											}
											?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
	</form>
	  
   </p>
    
	</div>
    
  </div>


			
		</div>
	</div>

</body>

</html>