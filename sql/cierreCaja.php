<?php
error_reporting(0);
require_once('../ver_sesion.php');

    //Start session
session_start();

    //Include database connection details
require_once('../conexion.php');

$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
$sesion_id_usuario = $_SESSION["sesion_id_usuario"];
$emision_codigo = $_SESSION["emision_codigo"];
$accion = $_POST['txtAccion'];

date_default_timezone_set('America/Guayaquil');

//   var_dump($_SESSION);
if($accion == '1'){
 $total=0;
 $valor = $_POST['valor'];
 $formaPago = $_POST['formaPago'];
 $detalle = $_POST['detalle'];
 
 
 
 $fecha = $_POST['fecha_cierre'];
//  $fecha=date('Y-m-d');
 $hora= $DateAndTime2 = date('h:i:s', time());  
 
 $sql0="SELECT `id`, `fecha`, `hora`, `id_usuario`, `total`, `caja` FROM `cierre_caja_encabezado` WHERE id_usuario =$sesion_id_usuario and DATE_FORMAT(fecha,'%Y-%m-%d')='".$fecha."'";
 $result0= mysql_query($sql0);
 $existeCabecera=  mysql_num_rows($result0);
 if($existeCabecera==0){
     $sqlNumCierre="SELECT cierre_caja_encabezado.`id`, cierre_caja_encabezado.`fecha`, cierre_caja_encabezado.`hora`, cierre_caja_encabezado.`id_usuario`, cierre_caja_encabezado.`total`, cierre_caja_encabezado.`caja`, MAX(cierre_caja_encabezado.`numero_cierre`) as ultimo_cierre FROM `cierre_caja_encabezado` INNER JOIN usuarios ON usuarios.id_usuario = cierre_caja_encabezado.id_usuario WHERE usuarios.id_empresa=$sesion_id_empresa;";
     $resultNumCierre = mysql_query($sqlNumCierre);
     while($rowCierre = mysql_fetch_array($resultNumCierre)  ){
         $ultimoCierre = $rowCierre['ultimo_cierre'];
     }
     $ultimoCierre++;
   $sql1= "INSERT INTO `cierre_caja_encabezado`(fecha,hora, `id_usuario`, `total`, `caja`, numero_cierre) VALUES ('".$fecha."','".$hora."','".$sesion_id_usuario."','".$total."','".$emision_codigo."','".$ultimoCierre."' )";
   $result1 = mysql_query($sql1);
   $idEncabezado = mysql_insert_id();
 }else{
   while($row= mysql_fetch_array($result0)){
     $idEncabezado = $row['id'];
   }
 }
 

 $sql2= "INSERT INTO `cierre_caja_detalle`( `detalle`, `id_forma`, `valor`,id_cierre) VALUES  ('".$detalle."','".$formaPago."','".$valor."','".$idEncabezado."')";
 $result2 = mysql_query($sql2);
 echo ($result2)?1:2;
 
}

if($accion == "5")
{
	

  $cont=$_POST['cont'];
// 			{
  $query = "SELECT `id_forma_pago`, `nombre`, `id_plan_cuenta`, `id_empresa`, `id_tipo_movimiento`, `diario`, `ingreso`, `egreso`, `pagar`, `tipo` FROM `formas_pago` WHERE `id_empresa`=$sesion_id_empresa  ";
  $result = mysql_query($query) or die(mysql_error());
  $numero_filas = mysql_num_rows($result); 
  if($result) 
  {
   if($numero_filas == 0)
   {
    echo "<div class='alert alert-danger'> No hay resultados con el parámetro ingresado. </div>";
  }
  else
  {
    echo "<table id='tblServicios' table class='table table-condensed table-hover' >";
    echo "<thead>";
    echo "<tr>";
    echo "<th style='padding-left: 5px; padding-right: 5px;'>#</th>  <th style='padding-left: 5px; padding-right: 5px;'>Forma Pago</th> <th style='padding-left: 5px; padding-right: 5px;'>Plan cuenta</th><th style='padding-left: 5px; padding-right: 5px;'><a href='javascript: fn_cerrar_div();'><button type='button' class='btn btn-default' aria-label='Left Align'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span></button></a></th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    
    while ($row = mysql_fetch_array($result))
    {
      echo '<tr onClick="fill10_formaPago(\''.$cont.'\','.$row["id_forma_pago"].',\''.$row["nombre"]."*".$row["id_plan_cuenta"].'\');" style="cursor: pointer" title="Clic para seleccionar">';
      echo "<td>".$row["id_forma_pago"]."</td>";
      echo "<td>".$row["nombre"]."</td>";
      echo "<td>".$row["id_plan_cuenta"]."</td>";

      echo "</tr>";
      
    }
    echo "</tbody>";
    echo"</table>";
    
  }
} else {
  echo 'ERROR: Hay un problema con la consulta.';
}
        // } else {
        //     echo 'La longitud no es la permitida.';
        // }
    // } else {
    //         echo 'No hay ningún acceso directo a este script!';
    // }
}