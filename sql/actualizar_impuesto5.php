<?php
exit;
  session_start();

    //Include database connection details
    require_once('../conexion.php');
    include('../conexion2.php');
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $accion = $_POST['txtAccion'];
    
    echo $sqlEmpresas="SELECT `id_empresa`, `ruc`, `contribuyente`, `nombre`, `razonSocial`, `direccion`, `pais`, `provincia`, `id_ciudad`, `telefono1`, `telefono2`, `email`, `pag_web`, `imagen`, `fecha_inicio`, `informacion_general`, `perfil_empresa`, `descripcion`, `mision`, `vision`, `actividad_empresa`, `codigo_empresa`, `caracter_identificacion`, `autorizacion_sri`, `id_tipo_empresa`, `estado`, `Redondeo`, `Cliente_id`, `Ocontabilidad`, `FElectronica`, `clave`, `cod_aula`, `leyenda`, `leyenda2`, `leyenda3`, `leyenda4`, `limiteFacturas`, `distribuidor`, `URL`, `rimpe`, `agente`, `formaPago`, `condicionesPago`, `pago`, `observacion`, `planOriginal`, `nombreContador`, `ruc_representante`, `ruc_contador` FROM `empresa` WHERE codigo_empresa in ('RIQ1','RIQ2')";
    
    $resultEmpresas = mysql_query($sqlEmpresas);
    while($rowEmp = mysql_fetch_array($resultEmpresas) ){
        $id_empresa = $rowEmp['id_empresa'];
        echo '<br>';
        echo '<br>';
       echo $sqlImp= "SELECT `id_iva`, `iva`, `estado`, `id_empresa`, `id_plan_cuenta`, `codigo` FROM `impuestos` WHERE id_empresa =$id_empresa AND codigo=4 ";
       echo '<br>';
        $resultImp = mysql_query($sqlImp);
          $id_iva =0;
            $iva =0;
        while($rowImp = mysql_fetch_array($resultImp) ){
            $id_iva = $rowImp['id_iva'];
            $iva = $rowImp['iva'];
        }
        echo '<br>';
       echo $sqlProductos="UPDATE `productos` SET `iva`='".$id_iva."' WHERE id_empresa=$id_empresa  ";
       echo '<br>';
        $resultProductos = mysql_query($sqlProductos);
    }