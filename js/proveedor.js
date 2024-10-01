

function guardar_proveedores_modal(){
    var repetir_ruc = "";
    repetir_ruc = no_repetir_ruc(document.form['txtRucProveedor'], 4);//retorna 1 o 0
//  var cedula_ruc = cedula_ruc(txtCedula);//retorna true o false    
    var ciudad = document.form.opcion2.value//validamos si es > que cero

    if(repetir_ruc == 0 ){
        if(ciudad >= 1){
            var str = $("#form").serialize();
            $.ajax(
			{
				url: 'sql/proveedores.php',
                    data: str+"&txtAccion="+7,
                    type: 'POST',
                    success: function(data){
             	    console.log(data);
				if(data==1){
                    alertify.success("Proveedor agregado con exito :)");
				fn_cerrar();
                listar_proveedores();
			}else{
				alertify.error("Proveedor ya existe");
			}
                    }
                });
            }else{
                alert ('No se puede guardar porque no ha seleccionado su ciudad');
                document.form.cbciudad.focus();
            }
    }else {
        alert ('No se puede guardar porque el Ruc "'+document.form['txtRucProveedor'].value+'" ya se encuentra registrado.');
        document.form.txtRucProveedor.focus();
        document.form.txtRucProveedor.value="";
        document.getElementById("noRepetirRuc").innerHTML="" ;
    }
    
}
function guardar_proveedores_modal2(){
    var repetir_ruc = "";
    repetir_ruc = no_repetir_ruc(document.form['txtRucProveedor'].value, 4);//retorna 1 o 0
//  var cedula_ruc = cedula_ruc(txtCedula);//retorna true o false    
    var ciudad =$('#cbciudad').val(); 
    //document.form.opcion2.value//validamos si es > que cero


// idproveedor
    let proveedor = document.getElementById("idproveedor").value;
    let accion = 77;
    if(proveedor>=0){
        accion = 88;
        repetir_ruc =0;
    }

    if(repetir_ruc == 0 ){
        if(ciudad >= 1){
            var str = $("#form").serialize();
            // console.log("str",str);
            $.ajax(
			{
				url: 'sql/proveedores.php',
                    data: str+"&txtAccion="+accion,
                    type: 'POST',
                    success: function(data){
             	    console.log(data);
				if(data==1){
				alertify.success("Proveedor agregado con exito :)");
				fn_cerrar()
			}else if (data==3){
				alertify.success("Proveedor modificado con exito :)");
				fn_cerrar()
			}else{
				alertify.error("Proveedor ya existe");
			}
                    }
                });
            }else{
                alert ('No se puede guardar porque no ha seleccionado su ciudad');
                document.form.cbciudad.focus();
            }
    }else {
        alert ('No se puede guardar porque el Ruc "'+document.form['txtRucProveedor'].value+'" ya se encuentra registrado.');
        document.form.txtRucProveedor.focus();
        document.form.txtRucProveedor.value="";
        document.getElementById("noRepetirRuc").innerHTML="" ;
    }
    
}

function guardar_proveedores_modal3(){
    var repetir_ruc = "";
   var ruc = document.form['txtRucProveedor'].value;
   $.ajax(
			{
				url: 'sql/proveedores.php',
                    data: "ruc="+ruc+"&txtAccion="+4,
                    type: 'POST',
                    success: function(data){
             	    console.log(data);

repetir_ruc=data.trim()
			 document.getElementById("noRepetirRuc").innerHTML="" ;
                 var ciudad =$('#cbciudad').val(); 
    let proveedor = document.getElementById("idproveedor").value;
    let accion = 77;
    
    
    if(proveedor>=0){
        accion = 88;
        repetir_ruc =0;
    }

    if(repetir_ruc==0){
      if(ciudad >= 1){
            var str = $("#form").serialize();
            // console.log("str",str);
            $.ajax(
			{
				url: 'sql/proveedores.php',
                    data: str+"&txtAccion="+accion,
                    type: 'POST',
                    success: function(data){
             	    console.log(data);
				if(data==1){
				alertify.success("Proveedor agregado con exito :)");
				fn_cerrar()
			}else if (data==3){
				alertify.success("Proveedor modificado con exito :)");
				fn_cerrar()
			}else{
				alertify.error("Proveedor ya existe");
			}
                    }
                });
            }else{
                alert ('No se puede guardar porque no ha seleccionado su ciudad');
                document.form.cbciudad.focus();
            }
            }else {
            alert ('No se puede guardar porque el Ruc "'+document.form['txtRucProveedor'].value+'" ya se encuentra registrado.');
            document.form.txtRucProveedor.focus();
            document.form.txtRucProveedor.value="";
            document.getElementById("noRepetirRuc").innerHTML="" ;
        }  
    }
                });

}

function modificarProveedor2(id_proveedor){
     window.location.href = "http://contaweb.ec/nuevoProveedor.php?id_proveedor="+id_proveedor;
    // //PAGINA: proveedores.php
    //   //comboRetencionIva(6)
   
    //   $("#div_oculto").load("ajax/modificarProveedor.php", {id_proveedor: id_proveedor}, function(){
    //       $.blockUI({
    //               message: $('#div_oculto'),
    //       overlayCSS: {backgroundColor: '#111'},
    //               css:{
    //               '-webkit-border-radius': '3px',
    //               '-moz-border-radius': '3px',
    //               'box-shadow': '6px 6px 20px gray',
    //               top: '5%',
    //               left: '15%',
    //               width: '75%',
    //               position: 'absolute'
    //               }
    //       });
    //   });
   }


function guardarModificarProveedor(accion){
    //PAGINA: proveedores.php
    var ciudad = document.form.opcion2.value//validamos si es > que cero
    if(ciudad >= 1){
       var str = $("#form").serialize();
       $.ajax({
               url: 'sql/proveedores.php',
               data: str+"&txtAccion="+accion,
               type: 'post',
               success: function(data){
               
                  listar_proveedores();
                  if(data==1){
                   alertify.success('Registro actualizado correctamente');
                   listar_proveedores();
                  }else{
                   alertify.error('Registro no se  actualizo correctamente');
                  }
                
               }
       });
    }else{
       alert ('No se puede guardar porque no ha seleccionado su ciudad');
       document.form.cbciudad.focus();
   }
}

function listar_proveedores(page=1){
 //PAGINA: proveedores.php
    var str = $("#form1").serialize();
    $.ajax({
            url: 'ajax/listarProveedores.php?page='+page,
            type: 'get',
            data: str,
            success: function(data){
                    $("#div_listar_proveedores").html(data);
            }
    });
}


function eliminarProveedor(id, accion, id_plan_cuenta) {
    // PAGINA: proveedores.php
    alertify.confirm("Confirmar Eliminación", "¿Seguro desea eliminar este registro? Esta acción eliminará de forma permanente la fila seleccionada del Módulo Proveedores.",
        function () {
            $.ajax({
                url: 'sql/proveedores.php',
                data: 'idProveedor=' + id + '&txtAccion=' + accion + '&id_cuenta=' + id_plan_cuenta,
                type: 'post',
                success: function (data) {
                    if (data != "") {
                        alertify.success(data);
                        listar_proveedores();
                    }
                },
                error: function () {
                    alertify.warning("Error en la solicitud.");
                }
            });
        },
        function () {
            alertify.warning("Acción cancelada.");
        }
    );
}
