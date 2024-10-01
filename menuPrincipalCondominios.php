<!DOCTYPE html>
<?php
error_reporting(0);
session_start();
include'conexion2.php';
require('ver_sesion.php');
$sesion_id_institucion_educativa= $_SESSION["sesion_id_institucion_educativa"];
$sesion_aula = $_SESSION["sesion_cod_aula"];
 date_default_timezone_set('America/Guayaquil');
$sesion_id_nivel_institucion = $_SESSION["sesion_id_nivel_institucion"];
$niveles=mysqli_query($conexion, "SELECT id_nivel,nombre from nivel_de_institucion where id_institucion_educativa= '$sesion_id_institucion_educativa'" ); 
$fcha = date("Y-m-d");
$fecha = date("Y-m-d H:i:s");
$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
$sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];

$sesion_cod_activacion_prof = $_SESSION["sesion_cod_activacion"];
$sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];

 
       $fecha_desde = date('Y-m-01 00:00:00'); 
       $fecha_hasta = date('Y-m-d 23:59:59'); 

?>
<html lang="es-ES">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Menú Principal</title>
    
    
    <script src="https://kit.fontawesome.com/7816c50242.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="librerias/bootstrap/css/bootstrap.css">  
    <link rel="stylesheet" href="librerias/bootstrap/css/style.css"> 

    <script src="librerias/jquery-3.2.1.min.js"></script>

    <script src="js/save.js"></script>
    <script src="librerias/bootstrap/js/bootstrap.js"></script>
    <!-- <script src="librerias/alertifyjs/alertify.js"></script> -->
    <!-- <script src="librerias/select2/js/select2.js"></script> -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/iconos.css">
    <script language="javascript" type="text/javascript" src="js/index.js"></script>
    

    <script src="js/jquery.validate.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@latest/dist/Chart.min.js"></script>
    
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-5P7EFT7PXC"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-5P7EFT7PXC');
    </script>
    	<link type="text/css" rel="stylesheet" href="css/calendario.css" media="screen"/>
	<script language="javascript" type="text/javascript" src="js/calendario.js"></script>
	  <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border: none;
            border-radius: 15px;
        }
        .transaction {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .transaction img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 1rem;
        }
        .transaction .details {
            flex-grow: 1;
        }
        .transaction .amount {
            font-weight: bold;
            font-size: 1.25rem;
        }
        .pagination {
            justify-content: center;
        }
        .statistics {
            background-color: #fff;
            padding: 1rem;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
	 <style>
    .card-custom {
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      border-radius: 10px;
      color: white;
      height: 100px;
    }
    .total-invoices { background-color: #28a745; }
    .paid-invoices { background-color: #17a2b8; }
    .unpaid-invoices { background-color: #ffc107; }
    .total-invoices-sent { background-color: #dc3545; }
  </style>
  <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .balance-amount {
            font-size: 2rem;
            font-weight: bold;
        }
        .balance-box {
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
            margin-bottom: 1rem;
        }
        .balance-box.primary {
            background-color: #007bff;
            color: white;
        }
        .balance-box.secondary {
            background-color: #f8f9fa;
            color: #6c757d;
        }
        .balance-box .label {
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        .balance-box .amount {
            font-size: 1.2rem;
            font-weight: bold;
        }
    </style>
</head>

<body onLoad="sendDates(1)">
<div class="wrapper d-flex align-items-stretch celeste">
    
    <nav id="sidebar" ><?php  {include("menusNiveles.php");      }   ?></nav>
    
    <div id="content"  class="p-0 m-0">
    
    <div class="row m-0"><?php  {include("header.php");      }   ?>  </div>



      <div class="row  m-0 border bg-white rounded py-3">  

                <?php $years = range(2010, strftime("%Y", time())); ?>
                
         <div class="col-auto">
            <label for="inputPassword6" class="col-form-label">Seleccione a&ntilde;o:</label>
          </div>
          <div class="col-auto">
           <select  id="anio" name="anio" required="required"  class="form-select"onChange="cargarGraficos(1);"  > 

            <?php foreach($years as $year) { ?>
            <option value="<?php echo $ano_actual = $year; ?>"><?php echo $year; ?></option>
            <?php  }?>

            </select>
        </div>
    </div>
 
    
    <div class="row  m-0 ">  
        <div class="row pl-4 r-10 p-1 pt-1 ">
            <div class="col-lg-5  m-auto">
                <canvas id="grafica" class="card p-2"></canvas>
            </div>
            <div class="col-lg-5 offset-lg-1  m-auto ">
                <canvas id="grafica2" class="card p-2" ></canvas>
            </div>
        </div>
       
            <div class="row pl-4 r-10 p-1 pt-1 ">
            <div class="col-lg-5  m-auto  ">
            <label class="text-left">Desde: </label> 
                        <input type="text" tabindex="1" id="txtFechaDesde" value="<?php echo $fecha_desde ; ?>"
                        class="form-control" required="required" name="txtFechaDesde" onChange="cargarGraficos(2);" onClick="displayCalendar(txtFechaDesde,'yyyy-mm-dd',this)"/>
            </div>
            <div class="col-lg-5 offset-lg-1  m-auto ">
            <label class="text-left">Hasta: </label> 
                        <input type="text" tabindex="2" id="txtFechaHasta" value="<?php echo date("Y-m-d",time()); ?>" class="form-control"
                        required="required" name="txtFechaHasta"onChange="cargarGraficos(2)" onClick="displayCalendar(txtFechaHasta,'yyyy-mm-dd',this)"/>
            </div>
        </div>

    </div>    

  
    
</div>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>

<script language="javascript" type="text/javascript" src="js/chart.js"></script>
<!-- <script language="javascript" type="text/javascript" src="js/chart2.js"></script> -->
<script src="librerias/bootstrap/js/main.js"></script> 
<script language="javascript" type="text/javascript" src="js/main.js"></script>
<script>
    $(function() {  
    $("#anio option[value='2023']").attr("selected",true);
    cargarGraficos(3);
});
</script>
</body>
</html>


