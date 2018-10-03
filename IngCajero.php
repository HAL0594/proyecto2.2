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

if (isset($_POST['Enviar'])) {

    $nombre = $_POST['nombre'];
    $usuario = $_POST['usuario'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $password = $_POST['password'];
    $con_password = $_POST['con_password'];
    $no_cuenta = $_POST['Cuenta'];
    

    if ($password == $con_password) {

       registraCajero($usuario,$password,$nombre,$email,1,null,3,$telefono,$no_cuenta);
       header("Location: Administrador.php"); 

    } else {
        echo "El Password no coincide";
    }




}
?>
