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
   
   if($accion == '1'){

        $txtCodigo=$_POST['txtCodigo'];
		$txtDireccion= $_POST['txtDireccion'];
		
	    $sql1 = "SELECT codigo, direccion from establecimientos where id_empresa ='".$sesion_id_empresa."' and codigo='".$txtCodigo."'  ";

		$resultado = mysql_query($sql1);
		$fila=mysql_num_rows($resultado);
		if ($fila>0)
		{	?>3<?php 	
		    
		} 	
		
		else{ 

	    try     {

        $txtCodigo=$_POST['txtCodigo'];	
       	$txtDireccion= $_POST['txtDireccion'];
        $cmbCentroCosto = $_POST['cmbCentroCosto'];
       
        if($txtCodigo != "" && $txtDireccion != "" && $cmbCentroCosto != ""  )
        
        {
          
          $sql = "insert into establecimientos( id,id_empresa, codigo, direccion,centro_costo)
                  values (NULL,'".$sesion_id_empresa."','".$txtCodigo."','".$txtDireccion."','".$cmbCentroCosto."'); ";
          $result = mysql_query($sql);
 

          if ($result)
          {
            ?>1<?php
          }
		  else 
			{
                ?> <div class='transparent_ajax_error'><p>Error al guarda en la tabla establecimientos
				<?php echo mysql_error();?> </p></div> <?php 
			}
        }
        else{
            ?> <div class="transparent_ajax_error"><p>Fallo en el envio del Formulario: No hay datos<?php echo mysql_error();?></p></div> <?php
        }

     }catch (Exception $e) {
     // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
     }
   }
   
   
   }


 if($accion == '2'){

        $cmbEst=$_POST['cmbEst'];
		$txtEmision= $_POST['txtEmision'];
		
	    $sql1 = "SELECT id_est, codigo from emision where id_est ='".$cmbEst."' and $txtEmision='".$txtCodigo."'  ";

		$resultado = mysql_query($sql1);
		$fila=mysql_num_rows($resultado);
		if ($fila>0)
		{	?>3<?php 	
		    
		} 	
		
		else{ 

	    try     {

        $cmbEst=$_POST['cmbEst'];
		$txtEmision= $_POST['txtEmision'];
		
		$numfac=$_POST['numfac'];
		$tipoEmision= $_POST['tipoEmision'];
		
		$ambiente= $_POST['ambiente'];
		$tipoFac= $_POST['tipoFac'];
$formato= $_POST['formato'];
       
        if($cmbEst != "" && $txtEmision != ""   )
        
        {
          
          $sql = "insert into emision( id,id_est, codigo,numFac,tipoEmision,ambiente,tipoFacturacion,formato)
                  values (NULL,'".$cmbEst."','".$txtEmision."','".$numfac."','".$tipoEmision."','".$ambiente."','".$tipoFac."','".$formato."'); ";
          $result = mysql_query($sql);
 

          if ($result)
          {
            ?>1<?php
          }
		  else 
			{
                ?> <div class='transparent_ajax_error'><p>Error al guarda en la tabla puntos de emision <?php echo mysql_error();?> </p></div> <?php 
			}
        }
        else{
            ?> <div class="transparent_ajax_error"><p>Fallo en el envio del Formulario: No hay datos<?php echo mysql_error();?></p></div> <?php
        }

     }catch (Exception $e) {
     // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
     }
   }
   
   
   }
   
   if($accion == '3'){
           $codigo = $_POST['codigo'];
            
               //consulta
            try {
                $consulta5="SELECT * FROM emision WHERE id_est='".$codigo."' ";
                $result=mysql_query($consulta5);
                while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
                    {
                   //     $cadena=$cadena."?".$row['id']."?".$row['codigo'];
                        echo "<option value='{$row["id"]}'>{$row["codigo"]}</option>";
                     }
             //  echo "".$cadena;

            }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
          
       
       
   } 
   
if ($accion == '5') {
    
    $emi = $_POST['emi'];
    $est = $_POST['est'];
    $tipo = $_POST['tipo'];
    
    if($tipo==1){
        
            $sqlp="Select numFac From emision where id_est='".$est."' and id='".$emi."' ";

            $result=mysql_query($sqlp);
            $numero_factura_venta='0';
            while($row=mysql_fetch_array($result))
            {
                    $numero_factura_venta=$row['numFac'];
            }
            echo $numero_factura_venta+1;
        
    }else if($tipo==4){
        
        
            $sqlpnpta="Select numnota From emision where id_est='".$est."' and id='".$emi."' ";


            $resultnota=mysql_query($sqlpnpta);
            $numero_nota='0';
            while($rowNota=mysql_fetch_array($resultnota))
            {
                    $numero_nota=$rowNota['numnota'];
            }
            echo $numero_nota+1;
        
    }else if($tipo==10){
        
     $sqlp="SELECT MAX(numero_pedido) AS numero_pedido from pedidos where  id_empresa='".$sesion_id_empresa."'  and codigo_lug='".$emi."' ";

      $result=mysql_query($sqlp);
      $numero_factura_venta='0';
      while($row=mysql_fetch_array($result))
      {
              $numero_factura_venta=$row['numero_pedido'];
      }
      echo $numero_factura_venta+1;
  
    }else{
        
        $sqlp = "Select max(numero_factura_venta) as numero_factura_venta From ventas 
        where codigo_pun='" . $est . "' and codigo_lug='" . $emi . "'  and  
        tipo_documento ='".$tipo."' ";
        
        // echo $sqlp; 

        $result = mysql_query($sqlp);
        $numero_factura_venta = '0';
        while ($row = mysql_fetch_array($result)) {
            $numero_factura_venta = $row['numero_factura_venta'];
            // echo "1 ->".$numero_factura_venta."</br>";
        }
        echo $numero_factura_venta+1;
       
    }
}

    


if($accion == '6'){
    
        $txtCodigo=$_POST['txtCodigo'];
		$txtDireccion= $_POST['txtDireccion'];
		$txtIdCodigo= $_POST['txtIdCodigo'];
        
        $cmbCentroCosto =  $_POST['cmbCentroCosto'];   
    
            $sql = "update establecimientos set codigo='".$txtCodigo."' , direccion='".$txtDireccion."', centro_costo='".$cmbCentroCosto."'  where id='".$txtIdCodigo."'" ;
          $result = mysql_query($sql);
 

          if ($result)
          {
            ?>1<?php
          }
		  else 
			{
                ?> <div class='transparent_ajax_error'><p>Error al guarda en la tabla establecimientos
				<?php echo mysql_error();?> </p></div> <?php 
			}
}

if($accion == '7'){
    
        $txtCodigo=$_POST['txtIdCodigo'];
		$cmbEst= $_POST['cmbEst'];
		$txtCodigoEmision= $_POST['txtEmisionCodigo'];
		$numfac= $_POST['numfac'];
		$tipoEmision= $_POST['tipoEmision'];
		$ambiente= $_POST['ambiente'];
		$tipoFac= $_POST['tipoFac'];
		$formato = $_POST['formato'];
	
		$radio_impresion = $_POST['radio_impresion'];
    
        $sql = "UPDATE `emision` SET `id_est`='".$cmbEst."',`codigo`='".$txtCodigoEmision."',`numFac`='".$numfac."',`tipoEmision`='".$tipoEmision."',
        `ambiente`='".$ambiente."',`tipoFacturacion`='".$tipoFac."',`formato`='".$formato."', impresion = '".$radio_impresion."'   where id='".$txtCodigo."'" ;
        $result = mysql_query($sql);

          if ($result)    {     ?>1<?php  }
		  else 	{  ?> <div class='transparent_ajax_error'><p>Error al guarda en la tabla emision
				<?php echo mysql_error();?> </p></div> <?php 	}  
    
}

    
    if($accion == '8'){
         $codigo_pun = $_POST['id'];
     
     	$sql1 = "SELECT codigo_pun from ventas where codigo_pun ='".$codigo_pun."'   ";
		$resultado = mysql_query($sql1);
		$fila=mysql_num_rows($resultado);
		if ($fila>0) {   
		    echo '1';
		}else{
		  
		  $sql = "delete from establecimientos  where id='".$codigo_pun."'" ;
          $result = mysql_query($sql);
          if ($result){
                echo '2';
          }else{
                echo '3';
          }
		    
		}
     
     
     
}

    if($accion == '9'){
        
        $codigo_lug = $_POST['id'];
     
     	$sql1 = "SELECT codigo_lug from ventas where codigo_lug ='".$codigo_lug."'   ";
		$resultado = mysql_query($sql1);
		$fila=mysql_num_rows($resultado);
		if ($fila>0) {   
		    echo '1';
		}else{
		  
		  $sql = "delete from emision  where id='".$codigo_lug."'" ;
          $result = mysql_query($sql);
          if ($result){
                echo '2';
          }else{
                echo '3';
          }
		    
		}
     
     
     
}

 if($accion == '10'){
        
        $codigo_lug = $_POST['emi'];
        // echo "</br>".$codigo_lug;
     
     	$sql1 = "SELECT SOCIO from emision where id ='".$codigo_lug."'   ";
    //  	echo $sql1;
		$resultado = mysql_query($sql1);
		$fila=mysql_num_rows($resultado);
		if ($fila>0) {   
		     while ($row = mysql_fetch_array($resultado)) {
		    echo $row['SOCIO'];
		  //  echo "SOCIO";
		     }
		}else{
		  echo '0';
		    
		}
     
     
     
}


  
  