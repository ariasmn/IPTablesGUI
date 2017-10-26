<?php

//login.php

/**
 * Start the session.
 */
session_start();

/**
 * Include ircmaxell's password_compat library.
 */
require 'lib/password.php';

/**
 * Include our MySQL connection.
 */
require 'lib/conexiones.php';


//Si se submitea el form del login
if(isset($_POST['login'])){
    
    //Recoge el usuario y la contraseña
    $username = !empty($_POST['username']) ? trim($_POST['username']) : null;
    $passwordAttempt = !empty($_POST['password']) ? trim($_POST['password']) : null;
    
    //Recoge la información del usuario introducido en el form de la base de datos
    $sql = "SELECT id, username, pass, token FROM users WHERE username = :username";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':username', $username);
    $stmt->execute();
    
    //Guarda la información del usuario 
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    //Si esta variable es falsa, quiere decir que el usuario no existe
    if($user === false){
 
 					echo '<script language="javascript">';
					echo 'alert("Usuario y/o contraseña incorrectas")';
					echo '</script>';
 
    		} else{
        
        //Compara las contraseñas, para ello, usa la función de la librería para deshashear la pass de la bbdd
        $validPassword = password_verify($passwordAttempt, $user['pass']);
        
        //Si coincide, se hace login
        if($validPassword){
            
            //Se asignan variable de sesión
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['logged_in'] = time();
            $_SESSION['user_token'] = $user['token'];
			$_SESSION['username'] = $user['username'];
            
            if($username == "admin" && $validPassword	== "admin"){
			
				$_SESSION['warning'] = "";
			header('Location: index.php');

            }else {
            
            //Se redirige a la página protegida
            header('Location: index.php');
            exit;
			}
        } else{
             echo '<script language="javascript">';
			 echo 'alert("Usuario y/o contraseña incorrectas")';
			 echo '</script>';
        }
    }
    
}
 
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/login.css">
        <title>Login</title>
    </head>
    <body>
    	<div class="container">
        <form class="form-signin" action="login.php" method="post">
        	<h2 class="form-signin-heading text-center">IPTablesGUI</h2>
            <label for="username" class="sr-only">Nombre de usuario</label>
            <input type="text" id="username" class="form-control" name="username"><br>
            <label for="password" class="sr-only" >Contraseña</label>
            <input type="password" id="password" class="form-control" name="password"><br>
            <input type="submit" class="btn btn-lg btn-primary btn-block" name="login" value="Login">
        </form>
        
       </div>
    </body>
</html>