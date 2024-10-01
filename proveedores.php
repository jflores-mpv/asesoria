<?php
	require_once('ver_sesion.php');

	//Start session
	session_start();		
	 $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
?>

<html lang="es-ES">
    
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Proveedores</title>
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
    <script src="librerias/bootstrap/js/main.js"></script>    
    
    <!-- START ESTILOS Y CLASES PARA AJAX -->
    <script language="javascript" type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
    <script language="javascript" type="text/javascript" src="js/jquery.blockUI.js"></script>
    <script language="javascript" type="text/javascript" src="js/jquery.validate.1.5.2.js"></script>
    <script language="javascript" type="text/javascript" src="js/index.js"></script>
    <script language="javascript" type="text/javascript" src="js/proveedor.js"></script>
    <!-- END ESTILOS Y CLASES PARA AJAX -->

    <!-- estilos para tablas -->
    <link href="css/listadoTablas.css" rel="stylesheet" type="text/css" />

    <!-- FUNCIONES -->
    <script type="text/javascript" src="js/funciones.js"></script>
        
    <!--validaciones de los campos -->
    <script type="text/javascript" src="js/validaciones.js"></script>
     

	<!--END estilo para  el menu -->
<link rel="shortcut icon" href="images/logoAlexsys.png">
</head>


<body onload="listar_proveedores(1);">
    
    <div class="wrapper d-flex align-items-stretch celeste">
        
        <nav id="sidebar" ><?php  {include("menusNiveles.php");      }   ?></nav>
        
        <div id="content"  class="p-0 m-0">
        
        <div class="row m-0"><?php  {include("header.php");      }   ?>  </div>
        
        <div class="row m-0 ">
            
            <a class="col-lg-3 col-sm-2 text-decoration-none p-1"  type="button"  id="btnNuevaFactura" name="btnNuevaFactura" onclick="location='nuevoProveedor.php'" target="_blank" />
                <div class="my-icon3 rounded  bg-white "><i class="fa fa-user-plus fa-2x"></i><span>Nuevo Proveedor</span>  </div>
            </a>
              
            <div class="col-lg-9 col-sm-2 p-1">
                
                <form action="" method="post"  name="frmExcelImport" id="frmExcelImport" enctype="multipart/form-data">
                    <div class="input-group  bg-white">
                        <span class="input-group-text" id="basic-addon1">Elija Archivo Proveedores</span>
                        <input type="file" class="form-control" name="file" id="file" accept=".xls,.xlsx">
                        <button type="submit" id="submit" name="import"  class="btn btn-outline-secondary">Importar Registros</button>
                        <a href="sql/subidas/proveedores/FormatoProveedor.xlsx"  class="btn btn-outline-secondary"  download >Descargar Formato</a>
                    </div>
                </form>
                
            </div>
            
        </div>   
    
        <form name="form1" id="form1" method="post" action="javascript: listar_proveedores(1);">
        
        <div class="row">
            <div class="col-lg-3 bg-white">
                <div class="row">
                    <div class="col-lg-12">
                        <label>Nombre:</label>
                        <input class="form-control" name="criterio_usu_per" type="search" id="criterio_usu_per" placeholder="Descripcion" 
                        title="Ingrese palabra para buscar" onChange="javascript: listar_proveedores(1);" onKeyUp="javascript: listar_proveedores(1);"/>
                    </div>
                    <div class="col-lg-12">    
                        <label>Ordenar: </label>
                        <select class="form-select" name="criterio_ordenar_por" id="criterio_ordenar_por" onChange="javascript: listar_proveedores(1);">
                                    <option value="proveedores.`nombre_comercial`">Nombre</option>
                                    <option value="proveedores.`id_ciudad`">Ciudad</option>
                                    <option value="proveedores.`ruc`"> RUC</option>
                                    
                        </select>
                    </div>
                    <div class="col-lg-12">
                        <label>Registros: </label>
                        <select class="form-select" name="criterio_mostrar" id="criterio_mostrar" onChange="javascript: listar_proveedores(1);">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="20" selected="selected">20</option>
                                    <option value="40">40</option>
                                    <option value="100">100</option>
                                    <option value="400000000">Todos</option>
                        </select>
                    </div>
                    <div class="col-lg-12">
                        <label>En: </label>
                        <select class="form-select" name="criterio_orden" id="criterio_orden" onChange="javascript: listar_proveedores(1);">
                            <option value="asc">Ascendente</option>
                            <option value="desc">Descendente</option>
                        </select>
                    </div>
                    <div class="col-lg-12 my-2">
                        <input id="button" type="button" value="REPORTE" class="btn btn-info text-white w-100" onclick="pdfProveedor()">
                    </div>
                    <div class="col-lg-12 my-2">
                        <input type="button" tabindex="5" name="submit" value="Excel Reporte Excel" id="" class="btn btn-outline-warning w-100" align="right" onclick="javascript: reporteExcelProveedores();">
                    </div>
                </div>
            </div>
            <div class="col-lg-9 bg-white">
                <div id="div_listar_proveedores" ></div>
            </div>
        </div>
        </form>
        
        <div id="div_oculto" style="display: none;"></div>     
            
        </div>
    </div>	
    
<script src="librerias/bootstrap/js/main.js"></script>   
</body>

<script type="text/javascript">

function reporteExcelProveedores(){
    var str = $("#form1").serialize();
        miUrl = "reportes/excel_proveedores.php?"+str;
        window.open(miUrl); 
}
function pdfProveedor(){
    let formulario  = $("#form1").serialize();
    
      miUrl = "reportes/rpt_Proveedores.php?"+formulario;
        window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
}

function abrirPdfProveedor(){

    miUrl = "reportes/rptProveedores.php?criterio_usu_per="+document.getElementById("criterio_usu_per").value+"&criterio_ordenar_por="+document.getElementById("criterio_ordenar_por").value+"&criterio_orden="+document.getElementById("criterio_orden").value+"&criterio_mostrar="+document.getElementById("criterio_mostrar").value;

    window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
}

function pdfInfoProveedor(id_proveedores){

miUrl = "reportes/rptInfoProveedores.php?id_proveedores="+id_proveedores;

 window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
}

  
  const formulario = document.querySelector('#frmExcelImport');

formulario.addEventListener('submit', (evento) => {
  evento.preventDefault(); // Evitar el comportamiento predeterminado del envío del formulario

//   const datos = new FormData(formulario);

  let fd = new FormData();
            fd.append("file", document.getElementById("file").files[0]);
            fd.append("txtAccion", '9');


  fetch('sql/proveedores.php', {
    method: 'POST',
    body: fd
  })
  .then(respuesta => {
  // Procesar la respuesta del servidor
  if (respuesta.ok) {
    // La solicitud fue exitosa (código de estado 200-299)
    return respuesta.text(); // Convertir la respuesta a JSON
  } else {
    throw new Error('Error en la solicitud');
  }
})
.then(data => {
  // Utilizar los datos recibidos del servidor
//   console.log(data);
  if(data.trim()=='Datos del proveedor guardados correctamente'){
	alertify.success('Datos del proveedor guardados correctamente');
	
  }else{
	alertify.error(data);
  }
listar_proveedores();
  // Hacer algo con los datos, como actualizar la interfaz de usuario
})
.catch(error => {
  // Manejar errores de la solicitud
  console.error('Error:', error);
});
});  
</script>
 
 
 
</html>