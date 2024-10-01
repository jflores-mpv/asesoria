
$( document ).ready(function() {
    contarNotificaciones(4);
});

function myFunction() {
  document.getElementById("myDropdown").classList.toggle("show");
  notificaciones(1);
}
        
        


function notificaciones(accion){
		$.ajax({
			url: 'sql/notificaciones.php',
			data: 'txtAccion='+accion,
			type: 'post',
			success: function(data){
			  	$("#notificaciones").html(data);
			}	
		});
}

function contarNotificaciones(accion){

		$.ajax({
			url: 'sql/notificaciones.php',
			data: 'txtAccion='+accion,
			type: 'post',
			success: function(data){
			    console.log(data);
			  	$("#contador").html(data);
			}	
		});
}
   
        

function estadoNotificacion(id, num,accion,idLead){
	var id = id;
	console.log(id);
	$.ajax({
			url: 'sql/notificaciones.php',
			data: 'id=' +id+'&txtAccion='+accion+'&num='+num,
			type: 'post',
			success: function(data){
			    if(data ='1')
				    notificaciones(1);
				    window.location.href = 'crmLead.php?id='+idLead ;
				     contarNotificaciones(4);
			    }
	});
}