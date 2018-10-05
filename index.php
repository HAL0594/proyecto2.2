<?php
session_start();
require 'funcs/conexion.php';
include 'funcs/funcs.php';

$errors = array();

if(!empty($_POST)){
    $usuario = $mysqli->real_escape_string($_POST['usuario']);
    $password = $mysqli->real_escape_string($_POST['password']);

   if(isNullLogin($usuario, $password))
   {
       $errors[] = "Debe llenar todos los campos";
   }

   $errors[] = login($usuario, $password);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>

  <title>MI Banca</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="css/estilo.css">
</head>

<body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60"></body>

<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#myPage">MI Banca</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#about">ACERCA DE</a></li>
        <li><a href="#services">SERVICIOS</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="jumbotron text-center">
  <h1>MI Banca</h1>
  <p>Especialistas en Banca en linea</p>
</div>

<!-- Seccion Acerca De-->
<div id="about" class="container-fluid">
  <div class="row">
    <div class="col-sm-8">
      <!-- CONTENIDO DEL ACERCA DE-->
      <div id="myCarousel" class="carousel slide" data-ride="carousel">
        <!-- Indicadores -->
        <ol class="carousel-indicators">
          <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
          <li data-target="#myCarousel" data-slide-to="1"></li>
          <li data-target="#myCarousel" data-slide-to="2"></li>
          <li data-target="#myCarousel" data-slide-to="3"></li>
        </ol>

        <!-- contenedor de sliders -->
        <div class="carousel-inner" role="listbox">
          <div class="item active">
            <img src="img/1.jpg" alt="Chania">
            <div class="carousel-caption">
              <h3>SLIDER 1</h3>
              <p>ESTE ES EL COMENTARIO DEL SLIDER 1 </p>
            </div>
          </div>

          <div class="item">
            <img src="img/2.jpg" alt="Chania">
            <div class="carousel-caption">
              <h3>SLIDER 2</h3>
              <p>ESTE ES EL COMENTARIO DEL SLIDER 2 </p>
            </div>
          </div>

          <div class="item">
            <img src="img/3.jpg" alt="Flower">
            <div class="carousel-caption">
              <h3>SLIDER 3</h3>
              <p>ESTE ES EL COMENTARIO DEL SLIDER 3 </p>
            </div>
          </div>

          <div class="item">
            <img src="img/4.jpg" alt="Flower">
            <div class="carousel-caption">
              <h3>SLIDER 4</h3>
              <p>ESTE ES EL COMENTARIO DEL SLIDER 4 </p>
            </div>
          </div>
        </div>

        <!-- Controles de navegacion -->
        <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
          <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
          <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>
      <p>Texto Aqui</p>

    </div>
    <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-4 ">
      <div class="panel panel-info">
        <div class="panel-heading">
          <div class="panel-title">Iniciar Sesi&oacute;n</div>

        </div>

        <div style="padding-top:30px" class="panel-body">

          <div style="display:none" id="login-alert" class="alert alert-danger col-sm-4"></div>

          <form id="loginform" class="form-horizontal" role="form" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST"
            autocomplete="off">

            <div style="margin-bottom: 25px" class="input-group">
              <span class="input-group-addon glyphicon glyphicon-user"></span>
              <input id="usuario" type="text" class="form-control" name="usuario" value="" placeholder="usuario o email"
                required>
            </div>

            <div style="margin-bottom: 25px" class="input-group">
              <span class="input-group-addon glyphicon glyphicon-lock"></span>
              <input id="password" type="password" class="form-control" name="password" placeholder="password" required>
            </div>

            <div style="margin-top:10px" class="form-group">
              <div class="col-sm-4 controls">
                <button id="btn-login" type="submit" class="btn btn-success">Iniciar Sesi&oacute;n</a>
              </div>
            </div>

            <div class="form-group">
              <div class="col-md-4 control">
                <div style="border-top: 1px solid#888; padding-top:15px; font-size:85%">
                  <h6>No tiene una cuenta! <a href="registro.php">Registrate aquí</h6></a>
                </div>
              </div>
            </div>
          </form>
          <?php echo resultBlock($errors); ?>
        </div>
      </div>
    </div>

  </div>
</div>

<div class="col-sm-12">

  <div class="container">

  </div>


  <footer class="container-fluid text-center">
    <a href="#myPage" title="To Top">
      <span class="glyphicon glyphicon-chevron-up"></span>
    </a>
    <p>Sitio Official de MI Banca.com </p>
  </footer>


  </body>

</html>