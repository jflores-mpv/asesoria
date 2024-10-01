<?php
	//require_once('ver_sesion.php');
    error_reporting(0);
	//Start session
	session_start();
		
	//Include database connection details
	//require_once('conexion.php');
	    $id_empresa_cookies = $_COOKIE["id_empresa_cookie"];
    $id_periodo_contable_cookies = $_COOKIE["id_periodo_contable_cookie"];
    $cookie_tipo = $_COOKIE['tipo_cookie'];

    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $sesion_id_periodo_contable = $_SESSION['sesion_id_periodo_contable'];
    $sesion_empresa_nombre = $_SESSION['sesion_empresa_nombre'];
    $sesion_tipo = $_SESSION["sesion_tipo"];
    $sesion_nombre = $_SESSION["sesion_nombre"];
    $sesion_apellido = $_SESSION["sesion_apellido"];
    $sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];
	
?>

<html lang="es-ES">
    
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Inventarios</title>
    
    <script src="https://kit.fontawesome.com/7816c50242.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="librerias/bootstrap/css/bootstrap.css">  
    <script src="librerias/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <script src="librerias/bootstrap/js/bootstrap.js"></script>
    <link rel="stylesheet" href="librerias/bootstrap/css/style.css"> 
    <link rel="stylesheet" type="text/css" href="librerias/alertifyjs/css/alertify.css">
    <script src="librerias/alertifyjs/alertify.js"></script>
    <link rel="stylesheet" href="librerias/dist/css/bootstrap-select.css">
    <link rel="stylesheet" type="text/css" href="librerias/select2/css/select2.css">

    <!-- START ESTILOS Y CLASES PARA AJAX -->
    <script language="javascript" type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
    <script language="javascript" type="text/javascript" src="js/jquery.blockUI.js"></script>
    <script language="javascript" type="text/javascript" src="js/jquery.validate.1.5.2.js"></script>
    <script language="javascript" type="text/javascript" src="js/index.js"></script>
        <script language="javascript" type="text/javascript" src="js/productos.js"></script>
    <!-- END ESTILOS Y CLASES PARA AJAX -->

    <!-- estilos para tablas -->
    <link href="css/listadoTablas.css" rel="stylesheet" type="text/css" />

    <!-- FUNCIONES -->
    <script type="text/javascript" src="js/funciones.js"></script>

     <!--validaciones de los campos -->
    <script type="text/javascript" src="js/validaciones.js"></script>
     <script type="text/javascript" src="js/condominios.js"></script>
     	<link type="text/css" rel="stylesheet" href="css/calendario.css" media="screen"/>
 <script language="javascript" type="text/javascript" src="js/calendario.js"></script>
    <!--END estilo para  el menu -->
     <script language="javascript" type="text/javascript" src="js/promociones.js"></script>
    <link rel="shortcut icon" href="images/logo.png">
        <style>
        #loader{
  display: none;
  height: 100vh;
  position: fixed;
  width: 100%;
  background: #000000a3;
  z-index: 20;
}
.spinner {
  margin: 40vh auto;
  width: 50px;
  height: 40px;
  text-align: center;
  font-size: 10px;
}

.spinner > div {
  background-color: #eee;
  height: 100%;
  width: 6px;
  display: inline-block;
  
  -webkit-animation: sk-stretchdelay 1.2s infinite ease-in-out;
  animation: sk-stretchdelay 1.2s infinite ease-in-out;
}

.spinner .rect2 {
  -webkit-animation-delay: -1.1s;
  animation-delay: -1.1s;
}

.spinner .rect3 {
  -webkit-animation-delay: -1.0s;
  animation-delay: -1.0s;
}

.spinner .rect4 {
  -webkit-animation-delay: -0.9s;
  animation-delay: -0.9s;
}

.spinner .rect5 {
  -webkit-animation-delay: -0.8s;
  animation-delay: -0.8s;
}

@-webkit-keyframes sk-stretchdelay {
  0%, 40%, 100% { -webkit-transform: scaleY(0.4) }  
  20% { -webkit-transform: scaleY(1.0) }
}

@keyframes sk-stretchdelay {
  0%, 40%, 100% { 
    transform: scaleY(0.4);
    -webkit-transform: scaleY(0.4);
    }  20% { 
      transform: scaleY(1.0);
      -webkit-transform: scaleY(1.0);
    }
  }
    </style>
</head>


<body onload="listar_productos(1);buscarCentrosCostos(11);">
    
    
<div class="wrapper d-flex align-items-stretch celeste">
        <nav id="sidebar" ><?php  {include("menusNiveles.php");      }   ?></nav>
    <div id="content"  >
        <header ><?php  {include("header.php");      }   ?>  </header>
        
<div class="row mx-5 mt-5 ">  
  
                <a class="col-lg-3 text-decoration-none card p-1 m-1"  type="button"  id="btnNuevaFactura" 
                name="btnNuevaFactura" onclick = "nuevo_producto()" />
                <div class="my-icon3 "><i class="fa fa-file mr-3 "></i><span>Nuevo Producto</span>  </div></a> 
                <a class="col-lg-3 text-decoration-none card p-1 m-1"  type="button"  id="btnNuevaFactura" 
                name="btnNuevaFactura" onclick = "location='Kardex.php'" />
                <div class="my-icon3 "><i class="fa fa-file mr-3 "></i><span>Kardex</span>  </div></a>
                
                 <a class="col-lg-3 text-decoration-none card p-1 m-1"  type="button"  id="btnNuevaFactura" 
                name="btnNuevaFactura" onclick = "nuevaBodega()" />
                <div class="my-icon3 "><i class="fa fa-file mr-3 "></i><span>Bodegas</span>  </div></a>
 

                <div class="row ">
                     <form action="" method="post"  name="frmExcelImport2" id="frmExcelImport2" enctype="multipart/form-data">

<div class="input-group">
              <span class="input-group-text">Bodega</span>
    <select name="idBodega" id="idBodega" class="form-control">
        <?php 
        $sql = "SELECT `id`, `detalle`, `id_empresa` FROM `bodegas` WHERE id_empresa=$sesion_id_empresa";
        $result = mysql_query($sql);
        while($row = mysql_fetch_array($result)) {
        ?>
            <option value="<?php echo $row['id'] ?>"><?php echo $row['detalle'] ?></option>
        <?php
        }
        ?>
    </select>
    <span class="input-group-text">&Aacute;rea</span>
    <select name="idArea" id="idArea" class="form-control">
        <?php 
        $sqlCc = "SELECT `id_centro_costo`, `descripcion`, `empresa` FROM `centro_costo` WHERE `empresa`=$sesion_id_empresa";
        $resultCc = mysql_query($sqlCc);
        while($rowCc = mysql_fetch_array($resultCc)) {
        ?>
            <option value="<?php echo $rowCc['id_centro_costo'] ?>"><?php echo $rowCc['descripcion'] ?></option>
        <?php
        }
        ?>
    </select>
    <input type="file" name="file" id="file" accept=".xls,.xlsx" class="form form-control">
    <div class="input-group-append">
        <button type="button" id="submit" name="import" class="btn btn-outline-secondary" onclick="agregar()">Importar Inventario</button>
    </div>
  
      <div class="input-group-append">
      <a href="sql/subidas/productos/formato_productos.xlsx" download="formato_productos.xlsx">  <input type="button" id="descargar" name="descargar" class="btn btn-outline-secondary"  value="Descargar Formato"></input></a>
    </div>
   
</div>
   
                    
</form>
     </div>



<div class="row">
 
    </br>
</div>
    

<div class="row">
    <div class="col-lg-2 bg-white p-2 border-rounded">
            <form name="form1" id="form1" method="post" action="javascript: listar_productos(1);">
                 <div class="row">
                     
                       <div class="col-lg-12">
                <td>C&oacute;digo:</td>
                <td><input class="form-control" name="criterio_usu_cod" type="search" id="criterio_usu_cod" placeholder="C&oacute;digo producto" 
                title="Ingrese un c&oacute;digo para buscar" onKeyUp="javascript: listar_productos(1);"/></td>
            </div>

            <div class="switch-field col-lg-12 text-center " style="margin-top:10px">
                <input type="radio" id="radio_agrupado_codigo" name="radio_agrupado_codigo" value="Agrupado" class="p-4" checked onchange="listar_productos(1);">
                <label for="radio_agrupado_codigo">Agrupado</label>
                <input type="radio" id="radio_sin_agrupar_codigo" name="radio_agrupado_codigo" value="Sin_agrupar" class="p-4" onchange="listar_productos(1);">
                <label for="radio_sin_agrupar_codigo">Sin Agrupar</label>
            </div>

                <div class="col-lg-12">
                <td>Nombre:</td>
                <td><input class="form-control" name="criterio_usu_per" type="search" id="criterio_usu_per" placeholder="Nombre producto" 
                title="Ingrese un nombre para buscar" onKeyUp="javascript: listar_productos(1);"/></td>
            </div>
            <div class="col-lg-12">    
                <td>Ordenar: </td>
                <td>
                    <select class="form-select" name="criterio_ordenar_por" id="criterio_ordenar_por" onChange="javascript: listar_productos(1);">
                        <option value="productos.`producto`">Nombre</option>
                        <option value="categorias.`categoria`">Tipo</option>
                        <option value="productos_stock">Stock</option>
                        <option value="productos.`iva`">IVA</option>
                        <option value="productos.`costo`">Costo</option>
                        <option value="productos.`codigo`">Codigo</option>
                    </select>
                </td>

            </div>
            <div class="col-lg-12">    
                <td>Tipo: </td>
                <td>
                    <select class="form-select" name="criterio_tipo" id="criterio_tipo" onChange="javascript: buscarCentrosCostos(11);">
                        <option value="1">Inventario</option>
                        <option value="2">Servicios</option>
                        <!--<option value="3">Activos Fijos</option>-->
                        <option value="4">Proveedur&iacute;a</option>
                    </select>
                </td>
    
            </div>
        
        <div class="col-lg-12">    
                <td>&Aacute;reas: </td>
                <td>
                    <select class="form-select" name="criterio_area" id="criterio_area" onChange="javascript: listar_productos(1);">
                    </select>
                </td>
        </div>
            
                 <div class="col-lg-12">    
                <td>Bodega: </td>
                <td>
                    <select class="form-select" name="bodega" id="bodega" onChange="javascript: listar_productos(1);">
                    <option value="0">Todos</option>
                    <?php
                    $sqlBodegas = "SELECT `id`, `detalle`, `id_empresa` FROM `bodegas` WHERE id_empresa=$sesion_id_empresa";
                    $resultBodegas = mysql_query($sqlBodegas);
                    while($rowBodega = mysql_fetch_array($resultBodegas)){
                      ?>
                      <option value="<?php echo $rowBodega['id'] ?>"><?php echo $rowBodega['detalle'] ?></option>
                      <?php
                    }
                    ?>
                    </select>
                </td>

            </div>
    
            <div class="switch-field col-lg-12 text-center " style="margin-top:10px">
                <input type="radio" id="radio-Cantidades"  name="criterio_valor" value="Cantidades" class="p-4" checked onChange="listar_productos(1);"/>
                <label for="radio-Cantidades">Cantidades</label>
                <input type="radio" id="radio-Valorados"     name="criterio_valor" value="Valorados" class="p-4"  onChange="listar_productos(1);"  />
                <label for="radio-Valorados">Valorados</label>
            </div>
            <div class="switch-field col-lg-12 text-center " style="margin-top:10px">
                <input type="radio" id="radio-Areas"     name="criterio_valor" value="Areas" class="p-4"  onChange="listar_productos(1);"  />
                <label for="radio-Areas">&Aacute;reas</label>
            </div>
                   <div class="col-lg-12" style="margin-top:10px">
                <td>IVA: </td>
                <td>
                    <select class="form-select" name="iva" id="iva" onChange="javascript: listar_productos(1);">
                        <option value="0">TODOS</option>
                        <?php
                        $sqlImp = "SELECT `id_iva`, `iva`, `estado`, `id_empresa`, `id_plan_cuenta`, `codigo` FROM `impuestos` WHERE id_empresa = '".$sesion_id_empresa."' ";
                        $resultImp = mysql_query( $sqlImp );
                        while( $rowImp = mysql_fetch_array( $resultImp ) ){
                            ?>
                            <option value="<?php echo $rowImp['id_iva'] ?>"><?php echo $rowImp['iva'] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
            </div>
            
             
             
            <div class="col-lg-12" style="margin-top:10px">
                <td>Registros: </td>
                <td>
                    <select class="form-select" name="criterio_mostrar" id="criterio_mostrar" onChange="javascript: listar_productos(1);">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="20" selected="selected">20</option>
                            <option value="40">40</option>
                            <option value="100">100</option>
                            <option value="400000000">Todos</option>
                    </select>
                </td>
            </div>
            <div class="col-lg-12">
                <td>En: </td>

                    <select class="form-select" name="criterio_orden" id="criterio_orden" onChange="javascript: listar_productos(1);">
                        <option value="asc">Ascendente</option>
                        <option value="desc">Descendente</option>
                    </select>

         
            </div>
            <div class="col-lg-12 my-2">
                <button  id="submit" type="submit" value="Buscar" class="btn btn-info text-white w-100" onClick="javascript:abrirPdfProductos(<?php echo $sesion_id_empresa?>)">Reporte</button>
            </div>
            
      
                    <input type="button" tabindex="5" name="submit" value="Excel Reporte Excel" id="" class="btn btn-outline-warning w-100" align="right" onclick="javascript: reporteExcelProductos();">
             
              </div>
            </form>    
            
            
      
    </div>
    <div class="col-lg-10">
        <div id="div_listar_productos"></div>
    </div>
</div>

        
        <div id="div_oculto" style="display: none;"></div>

    </div>	

</div>
<div id="loader">
		<div class="spinner">
			<div class="rect1"></div>
			<div class="rect2"></div>
			<div class="rect3"></div>
			<div class="rect4"></div>
			<div class="rect5"></div>
		</div>
	</div>
<script src="librerias/bootstrap/js/main.js"></script>      

<script type="">

const buscarCentrosCostos=(aux)=>{
    tipo = document.getElementById('criterio_tipo').value;
    ajax3=objetoAjax();
    ajax3.open("POST", "sql/busquedas.php",true);
    ajax3.onreadystatechange=function() {
        if (ajax3.readyState==4) {
            var respuesta=ajax3.responseText;
            llenarCentrosCostos(respuesta);
        }
    }
    ajax3.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax3.send("accion="+aux+"&tipo="+tipo)
}

const llenarCentrosCostos=(cadena)=>{
    array = cadena.split( "?" );
    limite=array.length;
    cont=1;
    let selectArea = document.getElementById('criterio_area');
    for (m=selectArea.options.length-1;m>=0;m--){
        selectArea.options[m]=null;
    }

    
    selectArea.options[0] = new Option("Seleccione Area","0");
    for(i=1;i<limite;i=i+2){
        selectArea.options[cont] = new Option(array[i+1], array[i]);
        cont++;
    }
    listar_productos(1);
}

 const abrirPdfProductos=(empresa)=>{
        let cOrden= document.getElementById('criterio_orden').value;
        let cMostrar= document.getElementById('criterio_mostrar').value;
        let cTipo= document.getElementById('criterio_tipo').value;
        let ordenar =document.getElementById('criterio_ordenar_por').value;
        let buscar =document.getElementById('criterio_usu_per').value;
        let valor = $('input:radio[name=criterio_valor]:checked').val();
let codigo =document.getElementById('criterio_usu_cod').value;
        let nombrePdf;

        if(valor=='Cantidades'){
            nombrePdf='rptProductos_bodegas';   
        }
        if(valor=='Valorados'){
            nombrePdf='rptProductos';
        }
        if(valor=='Areas'){
            nombrePdf='rptProductos_areas';
        }

        miUrl = "reportes/"+nombrePdf+".php?criterio_orden="+cOrden+"&criterio_mostrar="+cMostrar+"&criterio_tipo="+cTipo+"&criterio_ordenar_por="+ordenar+"&criterio_usu_per="+buscar+"&criterio_valor="+valor+"&criterio_ordenar_cod="+codigo;



        window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
    }

</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script> 
  <script>
//          // Give $ to prototype.js
         var $jq = jQuery.noConflict();
       </script>
       <script src="https://malsup.github.io/jquery.form.js"></script> 
       <script type="text/javascript">

        function agregar() {
            $('#loader').show(); 	
            let fd = new FormData();
            fd.append("file", document.getElementById("file").files[0]);
            fd.append("idBodega",  $('#idBodega').val() );
             fd.append("idArea",  $('#idArea').val() );
            
            fd.append("txtAccion", '21');

            fetch('sql/productos.php', {
                method: 'POST',
                body: fd
            })
           .then(function(response) {
            $('#loader').hide(); 	
            if (response.status >= 200 && response.status < 300) {
               
                return response.text()
            }
            throw new Error(response.statusText)
        })
  .then(function(response) {
   
    if(response.trim()=='1'){
        alertify.success('Registros guardados correctamente');
        console.log(response);
    }else{
        alertify.error('Error al  guardar los productos');
        alertify.error(response);
    }
           
        })
        }
        
        
        
    const reporteExcelProductos=()=>{
        var str = $("#form1").serialize();
        miUrl = "reportes/excel_productos.php?"+str;
        window.open(miUrl);
    } 
        function fn_cerrar(){
	$.unblockUI({ 
		onUnblock: function(){
			$("#div_oculto").html("");
		}
	});
}
      </script>
 <script src="https://code.jquery.com/jquery-3.2.1.js"></script>
    <script>
//          // Give $ to prototype.js
         var $jqry = jQuery.noConflict();
       </script>
       
<script>

    
    function imagen(idProducto) {
        var formData = new FormData();
        var files = $jqry('#image')[0].files[0];
        formData.append('file',files);
        formData.append('idProducto',idProducto);
        $jqry.ajax({
            url: '../sql/imagenProducto.php',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response != 0) {
                    $jqry(".card-img-top").attr("src", response);
                    alertify.success(response);
                    fn_cerrar();
                } else {
                    alert('Formato de imagen incorrecto.');
                    alertify.danger("Formato Incorrecto");
                }
            }
        });
        return false;
        
    }
    

    function actualizarCodigoProducto(id_producto, nuevo_codigo, codigo_anterior){

        var formData = new FormData();
        formData.append('txtAccion',22);
   
        formData.append('codigo',nuevo_codigo);
        formData.append('codigo_anterior',codigo_anterior);
        formData.append('id_producto',id_producto);
        $jqry.ajax({
            url: 'sql/productos.php',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                let respuesta =response.trim();

                if (respuesta ==1) {
                   
                    alertify.success('Se actualizo correctamente el c&oacute;digo del producto.');
                  
                } else {
            
                    alertify.danger("No se actualizo el c&oacute;digo del producto.");
                }
                listar_productos(1);
            }
        });
        return false;
    }
    </script>
      <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>	
    
</html>