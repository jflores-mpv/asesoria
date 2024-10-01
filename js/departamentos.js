
function nuevo_departamento(){
 //PAGINA: productos.php
 //alert("sss");
	$("#div_oculto").load("ajax/nuevoDepartamento.php", function(){
		$.blockUI({
			message: $('#div_oculto'),
		overlayCSS: {backgroundColor: '#111'},
			css:{
			 '-webkit-border-radius': '3px',
                '-moz-border-radius': '3px',
                'box-shadow': '6px 6px 20px gray',
				top: '10%',
				left: '35%',
                position: 'absolute'
			
			}
		});
	});
}



function guardar_departamento(accion){
         var str = $("#formDepartamento").serialize();
          $.ajax(
		{
			url: 'sql/departamentos.php',
            data: str+"&txtAccion="+accion,
            type: 'post',
            success: function(data)	{
			console.log(data);
				if(data.trim()=='Registro insertado correctamente.'){
				alertify.success("Departamento agregado con exito :)");
				$('#listadoDepartamento').load('ajax/listadoDepartamento.php');
				fn_cerrar()
			}else if(data.trim()=='1'){
			    	alertify.success("Departamento actualizado con exito :)");
				$('#listadoDepartamento').load('ajax/listadoDepartamento.php');
				fn_cerrar()
			}else{
				alertify.error("Departamento ya existe");
			}
            }
        });

}

function modificarDepartamento(id){
	$("#div_oculto").load("ajax/modificarDepartamento.php", {id: id}, function(){
		$.blockUI({
			message: $('#div_oculto'),
			overlayCSS: {backgroundColor: '#111'},
			css:{
				'-webkit-border-radius': '3px',
                '-moz-border-radius': '3px',
                'box-shadow': '6px 6px 20px gray',
				top: '10%',
				left: '35%',
                position: 'absolute'
			}
		});
	});
}

function preguntarSiNoDepartamento(id,accion){
	alertify.confirm('Eliminar Datos', '多Esta seguro de eliminar este registro?', 
	function(){ 
		eliminarDepartamento(id,accion) 
	 }
	, function(){ alertify.error('Se cancelo')});
}
function eliminarDepartamento(id,accion){
	
	 $.ajax(
   {
	   url: 'sql/departamentos.php',
	   data: "&txtAccion="+accion+"&id_departamento="+id,
	   type: 'post',
	   success: function(data)	{
	   console.log(data);
		   if(data==1){
		   alertify.success("Departamento eliminado con exito :)");
		   $('#listadoDepartamento').load('ajax/listadoDepartamento.php');
		   fn_cerrar()
	   }else{
		   alertify.error("Parametro ya existe");
	   }
	   }
   });

}