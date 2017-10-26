<?php

	//require_once 'register.php';
	require 'lib/password.php';
    require_once 'lib/conexiones.php';
	
	session_start();
	if(!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in'])){
    //Vemos si el usuario está logueado o no
    header('Location: login.php');
    exit;
}
	if ($_SESSION['user_token'] == "2"){
      		header("Location: index.php");
      	}
	
	
	//  ========== 
	//  = Funciones recogida IP y borrado usuarios = 
	//  ========== 
		$recogeIp ->execute();
		$adminIP = $recogeIp ->fetchColumn();
		
		
	if (isset($_POST['nuevaIP'])){
		
		$nuevaIP = recoge("nuevaIP");
		$cambiaIp->execute(array($nuevaIP));
		
			exec("sudo iptables -D INPUT -s $adminIP -p tcp --dport 80 -j ACCEPT");
			exec("sudo iptables -D INPUT -s $adminIP -p tcp --sport 80 -j ACCEPT");
			exec("sudo iptables -D OUTPUT -d $adminIP -p tcp --dport 80 -j ACCEPT");
			exec("sudo iptables -D OUTPUT -d $adminIP -p tcp --sport 80 -j ACCEPT");			
			
			exec("sudo iptables -A INPUT -s $nuevaIP -p tcp --dport 80 -j ACCEPT");
			exec("sudo iptables -A INPUT -s $nuevaIP -p tcp --sport 80 -j ACCEPT");
			exec("sudo iptables -A OUTPUT -d $nuevaIP -p tcp --dport 80 -j ACCEPT");
			exec("sudo iptables -A OUTPUT -d $nuevaIP -p tcp --sport 80 -j ACCEPT");
			exec("sudo bash /opt/lampp/htdocs/iptables/lib/iptables-save.sh");
		$recogeIp->execute();
		$adminIP = $recogeIp ->fetchColumn();
		
		
	}
	
	if (isset($_POST['numeroUser'])){
		
		$arrayUsuarios = array();
		$recogerUsuarios -> execute();
		foreach ($recogerUsuarios as $row) {
 	
		$arrayUsuarios[] = $row;
 			}
		$usernameDel = $arrayUsuarios[$_POST['numeroUser']]['username'];
		$borrarUsuarios -> execute(array($usernameDel));
	}
	//  ==============================================
	//  = Creación de usuarios con password hasheada = 
	//  ==============================================
		
		if(isset($_POST['register'])){
    
    		//Recoge el valor de los campos del formulario
    			$username = !empty($_POST['username']) ? trim($_POST['username']) : null;
   				$pass = !empty($_POST['password']) ? trim($_POST['password']) : null;
				if (isset($_POST['token'])){
					$token = "1";
				}else {
					$token = "2";
				}
    
    		//Sentencia para ver si el usuario ya existe
    		    $sql = "SELECT COUNT(username) AS num FROM users WHERE username = :username";
    			$stmt = $db->prepare($sql);
    
    		//Asignamos el valor del campo del form a la consulta
    			$stmt->bindValue(':username', $username);
    			$stmt->execute();
    
    		//Recogemos el valor
    			$row = $stmt->fetch(PDO::FETCH_ASSOC);
    			
			//Tratamiento del 'error'
    			if($row['num'] > 0){
       				echo '<script language="javascript">';
					echo 'alert("El usuario introducido ya existe")';
					echo '</script>';
    			}else {
    				//Hashea la contraseña para no almacenarla en texto plano
    				$passwordHash = password_hash($pass, PASSWORD_BCRYPT, array("cost" => 12));
					//Insertamos el nuevo usuario
   					$stmt = $db->prepare("INSERT INTO users VALUES (NULL, ?, ?, ?)");
					$data = array($username, $passwordHash, $token);
					$stmt->execute($data);
				}
}
		

?>
<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/controlpanel.css">
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
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
               <li><a href='controlpanel.php'>Panel de control</a></li>
				<li><a href='logout.php'>Logout</a></li>
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
				<?php } ?>
			<h2>IP de administrador</h2>
				<p>Aquí puede ver y cambiar la IP que estará exenta de los cambios en políticas</p>
				<form method="post" action="" id="ipadmin">
				<b>IP actual: <?php echo $adminIP; ?></b> <input type="text" title = "La IP debe tener un formato válido" pattern="(((^|\.)((25[0-5])|(2[0-4]\d)|(1\d\d)|([1-9]?\d))){4}$)|(((^|\.)((25[0-5])|(2[0-4]\d)|(1\d\d)|([1-9]?\d))){4}/(?:\d|[12]\d|3[01])$)" placeholder="IP Administrador" name="nuevaIP">	
				<button class="btn btn-primary btn-sm" type="submit">Guardar cambios</button>
				</form>
			<h2>Usuarios de la app</h2>
			<p>Aquí se muestran los usuarios registrados en el programa. Tambien puede crear y eliminar usuarios</p>
				<form action="" method="post">
						<table border="1" class="table table-bordered table-striped">
		<thead>
				<th>Nombre</th>
				<th>Admin</th>
				<th>Borrar</th>
		</thead>
		<tbody>
		<?php
			$contadorUser = 0;
			$recogerUsuarios -> execute();
			foreach ($recogerUsuarios as $row) {
		?>
			<tr>
				<td><?php echo $row['username']; ?></td>
				<td>
					<?php  if ($row['token'] == "1"){
								echo "Si";
								}
							else{
							echo "No";
					} ?>
				</td>
				<td>
					<?php if ($row['username'] == $_SESSION['username']){
						
					}else {?>
								
						<button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" name="numeroUser" type="submit" value="<?php echo $contadorUser; ?>"><span class="glyphicon glyphicon-trash"></span></button></td>
						
					<?php }
						echo "</tr>";
			$contadorUser++;
			}
			?>
		</tbody>
	</table>
	</form>
		<button class="btn btn-primary btn-sm" id="myBtn">Registrar</button>

<!-- div del modal -->
<div id="myModal" class="modal">

  <!-- div del contenido del modal -->
  <div class="modal-content">
  	<!-- &times en html significa "x", ponemos eso para crear un span con una "x", en caso de que la pulsemos, se cierra, la función está en reglas.js -->
    <span class="close">&times;</span>
	
<h3>Nuevo usuario</h3>
<hr>
<form action="controlpanel.php" method="post">

            <p><input type="text" id="username" name="username" placeholder="Nombre de usuario"></p>
            <p><input type="password" id="password" name="password" placeholder="Contraseña"></p>
            <p><input type="checkbox" id="token" name="token"> Permisos de administrador</p>
            <p>
            <input type="submit" name="register" class="btn btn-primary btn-sm" value="Registrar">
			</p>
</form>

</div>
  </div>
  </div>
  </div>
  <footer class="footer">
      <div class="container">
        <p class="text-muted">2017 - Ismael Arias.</p>
      </div>
    </footer>
  <script src="js/reglas.js"></script>
  <script src="js/jquery-3.2.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	
		</body>
</html>