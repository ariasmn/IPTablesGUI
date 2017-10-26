/**
Quita el contenido de la página en caso de que se desactive el firewall
Se controla con los distintos ids en el input (containerSi/containerNo)
 */

var statusid = $('input[name="fwstatus"]').attr('id');

if (statusid == "siContainer"){
	
	$('#containertodo').fadeIn('slow');
}else {
	
	$('#containertodo').fadeOut('slow');
}
