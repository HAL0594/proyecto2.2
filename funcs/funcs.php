<?php




function isNULL($nombre, $user, $pass, $pass_con, $email){
        if(strlen(trim($nombre)) < 1 
        || strlen(trim($user)) < 1 
        || strlen(trim($pass)) < 1 
        || strlen(trim($pass_con)) < 1 
        || strlen(trim($email)) < 1)
        {
            return true;
        } else{
            return false;
        }
    }

    function validaPassword($var1, $var2)
	{
		if (strcmp($var1, $var2) !== 0){
			return false;
			} else {
			return true;
		}
	}

function isEmail($email){
    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
        return true;
    }else {
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

    if ($num > 0){
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

    if ($num > 0){
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

function resultBlock($errors){
    if(count($errors) > 0)
    {
        echo "<div id='error' class='alert alert-danger' role='alert'>
        <a href='#' onclick=\"showHide('error');\">[X]</a>
        <ul>";
        foreach($errors as $error)
        {
            echo "<li>".$error."</li>";
        }
        echo "</ul>";
        echo "</div>";
    }
}

function registraUsuario($usuario,$pass_hash,$nombre,$email,$activo,$token,$tipo_usuario,$telefono,$no_cuenta){
		
    global $mysqli;
    
    $stmt = $mysqli->prepare("INSERT INTO usuarios (usuario, password, nombre, correo, activacion, token, id_tipo, telefono, no_cuenta) VALUES(?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param('ssssisiii',$usuario,$pass_hash,$nombre,$email,$activo,$token,$tipo_usuario,$telefono,$no_cuenta);

    if ($stmt->execute()){
        return $mysqli->insert_id;
        } else {
        return 0;	
    }		
}

function enviarEmail($email, $nombre, $asunto, $cuerpo){
		
    require_once 'PHPMailer/PHPMailerAutoload.php';
    
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls'; //tipo de seguridad
    $mail->Host = 'smtp.gmail.com'; //dominio
    $mail->Port = 587; //puerto
    
    $mail->Username = 'mibanca0594@gmail.com'; //Correo de donde enviaremos los correos
    $mail->Password = 'password123()'; // Password de la cuenta de envío
    
    $mail->setFrom('mibanca0594@gmail.com', 'Emisor');
    $mail->addAddress($email, $nombre);
    
    $mail->Subject = $asunto;
    $mail->Body    = $cuerpo;
    $mail->IsHTML(true);
    
    if($mail->send())
    return true;
    else
    return false;
}


function validaIdToken($id, $token){
    global $mysqli;
   
    
    $stmt = $mysqli->prepare("SELECT activacion FROM usuarios WHERE id_usuario = ? AND token = ? LIMIT 1");
    $stmt->bind_param("is", $id, $token);
    $stmt->execute();
    $stmt->store_result();
    $rows = $stmt->num_rows;
    
    if($rows > 0) {
        $stmt->bind_result($activacion);
        $stmt->fetch();
        
        if($activacion == 1){
            $msg = "La cuenta ya se activo anteriormente.";
            } else {
            if(activarUsuario($id)){
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

function isNullLogin($usuario, $password){
    if(strlen(trim($usuario)) < 1 || strlen(trim($password)) < 1)
    {
        return true;
    }
    else
    {
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
       
       if ($activacion == 1)
       {
           return true;
       }
       else
       {
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

   function login( $usuario, $password)
   {
       global $mysqli;
       
       $stmt = $mysqli->prepare("SELECT id_usuario, id_tipo, password FROM usuarios WHERE usuario = ? || correo = ? LIMIT 1");
       $stmt->bind_param("ss", $usuario, $usuario);
       $stmt->execute();
       $stmt->store_result();
       $rows = $stmt->num_rows;
       
       if($rows > 0) {
           
           if(isActivo($usuario)){
               
               $stmt->bind_result($id, $id_tipo, $passwd);
               $stmt->fetch();
               
               $validaPassw = password_verify($password, $passwd);
               
               if($validaPassw){
                   
                   lastSession($id);
                   $_SESSION['id_usuario'] = $id;
                   $_SESSION['tipo_usuario'] = $id_tipo;
                   
                   header("location: cliente.php");
 
                   } else 
                   {
                   
                   $errors = "La contrase&ntilde;a es incorrecta";
                   }
                  } else 
                  {
                 
               $errors = 'El usuario no esta activo';
           }
           } else {
               
           $errors = "El nombre de usuario o correo electr&oacute;nico no existe";
       }
       return $errors;
   }

    ?>