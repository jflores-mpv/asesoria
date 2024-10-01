<?php

//	require_once('../ver_sesion.php');

	//Start session
	session_start();

	//Include database connection details
	require_once('../conexion.php');

        $sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
        $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
        $accion = $_POST['accion'];

         date_default_timezone_set('America/Guayaquil');

        
        if($accion == 1){
            //saca listado de las cuentas de bancos
           $cadena="";
           //consulta
            try {
               $consulta5="SELECT
                 bancos.`id_bancos` AS bancos_id_bancos,
                 bancos.`id_plan_cuenta` AS bancos_id_plan_cuenta,
                 bancos.`saldo_conciliado` AS bancos_saldo_conciliado,
                 bancos.`id_periodo_contable` AS bancos_id_periodo_contable,
                 plan_cuentas.`id_plan_cuenta` AS plan_cuentas_id_plan_cuenta,
                 plan_cuentas.`codigo` AS plan_cuentas_codigo,
                 plan_cuentas.`nombre` AS plan_cuentas_nombre,
                 plan_cuentas.`clasificacion` AS plan_cuentas_clasificacion,
                 plan_cuentas.`tipo` AS plan_cuentas_tipo,
                 plan_cuentas.`categoria` AS plan_cuentas_categoria,
                 plan_cuentas.`nivel` AS plan_cuentas_nivel,
                 plan_cuentas.`total` AS plan_cuentas_total,
                 plan_cuentas.`id_empresa` AS plan_cuentas_id_empresa,
                 plan_cuentas.`cuenta_banco` AS plan_cuentas_cuenta_banco,
                 periodo_contable.`id_periodo_contable` AS periodo_contable_id_periodo_contable,
                 periodo_contable.`fecha_desde` AS periodo_contable_fecha_desde,
                 periodo_contable.`fecha_hasta` AS periodo_contable_fecha_hasta,
                 periodo_contable.`estado` AS periodo_contable_estado,
                 periodo_contable.`ingresos` AS periodo_contable_ingresos,
                 periodo_contable.`gastos` AS periodo_contable_gastos,
                 periodo_contable.`id_empresa` AS periodo_contable_id_empresa
            FROM
                 `bancos` bancos INNER JOIN `plan_cuentas` plan_cuentas ON bancos.`id_plan_cuenta` = plan_cuentas.`id_plan_cuenta`
                 INNER JOIN `periodo_contable` periodo_contable ON bancos.`id_periodo_contable` = periodo_contable.`id_periodo_contable`
                 WHERE periodo_contable.`id_periodo_contable`='".$sesion_id_periodo_contable."' AND plan_cuentas.`cuenta_banco` > 1; ";
               echo $consulta5;
                $result=mysql_query($consulta5);
                while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
                    {
                        $cadena=$cadena."?".$row['bancos_id_bancos']."?".$row['plan_cuentas_nombre']." ".$row['plan_cuentas_cuenta_banco'];
                    }
               echo "".$cadena;

            }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
          }

     
        if($accion == 2){
          
            try {
                // VALIDACIONES PARA QUE EL NUMERO DE DOCUMENTO EN BANCOS NO SE REPITA
                if(isset ($_POST['numero_documento'])){
                  $numero_documento = $_POST['numero_documento'];
                  //echo "".$numero_documento;
                  $sql2 = "SELECT
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
                     detalle_bancos.`id_libro_diario` AS detalle_bancos_id_libro_diario,
                     detalle_bancos.`fecha` AS detalle_bancos_fecha
                FROM
                     `bancos` bancos INNER JOIN `detalle_bancos` detalle_bancos ON bancos.`id_bancos` = detalle_bancos.`id_bancos`
                      where bancos.`id_periodo_contable`='".$sesion_id_periodo_contable."' and detalle_bancos.`numero_documento`='".$numero_documento."';";
                  $resp2 = mysql_query($sql2);
                  $entro=0;
                  while($row2=mysql_fetch_array($resp2))//permite ir de fila en fila de la tabla
                            {
                                $var=$row2["detalle_bancos_numero_documento"];
                            }
                  if($var==$numero_documento){
                       if($var==""&&$numero_documento==""){
                             $entro=0;
                          }else{
                              $entro=1;
                          }
                  }
                 echo $entro;
                 }               

            }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
            
          }



          
        if($accion == 3){
            //saca el saldo total de todas las cuentas de bancos conciliados
            $saldoTotal3= 0;
            $cmbIdBancos = $_POST['cmbIdBancos'];
           
            try {
               $consulta3="SELECT
                 detalle_bancos.`id_detalle_banco` AS detalle_bancos_id_detalle_banco,
                 detalle_bancos.`tipo_documento` AS detalle_bancos_tipo_documento,
                 detalle_bancos.`numero_documento` AS detalle_bancos_numero_documento,
                 detalle_bancos.`detalle` AS detalle_bancos_detalle,
                 detalle_bancos.`valor` AS detalle_bancos_valor,
                 detalle_bancos.`fecha_cobro` AS detalle_bancos_fecha_cobro,
                 detalle_bancos.`fecha_vencimiento` AS detalle_bancos_fecha_vencimiento,
                 detalle_bancos.`id_bancos` AS detalle_bancos_id_bancos,
                 detalle_bancos.`estado` AS detalle_bancos_estado,
                 detalle_bancos.`id_libro_diario` AS detalle_bancos_id_libro_diario,
                 bancos.`id_bancos` AS bancos_id_bancos,
                 bancos.`id_plan_cuenta` AS bancos_id_plan_cuenta,
                 bancos.`saldo_conciliado` AS bancos_saldo_conciliado,
                 bancos.`id_periodo_contable` AS bancos_id_periodo_contable
            FROM
                 `bancos` bancos INNER JOIN `detalle_bancos` detalle_bancos ON bancos.`id_bancos` = detalle_bancos.`id_bancos`
                 WHERE detalle_bancos.`estado`='Conciliado' and bancos.`id_periodo_contable`='".$sesion_id_periodo_contable."' and bancos.`id_bancos`='".$cmbIdBancos."' order by detalle_bancos.`id_detalle_banco` asc;";
            //   echo $consulta3;
                $result3=mysql_query($consulta3);
                while($row3=mysql_fetch_array($result3))//permite ir de fila en fila de la tabla
                    {
                        $detalle_bancos_tipo_documento3=strtolower($row3['detalle_bancos_tipo_documento']);
                        $detalle_bancos_valor3=$row3['detalle_bancos_valor'];
                        $bancos_id_plan_cuenta3=$row3['bancos_id_plan_cuenta'];

                        if($detalle_bancos_tipo_documento3 == "cheque"){
                            $saldoTotal3 = $saldoTotal3 - $detalle_bancos_valor3;
                        }
                        if($detalle_bancos_tipo_documento3 == "deposito"){
                            $saldoTotal3 = $saldoTotal3 + $detalle_bancos_valor3;
                        }
                        if($detalle_bancos_tipo_documento3 == "nota de credito"){
                            $saldoTotal3 = $saldoTotal3 + $detalle_bancos_valor3;
                        }
                        if($detalle_bancos_tipo_documento3 == "nota de debito"){
                            $saldoTotal3 = $saldoTotal3 - $detalle_bancos_valor3;
                        }
                        if($detalle_bancos_tipo_documento3 == "transferencia"){
                            $saldoTotal3 = $saldoTotal3 + $detalle_bancos_valor3;
                        }
                        
                        if($detalle_bancos_tipo_documento3 == "transferenciac"){
                            $saldoTotal3 = $saldoTotal3 - $detalle_bancos_valor3;
                        }
                        
                    }
                $sqlBancos = "select * from bancos where id_bancos = '".$cmbIdBancos."'; ";
                $resultBancos=mysql_query($sqlBancos);
                while($rowB=mysql_fetch_array($resultBancos))//permite ir de fila en fila de la tabla
                    {
                        $id_plan_cuentaB=$rowB['id_plan_cuenta'];
                    }
                $sqlSacaCodigoCuenta = "select * from plan_cuentas where id_plan_cuenta='".$id_plan_cuentaB."'; ";
                $resultSCC33=mysql_query($sqlSacaCodigoCuenta);
                while($row33=mysql_fetch_array($resultSCC33))//permite ir de fila en fila de la tabla
                    {
                        $codigo33=$row33['codigo'];
                    }
               echo $saldoTotal3."ô".$codigo33;

            }catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
          }

           //
           
        if($accion == 4){           
            // CAMBIA EL ESTADO A CONCILIADOS
            echo "ACCION 4";
            $fecha = date("Y-m-d h:i");
            $id_detalle_bancos = $_POST['id_detalle_bancos'];
            try {
            $sqlActualizaDetalleBancos = "update detalle_bancos set estado='Conciliado', fecha='".$fecha."' where 
            id_detalle_banco='".$id_detalle_bancos."';";
            // echo "DETALLE".$sqlActualizaDetalleBancos."</br>";
            $resultActualizaDetalleBancos=mysql_query($sqlActualizaDetalleBancos);
            
            if ($resultActualizaDetalleBancos){

                ?><div class='alert alert-success'><p>Registro Conciliado correctamente.</p></div> <?php
            }else {
                ?> <div class='alert alert-danger'><p>Error al actualiza la tabla bancos. </p></div> <?php
            }
               

            }catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
          }

    if($accion == 5){
        //CAMBIA EL ESTADO A NO CONCILIADOS
        $fecha = "0000:00:00 00:00";
        $id_detalle_bancos = $_POST['id_detalle_bancos'];
        try {
        $sqlActualizaDetalleBancos = "update detalle_bancos set estado='No Conciliado', fecha='".$fecha."' where id_detalle_banco='".$id_detalle_bancos."';";
        $resultActualizaDetalleBancos=mysql_query($sqlActualizaDetalleBancos);
        
        if ($resultActualizaDetalleBancos){

            ?><div class='alert alert-success'><p>Registro No Conciliado correctamente.</p></div> <?php
        }else {
            ?> <div class='alert alert-danger'><p>Error al actualiza la tabla bancos. </p></div> <?php
        }

        }catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }

    }
      

    
     if($accion == 8){
        //CAMBIA EL ESTADO A NO CONCILIADOS
        $fecha = "0000:00:00 00:00";
        $id_detalle_bancos = $_POST['cmbNombreCuentaCB'];
        try {

            
            $fecha_desde = trim($_POST['fechaDesdeInfo'])==''?'0000-00-00':$_POST['fechaDesdeInfo'];
            $fecha_hasta = trim($_POST['fechaHastaInfo'])==''?'0000-00-00' :$_POST['fechaHastaInfo'] ;
            $valor_registrado = trim($_POST['valor_registrado'])==''?0:$_POST['valor_registrado'];
            $valor_sistema = trim($_POST['txtSaldoConsolidacionCB'])==''?0:$_POST['txtSaldoConsolidacionCB'];
            $cuenta_banco = trim($_POST['cmbNombreCuentaCB'])==''?0:$_POST['cmbNombreCuentaCB'];
            
              $dominio = $_SERVER['SERVER_NAME'];
        if($dominio=='dcacorp.com.ec' or $dominio=='www.dcacorp.com.ec' or $dominio=='contaweb.ec' or $dominio=='www.contaweb.ec'){ 
            
            $sql="SELECT `id_bancos`, `id_plan_cuenta`, `saldo_conciliado`, `id_periodo_contable` FROM `bancos` WHERE id_bancos = $id_detalle_bancos";
            $result = mysql_query($sql);
            while($rowC = mysql_fetch_array($result) ){
                $idplancuenta = $rowC['id_plan_cuenta'];
            }
            
            $sqlValidar="SELECT `id_info`, `fecha_desde`, `fecha_hasta`, `valor_registrado`, `valor_sistema`, `cuenta_banco`, `id_banco`, `id_empresa` FROM `info_detalle_bancos` WHERE id_empresa=$sesion_id_empresa and DATE_FORMAT(fecha_desde,'%Y-%m') = DATE_FORMAT('".$fecha_desde."','%Y-%m')  ; ";
            $resultValidar = mysql_query( $sqlValidar );
            $numFilas = mysql_num_rows( $resultValidar );
            if($numFilas==0){
                 $sqlInfoDetalleBanco = "INSERT INTO `info_detalle_bancos`( `fecha_desde`, `fecha_hasta`, `valor_registrado`, `valor_sistema`, `cuenta_banco`,id_empresa,id_banco) VALUES ('".$fecha_desde."','".$fecha_hasta."','".$valor_registrado."','".$valor_sistema."','".$idplancuenta."','".$sesion_id_empresa."','".$cuenta_banco."')";
            }else{
                $info_detalle_bancos=0;
                while($r2 = mysql_fetch_array($resultValidar) ){
                    $info_detalle_bancos = $r2['id_info'];
                }
                 $sqlInfoDetalleBanco = "UPDATE `info_detalle_bancos` SET `fecha_desde`='".$fecha_desde."',`fecha_hasta`='".$fecha_hasta."',`valor_registrado`='".$valor_registrado."',`valor_sistema`='".$valor_sistema."',`cuenta_banco`='".$idplancuenta."',`id_banco`='".$cuenta_banco."',`id_empresa`='".$sesion_id_empresa."' WHERE id_info=$info_detalle_bancos ";
            }
            // echo $sqlInfoDetalleBanco;
            
            $resultInfoDetalleBanco = mysql_query( $sqlInfoDetalleBanco );
            
        }

        }catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }

    }   
?>


