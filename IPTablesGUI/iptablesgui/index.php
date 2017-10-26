<?php

/**
 * Start the session.
 */
session_start();


/**
 * Check if the user is logged in.
 */
if(!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in'])){
    //User not logged in. Redirect them back to the login.php page.
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="css/index.css">
		<link rel="stylesheet" type="text/css" href="css/bootstrap2-toggle.min.css">
	</head>
	<body>
		<nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">IPTablesGUI</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['username']; ?> <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <?php //se mostará o no el acceso al panel de control dependiendo del token
                
                if ($_SESSION['user_token'] == "2"){
                echo "<li><a href='logout.php'>Logout</a></li>";
				}else {
                echo "<li><a href='controlpanel.php'>Panel de control</a></li>";
				echo "<li><a href='logout.php'>Logout</a></li>";
				} ?>
              </ul>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
        <div class="container">
      <div class="mt-3">
		<?php
		if (isset($_SESSION['warning'])){
			?>
			<div class="alert alert-danger" role="alert">
  					<h4 class="alert-heading">Atención</h4>
  					<p>Parece que tus credenciales de acceso son las proporcionadas por defecto</p>
  					<p class="mb-0">Por seguridad, se recomienda crear una nueva cuenta con permisos de administrador y borrar la cuenta de "admin"</p>
				</div>
		<?php }
		
				if ($_SESSION['user_token'] == "2"){
	
					require 'lib/index_disabled.php';

				}else {
					
					require 'lib/policy.php';
					require 'lib/rules.php';
				}
		 ?>
		 </div>
		</div>
		 <footer class="footer">
      <div class="container">
        <p class="text-muted">2017 - Ismael Arias.</p>
      </div>
    </footer>
		 <script src="js/jquery-3.2.1.min.js"></script>
		 <script src="js/bootstrap.min.js"></script>
		 <script src="js/bootstrap2-toggle.min.js"></script>
		   <script src="js/reglas.js"></script>
		   <script src="js/policy.js"></script>
	</body>
</html>

