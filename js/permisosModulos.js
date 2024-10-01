function objetoAjax(){
    var xmlhttp=false;
    try {
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
        try {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (E) {
            xmlhttp = false;
        }
    }

    if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
        xmlhttp = new XMLHttpRequest();
    }
    return xmlhttp;
}


function entrarModulo(id) {
    
    return new Promise((resolve, reject)=>{
   
        let permisos = null;
         
    	$.ajax({
			type:"POST",
			url:"sql/permisosModulos.php",
			data:'id=' + id,
			success:function(data){
			    if(data!=null && data!= ""){
    	            permisos = JSON.parse(data).permisos;
    	        }
    	        resolve(permisos)
			    /*
			    console.log(r)
				if(r==1){
				//	$('#listadoCheques').load('ajax/listadoCheques.php');
					alertify.success("Eliminado con exito!");
				}
				else{
					alertify.error("Fallo el servidor :(");
				} */
			}, 
			error: function(e){
			    reject(e)
			}
		});
    });
} // lookup