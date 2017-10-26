<?php

	//conexiones
    try {
      $db = new PDO("sqlite:db/iptables.db");  //conectar con MySQL y SELECCIONAR LA BBDD
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {  // Si hubieran errores de conexión, se captura un objeto de tipo PDOException
        print "<p>Error: No se pudo conectar con la BBDD $dbname.</p>\n";
        print "<p>Error: " . $e->getMessage() . "</p>\n";  // mensaje de excepción
        exit();
      }
////////////////////////////////
//////////   REGLAS   //////////
////////////////////////////////

////////función para recoger los datos del formulario//////

	
	function recoge($var)
{
    $tmp = (isset($_POST[$var]))
        ? trim(htmlspecialchars($_POST[$var], ENT_QUOTES, "UTF-8"))
        : "";
    return $tmp;
}


//////////insertar en la base de datos las reglas///////////
	
		$insertar = $db ->prepare("INSERT INTO rules(type, source_ip, destination_ip, protocol, portType, portValue, J) VALUES (?, ?, ?, ?, ?, ?, ?)");

//////////recoger las reglas de la base de datos////////////
	
		$recogerReglas = $db -> prepare("SELECT *,rowid FROM rules");
		
//////////Borrar la regla seleccionada de la base de datos/////////

		$borrar = $db -> prepare ("DELETE FROM rules WHERE type=? AND source_ip=? AND destination_ip=? AND protocol=? AND portType=? AND portValue=? AND J=? AND rowid=?");
		
//  ===========================================
//  = Funciones para comprobar los casos dados = 
//  ============================================	

function añadirRegla(){

global $type;
global $source_ip;
global $destination_ip;
global $protocol;
global $portType;
global $portValue;
global $J;

switch ($type) {
	
	case 'INPUT':
		
		if ($source_ip == "" && $portType == ""){
		
			exec("sudo iptables -A $type -p $protocol -j $J");
			
			}
	
		else if ($portType == ""){
		
			exec("sudo iptables -A $type -s $source_ip -p $protocol  -j $J");
			}
	
		else if($source_ip == ""){
			
			if ($portType == "origen"){
				
				exec("sudo iptables -A $type -p $protocol --sport $portValue -j $J");
				
			}else {
				
				exec("sudo iptables -A $type -p $protocol --dport $portValue -j $J");
				}
			}
	
		else {
			
			if ($portType == "origen"){
				
				exec("sudo iptables -A $type -s $source_ip -p $protocol --sport $portValue -j $J");
				
			}else {
				
				exec("sudo iptables -A $type -s $source_ip -p $protocol --dport $portValue -j $J");
				
			}
			}
		
	break;
	
	case 'OUTPUT':
		
		
		if ($destination_ip == "" && $portType == ""){
		
			exec("sudo iptables -A $type -p $protocol -j $J");
			
			}
	
		else if ($portType == ""){
		
			exec("sudo iptables -A $type -d $destination_ip -p $protocol -j $J");
			}
	
		else if($destination_ip == ""){
			
			if ($portType == "origen"){
				
				exec("sudo iptables -A $type -p $protocol --sport $portValue -j $J");
				
			}else {
				
				exec("sudo iptables -A $type -p $protocol --dport $portValue -j $J");
				}
			}
	
		else {
			
			if ($portType == "origen"){
				
				exec("sudo iptables -A $type -d $destination_ip -p $protocol --sport $portValue -j $J");
				
			}else {
				
				exec("sudo iptables -A $type -d $destination_ip -p $protocol --dport $portValue -j $J");
				
			}
			}
	
		
	break;
	
	case 'FORWARD':
	
		if ($destination_ip == "" && $source_ip == ""){
		
			if ($portType == "") {
		
				exec("sudo iptables -A $type -p $protocol -j $J");
			
			}else if ($portType == "origen") {
				
				exec("sudo iptables -A $type -p $protocol --sport $portValue -j $J");
				
			}else {
					
				exec("sudo iptables -A $type -p $protocol --dport $portValue -j $J");
			}
			
			}
			
		else if ($destination_ip == ""){
			
			if ($portType == ""){
				exec("sudo iptables -A $type -s $source_ip -p $protocol -j $J");
			}else if($portType == "origen") {
				exec("sudo iptables -A $type -s $source_ip -p $protocol --sport $portValue -j $J");
			}else {
				exec("sudo iptables -A $type -s $source_ip -p $protocol --dport $portValue -j $J");
			}
			
		}
		
		else if ($source_ip == ""){
			
			if ($portType == ""){
				exec("sudo iptables -A $type -d $destination_ip -p $protocol -j $J");
			}else if($portType == "origen") {
				exec("sudo iptables -A $type -d $destination_ip -p $protocol --sport $portValue $J");
			}else {
				exec("sudo iptables -A $type -d $destination_ip -p $protocol --dport $portValue $J");
			}
		}else {
				
			if ($portType == ""){
				exec("sudo iptables -A $type -s $source_ip -d $destination_ip -p $protocol -j $J");
			}else if($portType == "origen") {
				exec("sudo iptables -A $type -s $source_ip -d $destination_ip -p $protocol --sport $portValue -j $J");
			}else {
				exec("sudo iptables -A $type -s $source_ip -d $destination_ip -p $protocol --dport $portValue -j $J");
			}	
			
		}
	
		
	
	break; 
}
exec("sudo bash /var/www/iptablesgui/lib/iptables-save.sh");
}	


function borrarRegla(){
	
global $typeDel;
global $source_ipDel;
global $destination_ipDel;
global $protocolDel;
global $portTypeDel;
global $portValueDel;
global $JDel;
	
	

switch ($typeDel) {
	
	
	case 'INPUT':
		
		if ($source_ipDel == "" && $portTypeDel == ""){
		
			exec("sudo iptables -D $typeDel -p $protocolDel -j $JDel");
			
			}
	
		else if ($portTypeDel == ""){
		
			exec("sudo iptables -D $typeDel -s $source_ipDel -p $protocolDel -j $JDel");
			}
	
		else if($source_ipDel == ""){
			
			if ($portTypeDel == "origen"){
				
				exec("sudo iptables -D $typeDel -p $protocolDel --sport $portValueDel -j $JDel");
				
			}else {
				
				exec("sudo iptables -D $typeDel -p $protocolDel --dport $portValueDel -j $JDel");
				}
			}
	
		else {
			
			if ($portTypeDel == "origen"){
				
				exec("sudo iptables -D $typeDel -s $source_ipDel -p $protocolDel --sport $portValueDel -j $JDel");
				
			}else {
				
				exec("sudo iptables -D $typeDel -s $source_ipDel -p $protocolDel --dport $portValueDel -j $JDel");
				
			}
			}
		
	break;
	
	case 'OUTPUT':
		
		
		if ($destination_ipDel == "" && $portTypeDel == ""){
		
			exec("sudo iptables -D $typeDel -p $protocolDel -j $JDel");
			
			}
	
		else if ($portTypeDel == ""){
		
		
			exec("sudo iptables -D $typeDel -d $destination_ipDel -p $protocolDel -j $JDel");
			}
	
		else if($destination_ipDel == ""){
				
			
			if ($portTypeDel == "origen"){
				
				exec("sudo iptables -D $typeDel -p $protocolDel --sport $portValueDel -j $JDel");
				
			}else {
				
				exec("sudo iptables -D $typeDel -p $protocolDel --dport $portValueDel -j $JDel");
				}
			}
	
		else {
					
			
			if ($portTypeDel == "origen"){
				
				exec("sudo iptables -D $typeDel -d $destination_ipDel -p $protocolDel --sport $portValueDel -j $JDel");
				
			}else {
						
				exec("sudo iptables -D $typeDel -d $destination_ipDel -p $protocolDel --dport $portValueDel -j $JDel");
				
			}
			}
	
		
	break;
	
		case 'FORWARD':
	
		if ($destination_ipDel == "" && $source_ipDel == ""){
		
			if ($portTypeDel == "") {
		
				exec("sudo iptables -D $typeDel -p $protocolDel -j $JDel");
			
			}else if ($portTypeDel == "origen") {
				
				exec("sudo iptables -D $typeDel -p $protocolDel --sport $portValueDel -j $JDel");
				
			}else {
					
				exec("sudo iptables -D $typeDel -p $protocolDel --dport $portValueDel -j $JDel");
			}
			
			}
			
		else if ($destination_ipDel == ""){
			
			if ($portTypeDel == ""){
				exec("sudo iptables -D $typeDel -s $source_ipDel -p $protocolDel -j $JDel");
			}else if($portTypeDel = "origen") {
				exec("sudo iptables -D $typeDel -s $source_ipDel -p $protocolDel --sport $portValueDel -j $JDel");
			}else {
				exec("sudo iptables -D $typeDel -s $source_ipDel -p $protocolDel --dport $portValueDel -j $JDel");
			}
			
		}
		
		else if ($source_ipDel == ""){
			
			if ($portTypeDel == ""){
				exec("sudo iptables -D $typeDel -d $destination_ipDel -p $protocolDel -j $JDel");
			}else if($portTypeDel = "origen") {
				exec("sudo iptables -D $typeDel -d $destination_ipDel -p $protocolDel --sport $portValueDel -j $JDel");
			}else {
				exec("sudo iptables -D $typeDel -d $destination_ipDel -p $protocolDel --dport $portValueDel -j $JDel");
			}
		}
		else {
				
			if ($portTypeDel == ""){
				exec("sudo iptables -D $typeDel -s $source_ipDel -d $destination_ipDel -p $protocolDel -j $JDel");
			}else if($portTypeDel == "origen") {
				exec("sudo iptables -D $typeDel -s $source_ipDel -d $destination_ipDel -p $protocolDel --sport $portValueDel -j $JDel");
			}else {
				exec("sudo iptables -D $typeDel -s $source_ipDel -d $destination_ipDel -p $protocolDel --dport $portValueDel -j $JDel");
			}
		}
		
	
	break; 
	}
	exec("sudo bash /var/www/iptablesgui/lib/iptables-save.sh");
}


function ambosLados(){
	
global $type;
global $source_ip;
global $destination_ip;
global $protocol;
global $portType;
global $portValue;
global $J;
global $db;

$insertarLado = $db ->prepare("INSERT INTO rules(type, source_ip, destination_ip, protocol, portType, portValue, J) VALUES (?, ?, ?, ?, ?, ?, ?)");

switch ($type) {
	
	case 'INPUT':
		
		if ($source_ip == "" && $portType == ""){
				
			
			exec("sudo iptables -A $type -p $protocol -j $J");
			exec("sudo iptables -A OUTPUT -p $protocol -j $J");
			$insertarLado -> execute(array("OUTPUT","","",$protocol,$portType,$portValue,$J));
			}
	
		else if ($portType == ""){
		
			exec("sudo iptables -A $type -s $source_ip -p $protocol  -j $J");
			exec("sudo iptables -A OUTPUT -d $source_ip -p $protocol -j $J");
			$insertarLado -> execute(array("OUTPUT","",$source_ip,$protocol,$portType,$portValue,$J));
			}
	
		else if($source_ip == ""){
			
			if ($portType == "origen"){
				
				exec("sudo iptables -A $type -p $protocol --sport $portValue -j $J");
				exec("sudo iptables -A OUTPUT -p $protocol --dport $portValue -j $J");
				$insertarLado -> execute(array("OUTPUT","","",$protocol,"destino",$portValue,$J));
			}else {
				
				exec("sudo iptables -A $type -p $protocol --dport $portValue -j $J");
				exec("sudo iptables -A OUTPUT -p $protocol --sport $portValue -j $J");
				$insertarLado -> execute(array("OUTPUT","","",$protocol,"origen",$portValue,$J));
				}
			}
	
		else {
			
			if ($portType == "origen"){
				
				exec("sudo iptables -A $type -s $source_ip -p $protocol --sport $portValue -j $J");
				exec("sudo iptables -A OUTPUT -d $source_ip -p $protocol --dport $portValue -j $J");
				$insertarLado -> execute(array("OUTPUT","",$source_ip,$protocol,"destino",$portValue,$J));
				
			}else {
				
				exec("sudo iptables -A $type -s $source_ip -p $protocol --dport $portValue -j $J");
				exec("sudo iptables -A OUTPUT -d $source_ip -p $protocol --sport $portValue -j $J");
				$insertarLado -> execute(array("OUTPUT","",$source_ip,$protocol,"origen",$portValue,$J));
			}
			}
		
	break;
	
	case 'OUTPUT':
		
		
		if ($destination_ip == "" && $portType == ""){
		
			exec("sudo iptables -A $type -p $protocol -j $J");
			exec("sudo iptables -A INPUT -p $protocol -j $J");
			$insertarLado -> execute(array("INPUT",$source_ip,$destination_ip,$protocol,$portType,$portValue,$J));
			}
	
		else if ($portType == ""){
		
			exec("sudo iptables -A $type -d $destination_ip -p $protocol -j $J");
			exec("sudo iptables -A INPUT -s $destination_ip -p $protocol -j $J");
			$insertarLado -> execute(array("INPUT",$destination_ip,"",$protocol,$portType,$portValue,$J));
			}
	
		else if($destination_ip == ""){
			
			if ($portType == "origen"){
				
				exec("sudo iptables -A $type -p $protocol --sport $portValue -j $J");
				exec("sudo iptables -A INPUT -p $protocol --dport $portValue -j $J");
				$insertarLado -> execute(array("INPUT",$source_ip,$destination_ip,$protocol,$portType,$portValue,$J));
				
			}else {
				
				exec("sudo iptables -A $type -p $protocol --dport $portValue -j $J");
				exec("sudo iptables -A INPUT -p $protocol --sport $portValue -j $J");
				$insertarLado -> execute(array("INPUT","","",$protocol,"origen",$portValue,$J));
				}
			}
	
		else {
			
			if ($portType == "origen"){
				
				exec("sudo iptables -A $type -d $destination_ip -p $protocol --sport $portValue -j $J");
				exec("sudo iptables -A INPUT -s $destination_ip -p $protocol --dport $portValue -j $J");
				$insertarLado -> execute(array("INPUT",$destination_ip,"",$protocol,"destino",$portValue,$J));
				
			}else {
				
				exec("sudo iptables -A $type -d $destination_ip -p $protocol --dport $portValue -j $J");
				exec("sudo iptables -A INPUT -s $destination_ip -p $protocol --sport $portValue -j $J");
				$insertarLado -> execute(array("INPUT",$destination_ip,"",$protocol,"origen",$portValue,$J));
				
			}
			}
	
		
	break;
	
	case 'FORWARD':
	
		if ($destination_ip == "" && $source_ip == ""){
		
			if ($portType == "") {
		
				exec("sudo iptables -A $type -p $protocol -j $J");
			
			}else if ($portType == "origen") {
				
				exec("sudo iptables -A $type -p $protocol --sport $portValue -j $J");
				exec("sudo iptables -A $type -p $protocol --dport $portValue -j $J");
				$insertarLado -> execute(array($type,$source_ip,$destination_ip,$protocol,"destino",$portValue,$J));
				
			}else {
					
				exec("sudo iptables -A $type -p $protocol --dport $portValue -j $J");
				exec("sudo iptables -A $type -p $protocol --sport $portValue -j $J");
				$insertarLado -> execute(array($type,$source_ip,$destination_ip,$protocol,"origen",$portValue,$J));
			}
			
			}
			
		else if ($destination_ip == ""){
			
			if ($portType == ""){
				exec("sudo iptables -A $type -s $source_ip -p $protocol -j $J");
				exec("sudo iptables -A $type -d $source_ip $protocol -j $J");
				$insertarLado -> execute(array($type,"",$source_ip,$protocol,$portType,$portValue,$J));
			}else if($portType == "origen") {
				exec("sudo iptables -A $type -s $source_ip -p $protocol --sport $portValue -j $J");
				exec("sudo iptables -A $type -d $source_ip -p $protocol --dport $portValue -j $J");
				$insertarLado -> execute(array($type,"",$source_ip,$protocol,"destino",$portValue,$J));
			}else {
				exec("sudo iptables -A $type -s $source_ip -p $protocol --dport $portValue -j $J");
				exec("sudo iptables -A $type -d $source_ip -p $protocol --sport $portValue -j $J");
				$insertarLado -> execute(array($type,"",$source_ip,$protocol,"origen",$portValue,$J));
			}
			
		}
		
		else if ($source_ip == ""){
			
			if ($portType == ""){
				exec("sudo iptables -A $type -d $destination_ip -p $protocol -j $J");
				exec("sudo iptables -A $type -s $destination_ip -p $protocol -j $J");
				$insertarLado -> execute(array($type,$destination_ip,"",$protocol,$portType,$portValue,$J));
			}else if($portType == "origen") {
				exec("sudo iptables -A $type -d $destination_ip -p $protocol --sport $portValue $J");
				exec("sudo iptables -A $type -s $destination_ip -p $protocol --dport $portValue $J");
				$insertarLado -> execute(array($type,$destination_ip,"",$protocol,"destino",$portValue,$J));
			}else {
				exec("sudo iptables -A $type -d $destination_ip -p $protocol --dport $portValue $J");
				exec("sudo iptables -A $type -s $destination_ip -p $protocol --sport $portValue $J");
				$insertarLado -> execute(array($type,$destination_ip,"",$protocol,"origen",$portValue,$J));
			}
		}else {
			
			if ($portType == ""){
				exec("sudo iptables -A $type -s $source_ip -d $destination_ip -p $protocol -j $J");
				exec("sudo iptables -A $type -s $destination_ip -d $source_ip -p $protocol -j $J");
				$insertarLado -> execute(array($type,$destination_ip,$source_ip,$protocol,$portType,$portValue,$J));
			}else if($portType == "origen") {
				exec("sudo iptables -A $type -s $source_ip -d $destination_ip -p $protocol --sport $portValue -j $J");
				exec("sudo iptables -A $type -s $destination_ip -d $source_ip -p $protocol --dport $portValue -j $J");
				$insertarLado -> execute(array($type,$destination_ip,$source_ip,$protocol,"destino",$portValue,$J));
			}else {
				exec("sudo iptables -A $type -s $source_ip -d $destination_ip -p $protocol --dport $portValue -j $J");
				exec("sudo iptables -A $type -s $destination_ip -d $source_ip -p $protocol --sport $portValue -j $J");
				$insertarLado -> execute(array($type,$destination_ip,$source_ip,$protocol,"origen",$portValue,$J));
			}	
		}
	
		
	
	break; 
}
exec("sudo bash /var/www/iptablesgui/lib/iptables-save.sh");
}


///////////////////////////////////////////////////////////////////		
		
///////////////////////////////////////
/////////    POLITICAS    /////////////
///////////////////////////////////////

		
	//sentencia para cambiar el estado de las politicas tambien en la base de datos
		$cambiar = $db ->prepare("UPDATE policy SET status=? WHERE type=?");
	//teniendola asi solo deberemos ejecutarla pasando los valores que queramos, haciendo un código eficiente.
	
	
	//sentencia preparada para recoger el estado de las politicas
		function recogidaInput() {
		global $db;
		$recoger = $db->prepare("SELECT * FROM policy");
		$recoger->execute();
		$estado = $recoger ->FetchAll();
		
		//guarda estados primarios
		$input = $estado[0]['status'];
		return $input;
		}
		//tenemops que usar 3 funciones distintas, porque si usamos el array, no funciona bien al tener que parsearlo en otra variable
		function recogidaOutput() {
		global $db;
		$recoger = $db->prepare("SELECT * FROM policy");
		$recoger->execute();
		$estado = $recoger ->FetchAll();
		
		//guarda estados primarios
		$output = $estado[1]['status'];
		return $output;
		}
		
		function recogidaForward() {
		global $db;
		$recoger = $db->prepare("SELECT * FROM policy");
		$recoger->execute();
		$estado = $recoger ->FetchAll();
		
		//guarda estados primarios
		$forward = $estado[2]['status'];
		return $forward;
		}
		
		function recogidaFWStatus(){
		global $db;
		$recoger = $db->prepare("SELECT * FROM fwstatus");
		$recoger->execute();
		$estado = $recoger -> fetchColumn();
		return $estado;
		}
	
//////////////////////////////////////////////
/////////    PANEL DE CONTROL    /////////////
//////////////////////////////////////////////
	
	
	//sentencias para la IP de administrador
	
		$recogeIp = $db ->prepare("SELECT *  from adminIP");

		$cambiaIp = $db ->prepare("UPDATE adminIP set ipAdmin = ?");
		
	
	//sentencias para los usuarios
		
		$recogerUsuarios = $db -> prepare("SELECT * FROM users");

		$borrarUsuarios = $db -> prepare ("DELETE FROM users WHERE username=?");
	
?>
