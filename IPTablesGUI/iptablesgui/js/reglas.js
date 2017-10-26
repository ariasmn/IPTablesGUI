//variable para el form, por defecto input, que es el select por defecto

var formElegido = "input";

// Get the modal
var modal = document.getElementById('myModal');
//var modal = $('#myModal');
// Get the button that opens the modal
var btn = document.getElementById("myBtn");
//var btn = $('#myBtn');
// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];
//var span = $('.close')[0];
// When the user clicks the button, open the modal 
btn.onclick = function() {
    modal.style.display = "block";
};

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
};

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
};

//Variable que almacenan los distintos forms.
//usamos dos backslash (\) para escaparlo


var divInputForm = '<form id="inputType" method="post" action=""> \
	<input type="hidden" name="tipo" value="INPUT"> \
	<input type="text" title = "La IP debe tener un formato válido" pattern="(((^|\\.)((25[0-5])|(2[0-4]\\d)|(1\\d\\d)|([1-9]?\\d))){4}$)|(((^|\\.)((25[0-5])|(2[0-4]\\d)|(1\\d\\d)|([1-9]?\\d))){4}/(?:\\d|[12]\\d|3[01])$)" id="ipOrigen" name="ipOrigen" placeholder="IP Origen"/> \
	<select name="protocolo"> \
		<option value="icmp">ICMP</option> \
		<option value="tcp">TCP</option> \
		<option value="udp">UDP</option> \
		<option value="all">Todos</option> \
	</select> \
	<select name="tipoPuerto" id="tipoPuerto"> \
		<option value="">Sin puerto</option> \
		<option value="origen">Origen</option>estination_port \
		<option value="destino">Destino</option> \
	</select> \
	<span id="puerto-container"></span> \
	<select name="permiso"> \
		<option value="ACCEPT">Aceptar</option> \
		<option value="DROP">Denegar</option> \
	</select> \
	<input type="checkbox" id="ambosLados" name="ambosLados">&nbsp;&nbsp;Crear regla a ambos lados&nbsp;&nbsp;</input> \
	<input type="submit" value="Guardar regla"></input> \
</form>';

var divOutputForm = '<form id="outputType" method="post" action="" style="inline;"> \
	<input type="hidden" name="tipo" value="OUTPUT"> \
	<input type="text" title = "La IP debe tener un formato válido" pattern="(((^|\\.)((25[0-5])|(2[0-4]\\d)|(1\\d\\d)|([1-9]?\\d))){4}$)|(((^|\\.)((25[0-5])|(2[0-4]\\d)|(1\\d\\d)|([1-9]?\\d))){4}/(?:\\d|[12]\\d|3[01])$)" id="ipDestino" name="ipDestino" placeholder="IP Destino"/> \
	<select name="protocolo"> \
		<option value="icmp">ICMP</option> \
		<option value="tcp">TCP</option> \
		<option value="udp">UDP</option> \
		<option value="all">Todos</option> \
	</select> \
		<select name="tipoPuerto" id="tipoPuerto"> \
		<option value="">Sin puerto</option> \
		<option value="origen">Origen</option>estination_port \
		<option value="destino">Destino</option> \
	</select> \
	<span id="puerto-container"></span> \
	<select name="permiso"> \
		<option value="ACCEPT">Aceptar</option> \
		<option value="DROP">Denegar</option> \
	</select> \
	<input type="checkbox" id="ambosLados" name="ambosLados">&nbsp;&nbsp;Crear regla a ambos lados&nbsp;&nbsp;</input> \
	<input type="submit" value="Guardar regla"></input> \
</form>';


var divForwardForm = '<form id="forwardType" method="post" action="" style="inline;"> \
	<input type="hidden" name="tipo" value="FORWARD"> \
	<input type="text" title = "La IP debe tener un formato válido" pattern="(((^|\\.)((25[0-5])|(2[0-4]\\d)|(1\\d\\d)|([1-9]?\\d))){4}$)|(((^|\\.)((25[0-5])|(2[0-4]\\d)|(1\\d\\d)|([1-9]?\\d))){4}/(?:\\d|[12]\\d|3[01])$)" id="ipOrigen" name="ipOrigen" placeholder="IP Origen" > \
	<input type="text" title = "La IP debe tener un formato válido" pattern="(((^|\\.)((25[0-5])|(2[0-4]\\d)|(1\\d\\d)|([1-9]?\\d))){4}$)|(((^|\\.)((25[0-5])|(2[0-4]\\d)|(1\\d\\d)|([1-9]?\\d))){4}/(?:\\d|[12]\\d|3[01])$)" id="ipDestino" name="ipDestino" placeholder="IP Destino" > \
	<select name="protocolo"> \
		<option value="icmp">ICMP</option> \
		<option value="tcp">TCP</option> \
		<option value="udp">UDP</option> \
		<option value="all">Todos</option> \
	</select> \
		<select name="tipoPuerto" id="tipoPuerto"> \
		<option value="">Sin puerto</option> \
		<option value="origen">Origen</option>estination_port \
		<option value="destino">Destino</option> \
	</select> \
	<span id="puerto-container"></span> \
	<select name="permiso"> \
		<option value="ACCEPT">Aceptar</option> \
		<option value="DROP">Denegar</option> \
	</select> \
	<input type="checkbox" id="ambosLados" name="ambosLados">&nbsp;&nbsp;Crear regla a ambos lados&nbsp;&nbsp;</input> \
	<input type="submit" value="Guardar regla"></input> \
</form>';


////VARIABLE QUE ALMACENA SI EL CAMPO PUERTO SE MUESTRA O NO////

var puertoSi = '<input type="text" id="puertoValor" title = "Rango de puertos 1-65535" pattern="([1-9]|[1-8][0-9]|9[0-9]|[1-8][0-9]{2}|9[0-8][0-9]|99[0-9]|[1-8][0-9]{3}|9[0-8][0-9]{2}|99[0-8][0-9]|999[0-9]|[1-5][0-9]{4}|6[0-4][0-9]{3}|65[0-4][0-9]{2}|655[0-2][0-9]|6553[0-5])" name="puertoValor" placeholder="Puerto">';


/*
 * 
 * 
 * 
 Esta función quita y añade los form según el tipo elegido
 A su vez, carga la función que añade o quita el campo del valor del puerto depende de lo que se seleccione
 * 
 * 
 */


$('#ruleType').on("change",function(){
   selection = $(this).val();    
   switch(selection)
   { 
       case 'inputForm':
           $('#inputType').remove();
           $('#outputType').remove();
           $('#forwardType').remove();
           $('#form-container').html(divInputForm);
           
           $(document).ready(function () {
    			anyadeCajaPuerto();
    				$('#tipoPuerto').on('change', function() {
        				anyadeCajaPuerto();
    					});
			});
           break;
           
       case 'outputForm':
           $('#outputType').remove();
           $('#inputType').remove();
           $('#forwardType').remove();
           $('#form-container').html(divOutputForm);
           
           $(document).ready(function () {
   			 anyadeCajaPuerto();
    			$('#tipoPuerto').on('change', function() {
        			anyadeCajaPuerto();
    				});
			});
           break;
           
       case 'forwardForm':
       		 $('#forwardType').remove();
           $('#inputType').remove();
           $('#outputType').remove();
           $('#form-container').html(divForwardForm);
           
           $(document).ready(function () {
    			anyadeCajaPuerto();
    				$('#tipoPuerto').on('change', function() {
        				anyadeCajaPuerto();
    					});
			});
           break;
   }
});

//AÑADE O QUITA EL CAMPO DEL PUERTO SI SE SELECCIONA O NO///

function anyadeCajaPuerto(){
   selection = $('#tipoPuerto').val();    
   switch(selection)
   { 
       case '':
           $('#puertoValor').remove();         
           break;
       case 'origen':
       	$('#puerto-container').html(puertoSi);
       	break;
       case 'destino':
       $('#puerto-container').html(puertoSi);
   }
}

////////CARGA LA FUNCIÓN ANTERIOR, QUE AÑADEEL PUERTO O NO////////////

$(document).ready(function () {
    anyadeCajaPuerto();
    $('#tipoPuerto').on('change', function() {
        anyadeCajaPuerto();
    });
});


//////////////////////////////////////////////////////////