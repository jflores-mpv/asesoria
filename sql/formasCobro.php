<?php
    require_once('../ver_sesion.php');

    //Start session
    session_start();

    //Include database connection details
    require_once('../conexion.php');

    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];

    $accion = $_POST['txtAccion'];
 
   
if($accion == '1'){
    // GUARDA FORMA DE PAGO PAGINA: ajax/formaPago.php
    try
    {
         
        $numeroFilaSelec=$_POST['numeroFilaSelec'];
        
        $txtNombre=ucwords($_POST['txtNombre'.$numeroFilaSelec]);
        $txtCodigo=$_POST['txtCodigo'.$numeroFilaSelec];
        $txtCuenta2=$_POST['txtCuenta2'.$numeroFilaSelec];
        $cmbTipoMovimientoFVC=$_POST['cmbTipoMovimientoFVC'.$numeroFilaSelec];
        $txtIdCuenta=$_POST['txtIdCuenta'.$numeroFilaSelec];
     
		$txtPorcentaje=$_POST['txtPorcentaje'.$numeroFilaSelec];
		//$codigo_sri=$_POST['txtCodigoSri'.$numeroFilaSelec];
		$codigo_sri=$_POST['txtCodigoSri'.$numeroFilaSelec];
		
	//	echo "WSRI";
	//	echo $codigo_sri;
		$sql="Select nombre,id_tipo_mov_cpra from tipo_movim_cpra where id_tipo_mov_cpra=".$cmbTipoMovimientoFVC; 
// 		echo $sql;
		$resp1 = mysql_query($sql);
        $txtTipo="";
        while($row1=mysql_fetch_array($resp1))//permite ir de fila en fila de la tabla
        {
            $txtTipo=$row1["nombre"];
            $idmovieminto=$row1["id_tipo_mov_cpra"];
        }        
        
        
        if($idmovieminto=='1' or $idmovieminto=='15' or $idmovieminto=='16' ){
            $codigo = '1';
        }else{
            $codigo = '2';
        }
        
        
        
        
        
		
        //if($txtIdCuenta != "" && $txtNombre != ""){
          if( $txtNombre != ""){
            //permite sacar el id maximo de formas de pago
            try {
                $sqlp11="Select max(id) From enlaces_compras";
                $resultp11=mysql_query($sqlp11);
                $id=0;
                while($rowp11=mysql_fetch_array($resultp11))//permite ir de fila en fila de la tabla
                {
                    $id=$rowp11['max(id)'];
                }
                $id++;
            }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }

            //$sql1 = "insert into enlaces_compras(id,          nombre,       porcentaje,         tipo   ,   codigo,  formula,  iva, otros_gastos,iva_credito_tributario, ice,  inventario_producto, cuenta_contable,      id_empresa,	           tipo_cpra,             nombre_cpra,codigo_sri)
           // values                          ('".$id."','".$txtNombre."','".$txtPorcentaje."','".$txtTipo."' ,  '0',     '0',    '0',   '0',           '0',                               '0',         '".$txtIdCuenta."','".$sesion_id_empresa."', '".$cmbTipoMovimientoFVC."',NULL,'".$codigo_sri."'); ";
			$sql1 = "insert into enlaces_compras(id,          nombre,       porcentaje,         tipo   ,     codigo,    codigo_sri,     formula,     cuenta_contable,  inventario_producto, iva,  otros_gastos,   iva_credito_tributario, ice,         id_empresa,	                tipo_cpra,             nombre_cpra)
            values                          ('".$id."','".$txtNombre."','".$txtPorcentaje."','".$txtTipo."' ,  '".$codigo."', '".$codigo_sri."',    '0',    '".$txtIdCuenta."' ,        '0',          '0',       '0',            '0',               '0',      '".$sesion_id_empresa."', '".$cmbTipoMovimientoFVC."',   '".$txtTipo."'); ";
// echo $sql1;
            $result1 = mysql_query($sql1);

            if ($result1)
            {
                ?><?php echo 1 ?><?php
            }else {
                ?> <div class='transparent_ajax_error'><p>Error al guarda detalles del producto: <?php echo mysql_error();?> </p></div> <?php 
            }
        }else{
            ?> <div class="transparent_ajax_error"><p>Fallo en el envio del Formulario: No hay datos</p></div> <?php
        }

    }catch (Exception $e) {
        // Error en algun momento.
        ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
    }
}

   
if($accion == '2'){
       // GUARDAR MODIFICACION FORMAS DE PAGO PAGINA: ajax/formaPago.php
    try
    {
        $numeroFilaSelec=$_POST['NumeroFilaSeleccionada'];
        $txtIdFormaCobro=$_POST['idFormaCobro'];
        $txtNombre=ucwords($_POST['txtNombre'.$numeroFilaSelec]);
        $txtCodigo=$_POST['txtCodigo'.$numeroFilaSelec];
        $txtCuenta2=$_POST['txtCuenta2'.$numeroFilaSelec];
        $cmbTipoMovimientoFVC=$_POST['cmbTipoMovimientoFVC'.$numeroFilaSelec];
        $txtIdCuenta=$_POST['txtIdCuenta'.$numeroFilaSelec];
        $txtPorcentaje=$_POST['txtPorcentaje'.$numeroFilaSelec];
		$codigo_sri=$_POST['txtCodigoSri'.$numeroFilaSelec];
		
		$sql="Select nombre,id_tipo_mov_cpra  from tipo_movim_cpra where id_tipo_mov_cpra=".$cmbTipoMovimientoFVC; 
// 		echo $sql."</br>";
		$resp1 = mysql_query($sql);
        $txtTipo="";
        while($row1=mysql_fetch_array($resp1))//permite ir de fila en fila de la tabla
        {
            $txtTipo=$row1["nombre"];
            $idmovieminto=$row1["id_tipo_mov_cpra"];
        }        


           
        if($idmovieminto=='1' or $idmovieminto=='15' or $idmovieminto=='16' ){
            $codigo = '1';
        }else{
            $codigo = '2';
        }
        
        //$tipo_movimientos_nombre=$_POST['$tipo_movimientos_nombre'.$numeroFilaSelec];
	//	$codigo_sri="10";
        //txtPorcentaje
		//echo $txtPorcentaje;
		//echo $codigo_sri;
        if($txtIdCuenta != "" && $txtNombre != "")
		{
          $sql2 = "update enlaces_compras set nombre='".$txtNombre."', cuenta_contable='".$txtIdCuenta."', 
		  id_empresa='".$sesion_id_empresa."', tipo_cpra ='".$cmbTipoMovimientoFVC."',
		  porcentaje='".$txtPorcentaje."', tipo='".$txtTipo."',codigo='".$codigo."', codigo_sri='".$codigo_sri."',
		  nombre_cpra='".$txtTipo."' 
		  WHERE id='".$txtIdFormaCobro."'; ";
		  
		  //echo $sql2;
          $resp2 = mysql_query($sql2) or die("<div class='transparent_ajax_error'><p>Error al modificar el enlace ".mysql_error()."</p></div>");
          // consultas para cambiar el ESTADO de CARGOS a LIBRE
          if($resp2){                
                ?> <div class='transparent_ajax_correcto'><p>Registro modificado correctamente.</p></div> <?php
                    
          }else{
              ?> <div class='transparent_ajax_error'><p>Error al modificar el enlace: <?php echo mysql_error();?> </p></div> <?php
          }   
        }else{
            ?> <div class="transparent_ajax_error"><p>Fallo en el envio del Formulario: No hay datos, <?php echo mysql_error();?></p></div> <?php
        }
    }catch (Exception $e) {
        // Error en algun momento.
        ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
    }
}

    
if($accion == "3"){
    // ELIMINA FORMA DE PAGO PAGINA: ajax/formaPago.php
    try
    {
        if(isset ($_POST['idFormaPago'])){
            $idFormaPago = $_POST['idFormaPago'];

            $sql3 = "delete from formas_pago WHERE id_forma_pago='".$idFormaPago."'; ";
            $result3=mysql_query($sql3);
            
            if($result3){
                ?> <div class='transparent_ajax_correcto'><p>Registro Eliminado correctamente.</p></div> <?php
            }else{
                ?> <div class='transparent_ajax_error'><p>Error al modificar el producto: <?php echo mysql_error();?> </p></div> <?php
            }
        }else{
            ?> <div class="transparent_ajax_error"><p>Fallo en el envio del Formulario: No hay datos, <?php echo mysql_error();?></p></div> <?php
        }

    }catch (Exception $e) {
        // Error en algun momento.
        ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
    }

}

    if($accion == "4"){
    // VALIDA PARA QUE EL NOMBRE DEL  NO SE REPITA PAGINA: .php
     try
     {
        if(isset ($_POST['nombre'])){
          $nombre1 = $_POST['nombre'];
          $sql1 = "SELECT - from  where producto='".$nombre1."'";
          $resp1 = mysql_query($sql1);
          $entro=0;
          while($row1=mysql_fetch_array($resp1))//permite ir de fila en fila de la tabla
                    {
                        $var1=$row1["producto"];
                    }
           $nombre2 = strtolower($nombre1);
           $var2 = strtolower($var1);
          if($var2==$nombre2){
               if($var2==""&&$nombre2==""){
                     $entro=0;
                  }else{
                      $entro=1;
                  }
          }
         echo $entro;
         }

     }catch (Exception $e) {
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
    }

    }


    if($accion == 5){
        $cadena5="";
        //consulta
        try {
            $consulta5="SELECT
             formas_pago.`id_forma_pago` AS formas_pago_id_forma_pago,
             formas_pago.`nombre` AS formas_pago_nombre,
             formas_pago.`id_plan_cuenta` AS formas_pago_id_plan_cuenta,
             formas_pago.`id_empresa` AS formas_pago_id_empresa,
             formas_pago.`id_tipo_movimiento` AS formas_pago_id_tipo_movimiento,
             tipo_movimientos.`id_tipo_movimiento` AS tipo_movimientos_id_tipo_movimiento,
             tipo_movimientos.`nombre` AS tipo_movimientos_nombre,
             tipo_movimientos.`id_empresa` AS tipo_movimientos_id_empresa
        FROM
             `tipo_movimientos` tipo_movimientos INNER JOIN `formas_pago` formas_pago ON tipo_movimientos.`id_tipo_movimiento` = formas_pago.`id_tipo_movimiento`

            where formas_pago.`pagar` !='1' and formas_pago.`id_empresa`='".$sesion_id_empresa."' order by formas_pago.`nombre` asc;";
            $result5=mysql_query($consulta5);
            while($row5=mysql_fetch_array($result5))//permite ir de fila en fila de la tabla
                {
                    $cadena5=$cadena5."?".$row5['formas_pago_id_forma_pago']."*".$row5['tipo_movimientos_nombre']."?".$row5['formas_pago_nombre'];
                }
            echo $cadena5;

        }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
    }

    
    if($accion == 6){
        $cadena6="";
        //consulta
        try {
            $consulta6="SELECT * FROM tipo_movim_cpra order by nombre asc;";
            
			$result6=mysql_query($consulta6);
            while($row6=mysql_fetch_array($result6))//permite ir de fila en fila de la tabla
                {
                    $cadena6=$cadena6."?".$row6['id_tipo_mov_cpra']."?".$row6['nombre'];
                }
            echo $cadena6;

        }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
    }

    
     if($accion == "7"){
        // ELIMINA FORMA DE PAGO PAGINA: ajax/formaPago.php
        try
        {
            if(isset ($_POST['idFormaPago']) ){
                
                
                $idFormaPago = $_POST['idFormaPago'];
                
                $sqlVerifiar="SELECT cobrospagos.id FROM cobrospagos WHERE cobrospagos.id_forma =$idFormaPago";
                $resultVerificar = mysql_query($sqlVerifiar);
                $numFilasVerificar = mysql_num_rows($resultVerificar); 
                if($numFilasVerificar==0){
                          $sql3 = "delete from enlaces_compras WHERE id='".$idFormaPago."'; ";
                    $result3=mysql_query($sql3);
                    
                    if($result3){
                        ?> <div class='alert alert-success'><p>Registro Eliminado correctamente.</p></div> <?php
                    }else{
                        ?> <div class='alert alert-danger'><p>Error al modificar el producto: <?php echo mysql_error();?> </p></div> <?php
                    }
                }else{
                    ?> <div class='alert alert-danger'><p>La forma de pago a eliminar ya se encuentra registrada en una o varias compras realizadas, no se puede eliminar.<?php echo mysql_error();?> </p></div> <?php
                }
    
              
            }else{
                ?> <div class="alert alert-danger"><p>Fallo en el envio del Formulario: No hay datos, <?php echo mysql_error();?></p></div> <?php
            }
    
        }catch (Exception $e) {
            // Error en algun momento.
            ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
        }
    
    }
?>