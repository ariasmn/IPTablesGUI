<?php
    session_start();
	//quitamos las variables de sesión
	session_unset();
	header("Location: index.php");
	
?>