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
    $no_cuenta = $_POST['Cuenta'];
    

    if ($password == $con_password) {

        echo "entramos a la funcion";
        echo "$usuario"." $password"."$nombre"."$email"."$telefono"."$no_cuenta";
 


       if(registraCajero($usuario,$password,$nombre,$email,1,'0',3,$telefono,$no_cuenta)){
        header("Location: Administrador.php"); 
       }
       //header("Location: Administrador.php"); 

    } else {
        $errors[] = "Error El Password no coincide";
        //echo "El Password no coincide";
    }




}
?>


