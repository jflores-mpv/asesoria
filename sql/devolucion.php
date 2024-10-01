<?php
    require_once('../ver_sesion.php');

    //Start session
    session_start();

    //Include database connection details
    require_once('../conexion.php');

    $sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $accion = $_POST['accion'];
//	date_default_timezone_set('America/Guayaquil');
	date_default_timezone_set('America/Guayaquil');


	//echo "va a grabar vendedores"."<br>";
	if($accion == 1)
	{
         //  echo "va a grabar";

        // GUARDAR VENDEDORES
 		try 
		{
		   $txtIdProducto = $_POST['txtIdProducto1'];
            $txtNombre = $_POST['txtNombre1'];
            $txtFecha = $_POST['txtFecha'];
			$TipoDevolucion = $_POST['txtTipoDevolucion'];
                     
		    $txtCantidad = $_POST['txtCantidad'];
			$txtValor = $_POST['txtValor'];
			
			$txtDetalle=$_POST['txtDetalle'];
	//		echo $txtIdProducto;
	/* echo "tipo";
			echo $TipoDevolucion; */
            //permite sacar el id maximo de clientes
            try 
			{
                $sqli="Select max(id_devolucion) From devolucion";
                $result=mysql_query($sqli);
                $id_devolucion=0;
                while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
                {
                    $id_devolucion=$row['max(id_devolucion)'];
                }
                $id_devolucion++;

            }
			catch(Exception $ex)
			{ ?> <div class="transparent_ajax_error"><p>Error en el id max:
			     <?php echo "".$ex ?></p></div> 
		    <?php }

       //     $sql1 = "insert into vendedores (id_vendedor, codigo, nombre, apellido, direccion, telefono, comision, id_empresa) values ('".$id_vendedor."', '".$txtCodigo."','".$txtNombre."', '".$txtApellido."', '".$txtDireccion."', '".$txtTelefono."', '".$txtComision."', '".$sesion_id_empresa."');";
			$sql1 = "insert into devolucion (id_devolucion, id_producto, tipo_devolucion,fecha, cantidad,valor_unitario, detalle, id_empresa) 
			values ('".$id_devolucion."', '".$txtIdProducto."','".$TipoDevolucion."','".$txtFecha."', '".$txtCantidad."', 
			'".$txtValor."', '".$txtDetalle."', '".$sesion_id_empresa."');";
				
			//echo $sql1;
			$result1=mysql_query($sql1);

			$stock_act=0;
			$sql222="select * From productos WHERE id_producto='".$txtIdProducto."';";
            $result222=mysql_query($sql222) or die("\nError al actualizar el Stock Fila 2: ".mysql_error());
            while($row2=mysql_fetch_array($result222))
            {
                $stock_act=$row2['stock'];
            }

//echo $stock_act;
			if ($TipoDevolucion=='1')
			{
				$stock_act=$stock_act - $txtCantidad; // SUMA EL ESTOCK y asigna el precio bruto
				$nombreDevol='Devolucion Compra';
			}
			else
			{
				$stock_act=$stock_act + $txtCantidad;
					$nombreDevol='Devolucion Venta';
			}
            $sql22="update productos set stock = '".$stock_act."' where id_producto='".$txtIdProducto."';";
		//	echo $sql22;
            $result22=mysql_query($sql22) or die("\nError al actualizar el Stock Fila 2: ".mysql_error());

			$sqlki="Select max(id_kardes) From kardes";
			$resultki=mysql_query($sqlki) or die("\nError al sacar el id_max de Kardex: ".mysql_error());
			$id_kardes='0';
			while($rowki=mysql_fetch_array($resultki))//permite ir de fila en fila de la tabla
			{
				$id_kardes=$rowki['max(id_kardes)'];
			}
			$id_kardes++;
			
			$sqlk="insert into kardes (id_kardes, fecha, detalle,  id_factura,id_empresa) values
			('".$id_kardes."','".$txtFecha."','".$nombreDevol."','".$id_devolucion."','".$sesion_id_empresa."')";
			
			//echo $sqlk;
			$resultk=mysql_query($sqlk) or die("\nError al actualizar EL Kardex: ".mysql_error());
        
		
		
		
            if($result1)
			{
                ?> <div class='alert alert-success'><p>Registro insertado correctamente.</p></div> <?php
            }
			else
			{
                ?> <div class='alert alert-danger'><p>Error al guarda los datos: problemas en la consulta</p></div> <?php
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
			echo "va llerr";
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


<?
/* if($accion == 2){

        // saca todos los vendedores
        try {
            $cadena2="";
            $consulta2="SELECT * FROM vendedores where id_empresa='".$sesion_id_empresa."' order by nombre asc;";
            $result2=mysql_query($consulta2);
            while($row2=mysql_fetch_array($result2))//permite ir de fila en fila de la tabla
                {
                    $cadena2=$cadena2."?".$row2['id_vendedor']."?".$row2['nombre'];
                }
            echo $cadena2;

        }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
    }
          
    if($accion == 3){
        try {
        // VALIDACIONES PARA QUE LA CEDULA DEL CLIENTE NO SE REPITA POR EMPRESA
        if(isset ($_POST['cedula'])){
          $cedula = $_POST['cedula'];
          //echo "".$cedula;
          $sql = "SELECT cedula from clientes where cedula='".$cedula."' and id_empresa='".$sesion_id_empresa."';";
          $resp = mysql_query($sql);
          $entro=0;
          while($row=mysql_fetch_array($resp))//permite ir de fila en fila de la tabla
                    {
                        $var=$row["cedula"];
                    }
          if($var==$cedula){
               if($var==""&&$cedula==""){
                     $entro=0;
                  }else{
                      $entro=1;
                  }
          }
         echo $entro;
         }

        }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
    }

    if($accion == 4){
        // VALIDACIONES PARA QUE EL EMAIL DEL CLIENTE NO SE REPITA
        try{
            if(isset ($_POST['email'])){

                $email = $_POST['email'];
                $sql4="Select email From clientes WHERE email ='".$email."' and id_empresa='".$sesion_id_empresa."';";
                $resp4 = mysql_query($sql4);
                $entro=0;
                while($row4=mysql_fetch_array($resp4))//permite ir de fila en fila de la tabla
                        {
                            $var1=$row4["email"];
                        }
                $email = strtolower($email);
                if($var1==$email){
                       if($var1==""&&$email==""){
                         $entro=0;
                      }else {
                          $entro=1;
                      }
                }
                echo $entro;
            }

        }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
    }

   
    if($accion == 5){
        // VALIDACIONES PARA QUE EL NUMERO DE CASA NO SE REPITA
        try{

            if(isset ($_POST['numero_casa'])){

                $numero_casa= $_POST['numero_casa'];
                $sql5="Select numero_casa From clientes WHERE numero_casa ='".$numero_casa."' and id_empresa='".$sesion_id_empresa."';";
                $resp5 = mysql_query($sql5);
                $entro=0;
                while($row5=mysql_fetch_array($resp5))//permite ir de fila en fila de la tabla
                        {
                            $var1=$row5["numero_casa"];
                        }
                //$numero_casa = strtolower($numero_casa);
                if($var1==$numero_casa){
                       if($var1==""&&$numero_casa==""){
                         $entro=0;
                      }else {
                          $entro=1;
                      }
                }
                echo $entro;
            }
        }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
    }

    if($accion == 6){
        if(isset($_POST['queryString'])) {
            $queryString = $_POST['queryString'];
            // Is the string length greater than 0?
            if(strlen($queryString) >0) {
                $query = "SELECT
                 clientes.`id_cliente` AS clientes_id_cliente,
                 clientes.`nombre` AS clientes_nombre,
                 clientes.`apellido` AS clientes_apellido,
                 clientes.`direccion` AS clientes_direccion,
                 clientes.`cedula` AS clientes_cedula,
                 clientes.`telefono` AS clientes_telefono,
                 clientes.`movil` AS clientes_movil,
                 clientes.`fecha_nacimiento` AS clientes_fecha_nacimiento,
                 clientes.`email` AS clientes_email,
                 clientes.`estado` AS clientes_estado,
                 clientes.`id_ciudad` AS clientes_id_ciudad,
                 clientes.`fecha_registro` AS clientes_fecha_registro,
                 clientes.`numero_cargas` AS clientes_numero_cargas,
                 clientes.`estado_civil` AS clientes_estado_civil,
                 clientes.`tipo` AS clientes_tipo,
                 clientes.`numero_casa` AS clientes_numero_casa,
                 clientes.`id_empresa` AS clientes_id_empresa,
                 clientes.`id_vendedor` AS clientes_id_vendedor,
                 clientes.`caracter_identificacion` AS clientes_caracter_identificacion,
                 clientes.`reponsable` AS clientes_reponsable,
                 clientes.`limite_credito` AS clientes_limite_credito,
                 clientes.`descuento` AS clientes_descuento,
                 clientes.`dias_plazo` AS clientes_dias_plazo,
                 clientes.`tipo_precio` AS clientes_tipo_precio,
                 clientes.`observacion` AS clientes_observacion
            FROM
                 `clientes` clientes
                WHERE clientes.`id_empresa`='".$sesion_id_empresa."' and CONCAT(clientes.`cedula`, clientes.`nombre`, clientes.`apellido`) LIKE '%$queryString%' AND estado='Activo' order by clientes.`nombre` asc LIMIT 10; ";

                $result = mysql_query($query) or die(mysql_error());

                $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
                if($result) {
                    if($numero_filas == 0){
                        echo "<center><p><label> No hay resulados con el parámetro ingresado. </label></p></center>";
                    }else{
                        // While there are results loop through them - fetching an Object (i like PHP5 btw!). overTable
                        echo "<table border='1' style='margin: -10px 0px 0px 0px;' cellpadding='0' cellspacing='0' class='overTable' ><tr><th>Id</th><th>Cedula</th><th>Nombre</th><th>Direccion</th><th>Telefono</th></tr>";
                        while ($row = mysql_fetch_array($result)) {
                            echo '<tr title="Clic para seleccionar" onClick="fill9(\''.$row['clientes_nombre']."*".$row['clientes_apellido']."*".$row['clientes_cedula']."*".$row["clientes_telefono"]."*".$row['clientes_direccion']."*".$row["clientes_id_cliente"].'\');" >';
                            echo "<td>".$row["clientes_id_cliente"]."</td>";
                            echo "<td>".$row["clientes_cedula"]."</td>";
                            echo "<td>".$row["clientes_nombre"]." ".$row["clientes_apellido"]."</td>";
                            echo "<td>".$row["clientes_direccion"]."</td>";
                            echo "<td>".$row["clientes_telefono"]."</td>";
                            echo "</tr>";
                        }
                        echo"</table> ";
                    }
                }else {
                    echo 'ERROR: Hay un problema con la consulta.';
                }
            }else{
                echo 'La longitud no es la permitida.';
                    // Dont do anything.
            } // There is a queryString.
        }else{
            echo 'No hay ningún acceso directo a este script!';
        }
    }
 */
 ?>