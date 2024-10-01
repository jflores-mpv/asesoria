
function nuevo_cargos(){
 //PAGINA: productos.php
 //alert("sss");
	$("#div_oculto").load("ajax/nuevoCargo.php", function(){
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



function guardar_cargoss(accion){
         var str = $("#formCargoss").serialize();
          $.ajax(
		{
			url: 'sql/cargos.php',
            data: str+"&txtAccion="+accion,
            type: 'post',
            success: function(data)	{
			console.log(data);
				if(data==1){
				alertify.success("Cargo agregado con exito :)");
				$('#listadoCargos').load('ajax/listadoCargos.php');
				fn_cerrar()
			}else if(data==5){
				alertify.success("Cargo modificado con exito :)");
				$('#listadoCargos').load('ajax/listadoCargos.php');
				fn_cerrar()
			}else{
				alertify.error("Cargo ya existe");
			}
            }
        });

}

function modificarCargos(id){
	$("#div_oculto").load("ajax/modificarCargos.php", {id: id}, function(){
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
function preguntarSiNoCargos(id,accion){
	alertify.confirm('Eliminar Datos', '多Esta seguro de eliminar este registro?', 
	function(){ 
		eliminar_cargoss(id,accion) 
	 }
	, function(){ alertify.error('Se cancelo')});
}
function eliminar_cargoss(accion,id){
	
	 $.ajax(
   {
	   url: 'sql/cargos.php',
	   data: "&txtAccion="+accion+"&id_cargo="+id,
	   type: 'post',
	   success: function(data)	{
	  // console.log(data);
		   if(data==1){
		   alertify.success("Cargo eliminado con exito :)");
		   $('#listadoCargos').load('ajax/listadoCargos.php');
		   fn_cerrar()
	   }else{
		   alertify.error("No se pudo eliminar el cargo");
	   }
	   }
   });

}
function nuevo_categoria(){
	//PAGINA: productos.php
	//alert("sss");
	   $("#div_oculto").load("ajax/nuevaCategoria.php", function(){
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

   
const guardar_categoria = (accion)=>{
	var str = $("#formCategorias").serialize();
	 $.ajax(
   {
	   url: 'sql/cargos.php',
	   data: str+"&txtAccion="+accion,
	   type: 'post',
	   success: function(data)	{
	   console.log(data);
		   if(data==1){
		   alertify.success("Categoria agregada con exito :)");
		     $('#listadoCategoriasCargos').load('ajax/listadoCategorias.php');
      
	
		   fn_cerrar()
	   }else if(data==5){
		   alertify.success("Cargo modificado con exito :)");
	  $('#listadoCategoriasCargos').load('ajax/listadoCategorias.php');
      
		   fn_cerrar()
	   }else{
		   alertify.error("Cargo ya existe");
	   }
	   }
   });

}
function modificarCategoria(id){
	$("#div_oculto").load("ajax/modificarCategoria.php", {id: id}, function(){
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
function preguntarSiNoCategorias(accion,id){
	alertify.confirm('Eliminar Datos', '多Esta seguro de eliminar este registro?', 
	function(){ 
		eliminar_categorias(accion,id) 
	 }
	, function(){ alertify.error('Se cancelo')});
}
function eliminar_categorias(accion,id){
	
	 $.ajax(
   {
	   url: 'sql/cargos.php',
	   data: "&txtAccion="+accion+"&id_categoria="+id,
	   type: 'post',
	   success: function(data)	{
	  // console.log(data);
		   if(data==1){
		   alertify.success("Categoria eliminado con exito :)");
		   $('#listadoCategoriasCargos').load('ajax/listadoCategorias.php');
		   fn_cerrar()
	   }else{
		   alertify.error("No se pudo eliminar la categoria");
	   }
	   }
   });

}