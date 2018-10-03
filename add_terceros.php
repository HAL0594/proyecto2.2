<?php


	require 'funcs/conexion.php';
	include 'funcs/funcs.php';
	
	if(isset($_GET['val']))
	{	
		$token = $_GET['val'];
		
		$mensaje = validaIdTokenTercero($token);
	}
?>
<html>
	<head>
		<title>Registro</title>
		<link rel="stylesheet" href="css/bootstrap.min.css" >  
		<link rel="stylesheet" href="css/bootstrap-theme.min.css" >
		<script src="js/bootstrap.min.js" ></script>
		
	</head>
	
	<body>
		<div class="container">
			<div class="jumbotron">
				
				<h1><?php echo $mensaje; ?></h1>
				
				<br />
				<p><a class="btn btn-primary btn-lg" href="cliente.php" role="button">Iniciar Sesi&oacute;n</a></p>
			</div>
		</div>
	</body>
</html>

