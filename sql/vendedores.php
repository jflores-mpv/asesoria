<?php

    // require_once('../ver_sesion.php');

    //Start session
    session_start();

    //Include database connection details
    require_once('../conexion.php');

    $sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $accion = $_POST['accion']; 
	date_default_timezone_set('America/Guayaquil');
   
    if($accion == 10)
	{
        // GUARDAR VENDEDORES
		try 
		{
            $rbCaracterIdentificacion = $_POST['rbCaracterIdentificacion'];
            $txtCedula = $_POST['txtCedula'];
			$txtCodigo = $_POST['txtCodigo'];
            $txtNombre = $_POST['txtNombre'];
            $txtApellido = $_POST['txtApellidos'];
         // $txtCedula = $_POST['txtCedula'];
            $txtCorreo = $_POST['txtCorreo'];
            $txtDireccion = $_POST['txtDireccion'];
            $txtTelefono = $_POST['txtTelefono'];
         	$txtComision="0";   

            //permite sacar el id maximo de clientes
            $sql = "SELECT `id_vendedor`, `codigo`, `nombre`, `apellidos`, `direccion`, `telefono`, `comision`, `id_empresa`, `caracter_identificacion`, `cedula`, `estado` FROM `vendedores` WHERE id_empresa=$sesion_id_empresa AND cedula=$txtCedula ";
            $result = mysql_query($sql);
            $numFilas = mysql_num_rows($result);
            if($numFilas>0){
                echo '3';
                exit;
            }
          {
                 $sql1 = "INSERT INTO `vendedores`(  `nombre`, `apellidos`, `direccion`, `telefono`, `id_empresa`, `caracter_identificacion`, `cedula`, `estado`,email) VALUES ('".$txtNombre."','".$txtApellido."','".$txtDireccion."','".$txtTelefono."','".$sesion_id_empresa."','".$rbCaracterIdentificacion."','".$txtCedula."','Activo','".$txtCorreo."')";
             }
			
				
// 			echo $sql1;
			$result1=mysql_query($sql1);

            if($result1)
			{
			    echo '1';
            }
			else
			{
               echo '2';
            }
        }
		catch(Exception $ex) 
		{ ?> 
		   <div class="transparent_ajax_error"><p>Error en la consulta: 
		   <?php echo "".$ex ?></p></div> 
		<?php
		}
    }
    if($accion == 11)
	{
        // GUARDAR VENDEDORES
		try 
		{
            $rbCaracterIdentificacion = $_POST['rbCaracterIdentificacion'];
            $txtCedula = $_POST['txtCedula'];
			$txtCodigo = $_POST['txtCodigo'];
            $txtNombre = $_POST['txtNombre'];
            $txtApellido = $_POST['txtApellidos'];
         // $txtCedula = $_POST['txtCedula'];
            $txtCorreo = $_POST['txtCorreo'];
            $txtDireccion = $_POST['txtDireccion'];
            $txtTelefono = $_POST['txtTelefono'];
         	$txtComision="0"; 
         	$txtEstado="Activo";
         	$id_vendedor = $_POST['id'];

            
          {
                $sql1 = "UPDATE `vendedores` SET `nombre`='".$txtNombre."',`apellidos`='".$txtApellido."',`direccion`='".$txtDireccion."',`telefono`='".$txtTelefono."',`caracter_identificacion`='".$rbCaracterIdentificacion."',`cedula`='".$txtCedula."',`estado`='".$txtEstado."',`email`='".$txtCorreo."' WHERE id_vendedor= $id_vendedor";
            }
			
				
// 			echo $sql1;
			$result1=mysql_query($sql1);

            if($result1)
			{
			    echo '5';
            }
			else
			{
               echo '6';
            }
        }
		catch(Exception $ex) 
		{ ?> 
		   <div class="transparent_ajax_error"><p>Error en la consulta: 
		   <?php echo "".$ex ?></p></div> 
		<?php
		}
    }
     if($accion == 12)
	{
        // GUARDAR VENDEDORES
		try 
		{
           $id_vendedor = $_POST['id'];

          
            
			$sql1 = "DELETE FROM `vendedores` WHERE `id_vendedor`=$id_vendedor";
				
// 			echo $sql1;
			$result1=mysql_query($sql1);

            if($result1)
			{
			    echo '1';
            }
			else
			{
               echo '2';
            }
        }
		catch(Exception $ex) 
		{ ?> 
		   <div class="transparent_ajax_error"><p>Error en la consulta: 
		   <?php echo "".$ex ?></p></div> 
		<?php
		}
    }
     if($accion == 13)
	{
        // GUARDAR VENDEDORES
		try 
		{
           $id_vendedor = $_POST['id_vendedor'];
         $estado = $_POST['estado'];
         if($estado=='0'){
             $estado= 'Inactivo';
         }else{
              $estado= 'Activo';
         }
          $sql1="UPDATE `vendedores` SET `estado`='".$estado."' WHERE id_vendedor=$id_vendedor ";
            
			$result1=mysql_query($sql1);

            if($result1)
			{
			    echo '1';
            }
			else
			{
               echo '2';
            }
        }
		catch(Exception $ex) 
		{ ?> 
		   <div class="transparent_ajax_error"><p>Error en la consulta: 
		   <?php echo "".$ex ?></p></div> 
		<?php
		}
    }
    
	//echo "va a grabar vendedores"."<br>";
	if($accion == 1)
	{
        // GUARDAR VENDEDORES
		try 
		{
            $rbCaracterIdentificacion = $_POST['rbCaracterIdentificacion'];
			$txtCodigo = $_POST['txtCodigo'];
            $txtNombre = $_POST['txtNombre'];
            $txtApellido = $_POST['txtApellido'];
         // $txtCedula = $_POST['txtCedula'];
            $txtDireccion = $_POST['txtDireccion'];
            $txtTelefono = $_POST['txtTelefono'];
         	$txtComision="0";   

            //permite sacar el id maximo de clientes
            try 
			{
                $sqli="Select max(id_vendedor) From vendedores";
                $result=mysql_query($sqli);
                $id_vendedor=0;
                while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
                {
                    $id_vendedor=$row['max(id_vendedor)'];
                }
                $id_vendedor++;

            }
			catch(Exception $ex)
			{ ?> <div class="transparent_ajax_error"><p>Error en el id max:
			     <?php echo "".$ex ?></p></div> 
		    <?php }

       //     $sql1 = "insert into vendedores (id_vendedor, codigo, nombre, apellido, direccion, telefono, comision, id_empresa) values ('".$id_vendedor."', '".$txtCodigo."','".$txtNombre."', '".$txtApellido."', '".$txtDireccion."', '".$txtTelefono."', '".$txtComision."', '".$sesion_id_empresa."');";
			$sql1 = "insert into vendedores (id_vendedor, codigo, nombre, apellidos, direccion, telefono, comision, id_empresa) values ('".$id_vendedor."', '".$txtCodigo."','".$txtNombre."', '".$txtApellido."', '".$txtDireccion."', '".$txtTelefono."', '".$txtComision."', '".$sesion_id_empresa."');";
				
	//		echo $sql1;
			$result1=mysql_query($sql1);

            if($result1)
			{
                ?> <div class='transparent_ajax_correcto'><p>Registro insertado correctamente.</p></div> <?php
            }
			else
			{
                ?> <div class='transparent_ajax_error'><p>Error al guarda los datos: problemas en la consulta</p></div> <?php
            }
        }
		catch(Exception $ex) 
		{ ?> 
		   <div class="transparent_ajax_error"><p>Error en la consulta: 
		   <?php echo "".$ex ?></p></div> 
		<?php
		}
    }
     
    
	if($accion == 7)
	{
    // GUARDA MODIFICACION Clientes PAGINA: cargos.php
		try
		{
			// echo "va llerr";
			$txtIdVendedor = $_POST['txtIdVendedor'];
			$txtCodigo = ucwords($_POST['txtCodigo']);
			$txtNombre = ucwords($_POST['txtNombre']);
			$txtApellido = ucwords($_POST['txtApellido']);
			echo $txtIdCliente;
			if($txtNombre != "")
			{
             // $sql3 = "update clientes SET nombre='".$txtNombre."', sueldo='".$txtSueldo."', id_departamento='".$cmbDepartamento."' where id_cargo='".$txtIdCargo."'; ";
              $sql3 = "update vendedores SET nombre='".$txtNombre."', apellidos='".$txtApellido." ' where id_vendedor='".$txtIdVendedor."'; ";
              echo "sql3";
			  echo $sql3;
			  $resp3 = mysql_query($sql3);
               if($resp3)
			   {
                ?> <div class='transparent_ajax_correcto'>
				  <p>Registro modificado correctamente.</p></div> 
				<?php
               }
			    else
				{
                  ?> <div class='transparent_ajax_error'>
				  <p>Error al guarda los datos: problemas en la consulta</p>
				  </div> 
				  <?php
                }
            }else 
			{
              ?> <div class="transparent_ajax_error"><p>Error: No hay datos</p></div>
			  <?php
            }
		}
		catch (Exception $e)
		{
         ?> <div class="transparent_ajax_error">
		 <p>Error: <?php echo "".$e ?></p>
		 </div> 
		 <?php
		}
    }

	if($accion == "8")
	{
    // ELIMINA CARGOS PAGINA: cargos.php
		try
		{
        if(isset ($_POST['id_cargo']))
		{
          $id_cliente = $_POST['id_cliente'];
          $sql4 = "delete from clientes where id_cliente='".$id_cliente."'; ";
          $resp4 = mysql_query($sql4);
          if(!mysql_query($sql4))
		  {
              echo "Ocurrio un error\n$sql4";
          }
		  else
		  {
             echo "El registro ha sido Eliminado."; 
		  }
		}
		else
		{
          ?> <div class="transparent_ajax_error"><p>Error: No hay datos</p></div> 
		  <?php
        }
    }
	 catch (Exception $e)
	 {
       ?> <div class="transparent_ajax_error">
	   <p>Error: <?php echo "".$e ?></p></div> 
	   <?php
      }

    }
    
?>


