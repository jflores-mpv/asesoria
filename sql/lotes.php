<?php

    require_once('../ver_sesion.php');

    //Start session
    session_start();

    //Include database connection details
    require_once('../conexion.php');

    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $accion = $_POST['txtAccion'];
  //  echo "accion".$accion;
 
    $sesion_punto = $_SESSION['userpunto'];
	$sesion_id_est = $_SESSION['userest'];
   
   date_default_timezone_set('America/Guayaquil');
   
   if($accion == '1'){

        $numero_lote=trim($_POST['txtNumeroLote']);
        $txtFechaElaboracion_lote=$_POST['txtFechaElaboracion_lote'];
        $txtFechaCaducidad_lote=$_POST['txtFechaCaducidad_lote'];
        $txtCalidad_lote=$_POST['txtCalidad_lote'];
        $estado_lote=$_POST['estado_lote'];
        $txtFechaRegistro_lote=$_POST['txtFechaRegistro_lote'];
        $txtDetalle_lote=$_POST['txtDetalle_lote'];
        
        $sqlValidacion = "SELECT `id_lote`, `numero_lote`, `fecha_elaboracion`, `fecha_caducidad`, `calidad_lote`, `estado_lote`, `fecha_registro`, `detalle`, `id_empresa` FROM `lotes` WHERE id_empresa=$sesion_id_empresa AND numero_lote='".$numero_lote."' ";
        $resultValidacion  = mysql_query($sqlValidacion);
        $numFilas = mysql_num_rows($resultValidacion);
        if($numFilas>0){
            echo '5';
            exit;
        }
        $sql1 = "INSERT INTO `lotes`( `numero_lote`, `fecha_elaboracion`, `fecha_caducidad`, `calidad_lote`, `estado_lote`, `fecha_registro`, `detalle`, `id_empresa`) VALUES ('".$numero_lote."','".$txtFechaElaboracion_lote."','".$txtFechaCaducidad_lote."','".$txtCalidad_lote."','".$estado_lote."','".$txtFechaRegistro_lote."','".$txtDetalle_lote."','".$sesion_id_empresa."')";
		$resultado = mysql_query($sql1);
		$id_lote = mysql_insert_id();
		if ($resultado)
		{
		    echo '1';
		     $date= date('Y-m-d h:i:s');
		        $sqlEstado = "INSERT INTO `historial_estados_lote`( `id_estado`, `id_lote`, `fecha_estado`,id_estado_anterior) VALUES ('".$estado_lote."','".$id_lote."','".$date."','0')";
		        $resultEstado = mysql_query($sqlEstado);
		}else{
		    echo '2';
		} 	

   }
     if($accion == '2'){

        $numero_lote=$_POST['txtNumeroLote'];
        $id=$_POST['id'];
         $txtFechaElaboracion_lote=$_POST['txtFechaElaboracion_lote'];
        $txtFechaCaducidad_lote=$_POST['txtFechaCaducidad_lote'];
        $txtCalidad_lote=$_POST['txtCalidad_lote'];
        $estado_lote=$_POST['estado_lote'];
        $txtFechaRegistro_lote=$_POST['txtFechaRegistro_lote'];
        $txtDetalle_lote=$_POST['txtDetalle_lote'];
        $estado_actual=$_POST['estado_actual'];

	    $sql1 = "UPDATE `lotes` SET `numero_lote`='".$numero_lote."',`fecha_elaboracion`='".$txtFechaElaboracion_lote."',`fecha_caducidad`='".$txtFechaCaducidad_lote."',`calidad_lote`='".$txtCalidad_lote."',`estado_lote`='".$estado_lote."',`fecha_registro`='".$txtFechaRegistro_lote."',`detalle`='".$numero_lote."',`numero_lote`='".$numero_lote."',`detalle`='".$txtDetalle_lote."' WHERE id_lote ='".$id."'  AND id_empresa='".$sesion_id_empresa."' ";

		$resultado = mysql_query($sql1);
		if ($resultado)
		{
		    echo '3';
		    if($estado_actual!=$estado_lote){
		        $date= date('Y-m-d h:i:s');
		        $sqlEstado = "INSERT INTO `historial_estados_lote`( `id_estado`, `id_lote`, `fecha_estado`,id_estado_anterior) VALUES ('".$estado_lote."','".$id."','".$date."','".$estado_actual."')";
		        $resultEstado = mysql_query($sqlEstado);
		    }
		}else{
		    echo '4';
		} 	

   }
if($accion == '3'){


        $id=$_POST['id'];
        
        $sqlEstado = "SELECT lotes.`id_lote`, estado_lote.estado
        FROM `lotes` 
        INNER JOIN estado_lote ON estado_lote.id_estado = lotes.estado_lote 
        WHERE id_lote ='".$id."' AND estado_lote.estado='creado'";
        $resultEstado = mysql_query($sqlEstado);
        $numFilas = mysql_num_rows($resultEstado);
        if($numFilas>0){
               $sql1 = "DELETE FROM `lotes`  WHERE id_lote ='".$id."'  AND id_empresa='".$sesion_id_empresa."' ";
    
    		$resultado = mysql_query($sql1);
    		if ($resultado)
    		{
    		    echo '1';
    		}else{
    		    echo '2';
    		} 	 
        }else{
            echo '3';
        }
	    

   }
   if($accion == '4'){



        $sqlEstado = "SELECT lotes.`id_lote`, lotes.`numero_lote`, estado_lote.estado
                      FROM `lotes` 
                      INNER JOIN estado_lote ON estado_lote.id_estado = lotes.estado_lote 
                      WHERE lotes.id_empresa='".$sesion_id_empresa."' AND estado_lote.estado='creado'";
        
        $resultEstado = mysql_query($sqlEstado);
        
        if (!$resultEstado) {
            die('Consulta fallida: ' . mysql_error());
        }
        
        $numFilas = mysql_num_rows($resultEstado);
        if ($numFilas > 0) {
            while ($row = mysql_fetch_assoc($resultEstado)) {
                echo "<option value='{$row["id_lote"]}'>{$row["numero_lote"]}</option>";
            }
        } else {
            echo "<option value='0'>No existen lotes creados</option>";
        }


   }
   
   if($accion == '5'){
       $filas = $_POST['filas'];
       $calidad= $_POST['txtCalidad'.$filas];
       $sql="INSERT INTO `calidad_lote`( `calidad`, `id_empresa`) VALUES ('".$calidad."','".$sesion_id_empresa."')";
       $result = mysql_query($sql);
       if($result){
           echo '1';
       }else{
           echo '2';
       }
   }
   if($accion == '6'){
       $id = $_POST['id'];
       $filas = $_POST['filas'];
       $calidad= $_POST['txtCalidad'.$filas];
       $sql="UPDATE `calidad_lote` SET `calidad`='".$calidad."',`id_empresa`='".$sesion_id_empresa."' WHERE id_calidad=$id";
       $result = mysql_query($sql);
       if($result){
           echo '3';
       }else{
           echo '4';
       }
   }
   if($accion == '7'){
       $id = $_POST['id'];
       $validar = "SELECT lotes.id_lote , `id_calidad`
       FROM lotes 
       INNER JOIN `calidad_lote` on lotes.calidad_lote = calidad_lote.id_calidad
       WHERE id_calidad =$id ";
       $resultValidar= mysql_query($validar);
       $numFilas = mysql_num_rows($resultValidar);
       if($numFilas>0){
           echo '3';
           exit;
       }
        $sql="DELETE FROM `calidad_lote` WHERE `id_calidad`=$id ";
       $result = mysql_query($sql);
       if($result){
           echo '1';
       }else{
           echo '2';
       }
   }
   if($accion == "8"){
    
    	$consulta5="SELECT `id_calidad`, `calidad`, `id_empresa` FROM `calidad_lote` WHERE id_empresa='".$sesion_id_empresa."' ";
        $result=mysql_query($consulta5);
        while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
        {
        echo "<option value='{$row["id_calidad"]}'   >{$row["calidad"]}</option>";
        }
}
  ?> 