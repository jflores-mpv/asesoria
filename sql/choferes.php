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

        $txtCodigo=$_POST['txtIdentificacion'];
		$txtDireccion= $_POST['tipoIdentificacion'];
		
	    $sql1 = "SELECT Cedula,Tipo from transportistas where id_empresa ='".$sesion_id_empresa."' and Cedula='".$txtCodigo."' and Tipo='".$txtDireccion."'  ";

		$resultado = mysql_query($sql1);
		$fila=mysql_num_rows($resultado);
		if ($fila>0)
		{	?>3<?php 	
		    
		} 	
		
		else{ 

	    try     {
        $txtRazonSocial=$_POST['txtRazonSocial'];	
       	$txtDireccion= $_POST['txtDireccion'];
       	$txtTelefono= $_POST['txtTelefono'];
       	$txtIdentificacion= $_POST['txtIdentificacion'];
       	$tipoIdentificacion= $_POST['tipoIdentificacion'];
       	$txtPlaca= $_POST['txtPlaca'];

       
        if($txtRazonSocial != "" && $txtDireccion != ""   )
        
        {
          
          $sql = " INSERT INTO `transportista`( `Nombres`,    `Direccion`,   `Telefono`,  `Cedula`,  `Tipo`, `Placa`,`id_empresa`) 
VALUES ('".$txtRazonSocial."','".$txtDireccion."','".$txtTelefono."','".$txtIdentificacion."','".$tipoIdentificacion."','".$txtPlaca."','".$sesion_id_empresa."')";

          $result = mysql_query($sql);
 

          if ($result)
          {
            echo "1";
          }
		  else 
			{
                ?> <div class='transparent_ajax_error'><p>Error al guarda en la tabla transportistas
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

    $txtCodigo=$_POST['id'];
    
    $sql1 = "SELECT  `Id`, `Nombres`, `Direccion`, `Telefono`, `Cedula`, `Tipo`, `Placa` from transportista where Id =$txtCodigo ";

    $resultado = mysql_query($sql1);
    $fila=mysql_num_rows($resultado);
    if ($fila>0)
    {		
        
    try     {
        $txtRazonSocial=$_POST['txtRazonSocial'];	
           $txtDireccion= $_POST['txtDireccion'];
           $txtTelefono= $_POST['txtTelefono'];
           $txtIdentificacion= $_POST['txtIdentificacion'];
           $tipoIdentificacion= $_POST['tipoIdentificacion'];
           $txtPlaca= $_POST['txtPlaca'];
    
       
        if($txtRazonSocial != "" && $txtDireccion != ""   )
        
        {
          
          $sql = "UPDATE `transportista` SET `Nombres`='$txtRazonSocial',`Direccion`='$txtDireccion',`Telefono`='$txtTelefono',`Cedula`='$txtIdentificacion',`Tipo`='$tipoIdentificacion',`Placa`='$txtPlaca' WHERE Id='$txtCodigo' ";
    
          $result = mysql_query($sql);
    
    
          if ($result)
          {
            echo "5";
          }
          else 
            {
                ?> <div class='transparent_ajax_error'><p>Error al actualizar en la tabla transportistas
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
    else{ 
        ?>3<?php 
}


}
if($accion == '3'){

    $txtCodigo=$_POST['id'];
    
    $sql1 = "SELECT  `Id` from transportista where Id =$txtCodigo ";

    $resultado = mysql_query($sql1);
    $fila=mysql_num_rows($resultado);
    if ($fila>0)
    {		
        
    try     {
          
          $sql = "DELETE FROM `transportista` WHERE `Id`=$txtCodigo ";
    
          $result = mysql_query($sql);
          if ($result)
          {
            echo "1";
          }
          else 
            {
                ?> <div class='transparent_ajax_error'><p>Error al eliminar en la tabla transportistas
                <?php echo mysql_error();?> </p></div> <?php 
            }
        
       
    
     }catch (Exception $e) {
     // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
     }
    } 	
    else{ 
        ?>3<?php 
}


}
  ?> 