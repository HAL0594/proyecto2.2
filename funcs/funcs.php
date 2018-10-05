<?php




function isNULL($nombre, $user, $pass, $pass_con, $email)
{
    if (strlen(trim($nombre)) < 1
        || strlen(trim($user)) < 1
        || strlen(trim($pass)) < 1
        || strlen(trim($pass_con)) < 1
        || strlen(trim($email)) < 1) {
        return true;
    } else {
        return false;
    }
}

function validaPassword($var1, $var2)
{
    if (strcmp($var1, $var2) !== 0) {
        return false;
    } else {
        return true;
    }
}

function isEmail($email)
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}

function usuarioExiste($usuario)
{
    global $mysqli;

    $stmt = $mysqli->prepare("SELECT id_usuario FROM usuarios WHERE usuario = ? LIMIT 1");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->store_result();
    $num = $stmt->num_rows;
    $stmt->close();

    if ($num > 0) {
        return true;
    } else {
        return false;
    }
}

function emailExiste($email)
{
    global $mysqli;

    $stmt = $mysqli->prepare("SELECT id_usuario FROM usuarios WHERE correo = ? LIMIT 1");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    $num = $stmt->num_rows;
    $stmt->close();

    if ($num > 0) {
        return true;
    } else {
        return false;
    }
}

function generateToken()
{
    $gen = md5(uniqid(mt_rand(), false));
    return $gen;
}

function hashPassword($password)
{
    $hash = password_hash($password, PASSWORD_DEFAULT);
    return $hash;
}

function resultBlock($errors)
{
    $pagina_actual = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    if (count($errors) > 0) {
        echo "<div id='error' class='alert alert-danger' role='alert'>
        <a href='$pagina_actual' onclick=\"showHide('error');\">[X]</a>
        <ul>";
        foreach ($errors as $error) {
            echo "<li>" . $error . "</li>";
        }
        echo "</ul>";
        echo "</div>";
    }
}

function ValidarCuenta($no_cuenta, $PIN)
{

    global $mysqli;

    $stmt = $mysqli->prepare("SELECT no_cuenta, PIN FROM cuentas WHERE no_cuenta = ? AND PIN = ? LIMIT 1");
    $stmt->bind_param("ii", $no_cuenta, $PIN);
    $stmt->execute();
    $stmt->store_result();
    $rows = $stmt->num_rows;

    if ($rows > 0) {
        return 1;
    } else {
        return 0;
        $errors = "La cuenta o el PIN son incorrectos";
    }
}

function registraUsuario($usuario, $pass_hash, $nombre, $email, $activo, $token, $tipo_usuario, $telefono, $no_cuenta)
{

    global $mysqli;

    $stmt = $mysqli->prepare("INSERT INTO usuarios (usuario, password, nombre, correo, activacion, token, id_tipo, telefono, no_cuenta) VALUES(?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param('ssssisiii', $usuario, $pass_hash, $nombre, $email, $activo, $token, $tipo_usuario, $telefono, $no_cuenta);

    if ($stmt->execute()) {
        return $mysqli->insert_id;
    } else {
        return 0;
    }
}

function enviarEmail($email, $nombre, $asunto, $cuerpo)
{

    require_once 'PHPMailer/PHPMailerAutoload.php';

    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls'; //tipo de seguridad
    $mail->Host = 'smtp.gmail.com'; //dominio
    $mail->Port = 587; //puerto

    $mail->Username = 'mibanca0594@gmail.com'; //Correo de donde enviaremos los correos
    $mail->Password = 'password123()'; // Password de la cuenta de envío

    $mail->setFrom('mibanca0594@gmail.com', 'MiBanca');
    $mail->addAddress($email, $nombre);

    $mail->Subject = $asunto;
    $mail->Body = $cuerpo;
    $mail->IsHTML(true);

    if ($mail->send())
        return true;
    else
        return false;
}


function validaIdToken($id, $token)
{
    global $mysqli;


    $stmt = $mysqli->prepare("SELECT activacion FROM usuarios WHERE id_usuario = ? AND token = ? LIMIT 1");
    $stmt->bind_param("is", $id, $token);
    $stmt->execute();
    $stmt->store_result();
    $rows = $stmt->num_rows;

    if ($rows > 0) {
        $stmt->bind_result($activacion);
        $stmt->fetch();

        if ($activacion == 1) {
            $msg = "La cuenta ya se activo anteriormente.";
        } else {
            if (activarUsuario($id)) {
                $msg = 'Cuenta activada.';
            } else {
                $msg = 'Error al Activar Cuenta';
            }
        }
    } else {
        $msg = 'No existe el registro para activar.';
    }
    return $msg;
}

function activarUsuario($id)
{
    global $mysqli;

    $act = 1;
    $stmt = $mysqli->prepare("UPDATE usuarios SET activacion = ? WHERE id_usuario = ?");
    $stmt->bind_param('is', $act, $id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function isNullLogin($usuario, $password)
{
    if (strlen(trim($usuario)) < 1 || strlen(trim($password)) < 1) {
        return true;
    } else {
        return false;
    }
}

function isActivo($usuario)
{
    global $mysqli;

    $stmt = $mysqli->prepare("SELECT activacion FROM usuarios WHERE usuario = ? || correo = ? LIMIT 1");
    $stmt->bind_param('ss', $usuario, $usuario);
    $stmt->execute();
    $stmt->bind_result($activacion);
    $stmt->fetch();

    if ($activacion == 1) {
        return true;
    } else {
        return false;
    }
}

function lastSession($id)
{
    global $mysqli;

    $stmt = $mysqli->prepare("UPDATE usuarios SET last_session=NOW(), password_request=1 WHERE id_usuario = ?");
    $stmt->bind_param('s', $id);
    $stmt->execute();
    $stmt->close();
}

function login($usuario, $password)
{
    global $mysqli;

    $stmt = $mysqli->prepare("SELECT id_usuario, id_tipo, password FROM usuarios WHERE usuario = ? || correo = ? LIMIT 1");
    $stmt->bind_param("ss", $usuario, $usuario);
    $stmt->execute();
    $stmt->store_result();
    $rows = $stmt->num_rows;

    if ($rows > 0) {

        if (isActivo($usuario)) {

            $stmt->bind_result($id, $id_tipo, $passwd);
            $stmt->fetch();

            $validaPassw = password_verify($password, $passwd);

            if ($validaPassw) {

                lastSession($id);
                $_SESSION['id_usuario'] = $id;
                $_SESSION['tipo_usuario'] = $id_tipo;

                if ($id_tipo == 1) {
                    header("location: Administrador.php");
                } else if($id_tipo = 2) {
                    header("location: cliente.php");
                } else if($id_tipo = 3){
                    header("location: Cajero.php");
                } 
                

            } else {

                $errors = "La contrase&ntilde;a es incorrecta";
            }
        } else {

            $errors = 'El usuario no esta activo';
        }
    } else {

        $errors = "El nombre de usuario o correo electr&oacute;nico no existe";
    }
    return $errors;
}

function registraTercero($usuario, $no_cuenta, $permitido, $token)
{

    global $mysqli;

    $stmt = $mysqli->prepare("INSERT INTO terceros (id_usuario, no_cuenta, permitido, token) VALUES(?,?,?,?)");
    $stmt->bind_param('iiis', $usuario, $no_cuenta, $permitido, $token);

    if ($stmt->execute()) {
        return $mysqli->insert_id;
    } else {
        return 0;
    }
}


function validaIdTokenTercero($token)
{
    global $mysqli;


    $stmt = $mysqli->prepare("SELECT permitido FROM terceros WHERE token = ? LIMIT 1");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();
    $rows = $stmt->num_rows;

    if ($rows > 0) {
        $stmt->bind_result($activacion);
        $stmt->fetch();

        if ($activacion == 1) {
            $msg = "La cuenta ya se agrego anteriormente.";
        } else {
            if (activarTercero($token)) {
                $msg = 'Cuenta Agregada.';
            } else {
                $msg = 'Error al enlazar cuenta del Contacto';
            }
        }
    } else {
        $msg = 'No existe el registro para activar.';
    }
    return $msg;
}

function activarTercero($token)
{
    global $mysqli;

    $act = 1;
    $stmt = $mysqli->prepare("UPDATE terceros SET permitido = ? WHERE token = ?");
    $stmt->bind_param('is', $act, $token);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}


function registraCajero($usuario, $pass_hash, $nombre, $email, $activo, $token, $tipo_usuario, $telefono, $no_cuenta)
{

    global $mysqli;

    

    $stmt = $mysqli->prepare("INSERT INTO usuarios (usuario, password, nombre, correo, activacion, telefono, 
    no_cuenta, token, id_tipo) VALUES(?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param('ssssiiisi', $usuario, $pass_hash, $nombre, $email, $activo, $token, $tipo_usuario, $telefono, $no_cuenta);

    if ($stmt->execute()) {
        return $mysqli->insert_id;
    } else {
        return 0;
    }
}

function RealizaTrans($Descrip, $CuenOrig, $CuenDest, $Monto )
{
    global $mysqli;

    $stmt = $mysqli->prepare("INSERT INTO transacciones (descripcion, no_cuenta_origen, no_cuenta_destino, cantidad, Fecha_Transaccion) VALUES(?,?,?,?,NOW())");
    $stmt->bind_param('siid', $Descrip, $CuenOrig, $CuenDest, $Monto);

    if ($stmt->execute()) {
        return $mysqli->insert_id;
    } else {
        return 0;
    }
}

?>