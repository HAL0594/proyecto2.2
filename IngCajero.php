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

if(isset($_POST['Enviar'])) {	

$nombre = $_POST['nombre'];
$usuario = $_POST['usuario'];
$email = $$_POST['email'];
$telefono = $_POST['telefono'];
$password = $_POST['password'];
$con_password = $_POST['con_password'];

if ($password == $con_password) {
    
    $query = "INSERT INTO `usuarios`(`usuario`, `password`, `nombre`, `correo`, `last_session`, `activacion`, `telefono`, 
                                     `no_cuenta`, `token`, `password_request`, `id_tipo`, `saldo_cuenta`) 
                    VALUES ($usuario,$password,$nombre,$email,null,1,$telefono,null,generateToken(),[value-11],[value-12],[value-13]);";
    echo "aqui va el insert";
}else{
    echo "El Password no coincide";
}



// if ($mysqli->query($query) === true) {
//     $mysqli->close();
//     header("Location: index.php");
    
// }else {
//     var_dump($mysqli);
// }

}
?>
