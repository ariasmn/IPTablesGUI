<?php
//botones radio provisionales para comprobar funcionamiento del comando exec para insertar las policys
//Hay que modificar el archivo sudoers, con el usuario daemon en este caso.
//para averiguar el usuario que usa xampp, primero hacemos el comando exec('whoami')
//Esto se hace para que no pida contraseña a la hora de ejecutar comandos de /sbin/iptables con sudo.
//es un problema de seguridad importante, por eso solo se le permite que use iptables sin sudo, y se usa un login antes de poder usar la app web-
require_once "conexiones.php";

if (isset ($_POST['ay'])){
		
	if(!empty($_POST['input'])) {
   				//mira si el checkbox está marcado o no
    			$nuevoEstado='ACCEPT';
				$tipo = 'INPUT';
			// ejecutamos las sentencias de pdo para cambiar el estado de la bbdd y recoger los nuevos valores
				$cambiar -> execute(array($nuevoEstado,$tipo));
				exec('sudo iptables -P INPUT ACCEPT');
				exec("sudo bash /var/www/iptablesgui/lib/iptables-save.sh");
				//hay que crear reglas al inicio de la primera vez que no se listeen para que permitan seguir usando la aplicacion aunque cierres el input
	}
		
			else{
    			$nuevoEstado='DROP';
				$tipo = 'INPUT';
				$cambiar -> execute(array($nuevoEstado,$tipo));
				exec("sudo iptables -P INPUT DROP");
				exec("sudo bash /var/www/iptablesgui/lib/iptables-save.sh");
			}
	}

if (isset ($_POST['ay'])){
		
	if(!empty($_POST['output'])) {
   				//mira si el checkbox está marcado o no
    			$nuevoEstado='ACCEPT';
				$tipo = 'OUTPUT';
			// ejecutamos las sentencias de pdo para cambiar el estado de la bbdd y recoger los nuevos valores
				$cambiar -> execute(array($nuevoEstado,$tipo));
				exec('sudo iptables -P OUTPUT ACCEPT');
				exec("sudo bash /var/www/iptablesgui/lib/iptables-save.sh");
				}
		
			else{
    			$nuevoEstado='DROP';
				$tipo = 'OUTPUT';
				$cambiar -> execute(array($nuevoEstado,$tipo));
				exec('sudo iptables -P OUTPUT DROP');
				exec("sudo bash /var/www/iptablesgui/lib/iptables-save.sh");
			}
	}

if (isset ($_POST['ay'])){
		
	if(!empty($_POST['forward'])) {
   				//mira si el checkbox está marcado o no
    			$nuevoEstado='ACCEPT';
				$tipo = 'FORWARD';
			// ejecutamos las sentencias de pdo para cambiar el estado de la bbdd y recoger los nuevos valores
				$cambiar -> execute(array($nuevoEstado,$tipo));
				exec('sudo iptables -P FORWARD ACCEPT');
				exec("sudo bash /var/www/iptablesgui/lib/iptables-save.sh");
				}
		
			else{
    			$nuevoEstado='DROP';
				$tipo = 'FORWARD';
				$cambiar -> execute(array($nuevoEstado,$tipo));
				exec ('sudo iptables -P FORWARD DROP');
				exec("sudo bash /var/www/iptablesgui/lib/iptables-save.sh");
			}
	}
if (isset ($_POST['statusSh'])){
if (!empty($_POST['fwstatus'])){
			$stmt = $db -> prepare("UPDATE fwstatus SET status='ON'");
			$stmt -> execute();
			exec('sudo iptables-restore /etc/iptables/rules.v4');
}else {
			$stmt = $db -> prepare("UPDATE fwstatus SET status='OFF'");
			$stmt -> execute();
			
			exec('sudo iptables -P INPUT ACCEPT');
			exec('sudo iptables -P OUTPUT ACCEPT');
			exec('sudo iptables -P FORWARD ACCEPT');
			exec('sudo iptables -F');
	
}}

//------------------------------------------------------------------------------------------------------------------------------------//
//si la variable INPUT del array es ACCEPT, crea el checkbox marcado, si es DROP, desmarcado.
	if (recogidaInput() == "ACCEPT") {
	$botonInput = '<input data-toggle="toggle" data-width="50" data-height="25" data-on="A" data-off="R" type="checkbox" name="input" onChange="this.form.submit()" checked>
	';
	} else {
		$botonInput =  '<input data-toggle="toggle" data-width="50" data-height="25" data-on="A" data-off="R" type="checkbox" name="input" onChange="this.form.submit()">
		';
		}
//lo mismo para la variable OUTPUT del array
	if (recogidaOutput() == "ACCEPT") {
	$botonOutput = '<input data-toggle="toggle" data-width="50" data-height="25" data-on="A" data-off="R" type="checkbox" name="output" onChange="this.form.submit()" checked>
	';
	} else {
		$botonOutput =  '<input data-toggle="toggle" data-width="50" data-height="25" data-on="A" data-off="R" type="checkbox" name="output" onChange="this.form.submit()">
		';
		}
//lo mismo para la variabe FORWARD

	if (recogidaForward() == "ACCEPT") {
	$botonForward = '<input data-toggle="toggle" data-width="50" data-height="25" data-on="A" data-off="R" type="checkbox" name="forward" onChange="this.form.submit()" checked>
	';
	} else {
		$botonForward =  '<input data-toggle="toggle" data-width="50" data-height="25" data-on="A" data-off="R" type="checkbox" name="forward" onChange="this.form.submit()">
		';
		}
		
	
/*
	Checkea el estado del firewall desde la base de datos y crea el boton
*/
if (recogidaFWStatus() == "ON"){
		$botonStatus = '<input type="checkbox" data-toggle="toggle" data-width="75" data-height="35" data-onstyle="success" data-offstyle="danger" name="fwstatus" id="siContainer" onChange="this.form.submit()" value="siContainer" checked>
	';
	}else {
		$botonStatus = '<input type="checkbox" data-toggle="toggle" data-width="75" data-height="35" data-onstyle="success" data-offstyle="danger" name="fwstatus" id="noContainer" onChange="this.form.submit()">
	';
		
	}


?>	
	<div>
		<h2>Estado del firewall y políticas</h2>
	<form method="post" action="" name="fwstatus">
		<?php 
		echo "<p>Estado del firewall   $botonStatus</p>";
		?>
		
	<input type="hidden" name="statusSh">
	</form>
	</div>
	<!-- Abrimos el div que almacenará todo lo que desaparecerá en caso de desactivar el firewall, se cierra en RULES.PHP -->
	
	<div name="containertodo" id="containertodo" style="display: block">
			
    <form method="post" action="" name="politicas">
		
<?php 
	
	echo "<p>INPUT   $botonInput</p>";
	echo "<p>OUTPUT   $botonOutput</p>";
	echo "<p>FORWARD   $botonForward</p>";
?>
<input type="hidden" name="ay">
</form>
