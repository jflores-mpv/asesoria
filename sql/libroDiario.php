<?php

	//require_once('../ver_sesion.php');

        date_default_timezone_set('America/Guayaquil');

	//Start session
	session_start();

	//Include database connection details
	require_once('../conexion.php');

        $txtAccion = $_POST['txtAccion'];
// echo "accion==".$txtAccion;
        $id_empresa_cookies = $_COOKIE["id_empresa"];
        $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
        $sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];

        date_default_timezone_set('America/Guayaquil');
       
    

if($txtAccion == 1){
// GUARDAR NUEVO ASIENTO CONTABLE     
try{
    
//    echo "LIBRO_DIARIO";
    
//echo ("guardar Asietno");

    $txtNumeroAsiento = $_POST['txtNumeroAsiento'];
    $contador_filas = $_POST['txtContadorFilas'];
    $total_debe = $_POST['txtDebeTotal'];
    $total_haber = $_POST['txtHaberTotal'];
    $descripcion = ucwords($_POST['txtDescripcion']);
    $centroCosto = ucwords($_POST['cmbCentro']);
    
    $txtContadorAsientosAgregados = $_POST['txtContadorAsientosAgregados'];
    
    if($txtContadorAsientosAgregados >= 2 && $contador_filas >=2 && $total_haber!= "" && $total_debe!= ""){

        if($total_debe == $total_haber ){
            $id_periodo_contable = $sesion_id_periodo_contable;
            $txtNumeroAsiento = $_POST['txtNumeroAsiento'];
            $hora = date("H:i:s");
            $fecha = $_POST['txtFecha']." ".$hora;
            $total_debe = $_POST['txtDebeTotal'];
            $total_haber = $_POST['txtHaberTotal'];
            $descripcion = ucwords($_POST['txtDescripcion']);
            $txtNumeroComprobante = $_POST['txtNumeroComprobante'];
            $cmbTipoComprobante = $_POST['cmbTipoComprobante'];
            $centroCosto = ucwords($_POST['cmbCentro']);
            //permite sacar el id maximo de libro_diario
            try{
                $sqli="Select max(id_libro_diario) From libro_diario";
                $result=mysql_query($sqli);
                $id_libro_diario=0;
                while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
                {
                    $id_libro_diario=$row['max(id_libro_diario)'];
                }
                $id_libro_diario++;

            }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php }

            //permite sacar el id maximo de comprobantes
            try{
                $sqlCM="Select max(id_comprobante) From comprobantes";
                $resultCM=mysql_query($sqlCM);
                $id_comprobante=0;
                while($rowCM=mysql_fetch_array($resultCM))//permite ir de fila en fila de la tabla
                {
                    $id_comprobante=$rowCM['max(id_comprobante)'];
                }
                $id_comprobante++;

            }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php }

            //GUARDA EN  COMPROBANTES
            $sqlC = "insert into comprobantes ( tipo_comprobante, numero_comprobante, id_empresa) values ('".$cmbTipoComprobante."','".$txtNumeroComprobante."','".$sesion_id_empresa."')";
			$respC = mysql_query($sqlC);
            $id_comprobante=mysql_insert_id();
//echo "<br>"."SQLC C ".$sqlC;

            //GUARDA EN EL LIBRO DIARIO
            $sql = "insert into libro_diario (id_periodo_contable, numero_asiento, fecha, total_debe, total_haber, descripcion, numero_comprobante, tipo_comprobante, 
            id_comprobante,centroCosto) values ('".$id_periodo_contable."','".$txtNumeroAsiento."','".$fecha."','".$total_debe."','".$total_haber."','".$descripcion."',
            '".$txtNumeroComprobante."','".$cmbTipoComprobante."', '".$id_comprobante."','".$centroCosto."')";
            $resp = mysql_query($sql);
			$id_libro_diario=mysql_insert_id();
//echo "<br>"."SQL ".$sql;            
            $contador_filas = $_POST['txtContadorFilas'];
            for($i=1; $i<=$contador_filas; $i++){
                

                if($_POST['txtIdCuenta'.$i] >= 1){ //verifica si en el campo esta agregada una cuenta

                    //permite sacar el id maximo de detalle_libro_diario
                    try {
                        $sqli="Select max(id_detalle_libro_diario) From detalle_libro_diario";
                        $result=mysql_query($sqli);
                        $id_detalle_libro_diario=0;
                        while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
                        {
                              $id_detalle_libro_diario=$row['max(id_detalle_libro_diario)'];
                        }
                        $id_detalle_libro_diario++;

                    }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max detalle_libro_diario: <?php echo "".$ex ?></p></div> <?php }
                
                    //$id_libro_diario = $_POST['txtNumeroAsiento'];
                    $id_plan_cuentas = $_POST['txtIdCuenta'.$i];
                    $debe = $_POST['txtDebe'.$i];
                    $haber = $_POST['txtHaber'.$i];
                    //GUARDA EN EL DETALLE LIBRO DIARIO
                    $sql2 = "insert into detalle_libro_diario (id_libro_diario, id_plan_cuenta, debe, haber, id_periodo_contable) values ('".$id_libro_diario."','".$id_plan_cuentas."','".$debe."','".$haber."','".$id_periodo_contable."');";
                    $resp2 = mysql_query($sql2);
                    
//echo "<br>"."SQL2 ".$sql2;   



                    // consulta para sacar el parametro total de la tabla plan_cuentas y saber si la cuenta esta en movimiento o no
                    $sql3 = "Select total From plan_cuentas where id_plan_cuenta='".$id_plan_cuentas."';";
                    $result3=mysql_query($sql3);
                    while($row3=mysql_fetch_array($result3))//permite ir de fila en fila de la tabla
                        {
                                $total=$row3['total'];
                        }
                        
                    
                    $total = $total + ($debe + $haber); //suma lo que se va a ingresar mas lo que ya esta en el parametro total
                    
                    
                    $sql4 = "update plan_cuentas set  total='".$total."' where id_plan_cuenta='".$id_plan_cuentas."';";
                    
                    $result4=mysql_query($sql4);

                    
                    

                    // CONSULTAS PARA GENERAR LA MAYORIZACION
                    $sql5 = "SELECT * FROM mayorizacion WHERE id_plan_cuenta ='".$id_plan_cuentas."' AND id_periodo_contable = '".$id_periodo_contable."';";
                    $result5=mysql_query($sql5);
                    while($row5=mysql_fetch_array($result5))//permite ir de fila en fila de la tabla
                        {
                            $id_mayorizacion=$row5['id_mayorizacion'];
                        }
                    $numero = mysql_num_rows($result5); // obtenemos el número de filas
                    if($numero > 0){
                           // si hay filas
                           
                    }else {
                        // no hay filas
                        //INSERCION DE LA TABLA MAYORIZACION
                        try {
                            //permite sacar el id maximo de la tabla mayorizacion
                            $sqli6="Select max(id_mayorizacion) From mayorizacion";
                            $resulti6=mysql_query($sqli6);
                            $id_mayorizacion=0;
                            while($row6=mysql_fetch_array($resulti6))//permite ir de fila en fila de la tabla
                            {
                                $id_mayorizacion=$row6['max(id_mayorizacion)'];
                            }
                            $id_mayorizacion++;

                            $sql6 = "insert into mayorizacion (id_mayorizacion,id_plan_cuenta, id_periodo_contable) 
                            values ('".$id_mayorizacion."','".$id_plan_cuentas."','".$id_periodo_contable."');";
                            $result6=mysql_query($sql6);
							$id_mayorizacion= mysql_insert_id();
                        }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la insercion de la tabla mayorizacion: <?php echo "".$ex ?></p></div> <?php }
                    //    echo "<br>"."SQL6 ".$sql6; 
                    }
                    //******************************* BANCOS  ****************************
                    if($_POST['txtCuentaBanco'.$i] > 1){ //verifica si es una cuenta de bancos
                        // GUARDAR BANCOS                       
                        
                        //permite sacar el id maximo de bancos
                        try{
                            $sqlmaxb="Select max(id_bancos) From bancos;";
                            $resultmaxb=mysql_query($sqlmaxb);
                            $id_bancos=0;
                            while($rowmaxb=mysql_fetch_array($resultmaxb))//permite ir de fila en fila de la tabla
                            {
                                $id_bancos=$rowmaxb['max(id_bancos)'];
                            }
                            $id_bancos++;

                        }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php }

                        try {
                            //permite sacar el id maximo de la tabla detalle_bancos
                            $sqlmaxdb="Select max(id_detalle_banco) From detalle_bancos;";
                            $resultmaxdb=mysql_query($sqlmaxdb);
                            $id_detalle_banco=0;
                            while($rowmaxdb=mysql_fetch_array($resultmaxdb))//permite ir de fila en fila de la tabla
                            {
                                $id_detalle_banco=$rowmaxdb['max(id_detalle_banco)'];
                            }
                            $id_detalle_banco++;

                        }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la insercion de la tabla mayorizacion: <?php echo "".$ex ?></p></div> <?php }

                        $cmbTipoDocumento=$_POST['cmbTipoDocumento'];
                        $txtNumeroDocumento=$_POST['txtNumeroDocumento'];
                        $txtDetalleDocumento=$_POST['txtDetalleDocumento'];
                        $txtFechaEmision=$_POST['txtFechaEmision'];
                        $txtFechaVencimiento=$_POST['txtFechaVencimiento'];
                        $saldo_conciliado = 0;
                        $valorConciliacion = $debe+$haber;
                        $estado = "No Conciliado";
                        //echo "fecha emision: ".$txtFechaEmision." fecha vencimiento: ".$txtFechaVencimiento;
                        // CONSULTAS PARA  BANCOS
                        $sqlb2 = "SELECT * FROM bancos WHERE id_plan_cuenta ='".$id_plan_cuentas."' AND id_periodo_contable = '".$id_periodo_contable."';";
                        $resultb2=mysql_query($sqlb2);
                        while($rowb2=mysql_fetch_array($resultb2))//permite ir de fila en fila de la tabla
                            {
                                $id_bancos2=$rowb2['id_bancos'];
                            }
                        $numero_fil = mysql_num_rows($resultb2); // obtenemos el número de filas
                        if($numero_fil > 0){
                           // si hay filas
                           // actualiza el saldo_conciliado
                            //$sql
                            //INSERCION DE LA TABLA DETALLE_BANCOS
                            $sqlDB = "insert into detalle_bancos ( tipo_documento, numero_documento, detalle, valor, fecha_cobro, fecha_vencimiento, id_bancos, estado, id_libro_diario) 
                            values ('".$cmbTipoDocumento."','".$txtNumeroDocumento."','".$txtDetalleDocumento."','".$valorConciliacion."','".$txtFechaEmision."','".$txtFechaVencimiento."','".$id_bancos2."', '".$estado."', '".$id_libro_diario."');";
                            $resultDB=mysql_query($sqlDB);
							$id_detalle_banco=mysql_insert_id();
                            //echo " solo detalle bancos: ".$sqlDB;
//echo "<br>"."SQL DB ".$sqlDB; 
                        }else {
                            // no hay filas
                            // INSERCION DE LA TABLA BANCOS
                            $sqlB = "insert into bancos ( id_plan_cuenta, saldo_conciliado, id_periodo_contable) values 
                            ('".$id_plan_cuentas."','".$saldo_conciliado."', '".$id_periodo_contable."');";
                            $resultB=mysql_query($sqlB);
							$id_bancos=mysql_insert_id();
                         //   echo " bancos: ".$sqlB;
//echo "<br>"."SQL B ".$sqlB; 
                            //INSERCION DE LA TABLA DETALLE_BANCOS
                            $sqlDB = "insert into detalle_bancos ( tipo_documento, numero_documento, detalle, valor, fecha_cobro, fecha_vencimiento, id_bancos, estado, id_libro_diario) values ('".$cmbTipoDocumento."','".$txtNumeroDocumento."','".$txtDetalleDocumento."','".$valorConciliacion."','".$txtFechaEmision."','".$txtFechaVencimiento."','".$id_bancos."', '".$estado."', '".$id_libro_diario."');";
                            $resultDB=mysql_query($sqlDB);
							$id_detalle_banco=mysql_insert_id();
                            //echo " detalle bancos:  ".$sqlDB;


                        }
                        
                    }

                    
                    
                }
                
            }// for recorre cada linea agregada

            

            if($resp & $respC){
                if($resp2 & $result4){
                   echo "1";
                }else{
                   echo "2";
                }
            }else {
               echo "3";
            }

        }else{
            echo "4";
        }

    }else{
        echo "5";
    }


}catch (Exception $e) {
// Error en algun momento.
   ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
} 

}

	
// GUARDAR MODIFICACION LIBRO DIARIO Y DETALLE LIBRO DIARIO
if($txtAccion == 2){

    try{

        $id_libro_diario2 = $_POST['txtIdLibroDiario'];
        $contador_filas2 = $_POST['txtContadorFilas2'];
        $descripcion = ucwords($_POST['txtDescripcion']);
        $txtPeriodoContable = $_POST['txtPeriodoContable'];
        $txtDebeTotal2 = $_POST['txtDebeTotal2'];
        $txtHaberTotal2 = $_POST['txtHaberTotal2'];
        $txtFecha = $_POST['txtFecha'];

        if($txtDebeTotal2 == $txtHaberTotal2 ){
             //actualiza la tabla libro_diario
             $sqldes = "update libro_diario set descripcion='".$descripcion."', fecha='".$txtFecha."', total_debe='".$txtDebeTotal2."', total_haber='".$txtHaberTotal2."' where id_libro_diario='".$id_libro_diario2."';";
             $resultdes=mysql_query($sqldes);

             //elimina los detalles del libro diario para volver a guardarlos
             $sqlelimina = "Delete From detalle_libro_diario where id_libro_diario='".$id_libro_diario2."';";
             $resultelimina=mysql_query($sqlelimina);

             for($i=1; $i<=$contador_filas2; $i++){
                //permite sacar el id maximo de detalle_libro_diario
                try {
                    $sqlmaxi="Select max(id_detalle_libro_diario) From detalle_libro_diario";
                    $resultmax=mysql_query($sqlmaxi);
                    $id_detalle_libro_diario = 0;
                    while($rowmax=mysql_fetch_array($resultmax))//permite ir de fila en fila de la tabla
                    {
                        $id_detalle_libro_diario=$rowmax['max(id_detalle_libro_diario)'];
                    }
                    $id_detalle_libro_diario++;

                }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max detalle_libro_diario: <?php echo "".$ex ?></p></div> <?php }

                 $txtIdCuenta = $_POST['txtIdCuenta'.$i];
                 $txtDebe = $_POST['txtDebe'.$i];
                 $txtHaber = $_POST['txtHaber'.$i];
                 $txtCuentaBanco = $_POST['txtCuentaBanco'.$i];
                //GUARDA EN EL DETALLE LIBRO DIARIO
                $sqldld = "insert into detalle_libro_diario (id_libro_diario, id_plan_cuenta, debe, haber, id_periodo_contable) values ('".$id_libro_diario2."','".$txtIdCuenta."','".$txtDebe."','".$txtHaber."','".$txtPeriodoContable."');";
                $respdld = mysql_query($sqldld);
                $id_detalle_libro_diario=mysql_insert_id();
				//echo ''.$sql2;
                
            }
                      

             if($resultdes){
                 if($resultelimina && $respdld){
                     ?> <div class='transparent_ajax_correcto'><p>Registros modificados correctamente.</p></div> <?php

                 }else { ?> <div class='transparent_ajax_error'><p>Error al actualiza la tabla detalle_libro_diario. </p></div> <?php }

               }else{ ?> <div class='transparent_ajax_error'><p>Error al actualiza la tabla libro_diario. </p></div> <?php }

        } else{
        ?> <div class='transparent_ajax_error'><p>El Debe y el Haber deben cumplir la partida doble. </p></div><?php
        }

    }catch (Exception $e) {
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
    }

}


if($txtAccion == 22){
    // GUARDAR ASIENTO CONTABLE MODIFICACION LIBRO DIARIO Y DETALLE LIBRO DIARIO
    try{

        $id_libro_diario2 = $_POST['txtIdLibroDiario'];
        $contador_filas2 = $_POST['txtContadorFilas'];
        $descripcion2 = ucwords($_POST['txtDescripcion']);
        $txtPeriodoContable2 = $sesion_id_periodo_contable;
        $txtDebeTotal2 = $_POST['txtDebeTotal'];
        $txtHaberTotal2 = $_POST['txtHaberTotal'];
        $cmbTipoComprobante2 = $_POST['cmbTipoComprobante'];
        $txtNumeroAsiento2 = $_POST['txtNumeroAsiento'];
        $id_periodo_contable = $sesion_id_periodo_contable;
        
        $hora2 = date("H:i:s");
        $txtFecha2 = $_POST['txtFecha']." ".$hora2;
        $txtContadorAsientosAgregados2 = $_POST['txtContadorAsientosAgregados'];
        if($txtContadorAsientosAgregados2 >= 2 && $contador_filas2 >=2 ){
            
            if($txtDebeTotal2 == $txtHaberTotal2 ){
                 //actualiza la tabla libro_diario
                 //elimina los detalles del libro diario para volver a guardarlos
                 
$sqlelimina22 = "DELETE FROM detalle_libro_diario WHERE id_libro_diario='$id_libro_diario2';";
$resultelimina22 = mysql_query($sqlelimina22);

if (!$resultelimina22) {
     echo $sqlelimina22 . "</br>";
    die("Error al eliminar de detalle_libro_diario: " . mysql_error());
    
} else {
    // echo "detalle eliminado" . "</br>";
}
                 
$sql22 = "UPDATE libro_diario SET 
    descripcion='$descripcion2', 
    fecha='$txtFecha2', 
    total_debe='$txtDebeTotal2', 
    total_haber='$txtHaberTotal2', 
    tipo_comprobante='$cmbTipoComprobante2' 
    WHERE id_libro_diario='$id_libro_diario2';";

$result22 = mysql_query($sql22);

if (!$result22) {
    die("Error al actualizar libro_diario: " . mysql_error());
} else {
    // echo $sql22 . "</br>";
}
 
 
 
$sqleliminaMayor = "DELETE FROM mayorizacion WHERE id_plan_cuenta='$txtIdCuenta2' AND id_periodo_contable='$id_periodo_contable';";
$resulteliminaMayor = mysql_query($sqleliminaMayor);

if (!$resulteliminaMayor) {
    die("Error al eliminar de mayorizacion: " . mysql_error());
} else {
    // echo $sqleliminaMayor . "</br>";
}


                 for($i=1; $i<=$contador_filas2; $i++){
                     
                     

                     if($_POST['txtIdCuenta'.$i] >= 1){ //verifica si en el campo esta agregada una cuenta
                     
                        //permite sacar el id maximo de detalle_libro_diario
                        try {
                            $sqlmaxi="Select max(id_detalle_libro_diario) From detalle_libro_diario";
                            $resultmax=mysql_query($sqlmaxi);
                            $id_detalle_libro_diario2 = 0;
                            while($rowmax=mysql_fetch_array($resultmax))//permite ir de fila en fila de la tabla
                            {
                                $id_detalle_libro_diario2=$rowmax['max(id_detalle_libro_diario)'];
                            }
                            $id_detalle_libro_diario2++;

                        }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max detalle_libro_diario: <?php echo "".$ex ?></p></div> <?php }

                         $txtIdCuenta2 = $_POST['txtIdCuenta'.$i];
                         $txtDebe2 = $_POST['txtDebe'.$i];
                         $txtHaber2 = $_POST['txtHaber'.$i];

                        //GUARDA EN EL DETALLE LIBRO DIARIO
                        $sqldld22 = "insert into detalle_libro_diario ( id_libro_diario, id_plan_cuenta, debe, haber, id_periodo_contable) 
                        values ('".$id_libro_diario2."','".$txtIdCuenta2."','".$txtDebe2."','".$txtHaber2."','".$txtPeriodoContable2."');";
                        $respdld22 = mysql_query($sqldld22);
                        $id_detalle_libro_diario2=mysql_insert_id();
                        
                        if($respdld22){
                            
                            $existeMayorizacion="SELECT * FROM `mayorizacion` WHERE id_plan_cuenta='".$txtIdCuenta2."' ";
                            $resultadoexisteMayorizacion = mysql_query($existeMayorizacion);
                        
                            	$filasMayorizacion=mysql_num_rows($resultadoexisteMayorizacion);
                            	if ($filasMayorizacion>0)     		{	
                            		    
                            		    ?>  
                            		    <!--<div class='alert alert-danger'><p>Registro ya existe en mayorizacion</p></div>-->
                            		    <?php	
                            		    
                            		   
                            		}else{
                            	    
                            	    ?>
                            	    <!--<div class='alert alert-danger'><p>Registro no existe en mayorizacion</p></div> -->
                            	    <?php
                            	    
                            	    $sql6 = "insert into mayorizacion (id_mayorizacion,id_plan_cuenta, id_periodo_contable) 
                                    values (NULL,'".$txtIdCuenta2."','".$id_periodo_contable."');";
                                    $result6=mysql_query($sql6);
    							    $id_mayorizacion= mysql_insert_id();
    							    
                                    }
                            	}
						

                            if($_POST['txtCuentaBanco'.$i] > 1){ //verifica si es una cuenta de bancos
                                // GUARDAR BANCOS

                                //permite sacar el id maximo de bancos
                                try{
                                    $sqlmaxb="Select max(id_bancos) From bancos;";
                                    $resultmaxb=mysql_query($sqlmaxb);
                                    $id_bancos=0;
                                    while($rowmaxb=mysql_fetch_array($resultmaxb))//permite ir de fila en fila de la tabla
                                    {
                                        $id_bancos=$rowmaxb['max(id_bancos)'];
                                    }
                                    $id_bancos++;

                                    }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max libro_diario: <?php echo "".$ex ?></p></div> <?php }

                                try {
                                    //permite sacar el id maximo de la tabla detalle_bancos
                                    $sqlmaxdb="Select max(id_detalle_banco) From detalle_bancos;";
                                    $resultmaxdb=mysql_query($sqlmaxdb);
                                    $id_detalle_banco=0;
                                    while($rowmaxdb=mysql_fetch_array($resultmaxdb))//permite ir de fila en fila de la tabla
                                    {
                                        $id_detalle_banco=$rowmaxdb['max(id_detalle_banco)'];
                                    }
                                    $id_detalle_banco++;

                                }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la insercion de la tabla mayorizacion: <?php echo "".$ex ?></p></div> <?php }

                                $cmbTipoDocumento=$_POST['cmbTipoDocumento'];
                                $txtNumeroDocumento=$_POST['txtNumeroDocumento'];
                                $txtDetalleDocumento=$_POST['txtDetalleDocumento'];
                                $txtFechaEmision=$_POST['txtFechaEmision'];
                                $txtFechaVencimiento=$_POST['txtFechaVencimiento'];
                                $saldo_conciliado = 0;
                                $valorConciliacion = $txtDebe2+$txtHaber2;
                                $txtIdBancos=$_POST['txtIdBancos'];
                                $txtIdDetalleBancos=$_POST['txtIdDetalleBancos'];
                                $estado = "No Conciliado";

                                // CONSULTAS PARA  BANCOS
                                $sqlb2 = "SELECT * FROM bancos WHERE id_plan_cuenta ='".$txtIdCuenta2."' AND id_periodo_contable = '".$sesion_id_periodo_contable."';";
                                $resultb2=mysql_query($sqlb2);
                                while($rowb2=mysql_fetch_array($resultb2))//permite ir de fila en fila de la tabla
                                    {
                                        $id_bancos2=$rowb2['id_bancos'];
                                    }
                                    
                                $numero_fil = mysql_num_rows($resultb2); // obtenemos el número de filas
                                if($numero_fil > 0){
                                    // si hay filas                                    
                                    // CONSULTAS PARA  DETALLE BANCOS
                                    $sqldb2 = "SELECT * FROM detalle_bancos WHERE id_detalle_banco ='".$txtIdDetalleBancos."';";                                    
                                    $resultdb2=mysql_query($sqldb2);
                                    while($rowdb2=mysql_fetch_array($resultdb2))//permite ir de fila en fila de la tabla
                                        {
                                            $id_detalle_banco2=$rowdb2['id_detalle_banco'];
                                        }

                                    $numero_filasdb2 = mysql_num_rows($resultdb2); // obtenemos el número de filas
                                    if($numero_filasdb2 > 0){
                                        // ACTUALIZA LA TABLA DETALLE BANCOS                                        
                                        $sqlDB = "update detalle_bancos set tipo_documento='".$cmbTipoDocumento."', numero_documento='".$txtNumeroDocumento."', detalle='".$txtDetalleDocumento."', valor='".$valorConciliacion."', fecha_cobro='".$txtFechaEmision."', fecha_vencimiento='".$txtFechaVencimiento."', id_bancos='".$id_bancos2."', id_libro_diario='".$id_libro_diario2."' where id_detalle_banco='".$id_detalle_banco2."';";                                        
                                        $resultDB=mysql_query($sqlDB) or die(mysql_error());
                                    }else{
                                        //INSERCION DE LA TABLA DETALLE_BANCOS
                                        $sqlDB = "insert into detalle_bancos ( tipo_documento, numero_documento, detalle, valor, fecha_cobro, fecha_vencimiento, id_bancos, estado, id_libro_diario) values ('".$cmbTipoDocumento."','".$txtNumeroDocumento."','".$txtDetalleDocumento."','".$valorConciliacion."','".$txtFechaEmision."','".$txtFechaVencimiento."','".$id_bancos2."', '".$estado."', '".$id_libro_diario2."');";
                                        $resultDB=mysql_query($sqlDB) or die(mysql_error());
                                    }
                                    
  
                                }else {
                                    // no hay filas
                                    // INSERCION DE LA TABLA BANCOS
                                    $sqlB = "insert into bancos (id_plan_cuenta, saldo_conciliado, id_periodo_contable) values ('".$id_plan_cuentas."','".$saldo_conciliado."', '".$id_periodo_contable."');";
                                    $resultB=mysql_query($sqlB) or die(mysql_error());                                    
									$id_bancos=mysql_insert_id();
                                    //INSERCION DE LA TABLA DETALLE_BANCOS
                                    $sqlDB = "insert into detalle_bancos (tipo_documento, numero_documento, detalle, valor, fecha_cobro, fecha_vencimiento, id_bancos, estado, id_libro_diario) values ('".$cmbTipoDocumento."','".$txtNumeroDocumento."','".$txtDetalleDocumento."','".$valorConciliacion."','".$txtFechaEmision."','".$txtFechaVencimiento."','".$id_bancos."', '".$estado."', '".$id_libro_diario2."');";
                                    $resultDB=mysql_query($sqlDB) or die(mysql_error());  
									$id_detalle_banco=mysql_insert_id();                                  

                                }

                            }

                        //*************************************************************************************************************************
                      }
                  }

                 if($result22){
                     if($resultelimina22 && $respdld22 ){
                         ?> <div class='alert alert-success'><p>Asiento Nro. <?php echo $txtNumeroAsiento2; ?> modificado correctamente. (Por favor espere que el mensaje desaparezca para continuar)</p></div> <?php

                     }else { ?> <div class='transparent_ajax_error'><p>Error al actualiza la tabla detalle_libro_diario. </p></div> <?php }

                   }else{ ?> <div class='transparent_ajax_error'><p>Error al actualiza la tabla libro_diario. </p></div> <?php }

            } else{
            ?> <div class='transparent_ajax_error'><p>El Debe y el Haber deben cumplir la partida doble. </p></div><?php
            }

      }else{
            ?> <div class="transparent_notice"><p>No hay suficientes cuentas para guardar.</p></div> <?php
        }

    }catch (Exception $e) {
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
    }

}

if($txtAccion == 3){
    // ELIMINA ASIENTO CONTABLE
    $id_libro_diario = $_POST['id_libro_diario'];
    $sqlConsulta3 = "SELECT
     libro_diario.`id_libro_diario` AS libro_diario_id_libro_diario,
     libro_diario.`id_periodo_contable` AS libro_diario_id_periodo_contable,
     libro_diario.`numero_asiento` AS libro_diario_numero_asiento,
     libro_diario.`fecha` AS libro_diario_fecha,
     libro_diario.`total_debe` AS libro_diario_total_debe,
     libro_diario.`total_haber` AS libro_diario_total_haber,
     libro_diario.`descripcion` AS libro_diario_descripcion,
     libro_diario.`numero_comprobante` AS libro_diario_numero_comprobante,
     libro_diario.`tipo_comprobante` AS libro_diario_tipo_comprobante,
     libro_diario.`id_comprobante` AS libro_diario_id_comprobante,
     detalle_libro_diario.`id_detalle_libro_diario` AS detalle_libro_diario_id_detalle_libro_diario,
     detalle_libro_diario.`id_libro_diario` AS detalle_libro_diario_id_libro_diario,
     detalle_libro_diario.`id_plan_cuenta` AS detalle_libro_diario_id_plan_cuenta,
     detalle_libro_diario.`debe` AS detalle_libro_diario_debe,
     detalle_libro_diario.`haber` AS detalle_libro_diario_haber,
     detalle_libro_diario.`id_periodo_contable` AS detalle_libro_diario_id_periodo_contable,
     comprobantes.`id_comprobante` AS comprobantes_id_comprobante,
     comprobantes.`tipo_comprobante` AS comprobantes_tipo_comprobante,
     comprobantes.`numero_comprobante` AS comprobantes_numero_comprobante,
     comprobantes.`id_empresa` AS comprobantes_id_empresa
FROM
     `libro_diario` libro_diario INNER JOIN `detalle_libro_diario` detalle_libro_diario ON 
     libro_diario.`id_libro_diario` = detalle_libro_diario.`id_libro_diario`
     INNER JOIN `comprobantes` comprobantes ON libro_diario.`id_comprobante` = comprobantes.`id_comprobante`
     Where libro_diario.`id_libro_diario`='".$id_libro_diario."';";
    $result3 = mysql_query($sqlConsulta3) or die(mysql_error());
    while($row3=mysql_fetch_array($result3))//permite ir de fila en fila de la tabla
    {
        $detalle_libro_diario_id_plan_cuenta=$row3['detalle_libro_diario_id_plan_cuenta'];
        $libro_diario_id_comprobante=$row3['libro_diario_id_comprobante'];
        //$sqlEliminaMayores = "Delete From mayorizacion Where id_periodo_contable='".$sesion_id_periodo_contable."' and id_plan_cuenta='".$detalle_libro_diario_id_plan_cuenta."';";
        //$result31 = mysql_query($sqlEliminaMayores) or die(mysql_error());
    }
    $sqlEliminaComprobantes = "Delete From comprobantes Where id_comprobante='".$libro_diario_id_comprobante."' and id_empresa='".$sesion_id_empresa."';";
    $result32 = mysql_query($sqlEliminaComprobantes) or die(mysql_error());
        
    $sql33 = "delete from libro_diario where id_libro_diario='".$id_libro_diario."';";
	
	if(!mysql_query($sql33))
	{
		echo "2";
    //echo "Ocurrio un error\n$sql";
    }
	else
	{
       	$sql33 = "delete from detalle_libro_diario where id_libro_diario='".$id_libro_diario."';";
		if(!mysql_query($sql33))
		{
			echo "2";
			//echo "Ocurrio un error\n$sql";
		}
		else
		{
			echo "1";
			
        //echo "La Transaccion ha sido Eliminada.";
		}
    }
	
/* 	if(!mysql_query($sql33)){
        ?> <div class='transparent_ajax_correcto'><p>Ocurrio un error: <?php echo $sql33;?></p></div> <?php
        //echo "Ocurrio un error\n$sql";
    }
	else
	{
        ?> <div class='transparent_ajax_correcto'><p>El Asiento Contable ha sido Eliminado.</p></div> <?php
        //echo "La Transaccion ha sido Eliminada.";
    } */

}

if($txtAccion == 4){
    // VACIAR LA TABLA LIBRO DIARIO
        $periodo = $_POST['periodo'];
        $consulta6="delete from `libro_diario` where id_periodo_contable='".$periodo."';";
        $result6=mysql_query($consulta6);
        if($result6){
            $consulta7="delete from `mayorizacion` where id_periodo_contable='".$periodo."';";
            $result7=mysql_query($consulta7);
            if($result6){
                echo "Se han eliminado las transacciones del Libro Diario y Mayorizacion del periodo Actual";
            }
        }else{
            echo "Error al vaciar la tabla libro_diario";
        }

   }

if($txtAccion == 5){
    // BUSQUEDA DE CUENTAS CONTABLES
    try {
    // esta pagina retorna en la pagina: asientosContables.php y modificarTransaccion.php

    // Is there a posted query string?
    if(isset($_POST['queryString'])) {
            $queryString = $_POST['queryString'];
            $aux = $_POST['aux'];
            // Is the string length greater than 0?
            $a=0;
            if(strlen($queryString) >0) {
    
                $query = "SELECT * FROM plan_cuentas WHERE id_empresa='".$sesion_id_empresa."' and ( plan_cuentas.codigo LIKE '$queryString%' OR plan_cuentas.nombre LIKE '%$queryString%')  
                order by plan_cuentas.codigo asc LIMIT 20;";
                $result = mysql_query($query) or die(mysql_error());
                $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
                if($result) {
                    if($numero_filas == 0){
                            //echo "<option> No hay resulados con el parámetro ingresado. </option>";
                            echo "<div class='alert alert-danger'> No hay resultados con el parámetro ingresado. </div>";
                     }else{
                         
                      echo "<ul class='list-group text-left'>";     
                        while ($row = mysql_fetch_assoc($result)) {
                            $tipo=$row['tipo'];
                            $cuenta_banco=$row['cuenta_banco'];
                            
                          
//                                $cantidad=substr($canti,0,1);
                            if($a == 0){
                                //echo "<option onClick='nuevoPlanCuentas();'>Crear Nueva Cuenta</option>";
                              echo "
                              <a href='javascript: fn_cerrar_div();'> <span class='glyphicon glyphicon-remove' aria-hidden='true'></span></a>
                              <br>";
                            }
                            if($tipo == 'Agrupacion'){
                                
                                echo '<li class="list-group-item disabled"> ';
                                
                                echo $row["codigo"]." - ".$row["nombre"];
                                
                                echo "</li>";
                                $a++;
                            }else{
                                echo '<li class="list-group-item" onclick="fill2(\''.$row["codigo"]." - ".$row["nombre"]." ".$row["cuenta_banco"].'\','.$aux.','.$row["id_plan_cuenta"].',\''.$row["cuenta_banco"].'\');">'.$row["codigo"]." - ".$row["nombre"]." ".$row["cuenta_banco"].'</li>';
                                $a++;
                            }
                                
                        } 
                        
                        echo "</ul>";  
                    }

                }else {
                        echo 'ERROR: Hay un problema con la consulta.';
                }
            } else {
                echo 'La longitud no es la permitida.';
                    // Dont do anything.
            } // There is a queryString.
    } else {
            echo 'No hay ningún acceso directo a este script!';
    }

    }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
}


if($txtAccion == 6){
    //BUQUEDA DE CUENTAS CONTABLES PARA LA EDICION O ELIMINAR
    try {
    // esta pagina retorna en la pagina: asientosContables.php 
    
    if(isset($_POST['queryString'])) {
            $queryString = $_POST['queryString'];
            //$aux = $_POST['aux'];
            
            $a=0;
            if(strlen($queryString) >0) {

                    $query6 = "SELECT
     libro_diario.`id_libro_diario` AS libro_diario_id_libro_diario,
     libro_diario.`id_periodo_contable` AS libro_diario_id_periodo_contable,
     libro_diario.`numero_asiento` AS libro_diario_numero_asiento,
     libro_diario.`fecha` AS libro_diario_fecha,
     libro_diario.`total_debe` AS libro_diario_total_debe,
     libro_diario.`total_haber` AS libro_diario_total_haber,
     libro_diario.`descripcion` AS libro_diario_descripcion,
     libro_diario.`numero_comprobante` AS libro_diario_numero_comprobante,
     libro_diario.`tipo_comprobante` AS libro_diario_tipo_comprobante,
     detalle_libro_diario.`id_detalle_libro_diario` AS detalle_libro_diario_id_detalle_libro_diario,
     detalle_libro_diario.`id_libro_diario` AS detalle_libro_diario_id_libro_diario,
     detalle_libro_diario.`id_plan_cuenta` AS detalle_libro_diario_id_plan_cuenta,
     detalle_libro_diario.`debe` AS detalle_libro_diario_debe,
     detalle_libro_diario.`haber` AS detalle_libro_diario_haber,
     detalle_libro_diario.`id_periodo_contable` AS detalle_libro_diario_id_periodo_contable,
     plan_cuentas.`id_plan_cuenta` AS plan_cuentas_id_plan_cuenta,
     plan_cuentas.`codigo` AS plan_cuentas_codigo,
     plan_cuentas.`nombre` AS plan_cuentas_nombre,
     plan_cuentas.`clasificacion` AS plan_cuentas_clasificacion,
     plan_cuentas.`tipo` AS plan_cuentas_tipo,
     plan_cuentas.`categoria` AS plan_cuentas_categoria,
     plan_cuentas.`nivel` AS plan_cuentas_nivel,
     plan_cuentas.`total` AS plan_cuentas_total,
     plan_cuentas.`id_empresa` AS plan_cuentas_id_empresa,
     plan_cuentas.`cuenta_banco` AS plan_cuentas_cuenta_banco
FROM
     `libro_diario` libro_diario INNER JOIN `detalle_libro_diario` detalle_libro_diario ON libro_diario.`id_libro_diario` = detalle_libro_diario.`id_libro_diario`
     INNER JOIN `plan_cuentas` plan_cuentas ON detalle_libro_diario.`id_plan_cuenta` = plan_cuentas.`id_plan_cuenta`
                        WHERE plan_cuentas.`id_empresa`='".$sesion_id_empresa."' and libro_diario.`numero_asiento` = '$queryString' order by detalle_libro_diario.`id_detalle_libro_diario` asc; ";
                    
                    $result6 = mysql_query($query6) or die(mysql_error());
                    $numero_filas = mysql_num_rows($result6); // obtenemos el número de filas
                    //echo $query6;
                    if($result6) {
                        if($numero_filas == 0){// cuando no hay datos envia 0
                            echo $numero_filas;
                                //echo "<center><p><label> No hay resulados con el parámetro ingresado. </label></p></center>";
                         }else{
                             $cadena = "";
                             $cadenaBancos = "";
                            while ($row = mysql_fetch_assoc($result6)) {
                                
                                if($row['plan_cuentas_cuenta_banco'] > 1){
                                    // consulta los bancos de la cuenta
                                    $sqlBancos = "SELECT
                                     bancos.`id_bancos` AS bancos_id_bancos,
                                     bancos.`id_plan_cuenta` AS bancos_id_plan_cuenta,
                                     bancos.`saldo_conciliado` AS bancos_saldo_conciliado,
                                     bancos.`id_periodo_contable` AS bancos_id_periodo_contable,
                                     detalle_bancos.`id_detalle_banco` AS detalle_bancos_id_detalle_banco,
                                     detalle_bancos.`tipo_documento` AS detalle_bancos_tipo_documento,
                                     detalle_bancos.`numero_documento` AS detalle_bancos_numero_documento,
                                     detalle_bancos.`detalle` AS detalle_bancos_detalle,
                                     detalle_bancos.`valor` AS detalle_bancos_valor,
                                     detalle_bancos.`fecha_cobro` AS detalle_bancos_fecha_cobro,
                                     detalle_bancos.`fecha_vencimiento` AS detalle_bancos_fecha_vencimiento,
                                     detalle_bancos.`id_bancos` AS detalle_bancos_id_bancos,
                                     detalle_bancos.`estado` AS detalle_bancos_estado,
                                     detalle_bancos.`id_libro_diario` AS detalle_bancos_id_libro_diario
                                FROM
                                     `bancos` bancos INNER JOIN `detalle_bancos` detalle_bancos ON bancos.`id_bancos` = detalle_bancos.`id_bancos`
                                     WHERE bancos.`id_periodo_contable`='".$sesion_id_periodo_contable."' and bancos.`id_plan_cuenta`='".$row['plan_cuentas_id_plan_cuenta']."' and detalle_bancos.`id_libro_diario`='".$row['libro_diario_id_libro_diario']."' ;";
                                    $resultBancos = mysql_query($sqlBancos) or die(mysql_error());
                                    while ($rowBancos = mysql_fetch_assoc($resultBancos)) {
                                        $cadenaBancos=$rowBancos['bancos_id_bancos']."?".$rowBancos['detalle_bancos_id_detalle_banco']."?".$rowBancos['detalle_bancos_tipo_documento']."?".$rowBancos['detalle_bancos_numero_documento']."?".$rowBancos['detalle_bancos_detalle']."?".$rowBancos['detalle_bancos_fecha_cobro']."?".$rowBancos['detalle_bancos_fecha_vencimiento'];
                                    }
                                }
                                
                                
                                $cadena=$cadena."*".$numero_filas."?".$row['detalle_libro_diario_id_detalle_libro_diario']."?".$row['plan_cuentas_codigo']."?".$row['plan_cuentas_nombre']."?".$row['plan_cuentas_cuenta_banco']."?".$row['detalle_libro_diario_debe']."?".$row['detalle_libro_diario_haber']."?".$row['libro_diario_descripcion']."?".$row['libro_diario_fecha']."?".$row['libro_diario_numero_comprobante']."?".$row['libro_diario_id_libro_diario']."?".$row['plan_cuentas_id_plan_cuenta']."?".$row['libro_diario_tipo_comprobante'];

                            }
                            echo $cadenaBancos."î".$cadena;
                            //echo $cadena;
                        }

                    } else {
                            echo 'ERROR: Hay un problema con la consulta.';
                    }
            } else {
                echo 'La longitud no es la permitida.';
                    // Dont do anything.
            } // There is a queryString.
    } else {
            echo 'No hay ningún acceso directo a este script!';
    }

    }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
}

if($txtAccion == 7){
    // SACA EL NUMERO DE COMPROBANTE DEPENDIENDO DEL TIPO DE COMPROBANTE pag: asientosContables.php
    try{
        $tipoComprobante = $_POST['tipoComprobante'];
        $consulta7="SELECT
         max(numero_comprobante) AS max_numero_comprobante
    FROM
         `comprobantes` comprobantes
       WHERE comprobantes.`id_empresa` = '".$sesion_id_empresa."' AND  comprobantes.`tipo_comprobante` = '".$tipoComprobante."' ;";
        $result7=mysql_query($consulta7);
        $cadena7 = 0;
        while($row7=mysql_fetch_array($result7))//permite ir de fila en fila de la tabla
            {
                $cadena7=$row7['max_numero_comprobante'];
            }
        echo $cadena7= $cadena7+1;

    }catch (Exception $e) {
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
    }

}


// echo "ACCION==>".$txtAccion;
if($txtAccion=="8"){
    // echo "PERIO CONTABLE==>".$sesion_id_periodo_contable;
    
    $idPlanCuenta= $_POST['planCuenta'];
     $sql="SELECT id_bancos FROM `bancos` WHERE `id_periodo_contable`='$sesion_id_periodo_contable' and `id_plan_cuenta`=$idPlanCuenta";
    $result = mysql_query($sql);
    $idBanco='';
    while($row = mysql_fetch_array($result)){
        $idBanco = $row['id_bancos'];
    }
    
    
                $tipoDocumento = $_POST['tipoDocumento'];
        
                // for($i=1; $i<=$contador_filas2; $i++){
                $txtContadorAsientosAgregados = $_POST['txtContadorAsientosAgregados'];
                // echo "cuenta ==".$_POST['txtIdCuenta'.$i];   
                
                
             $sqlnumbanco="Select max(numero_documento) From detalle_bancos 
                
                INNER JOIN `bancos` bancos ON detalle_bancos.`id_bancos` = bancos.`id_bancos`
                
                where tipo_documento='".$tipoDocumento."' and  bancos.id_periodo_contable='".$sesion_id_periodo_contable."' and  bancos.`id_bancos`=$idBanco";
                
                // echo '|';
                $resultnumbanco=mysql_query($sqlnumbanco);
                $numdocumento=0;
                while($rowBANCO=mysql_fetch_array($resultnumbanco))//permite ir de fila en fila de la tabla
                {
                    $numdocumento=$rowBANCO['max(numero_documento)'];
                }
                echo $numdocumento+1;
    // }
}

?>