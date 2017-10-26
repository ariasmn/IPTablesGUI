<?php
require_once "conexiones.php";

//------------------------------------------------------------------------------------------------------------------------------------//
//si la variable INPUT del array es ACCEPT, crea el checkbox marcado, si es DROP, desmarcado.
	if (recogidaInput() == "ACCEPT") {
	$botonInput = '<input data-toggle="toggle" data-width="50" data-height="25" data-on="A" data-off="R" type="checkbox" name="input" onChange="this.form.submit()" disabled="disabled" checked>
	';
	} else {
		$botonInput =  '<input data-toggle="toggle" data-width="50" data-height="25" data-on="A" data-off="R" type="checkbox" name="input" onChange="this.form.submit()" disabled="disabled">
		';
		}
//lo mismo para la variable OUTPUT del array
	if (recogidaOutput() == "ACCEPT") {
	$botonOutput = '<input data-toggle="toggle" data-width="50" data-height="25" data-on="A" data-off="R" type="checkbox" name="output" onChange="this.form.submit()" disabled="disabled" checked>
	';
	} else {
		$botonOutput =  '<input data-toggle="toggle" data-width="50" data-height="25" data-on="A" data-off="R" type="checkbox" name="output" onChange="this.form.submit()" disabled="disabled">
		';
		}
//lo mismo para la variabe FORWARD

	if (recogidaForward() == "ACCEPT") {
	$botonForward = '<input data-toggle="toggle" data-width="50" data-height="25" data-on="A" data-off="R" type="checkbox" name="forward" onChange="this.form.submit()" disabled="disabled" checked>
	';
	} else {
		$botonForward =  '<input data-toggle="toggle" data-width="50" data-height="25" data-on="A" data-off="R" type="checkbox" name="forward" onChange="this.form.submit()" disabled="disabled">
		';
		}
		
	
/*
	Checkea el estado del firewall desde la base de datos y crea el boton
*/
if (recogidaFWStatus() == "ON"){
		$botonStatus = '<input type="checkbox" data-toggle="toggle" data-width="75" data-height="35" data-onstyle="success" data-offstyle="danger" name="fwstatus" id="siContainer" onChange="this.form.submit()" value="siContainer" disabled="disabled" checked>
	';
	}else {
		$botonStatus = '<input type="checkbox" data-toggle="toggle" data-width="75" data-height="35" data-onstyle="success" data-offstyle="danger" name="fwstatus" id="noContainer" onChange="this.form.submit()" disabled="disabled">
	';
		
	}


?>
	<h2>Estado del firewall y políticas</h2>
	<form method="post" action="" name="fwstatus">
		<?php 
		echo "<p>Estado del firewall: $botonStatus</p>";
		?>
		
	<input type="hidden" name="statusSh">
	</form>
	<!-- Abrimos el div que almacenará todo lo que desaparecerá en caso de desactivar el firewall, se cierra en RULES.PHP -->
	
	<div name="containertodo" id="containertodo" style="display: block">
			
    <form method="post" action="" name="politicas">
		
<?php 

	echo "<p>INPUT $botonInput</p>";
	echo "<p>OUTPUT $botonOutput</p>";
	echo "<p>FORWARD $botonForward</p>";
?>
<input type="hidden" name="ay">
</form>
<h2>Listado de reglas</h2>
<table border="1" class="table table-bordered table-striped">
		<thead>
				<th>TIPO</th>
				<th>IP DESTINO</th>
				<th>IP ORIGEN</th>
				<th>PROTOCOLO</th>
				<th>PUERTO</th>
				<th>Nº PUERTO</th>
				<th>ESTADO</th>
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
			</tr>
		<?php 
			$contadorReglas++;
			}
			?>
		</tbody>
</table>
<script src="js/policy.js" type="text/javascript"></script>
</div>
