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
        $sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];

        //PERMISOS AL MODULO ASIENTOS CONTABLES
        $sesion_bancos_guardar = $_SESSION['sesion_bancos_guardar'];

        
?>

<html lang="es-ES">
    
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>ANEXO TRANSACCIONAL</title>
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
     <script src="js/ats.js"></script>


</head>

<body >
    
<div class="wrapper d-flex align-items-stretch celeste">
    <nav id="sidebar" ><?php  {include("menusNiveles.php");      }   ?></nav>
<div id="content"  >
    <header ><?php  {include("header.php");      }   ?>  </header>

   

    <div class="row p-5 bg-white mt-3 text-info r-10 pt-3">

        <form name="frmComprasats" id="frmComprasats" method="post" action="javascript: listar_compras();" >
        <div class="row mt-4">
            <div class="col-lg-2">
                <h3>MES:</h3>
            </div>
            <div class="col-lg-2">
                <select  id="mes" name="mes" required="required" onchange="imprimir()"  class="form-select"  > 
                <option value="01">Enero</option>
                <option value="02">Febrero</option>
                <option value="03">Marzo</option>
                <option value="04">Abril</option>
                <option value="05">Mayo</option>
                <option value="06">Junio</option>
                <option value="07">Julio</option>
                <option value="08">Agosto</option>
                <option value="09">Septiembre</option>
                <option value="10">Octubre</option>
                <option value="11">Noviembre</option>
                <option value="12">Diciembre</option>
                
                </select>
            </div>
            <div class="col-lg-2">
                <select  id="anio" name="anio" required="required"  class="form-select"  > 
                <option value="2021">2021</option>
                <option value="2022">2022</option>
                </select>
            </div>
            <div class="col-lg-2">
                 <p id="fecha" class="text-info">
            </div>
               
            <div class="col-lg-2">
                    <a class="btn btn-success" onclick="anexo()" value="Generar XML">Generar XML</a>
                    
<script>
    function imprimir(){
            var mes=document.getElementById("mes").value
            var anio=document.getElementById("anio").value
            var fecha = anio+"-"+mes;
            document.getElementById("fecha").innerText = fecha;
    }
    function anexo(){
        
            var mes=document.getElementById("mes").value
            var anio=document.getElementById("anio").value
            
            window.open('reportes/reporteXMLanexo.php?mes='+ mes+'&anio='+anio);
            window.location.href='reportes/DescargaXML.php?';
    }

    
</script>


            </div>
          <!--   <a type="submit" value='Buscar' tabindex='5' id='submit' onClick="listar_compras()" 
             class="botonListado glyphicon glyphicon-ok" name='btnBuscar' align="right" /> </a> -->
        </div>
        
        
        </form>
        <div id="div_listar_compras">     </div>

    </div>
   
</div>
</div>
  <script type="text/javascript">
	$(document).ready(function(){
		$('#compras').load('ajax/atsCompras.php');
   
	});
</script>
    

	<script src="librerias/bootstrap/js/main.js"></script>   
</body>
</html>

