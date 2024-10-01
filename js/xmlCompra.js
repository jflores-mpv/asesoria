


function agregaform(datos){

	d=datos.split('||');
	$('#idE').val(d[0]);
	$('#nombreBancoE').val(d[1]);
	$('#pagueseE').val(d[2]);
	$('#valorE').val(d[3]);
	$('#valorLetrasE').val(d[4]);
	$('#numCuentaE').val(d[5]);
	$('#numChequeE').val(d[6]);
	$('#lugarFechaE').val(d[7]);
	$('#firmaE').val(d[8]);
	$('#idEjercicioE').val(d[9]);
    $('#cruzadoE').val(d[10]);
	
}
