<?php 

require_once "conexiones.php";

$type = recoge("tipo");
$source_ip = recoge("ipOrigen");
$destination_ip = recoge("ipDestino");
$protocol = recoge("protocolo");
$portType = recoge("tipoPuerto");
$portValue = recoge("puertoValor");
$J = recoge("permiso");
/*
	Añadir reglas una vez las submiteas en el form
*/


if(isset($_POST['tipo'])){
	
	if(isset($_POST['ambosLados'])){
		
		$insertar ->execute(array($type,$source_ip,$destination_ip,$protocol,$portType,$portValue,$J));
		ambosLados();
		
	}else {
		
		$insertar ->execute(array($type,$source_ip,$destination_ip,$protocol,$portType,$portValue,$J));
		añadirRegla();
	}
	
/*
*/	
}



/*
	Borrar reglas tanto de iptables como de la bbdd una vez pulsas el boton delete
*/

if (isset($_POST['numeroRegla'])){
				
$arrayReglas = array();
$recogerReglas -> execute();
foreach ($recogerReglas as $row) {
 	
	$arrayReglas[] = $row;
 }
	
	$typeDel = $arrayReglas[$_POST['numeroRegla']]['type'];
	$source_ipDel = $arrayReglas[$_POST['numeroRegla']]['source_ip']; 
	$destination_ipDel = $arrayReglas[$_POST['numeroRegla']]['destination_ip'];
	$protocolDel = $arrayReglas[$_POST['numeroRegla']]['protocol'];
	$portTypeDel = $arrayReglas[$_POST['numeroRegla']]['portType'];
	$portValueDel = $arrayReglas[$_POST['numeroRegla']]['portValue'];
	$JDel = $arrayReglas[$_POST['numeroRegla']]['J'];
	$numeroRow = $arrayReglas[$_POST['numeroRegla']]['rowid'];

	$borrar ->execute(array($typeDel,$source_ipDel,$destination_ipDel,$protocolDel,$portTypeDel,$portValueDel,$JDel,$numeroRow));	

	
 borrarRegla();



}


?>
	<h2>Listado de reglas</h2>
	<form action="" method="post">
		<p>
	<table border="1" class="table table-bordered table-striped" >
		<thead>
				<th>TIPO</th>
				<th>IP DESTINO</th>
				<th>IP ORIGEN</th>
				<th>PROTOCOLO</th>
				<th>PUERTO</th>
				<th>Nº PUERTO</th>
				<th>ESTADO</th>
				<th>BORRAR</th>
		</thead>
		<tbody>
		<?php
			$contadorReglas = 0;
			$recogerReglas -> execute();
			foreach ($recogerReglas as $row) {
		?>
			<tr>
				<td><?php echo $row['type']; ?></td>
				<td><?php echo $row['source_ip']; ?></td>
				<td><?php echo $row['destination_ip']; ?></td>
				<td><?php echo $row['protocol']; ?></td>
				<td><?php echo $row['portType']; ?></td>
				<td><?php echo $row['portValue']; ?></td>
				<td><?php echo $row['J']; ?></td>
				<td><button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" name="numeroRegla" type="submit" value="<?php echo $contadorReglas; ?>"><span class="glyphicon glyphicon-trash"></span></td>
			</tr>
		<?php 
			$contadorReglas++;
			}
			?>
		</tbody>
	</table>
	</p>
	</form>
	

<!-- Boton para abrir el modal, hay que hacer un símbolo minimalista de "+" con BootStrap -->
<button class="btn btn-primary btn-sm" id="myBtn">Nueva regla</button>

<!-- div del modal -->
<div id="myModal" class="modal">

  <!-- div del contenido del modal -->
  <div class="modal-content">
  	<!-- &times en html significa "x", ponemos eso para crear un span con una "x", en caso de que la pulsemos, se cierra, la función está en reglas.js -->
    <span class="close">&times;</span>
	
<h3>Nueva regla</h3>
<hr>
<select name="ruleType" id="ruleType" >
   <option value="inputForm">Input</option>
   <option value="outputForm">Output</option>
   <option value="forwardForm">Forward</option>
</select>

<div id="form-container" style="display: inline-block;">

<form id="inputType" method="post" action="">
	<input type="text" title = "La IP debe tener un formato válido" pattern="(((^|\.)((25[0-5])|(2[0-4]\d)|(1\d\d)|([1-9]?\d))){4}$)|(((^|\.)((25[0-5])|(2[0-4]\d)|(1\d\d)|([1-9]?\d))){4}/(?:\d|[12]\d|3[01])$)" name="ipOrigen" placeholder="IP Origen"/>
	<select name="protocolo">
		<option value="icmp">ICMP</option>
		<option value="tcp">TCP</option>
		<option value="udp">UDP</option>
		<option value="all">Todos</option>
	</select>
	<select name="tipoPuerto" id="tipoPuerto">
		<option value="">Sin puerto</option>
		<option value="origen">Origen</option>estination_port
		<option value="destino">Destino</option>		
	</select>
	<span id="puerto-container"></span>
	<select name="permiso">
		<option value="ACCEPT">Aceptar</option>
		<option value="DROP">Denegar</option>
	</select>
	<input type="checkbox" id="ambosLados" name="ambosLados">&nbsp;&nbsp;Crear regla a ambos lados&nbsp;&nbsp;
	<input type="submit" value="Guardar regla">
	<input type="hidden" name="tipo" value="INPUT">
</form>

</div>
  </div>

</div>
</div>