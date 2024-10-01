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
                
        if($_POST['checkDiario'.$numeroFilaSelec] == true){
            $checkDiario = "Si";
        }else{
            $checkDiario = "No";
        }
        if($_POST['checkIngreso'.$numeroFilaSelec] == true){
            $checkIngreso = "Si";
        }else{
            $checkIngreso = "No";
        }
        if($_POST['checkEgreso'.$numeroFilaSelec] == true){
            $checkEgreso = "Si";
        }else{
            $checkEgreso = "No";
        }       

        if($txtIdCuenta != "" && $txtNombre != ""){
          
            //permite sacar el id maximo de formas de pago
            try {
                $sqlp11="Select max(id_forma_pago) From formas_pago";
                $resultp11=mysql_query($sqlp11);
                $id_forma_pago=0;
                while($rowp11=mysql_fetch_array($resultp11))//permite ir de fila en fila de la tabla
                {
                    $id_forma_pago=$rowp11['max(id_forma_pago)'];
                }
                $id_forma_pago++;
            }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }

            $sql1 = "insert into formas_pago(id_forma_pago, nombre, id_plan_cuenta, id_empresa, id_tipo_movimiento, diario, ingreso, egreso)
            values ('".$id_forma_pago."','".$txtNombre."','".$txtIdCuenta."', '".$sesion_id_empresa."', '".$cmbTipoMovimientoFVC."', '".$checkDiario."', '".$checkIngreso."', '".$checkEgreso."'); ";
            $result1 = mysql_query($sql1);

            if ($result1)
            {
                ?> <div class='transparent_ajax_correcto'><p>Registro insertado correctamente.</p></div> <?php
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
        $txtIdFormaPago=$_POST['idFormaPago'];
        $txtNombre=ucwords($_POST['txtNombre'.$numeroFilaSelec]);
        $txtCodigo=$_POST['txtCodigo'.$numeroFilaSelec];
        $txtCuenta2=$_POST['txtCuenta2'.$numeroFilaSelec];
        $cmbTipoMovimientoFVC=$_POST['cmbTipoMovimientoFVC'.$numeroFilaSelec];
        $txtIdCuenta=$_POST['txtIdCuenta'.$numeroFilaSelec];
        
        if($_POST['checkDiario'.$numeroFilaSelec] == true){
            $checkDiario = "Si";
        }else{
            $checkDiario = "No";
        }
        if($_POST['checkIngreso'.$numeroFilaSelec] == true){
            $checkIngreso = "Si";
        }else{
            $checkIngreso = "No";
        }
        if($_POST['checkEgreso'.$numeroFilaSelec] == true){
            $checkEgreso = "Si";
        }else{
            $checkEgreso = "No";
        }      

        if($txtIdCuenta != "" && $txtNombre != ""){

          $sql2 = "update formas_pago set nombre='".$txtNombre."', id_plan_cuenta='".$txtIdCuenta."', id_empresa='".$sesion_id_empresa."', id_tipo_movimiento ='".$cmbTipoMovimientoFVC."', diario='".$checkDiario."', ingreso='".$checkIngreso."', egreso='".$checkEgreso."' WHERE id_forma_pago='".$txtIdFormaPago."'; ";
          $resp2 = mysql_query($sql2) or die("<div class='transparent_ajax_error'><p>Error al modificar el producto: ".mysql_error()."</p></div>");
          // consultas para cambiar el ESTADO de CARGOS a LIBRE
          if($resp2){                
                ?> <div class='transparent_ajax_correcto'><p>Registro modificado correctamente.</p></div> <?php
                    
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
             enlaces_compras.`id` AS enlaces_compras_id,
             enlaces_compras.`nombre` AS enlaces_compras_nombre,
			 enlaces_compras.`porcentaje` AS enlaces_compras_porcentaje, 
             enlaces_compras.`id_empresa` AS enlaces_compras_id_empresa
        FROM
            `enlaces_compras` enlaces_compras 
            where enlaces_compras.`id_empresa`='".$sesion_id_empresa."' order by enlaces_compras.`nombre` asc;";
			//echo $consulta5;
            $result5=mysql_query($consulta5);
            while($row5=mysql_fetch_array($result5))//permite ir de fila en fila de la tabla
                {
       //		$cadena5=$cadena5."?".$row5['formas_pago_id_forma_pago']."*".$row5['tipo_movimientos_nombre']."?".$row5['formas_pago_nombre'];                         
//			   $cadena5=$cadena5."?".$row5['enlaces_compras_id']."*".$row5['enlaces_compras_porcentaje']."?".$row5['enlaces_compras_nombre'];
				$cadena5=$cadena5."?".$row5['enlaces_compras_porcentaje']."?".$row5['enlaces_compras_nombre'];
              
        }
            echo $cadena5;

        }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
    }

    
    if($accion == 6){
        $cadena6="";
        //consulta
        try {
          //$consulta6="SELECT * FROM tipo_movimientos where id_empresa='".$sesion_id_empresa."' order by nombre asc;";
            $consulta6="SELECT * FROM tipo_movimientos order by nombre asc;";
            
			$result6=mysql_query($consulta6);
            while($row6=mysql_fetch_array($result6))//permite ir de fila en fila de la tabla
                {
                    $cadena6=$cadena6."?".$row6['id_tipo_movimiento']."?".$row6['nombre'];
                }
            echo $cadena6;

        }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
    }

    
  
?>