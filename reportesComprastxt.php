<?php
	//Start session
	session_start();
	require_once('ver_sesion.php');

	//Include database connection details
	require_once('conexion.php');

    $cookie_tipo = $_COOKIE['tipo_cookie'];
    $cookie_id_empleado = $_COOKIE['id_empleado_cookie'];

    $nombre = $_SESSION['sesion_nombre'];
    $apellido = $_SESSION['sesion_apellido'];
    $sesion_tipo = $_SESSION["sesion_tipo"];
    $sesion_id_empleado = $_SESSION["sesion_id_empleado"];
    $sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
   
    $sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
    ?>

<html>
<head>
 <link rel="shortcut icon" href="images/logo.png">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Reporte Facturas</title>

    <script src="https://kit.fontawesome.com/7816c50242.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="librerias/bootstrap/css/bootstrap.css">  
    <script src="librerias/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <script src="librerias/bootstrap/js/bootstrap.js"></script>
    <link rel="stylesheet" href="librerias/bootstrap/css/style.css"> 
    <link rel="stylesheet" type="text/css" href="librerias/alertifyjs/css/alertify.css">
    <script src="librerias/alertifyjs/alertify.js"></script>
    <script src="librerias/select2/js/select2.js"></script>
    <link rel="stylesheet" type="text/css" href="librerias/select2/css/select2.css">
    
    
    
    <script language="javascript" type="text/javascript" src="js/condominios.js"></script>
	<script language="javascript" type="text/javascript" src="js/index.js"></script>
    <!-- validaciones -->
    <script language="javascript" type="text/javascript" src="js/reportes.js"></script>
    <script language="javascript" type="text/javascript" src="js/validaciones.js"></script>
	
	<!-- reportes -->
    <script type="text/javascript">
        $(document).ready(function(){
            
            listar_reporte_facturas();
            
			$('#txtClientes').select2();
			
		});
    </script>

    <!--END estilo para  el menu -->

</head>

<body onload="combopais2(1); comboprovincia2(2); ">
    
    <div class="wrapper d-flex align-items-stretch celeste">
    
    <nav id="sidebar" ><?php  {include("menusNiveles.php");      }   ?></nav>
    
    <div id="content"  class="p-0 m-0">
    
    <div class="row m-0"><?php  {include("header.php");      }   ?>  </div>
 
    <div class="row  m-0 ">  
        <a class="col-lg-2 col-sm-2 text-decoration-none card p-1 m-1"  type="button"   id="btnNuevaFactura" name="btnNuevaFactura" onclick="location='nuevaFacturaCompra.php'" />
            <div class="my-icon3 rounded "><i class="fa fa-shopping-cart mr-3 fa-2x"></i><span>Compra</span>  </div>
        </a>
    </div>    

     
 

        
<div class="row">
<div class="col-lg-10 mx-5 table-responsive">
<?php
if(isset($_FILES['userfile'])) {
    $file = $_FILES['userfile'];

    // Verificar si es un archivo de texto
    if($file['type'] === 'text/plain') {
        // Leer el contenido del archivo
        $content = file_get_contents($file['tmp_name']);
        $lines = explode("\n", $content);

        // Mostrar contenido en tabla
        echo '<table class="table table-bordered bg-white">';
        echo '<thead class="thead-dark bg-success text-white"><tr><th>Guardar</th><th>RUC EMISOR</th><th>RAZON SOCIAL EMISOR</th><th>TIPO COMPROBANTE</th><th>SERIE COMPROBANTE</th><th>CLAVE ACCESO</th><th>FECHA AUTORIZACION</th><th>FECHA EMISION</th><th>IDENTIFICACION RECEPTOR</th><th>VALOR SIN IMPUESTOS</th><th>IVA</th><th>IMPORTE TOTAL</th><th>NUMERO DOCUMENTO MODIFICADO</th></tr></thead>';
        echo '<tbody>';
        $firstLine = true;
        foreach ($lines as $line) {
            // Omitir la primera línea
            if($firstLine) {
                $firstLine = false;
                continue;
            }
            
            $columns = explode("\t", $line); // Suponiendo que las columnas están separadas por tabulaciones (\t)

            // Agregar validación SQL
            $sqltxt1= "SELECT compras.id_compra AS compras_id_compra FROM compras,proveedores 
       where compras.id_proveedor=proveedores.id_proveedor 
       AND CONCAT(`numSerie`,'-',`txtEmision`,'-',`txtNum`)='".$columns[3]."' AND proveedores.ruc='".$columns[0]."' and compras.id_empresa='".$sesion_id_empresa."'";
            $sqltxt11 = mysql_query($sqltxt1);  
            $row0=mysql_fetch_array($sqltxt11);

            $idCompraTxt=$row0['compras_id_compra'];
            $color=($idCompraTxt>0)?"#ff0000":"#000";
            $boton = ($idCompraTxt>0)?"none":"block"; // Mostrar o ocultar el botón según el resultado de la validación
            
            echo "<tr id='fila{$fila}' style='color: {$color}; font-size: 13px;'>";
            echo "<td ><a onclick='Gcompras(\"{$columns[4]}\")'><button class='btn btn-success' style='display: {$boton};'>Guardar</button></a></td>"; // Botón al principio de la fila
            foreach ($columns as $column) {
                echo '<td>' . $column . '</td>'; // Mostrar cada columna
            }
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<div class="alert alert-danger" role="alert">El archivo seleccionado no es un archivo de texto.</div>';
    }
} else {
    echo '<div class="alert alert-danger" role="alert">No se ha seleccionado ningún archivo.</div>';
}
?>




</div></div>


        
        
<script>
function Gcompras(fila) {
    
    var claveAcceso = fila;
    // console.log("claveAcceso",claveAcceso)

    if (!claveAcceso) {
        console.log('El valor de la clave de acceso está vacío o indefinido.');
        return;
    }

    $.post("sql/xmltxt.php", { fila: claveAcceso }, function (data) {
        
        let response = data.trim();

        if (response.includes("Error")) {
        
            alertify.error('Error al registrar la compra.');
                
        }else if(response.includes('COMPRA YA FUE REGISTRADA')){
            console.log('Compra ya fue registrada.');
             alertify.error('Compra ya fue registrada.');
        } else {
            console.log('Compra registrada correctamente ');
            // console.log(response);
            alertify.success(response);
        }
        
    });
}

</script>


</body>
</html>

                      
                      
                         