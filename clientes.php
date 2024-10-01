<?php
    require_once('../ver_sesion.php');

    //Start session
    session_start();

    //Include database connection details
    require_once('../conexion.php');

    $sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];

    $accion = $_POST['accion'];

    date_default_timezone_set('America/Guayaquil');

	//echo "VA A GRABAR SQL";
	if($accion == 1)
	{
        // GUARDAR CLIENTES
        try 
		{
            $rbCaracterIdentificacion = $_POST['rbCaracterIdentificacion'];
            $txtNombre = $_POST['txtNombre'];
            $txtApellido = $_POST['txtApellido'];
            $txtCedula = $_POST['txtCedula'];
            $txtDireccion = $_POST['txtDireccion'];
            $txtTelefono = $_POST['txtTelefono'];
            $txtMovil = $_POST['txtMovil'];
            $txtEmail = $_POST['txtEmail'];
            $txtNumeroCasa = $_POST['txtNumeroCasa'];
            $cmbEstado = $_POST['cmbEstado'];
            $id_ciudad = $_POST['cbciudad'];
            $txtResponsable = $_POST['txtResponsable'];
            $txtLimiteCredito = $_POST['txtLimiteCredito'];
            $txtDescuento = $_POST['txtPorcentajeDescuento'];
            $cmbDiasPlazo = $_POST['cmbDiasPlazo'];
            $cmbVendedor = $_POST['cmbVendedor'];
            $cmbTipoPrecio = $_POST['cmbTipoPrecio'];
            $cmbTipoCliente=$_POST['cbTipoCliente'];
			$propNombre=$_POST['txtPropNombre'];
			$propTelefono=$_POST['txtPropTelefono'];
            $txtObservaciones = $_POST['txtObservaciones'];
            $fecha_registro = date("Y-m-d h:i:s");
            $numero_cargas = "0";
            $estado_civil = "";
            $tipo = "";

            //permite sacar el id maximo de clientes
            try
			{
                $sqli="Select max(id_cliente) From clientes";
                $result=mysql_query($sqli);
                $id_cliente=0;
                while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
                {
                    $id_cliente=$row['max(id_cliente)'];
                }
                $id_cliente++;

            }
			catch(Exception $ex) 
			{
				?> <div class="transparent_ajax_error"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php 
			}

            $sql1 = "insert into clientes (id_cliente, nombre, apellido, direccion, cedula, telefono, movil, fecha_nacimiento, email, estado, id_ciudad, fecha_registro, numero_cargas, estado_civil, tipo, numero_casa, id_empresa, id_vendedor, caracter_identificacion, reponsable, limite_credito, descuento, dias_plazo, tipo_precio, tipo_cliente, prop_nombre, prop_telefono, observacion) values ('".$id_cliente."', '".$txtNombre."', '".$txtApellido."', '".$txtDireccion."', '".$txtCedula."', '".$txtTelefono."', '".$txtMovil."', '', '".$txtEmail."', '".$cmbEstado."', '".$id_ciudad."', '".$fecha_registro."', '".$numero_cargas."', '".$estado_civil."', '".$tipo."', '".$txtNumeroCasa."', '".$sesion_id_empresa."', '".$cmbVendedor."', '".$rbCaracterIdentificacion."', '".$txtResponsable."', '".$txtLimiteCredito."', '".$txtDescuento."', '".$cmbDiasPlazo."', '".$cmbTipoPrecio."','".$cmbTipoCliente."','".$propNombre."','".$propTelefono."', '".$txtObservaciones."');";
			//echo $sql1;
			$result1=mysql_query($sql1);

            if($result1){
                ?> <div class='transparent_ajax_correcto'><p>Registro insertado correctamente.</p></div> <?php
            }else{
                ?> <div class='transparent_ajax_error'><p>Error al guarda los datos: problemas en la consulta</p></div> <?php
            }

        }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
    }
   
    if($accion == 2){

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

	
    if($accion == 7)
	{
    // GUARDA MODIFICACION Clientes PAGINA: cargos.php
		try
		{
		//echo "va llerr";
			$txtIdCliente = $_POST['txtIdCliente'];
			$txtNombre = ucwords($_POST['txtNombre']);
			$txtApellido = ucwords($_POST['txtApellido']);
			$txtDireccion = ucwords($_POST['txtDireccion']);
			$txtCedula = ucwords($_POST['txtCedula']);
			$txtTelefono = ucwords($_POST['txtTelefono']);
			 
			$txtMovil = ucwords($_POST['txtMovil']);
			$txtEmail = ucwords($_POST['txtEmail']);
			$txtCasa = ucwords($_POST['txtCasa']);
			$cmbTipoCliente = ucwords($_POST['cmbTipoCliente']);
					
			$txtPropNombre = ucwords($_POST['txtPropNombre']);
			$txtPropTelefono = ucwords($_POST['txtPropTelefono']);

			if($txtNombre != "")
			{
             // $sql3 = "update clientes SET nombre='".$txtNombre."', sueldo='".$txtSueldo."', id_departamento='".$cmbDepartamento."' where id_cargo='".$txtIdCargo."'; ";
              $sql3 = "update clientes SET nombre='".$txtNombre."', apellido='".$txtApellido."', direccion='".$txtDireccion."', numero_casa='".$txtCasa."', cedula='".$txtCedula."', telefono='".$txtTelefono."', movil='".$txtMovil."', email='".$txtEmail."', tipo_cliente='".$cmbTipoCliente."', prop_nombre='".$txtPropNombre."', prop_telefono='".$txtPropTelefono." ' where clientes.`id_empresa`='".$sesion_id_empresa."' and clientes.id_cliente=".$txtIdCliente."; ";
              //echo "sql2";
			  //echo $sql3;
			  $resp3 = mysql_query($sql3);
              if($resp3)
			  {
                ?> <div class='transparent_ajax_correcto'><p>Registro modificado correctamente.</p></div> <?php
              }
			  else
			  {
                ?> <div class='transparent_ajax_error'><p>Error al guarda los datos: problemas en la consulta</p></div> <?php
              }
			}
			else
			{
              ?> <div class="transparent_ajax_error"><p>Error: No hay datos</p></div> <?php
            }
		}
		catch (Exception $e) 
		{
    // Error en algun momento.
		?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
		}
	}
	  
    
 
	if($accion == 8)
	{
    // ELIMINA CARGOS PAGINA: cargos.php
		try
		{
			if(isset ($_POST['id_cliente']))
			{
				$id_cliente = $_POST['id_cliente'];
				$sql4 = "delete from clientes where id_cliente='".$id_cliente."'; ";
		//  echo $sql4;
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
              ?> <div class="transparent_ajax_error"><p>Error: No hay datos</p></div> <?php
            }
		}
		catch (Exception $e)
		{
    // Error en algun momento.
		?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> 
		<?php
		}

    }
      if($accion == 20){

                    //   echo "==>1";
                //    include('../conexion.php');
                   include('../conexion2.php');
                   require_once('../vendor/php-excel-reader/excel_reader2.php');
                   require_once('../vendor/SpreadsheetReader.php');
            //   echo "==>2";
            
                   $allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
            
                   if(in_array($_FILES["file"]["type"],$allowedFileType)){
            
                     $targetPath = 'subidas/clientes/'.$_FILES['file']['name'];
                     move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
                //   echo "==>3";
                     $Reader = new SpreadsheetReader($targetPath);
                     $con = $conexion;
                //   $sheetCount = count($Reader->sheets());
                     $sheetCount = count($Reader->sheets());
                     for($i=0;$i<$sheetCount;$i++)
                     {
                    //   echo $i;
                      $Reader->ChangeSheet($i);
                //   var_dump($Reader);
            
                //  echo "==>4";
                      foreach ($Reader as $Row)
                      {
                      
                
                        $fecha_registro= date('Y-m-d');
                        $ano= date('Y');
                        $mes = date('m');
                        $id_empresa = $sesion_id_empresa;
                       
                        $txtNombre = (isset($Row[0]) || $Row[0]=='')? mysqli_real_escape_string($con,$Row[0]): 'Indefinido' ;
                        $txtApellido = (isset($Row[1]) || $Row[1]=='')? mysqli_real_escape_string($con,$Row[1]): 'Indefinido' ;
                        $txtDireccion= (isset($Row[2]) || $Row[2]=='')? mysqli_real_escape_string($con,$Row[2]): 'Indefinido' ;
                        $txtCedula = (isset($Row[3]) || $Row[3]=='')? mysqli_real_escape_string($con,$Row[3]): '00000000' ;
                        $txtTelefono = (isset($Row[4]) || $Row[4]=='')? mysqli_real_escape_string($con,$Row[4]): '00000000' ;
                        $txtMovil = (isset($Row[5]) || $Row[5]=='')? mysqli_real_escape_string($con,$Row[5]): '00000000' ;
                        $txtEmail = (isset($Row[6]) || $Row[6]=='')? mysqli_real_escape_string($con,$Row[6]): 'indefinido@gmail.com' ;
                        $cmbEstado=  (isset($Row[7]) || $Row[7]=='')? mysqli_real_escape_string($con,$Row[7]): 'Activo' ;
                        $fecha_registro = date('Y-m-d');

                        $nombre_ciudad  = (isset($Row[8]) || $Row[8]=='')? mysqli_real_escape_string($con,$Row[8]): '' ;
                        $id_ciudad=0;
                        if($nombre_ciudad!=''){
                            $nombre_ciudad= strtoupper(trim($nombre_ciudad));
                            $sql="SELECT `id_ciudad`, `ciudad` FROM `ciudades` WHERE  ciudad ='".$nombre_ciudad."' ";
                            $result[$z] = mysql_query($sql);
                            while($row= mysql_fetch_array( $result[$z])){
                                $id_ciudad=$row['id_ciudad'];
                            }
                        }
                      
                       
                        $numero_cargas  = (isset($Row[9]) || $Row[9]=='')? mysqli_real_escape_string($con,$Row[9]): '0' ;
                        $estado_civil  = (isset($Row[10]) || $Row[10]=='')? mysqli_real_escape_string($con,$Row[10]): 'Indefinido' ;
                        $tipo  = (isset($Row[11]) || $Row[11]=='')? mysqli_real_escape_string($con,$Row[11]): '0' ;
                        $txtNumeroCasa  = '' ;
                        $cmbVendedor  = '' ;
                        $rb = '0';
                        if(isset($Row[12])){
                            if(strtoupper(trim($Row[12]))=='RUC'){
                                $rb = '04';
                            }
                            if( strtoupper(trim($Row[12]))=='CEDULA'){
                                $rb = '05';
                            }
                            if( strtoupper(trim($Row[12]))=='PASAPORTE'){
                                $rb = '06';
                            }
                            if( strtoupper(trim($Row[12]))=='CONSUMIDORFINAL' || strtoupper(trim($Row[12]))=='CONSUMIDOR FINAL'){
                                $rb = '07';
                            }

                        }
                        $rbCaracterIdentificacion  = $rb ;//04 ruc, 05 cedula, 06pasaporte, 07 consumidorFinal
                        $txtResponsable  =  '' ;
                        $txtLimiteCredito  = '0' ;
                        $txtDescuento  = '0' ;
                        $cmbDiasPlazo  = '0' ;
                        $cmbTipoPrecio  =  '0' ;
                        $cmbTipoCliente  =  '0' ;
                        $propNombre  ='' ;
                        $propTelefono  = '' ;
                        $txtObservaciones  = (isset($Row[13]) || $Row[13]=='')? mysqli_real_escape_string($con,$Row[13]): '' ;
                      
                      echo  $sql1 = "insert into clientes ( nombre, apellido, direccion, cedula, telefono, movil, fecha_nacimiento, email, estado, id_ciudad, fecha_registro, numero_cargas, estado_civil, tipo, numero_casa, id_empresa, id_vendedor, caracter_identificacion, reponsable, limite_credito, descuento, dias_plazo, tipo_precio, tipo_cliente, prop_nombre, prop_telefono, observacion) values ( '".$txtNombre."', '".$txtApellido."', '".$txtDireccion."', '".$txtCedula."', '".$txtTelefono."', '".$txtMovil."', '', '".$txtEmail."', '".$cmbEstado."', '".$id_ciudad."', '".$fecha_registro."', '".$numero_cargas."', '".$estado_civil."', '".$tipo."', '".$txtNumeroCasa."', '".$sesion_id_empresa."', '".$cmbVendedor."', '".$rbCaracterIdentificacion."', '".$txtResponsable."', '".$txtLimiteCredito."', '".$txtDescuento."', '".$cmbDiasPlazo."', '".$cmbTipoPrecio."','".$cmbTipoCliente."','".$propNombre."','".$propTelefono."', '".$txtObservaciones."');";
			//echo $sql1;
			$result1=mysql_query($sql1);
            
                        if (! empty($resultados)) {
                          $type = "success";
                          $message = "Excel importado correctamente";
                        } else {
                          $type = "error";
                          $message = "Hubo un problema al importar registros";
                        }
                        //  }
                      }
            
                    }
                  }
                  else
                  { 
                    $type = "error";
                    $message = "El archivo enviado es invalido. Por favor vuelva a intentarlo";
                  }
                }
?>


