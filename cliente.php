<?php

$errors = array();

	session_start();
	require 'funcs/conexion.php';
	include 'funcs/funcs.php';
	
	if(!isset($_SESSION["id_usuario"])){ //Si no ha iniciado sesión redirecciona a index.php
		header("Location: index.php");
	}
	
//*********************************** Obteniendo Datos de Session ********************************************

    $permitido = 0;
	$idUsuario = $_SESSION['id_usuario'];
	
	$sql = "SELECT id_usuario, nombre, no_cuenta, correo, telefono FROM usuarios WHERE id_usuario = '$idUsuario'";
	$result = $mysqli->query($sql);
	$row = $result->fetch_assoc();
	$minombre = $row['nombre'];
	$micuenta = $row['no_cuenta'];
	$miid = $row['id_usuario'];
	$micorreo = $row['correo'];
	$mitelefono = $row['telefono'];

	$sqlCuenta = "SELECT saldo, no_cuenta FROM cuentas WHERE no_cuenta = '$micuenta'";
	$ResCeunta = $mysqli->query($sqlCuenta);
	$Dcuenta = $ResCeunta->fetch_assoc();
	$misaldo = $Dcuenta['saldo'];

//*********************************** Query para lista de terceros ********************************************

$SQLListaContactos="SELECT no_cuenta FROM terceros WHERE id_usuario = '$idUsuario' AND permitido = 1";
$resultC = $mysqli->query($SQLListaContactos);

//*********************************** Comienza El Codigo de Transacciones ********************************************

if (isset($_POST['agregar_contacto'])) 
	{

	$no_cuentaP = $_POST['no_cuenta'];

	if($micuenta != $no_cuentaP){

$SQLYaExiste="SELECT no_cuenta  FROM terceros WHERE no_cuenta = '$no_cuentaP' AND permitido = 1 AND id_usuario = '$idUsuario'";
$YaExiste = $mysqli->query($SQLYaExiste);
if ($YaExiste->num_rows > 0)
	{

		$errors[] = "Esta cuenta ya fue agregada";
	} else {
		


		
		$SQLExisteCuenta="SELECT no_cuenta FROM usuarios Where no_cuenta='$no_cuentaP'";
		$ExisteCuenta=$mysqli->query($SQLExisteCuenta);
		if ($ExisteCuenta->num_rows > 0)
		{
		
			$sqlTercero = "SELECT id_usuario, nombre, correo, no_cuenta FROM usuarios WHERE no_cuenta = '$no_cuentaP'";
			$resultTercero = $mysqli->query($sqlTercero);
			$rowTercero = $resultTercero->fetch_assoc();
			$emailTercero = $rowTercero['correo'];
			$nombreTercero = $rowTercero['nombre'];
			$cuentaTercero = $rowTercero['no_cuenta'];
			
			$token = generateToken();
			$registro = registraTercero($miid, $cuentaTercero, $permitido, $token);			
							if($registro > 0)
							{				
								$url = 'http://'.$_SERVER["SERVER_NAME"].'/proyecto2.2/add_terceros.php?val='.$token;
								
								$asunto = 'Uso de cuenta Para Transaccion';
								$cuerpo = "Estimado Usuario: <br /><br />El Usuario $minombre quiere usar tu cuenta para realizar transacciones  si quieres aceptar has click: <a href='$url'>Dar Permiso</a>";
								
								if(enviarEmail($emailTercero, $minombre, $asunto, $cuerpo)){
									
									$errors[] = "para agregar el Contacto para transferencias es necesario que este verifique el uso de su cuenta para ello se le a enviado un correo Para dar permisos";
									} else {
									$erros[] = "Error al enviar Email";
								}
								
								} else {
								$errors[] = "Error al agregar Cuenta";
							}
	
		} else {
			$errors[] = "No existe ninguna cuenta $no_cuentaP";
		}

	}

    	}else{ $errors[] = "No puedes agregar tu propia cuenta como un Contacto"; }
			}

//*********************************** Comienza El Codigo de Transacciones ********************************************

			if (isset($_POST['depositar']))
			{
				$CantTran = $_POST['CantTran'];
				$ContactoSel = $_POST['contactos'];
                  //codigo De transaccionnes
				  if($misaldo > $CantTran)
				  {
				  $DescTrans = "transaccion";  
				  $RTransac = RealizaTrans($DescTrans, $micuenta, $ContactoSel, $CantTran);
				  if($RTransac > 0)
					 echo "la transaccion se realiza correctamente"; 
					 header("Location: cliente.php");
				  }
				  else
				  {
					$errors[] = "Error al realizar la transaccion";
				  }
			

			}

//******************************** LISTA DE Transacciones **********************************************************#endregion

$SQLTransacList="SELECT descripcion, no_cuenta_origen, no_cuenta_destino, cantidad FROM transacciones WHERE no_cuenta_origen = '$micuenta' OR no_cuenta_destino = '$micuenta'";
$TransacList = $mysqli->query($SQLTransacList);
$len = $TransacList->num_rows;
           


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

.box select{
	background: #CCCCCC;
	color: #000;
	width: 250px;
	height: 40px;
	}

.table-fixed thead,
.table-fixed tfoot{
  width: 97%;
}

.table-fixed tbody {
  height: 230px;
  overflow-y: auto;
  width: 100%;
}



.table-fixed thead,
.table-fixed tbody,
.table-fixed tfoot,
.table-fixed tr,
.table-fixed td,
.table-fixed th {
  display: inline-block;
}

.table-fixed tbody td,
.table-fixed thead > tr> th,
.table-fixed tfoot > tr> td{

  border-bottom-width: 0;
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
				<li class="active"><a data-toggle="tab" href="#menu1">Estado De Cuenta </a></li>
				<li><a data-toggle="tab" href="#menu2">Transferencias</a></li>
				<li><a data-toggle="tab" href="#menu3">Agregar Cuentas de Contactos</a></li>
			</ul>

			<div class="tab-content">

				<div id="menu1" class="tab-pane fade in active">
					<form class="form-horizontal" action="TransacClientes" method="POST" autocomplete="off">
						<div class="form-group tab-pane col-sm-6">
							<h3>Datos de Cuenta</h3>
							<table class="table table-hover">
								<thead>
									<tr>
										<th>Campo:</th>
										<th>Mi informacion:</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<th scope="row">Usuario:</th>
										<td><?php echo "$minombre" ?></td>
									</tr>
								 	<tr>
										<td scope="row">No. de Cuenta:</td>
										<td><?php echo "$micuenta" ?></td>
									</tr>
									<tr>
										<td scope="row">Saldo:</td>
										<td><?php echo "Q.$misaldo" ?></td>
									</tr>
									<tr>
										<td scope="row">Correo:</td>
										<td><?php echo "$micorreo" ?></td>
									</tr>
									<tr>
										<td scope="row">Telefono:</td>
										<td><?php echo "$mitelefono" ?></td>
									</tr>
								</tbody>
							</table>

						</div>
						<div class="form-group tab-pane col-sm-2">
						</div>
						<div class="form-group tab-pane col-sm-4">
							<h3>Lista de transacciones</h3>
							<div class="panel panel-default">
								<div class="panel-body">
									<table class="table table-fixed">
										<thead>
											<tr>
												<th>No.:</th>
												<th>Tipo:</th>
												<th>Origen:</th>
												<th>Destino:</th>
												<th>Monto:</th>
											</tr>
										</thead>
										<tbody>
											 <?php

                                              for ($i = 0; $i < $len; $i++){
												$lista = $TransacList->fetch_assoc();    
                                                   echo "<tr><td width: 5%>". $i . "</td><td>" . $lista['descripcion'] . "</td><td>". $lista['no_cuenta_origen']  . "</td><td>" . $lista['no_cuenta_destino'] . "</td><td>Q." . $lista['cantidad'] . "</td></tr>";
                                             }
                                             ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</form>
				</div>

				<div id="menu2" class="tab-pane fade">
					<form class="form-horizontal" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" autocomplete="off">
						<div class="form-group tab-pane col-sm-6">
							<h3>Contactos</h3>
							<div class="box tab-pane">
								<select name="contactos">

									<?php 
		                     if ($resultC->num_rows > 0)
		                     {
								echo " <option value=''>Seleccione un contacto</option>"; 
							
			                 while ($row = $resultC->fetch_array(MYSQLI_ASSOC)) 
			                 {
								$accu= $row['no_cuenta'];
								$sql1= "SELECT NombreCuenta FROM cuentas WHERE no_cuenta = '$accu'";
								$ResNomC = $mysqli->query($sql1);
								$S1 = $ResNomC->fetch_assoc();
								$NombreActual = $S1['NombreCuenta'];
				             echo " <option value='".$accu."'>".$NombreActual.": ".$accu."</option>"; 
							 }
							 echo "</select>";
		                     }
		                     else
		                     {
							 echo " <option value='Sin Contactos'>Sin Contactos</option>"; 
							 echo "</select>";
							 echo "<br><div class="."col-sm-4"."><p>IMPORTANTE: para agregar un contacto ve a la pestana contactos y agrega el numero de cuenta del contacto, El usuario de dicha cuenta recivira una peticion de parte tulla en su correo para que puedas realizar transacciones a su cuenta</p></div>";
							 }
							 
		                     ?>
								
							</div>
						</div>
						<div class="form-group tab-pane col-sm-6">
							<div class="form-group">
								<label for="CantTran">Catidad de la transferencia:</label>
								<input type="CantTran" name="CantTran" class="form-control" id="CantTran">
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-primary btn-sm btn-default " name="depositar">Confirmar</button>
							</div>
						</div>
					</form>
				</div>

				<div id="menu3" class="tab-pane fade">

					<form class="form-horizontal" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" autocomplete="off">
					
						<div class="form-group">
						     <br>
							<label for="no_cuenta">No. cuenta de Contacto</label>
							<input type="no_cuenta" name="no_cuenta" class="form-control" id="no_cuenta">
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-primary btn-sm btn-default" name="agregar_contacto">Confirmar</button>
						</div>
					</form>

				</div>
			</div>
		</div>
		<?php echo resultBlock($errors); ?>
	</div>

</body>

</html>