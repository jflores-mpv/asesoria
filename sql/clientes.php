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

function agregarOQuitarPuntoyComa($texto) {
    // Verificar si el último carácter es ;
    if (substr($texto, -1) !== ';') {
        // Agregar ; al final si no está presente
        $texto .= ';';
    }

    return $texto;
}

	if($accion == 1)
	{
        // GUARDAR CLIENTES
        try 
		{
            $rbCaracterIdentificacion = $_POST['rbCaracterIdentificacion'];
            $txtNombre = $_POST['txtNombre'];
            $txtApellido = $_POST['txtApellido'];
            $txtCedula = $_POST['txtCedula'];
            
            $txtNacionalidad = isset($_POST['txtNacionalidad'])?$_POST['txtNacionalidad']:'';
             
            $txtDireccion = $_POST['txtDireccion'];
            $txtTelefono = $_POST['txtTelefono'];
            $txtMovil = $_POST['txtMovil'];
            $txtEmail = $_POST['txtEmail'];
            $txtNumeroCasa = $_POST['txtNumeroCasa'];
            $cmbEstado = 'Activo';
            $id_ciudad = $_POST['cbciudad'];
          

            $txtLimiteCredito = "0";
            $txtDescuento = "0";
            $cmbDiasPlazo = "0";
            $cmbVendedor = "0";
            if(isset($_POST['cbVendedor']) ){
                if($_POST['cbVendedor']!=''){
                     $cmbVendedor = $_POST['cbVendedor'];
                }
            }
            
            $cmbTipoPrecio = "0";
            $cmbTipoCliente="0";
            
			$propNombre=$_POST['txtPropNombre'];
			$propTelefono=$_POST['txtPropTelefono'];
            $txtObservaciones = $_POST['txtObservacion'];
            $fecha_registro = date("Y-m-d h:i:s");
            $numero_cargas = "0";
            $estado_civil = "";
            $tipo = '0';
            
            
            
            
            
            if ($_POST['cbciudad'] !='')
            {
                $id_ciudad = $_POST['cbciudad'];
            }
            else
            {
                $id_ciudad=0;
            }
          //  echo $id_ciudad;
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
			$exepciones[]=$ex;
			}
            
            if( $txtCedula!=='' && $txtDireccion!=='' ){
                
            
            $sqlValidaCedula="SELECT `id_cliente`, `nombre`, `apellido`, `direccion`, `cedula`,`id_empresa` FROM `clientes` WHERE cedula='".$txtCedula."' AND id_empresa=$sesion_id_empresa";
            $resultValidaCedula= mysql_query($sqlValidaCedula);
            $numFilasValidaCedula = mysql_num_rows($resultValidaCedula);
            
            if($numFilasValidaCedula>0){
                
			 $response['clientes']='3';   
               echo json_encode(['resultado'=>$response,'exepciones'=>$exepciones]);
                exit;
            }
            $sql1 = "insert into clientes (id_cliente,       nombre,           apellido,            direccion,           cedula,          telefono,            movil,       fecha_nacimiento,     email,        estado,           id_ciudad,         fecha_registro,        numero_cargas, estado_civil, tipo, numero_casa, id_empresa, id_vendedor, caracter_identificacion, reponsable, limite_credito, descuento, dias_plazo, tipo_precio, tipo_cliente, prop_nombre, prop_telefono, observacion , empresaCliente,nacionalidad) 
            values ('".$id_cliente."', '".$txtNombre."', '".$txtApellido."', '".$txtDireccion."', '".$txtCedula."', '".$txtTelefono."', '".$txtMovil."', '".$fecha_registro."' ,        '".$txtEmail."', '".$cmbEstado."', '".$id_ciudad."', '".$fecha_registro."', '".$numero_cargas."', '".$estado_civil."', '".$tipo."', '".$txtNumeroCasa."', '".$sesion_id_empresa."', '".$cmbVendedor."', '".$rbCaracterIdentificacion."', '".$txtResponsable."', '".$txtLimiteCredito."', '".$txtDescuento."', '".$cmbDiasPlazo."', '".$cmbTipoPrecio."','".$cmbTipoCliente."','".$propNombre."','".$propTelefono."', '".$txtObservaciones."', '".$tipo."','".$txtNacionalidad."');";
			    $result1=mysql_query($sql1);
	
			 $response['id_cliente']=$id_cliente;
			 $response['clientes']=($result1)?'1':'Error al guarda los datos: problemas en la consulta';   

          
            
		}else{
		   $campos= ($txtCedula=='')?'-cedula':'';
		   $campos.=($txtDireccion=='')?' -direccion':'';
		  //  $campos.=($txtEmail=='')?' -email':'';
		   	 $response['datos']='Existen campos vacios:'.$campos;   
		   	
		}    
            

        }catch(Exception $ex) { 
            $exepciones[]=$ex;
             }
                 echo json_encode(['resultado'=>$response,'exepciones'=>$exepciones]);
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
         // echo $sql;
          $resp = mysql_query($sql);
          $entro=0;
		    $fila=mysql_num_rows($resp);
            	if ($fila>0)
            		{ $entro=1;
            		    
            		}else{
            		    $entro=0;
            		}
            echo $entro;
         }

        }catch(Exception $ex) { ?> <div class="alert alert-danger"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
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
                WHERE clientes.`id_empresa`='".$sesion_id_empresa."' and CONCAT(clientes.`cedula`, clientes.`nombre`, clientes.`apellido`) LIKE '%$queryString%'  order by clientes.`nombre` asc LIMIT 10; ";

                $result = mysql_query($query) or die(mysql_error());

                $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
                if($result) {
                    if($numero_filas == 0){
                        echo "<center><p><div class='alert alert-danger'> No hay resultados con el parámetro ingresado. </label></p></center>";
                        echo "<a href='javascript: fn_cerrar_div();'><span class='fa fa-close' aria-hidden='true'></span></a>";
                        
                    }else{
                        echo $row["clientes_estado"];
                        // While there are results loop through them - fetching an Object (i like PHP5 btw!). overTable
                        echo "<table border='1'  class='table table-bordered table-hover'><tr><th class='w-25'>Cedula</th><th class='w-25'>Nombre</th>
                        <th class='w-25'>Direccion</th><th class='w-25'>Telefono</th><th class='w-25'>Estado</th><th><a href='javascript: fn_cerrar_div();'><span class='fa fa-close' aria-hidden='true'></span></a></th></tr>";
                        while ($row = mysql_fetch_array($result)) {
                            
                             if($row["clientes_estado"]=='Activo'){
                               echo '<tr title="Clic para seleccionar" onClick="fill9(\''.$row['clientes_nombre']."*".$row['clientes_apellido']."*".$row['clientes_cedula']."*".$row["clientes_telefono"]."*".$row['clientes_direccion']."*".$row["clientes_id_cliente"]."*".$row["clientes_caracter_identificacion"]."*".$row["clientes_id_vendedor"].  '\');" >';

                            }else{
                                 echo '<tr>';
                            }
                            echo "<td>".$row["clientes_cedula"]."</td>";
                            echo "<td>".$row["clientes_nombre"]." ".$row["clientes_apellido"]."</td>";
                            echo "<td>".$row["clientes_direccion"]."</td>";
                            echo "<td>".$row["clientes_telefono"]."</td>";
                            echo "<td>".$row["clientes_estado"]."</td>";
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
        $rbCaracterIdentificacion = $_POST['rbCaracterIdentificacion'];
			$txtIdCliente = $_POST['txtIdCliente'];
			$txtNombre = ucwords($_POST['txtNombre']);
			$txtApellido = ucwords($_POST['txtApellido']);
			$txtDireccion = ucwords($_POST['txtDireccion']);
			$txtCedula = ucwords($_POST['txtCedula']);
			$txtTelefono = ucwords($_POST['txtTelefono']);
			 
			$txtMovil = ucwords($_POST['txtMovil']);
			$txtEmail = $_POST['txtEmail'];
			$txtCasa = ucwords($_POST['txtCasa']);
			$cmbTipoCliente = ucwords($_POST['cmbTipoCliente']);
					
		$txtNacionalidad = isset($_POST['txtNacionalidad'])?$_POST['txtNacionalidad']:'';
		
            $txtObservacion = ucwords($_POST['txtObservacion']);
            $cbprovincia = ucwords($_POST['cbprovincia']);
            $cbciudad = ucwords($_POST['cbciudad']);
            $switch_estado = isset($_POST['switch-estado'])?$_POST['switch-estado']:'';
            
            if($switch_estado==''){
                $switch_estado='Activo';
            }
            
            $cmbVendedor = "0";
            if(isset($_POST['cbVendedor']) ){
                if($_POST['cbVendedor']!=''){
                     $cmbVendedor = $_POST['cbVendedor'];
                }
            }
            
             // $sql3 = "update clientes SET nombre='".$txtNombre."', sueldo='".$txtSueldo."', id_departamento='".$cmbDepartamento."' where id_cargo='".$txtIdCargo."'; ";
              $sql3 = "update clientes SET nombre='".$txtNombre."', apellido='".$txtApellido."', direccion='".$txtDireccion."', cedula='".$txtCedula."', telefono='".$txtTelefono."', movil='".$txtMovil."', email='".$txtEmail."', tipo_cliente='0',  observacion='".$txtObservacion."' , id_ciudad='".$cbciudad."', caracter_identificacion='".$rbCaracterIdentificacion."',  nacionalidad='".$txtNacionalidad."', estado ='".$switch_estado."', id_vendedor ='".$cmbVendedor."'  where clientes.`id_empresa`='".$sesion_id_empresa."' and clientes.id_cliente=".$txtIdCliente."; ";
        	  $resp3 = mysql_query($sql3);
    //   echo $sql3;
              if($resp3)
			  {
                ?><?php echo 1?> <?php
              }
			  else
			  {
                ?><?php echo 2?><?php
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
        $id_cliente=$_POST['id_cliente'];

			if(isset ($id_cliente))
			{
			    
		$sqlClienteVenta="SELECT id_cliente FROM `ventas` WHERE id_empresa='$sesion_id_empresa' and id_cliente='".$id_cliente."'";
// 		echo $sqlClienteVenta;
        $resultClienteVenta=mysql_query($sqlClienteVenta);
        ////while($row1=mysql_fetch_array($resultClienteVenta))
          $filaClientes=mysql_num_rows($resultClienteVenta);
        //   echo $filaClientes;
          
          
            	if ($filaClientes>0)
            		{ 	
            		    
            		    echo "3";
     
            		}else{
               		   //  echo "1";
        				$sql4 = "delete from clientes where id_cliente='".$id_cliente."'; ";
        				$resp4 = mysql_query($sql4);
        				if($resp4)
        				{
        					echo "1";
        				}else
        				{
        					echo "2";
        				}
            		}
			    
			    
			
			}
			else
			{
              ?> <div class="transparent_ajax_error"><p>Error: No hay datos</p></div> <?php
            }
	}
    
    
    if($accion == 9){
        // VALIDACIONES PARA QUE EL EMAIL DEL CLIENTE NO SE REPITA
        try{
            if(isset ($_POST['email'])){

                $email = $_POST['email'];
                $sql4="Select email From leads WHERE email ='".$email."' and id_empresa='".$sesion_id_empresa."';";
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
    
     if($accion == 10){
        // VALIDACIONES PARA QUE EL EMAIL DEL CLIENTE NO SE REPITA
        try{
            if(isset ($_POST['telefono'])){
                
                
                $telefono = $_POST['telefono'];
                $sql4="Select telefono From leads WHERE telefono ='".$telefono."' and id_empresa='".$sesion_id_empresa."';";
                  $resp4 = mysql_query($sql4);
                $entro=0;
                while($row4=mysql_fetch_array($resp4))//permite ir de fila en fila de la tabla
                {
                    $var1=$row4["telefono"];
                }
                $telefono = strtolower($telefono);
                if($var1==$telefono){
                       if($var1==""&&$telefono==""){
                         $entro=0;
                }else {
                          $entro=1;
                      }
                }
                echo $entro;
            }

        }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
    }
    
     if($accion == '13'){
        if(isset($_POST['queryString'])) {
            $queryString = $_POST['queryString'];
            // Is the string length greater than 0?
            if(strlen($queryString) >0) {
                $query = "SELECT `id`, `idCliente`, clientes.nombre,apellido,cedula, `modelo`, `color`, `chasis`, `motor`, `placa`, `modeloMotor`, `kilometraje` FROM `vehiculos` 
                INNER JOIN clientes ON clientes.id_cliente=vehiculos.idCliente WHERE clientes.id_empresa='".$sesion_id_empresa."' and placa LIKE '%$queryString%' ";
                $cliente= $_POST['cliente'];
                
                if($cliente!==''){
                    $query.="  and vehiculos.idCliente='".$cliente."'";
                }
                $query.=" order by placa asc LIMIT 10;";

                $result = mysql_query($query) or die(mysql_error());

                $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
                if($result) {
                    if($numero_filas ==0){
                     //  echo $query;
                        echo "<center><p><div class='alert alert-danger'> No hay resultados con el parámetro ingresado. </div></p></center>  <div class='col-lg-3'><label class='form-label' > </label> <button type='button' class='btn btn-success' data-bs-toggle='modal' data-bs-target='#staticBackdrop'> Nuevo Vehiculo </button>
                    </div>";
                    }else{
                  //    echo $query;
                        // While there are results loop through them - fetching an Object (i like PHP5 btw!). overTable
                        echo "<table border='1'  class='table table-bordered table-hover' ><tr><th>Cliente</th><th>Placa</th><th>Modelo</th><th>Color</th><th>Chasis</th><th>Motor</th><th>kilometraje</th></tr>";
                        while ($row = mysql_fetch_array($result)) {
                            echo '<tr title="Clic para seleccionar" onClick="fillPlaca(\''.$row['cedula']."*".$row["nombre"]."*".$row["apellido"]."*".$row["placa"]."*".$row["idCliente"].'\');" >';
                             echo "<td>".$row["nombre"]."</td>";
                             echo "<td>".$row["placa"]."</td>";
                            echo "<td>".$row["modelo"]."</td>";
                            echo "<td>".$row["color"]."</td>";
                            echo "<td>".$row["chasis"]."</td>";
                            echo "<td>".$row["motor"]."</td>";
                            echo "<td>".$row["kilometraje"]."</td>";
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
        if($accion == '15'){
                    if(isset($_POST['queryString'])) {
                        $queryString = $_POST['queryString'];
                        $parametro= $_POST['parametro'];

                        // compruebo que la placa tenga datos
                        if(strlen($queryString) >0) {

                               $sqlPlaca="SELECT id,placa from vehiculos where placa= '$queryString' LIMIT 1";
                                $resulPlaca=mysql_query($sqlPlaca);
                                while($rowP=mysql_fetch_array($resulPlaca))
                                {  $vehiculo=$rowP['id'];  } //Saco el id del vehiculo mediante la placa

                            //Hago una consulta para conocer los posibles ingresos o avaluos 
                            if($parametro=='2'){
                                $query = 'SELECT `id`, `fecha`, `id_vehiculo`, `id_usuario`, IF( codigo_estado="I","Ingresado","Facturado") as codigo_estado , `observaciones`, IF( tipo=1,"Avaluo","Ingreso") as tipo FROM `ingreso_vehiculos`  WHERE id_vehiculo="'.$vehiculo.'" and codigo_estado="I" ';
                            }else{
                                $query = 'SELECT `id`, `fecha`, `id_vehiculo`, `id_usuario`, IF( codigo_estado="I","Ingresado","Facturado") as codigo_estado, `observaciones` , IF( tipo=1,"Avaluo","Ingreso") as tipo FROM `ingreso_vehiculos`  WHERE id_vehiculo="'.$vehiculo.'" and codigo_estado="I" and tipo="'.$parametro.'" ';
                            }
                          
                           
                            $query.=" order by id asc LIMIT 10;";
            
                            $result = mysql_query($query) or die(mysql_error());
            
                            $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
                            if($result) {
                                if($numero_filas == 0){
                                 //  echo $query;
                                    echo "<center><p><div class='alert alert-danger'> No hay resultados con el parámetro ingresado. </div></p></center>";
                                }else{
                               // echo $query;
                                    // While there are results loop through them - fetching an Object (i like PHP5 btw!). overTable
                                    echo "<table border='1'  class='table table-bordered table-hover' ><tr><th>Fecha</th><th>Codigo estado</th><th>Observaciones</th><th>Tipo</th></tr>";
                                    while ($row = mysql_fetch_array($result)) {
                                        echo '<tr title="Clic para seleccionar" onClick="fillIngreso(\''.$row['id']."*".$row["fecha"]."*".$row["id_vehiculo"]."*".$row["codigo_estado"]."*".$row["observaciones"].'\');" >';
                                         echo "<td>".$row["fecha"]."</td>";
                                         echo "<td>".$row["codigo_estado"]."</td>";
                                        echo "<td>".$row["observaciones"]."</td>";
                                        echo "<td>".$row["tipo"]."</td>";
                                     
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
    
       if($accion == 16){
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
                        echo "<center><p><div class='alert alert-danger'> 1 == No hay resultados con el parámetro ingresado. </label></p></center>";
                    }else{
                        // While there are results loop through them - fetching an Object (i like PHP5 btw!). overTable
                        echo "<table border='1'  class='table table-bordered table-hover' ><tr><th>Id</th><th>Cedula</th><th>Nombre</th><th>Direccion</th><th>Telefono</th></tr>";
                        while ($row = mysql_fetch_array($result)) {
                            echo '<tr title="Clic para seleccionar" onClick="fill99(\''.$row['clientes_nombre']."*".$row['clientes_apellido']."*".$row['clientes_cedula']."*".$row["clientes_telefono"]."*".$row['clientes_direccion']."*".$row["clientes_id_cliente"].'\');" >';
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
 if($accion == 17){
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
                        echo "<center><p><div class='alert alert-danger'>2== No hay resultados con el parámetro ingresado. </label></p></center>";
                    }else{
                        // While there are results loop through them - fetching an Object (i like PHP5 btw!). overTable
                        echo "<table border='1'  class='table table-bordered table-hover' ><tr><th>Id</th><th>Cedula</th><th>Nombre</th><th>Direccion</th><th>Telefono</th></tr>";
                        while ($row = mysql_fetch_array($result)) {
                            echo '<tr title="Clic para seleccionar" onClick="fillCliente(\''.$row['clientes_nombre']."*".$row['clientes_apellido']."*".$row['clientes_cedula']."*".$row["clientes_telefono"]."*".$row['clientes_direccion']."*".$row["clientes_id_cliente"].'\');" >';
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
                       
                        $txtNombre = ( $Row[0]!='')? mysqli_real_escape_string($con,$Row[0]): 'Indefinido' ;
                        $txtApellido = ( $Row[1]!='')? mysqli_real_escape_string($con,$Row[1]): 'Indefinido' ;
                        $txtDireccion= ( $Row[2]!='')? mysqli_real_escape_string($con,$Row[2]): 'Indefinido' ;
                     $txtCedula = (  trim($Row[3] )!='' ) ? mysqli_real_escape_string($con, strval( $Row[3] ) ) : '00000000';
                        $txtCedula = trim($txtCedula);
                        $txtTelefono = ( trim($Row[4])!='')? mysqli_real_escape_string($con,$Row[4]): '000000000' ;
                        $txtMovil = ( trim($Row[5])!='')? mysqli_real_escape_string($con,$Row[5]): '000000000' ;
                        $txtEmail = ( $Row[6]!='')? mysqli_real_escape_string($con,$Row[6]): 'indefinido@gmail.com' ;
                        $cmbEstado=  'Activo' ;
                        $fecha_registro = date('Y-m-d');

                        // $nombre_ciudad  = (isset($Row[8]) || $Row[8]=='')? mysqli_real_escape_string($con,$Row[8]): '' ;
                        // $id_ciudad=0;
                        // if($nombre_ciudad!=''){
                        //     $nombre_ciudad= strtoupper(trim($nombre_ciudad));
                        //     $sql="SELECT `id_ciudad`, `ciudad` FROM `ciudades` WHERE  ciudad ='".$nombre_ciudad."' ";
                        //     $result[$z] = mysql_query($sql);
                        //     while($row= mysql_fetch_array( $result[$z])){
                        //         $id_ciudad=$row['id_ciudad'];
                        //     }
                        // }
                      
                       $id_ciudad= (trim($_SESSION['sesion_empresa_id_ciudad'])!='')?$_SESSION['sesion_empresa_id_ciudad']:0;
                        $numero_cargas  =  '0' ;
                        $estado_civil  = 'Soltero' ;
                        $tipo  = ( $Row[7]!='')? mysqli_real_escape_string($con,$Row[7]): '0' ;
                        $txtNumeroCasa  = '' ;
                        $cmbVendedor  = '0' ;
                        // $rb = '0';
                     // Nueva condición para determinar el valor de $rb
                        if (strlen($txtCedula) == 10) {
                            $rb = '05';
                        } elseif (strlen($txtCedula) == 13) {
                            $rb = '04';
                        }else{
                             $rb = '06';
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
                        $txtObservaciones  = ( $Row[8]!='')? mysqli_real_escape_string($con,$Row[8]): '' ;
                      
                        $sql1 = "insert into clientes ( nombre, apellido, direccion, cedula, telefono, movil, email, estado, id_ciudad, fecha_registro, numero_cargas, estado_civil, tipo, numero_casa, id_empresa, id_vendedor, caracter_identificacion, reponsable, limite_credito, descuento, dias_plazo, tipo_precio, tipo_cliente, prop_nombre, prop_telefono, observacion) values ( '".$txtNombre."', '".$txtApellido."', '".$txtDireccion."', '".$txtCedula."', '".$txtTelefono."', '".$txtMovil."', '".$txtEmail."', '".$cmbEstado."', '".$id_ciudad."', '".$fecha_registro."', '".$numero_cargas."', '".$estado_civil."', '".$tipo."', '".$txtNumeroCasa."', '".$sesion_id_empresa."', '".$cmbVendedor."', '".$rbCaracterIdentificacion."', '".$txtResponsable."', '".$txtLimiteCredito."', '".$txtDescuento."', '".$cmbDiasPlazo."', '".$cmbTipoPrecio."','".$cmbTipoCliente."','".$propNombre."','".$propTelefono."', '".$txtObservaciones."');";
			//echo $sql1;
			$result1=mysql_query($sql1);
            
                        if (! empty($result1)) {
                          $type = "success";
                          $message = "Excel importado correctamente";
                        } else {
                          $type = "error";
                          $message = "Hubo un problema al importar registros";
                        }
                        //  }
                      }
            
                    }
                    echo $message;
                  }
                  else
                  { 
                    $type = "error";
                    $message = "El archivo enviado es invalido. Por favor vuelva a intentarlo";
                  }
                }
    
     if($accion == 21){
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
                        echo "<center><p><div class='alert alert-danger'> 3==No hay resultados con el parámetro ingresado. </label></p></center>";
                        echo '<input type="button" class="btn btn-success" value="Nuevo Cliente" onclick="residentes()">';
                    }else{
                        // While there are results loop through them - fetching an Object (i like PHP5 btw!). overTable
                        echo "<table border='1'  class='table table-bordered table-hover' ><tr><th>Id</th><th>Cedula</th><th>Nombre</th><th>Direccion</th><th>Telefono</th></tr>";
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
    
       if($accion == 66){
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
                WHERE clientes.`id_empresa`='".$sesion_id_empresa."' and CONCAT(clientes.`cedula`, clientes.`nombre`, clientes.`apellido`) LIKE '%$queryString%'  order by clientes.`nombre` asc LIMIT 10; ";

                $result = mysql_query($query) or die(mysql_error());

                $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
                if($result) {
                    if($numero_filas == 0){
                        echo "<center><p><div class='alert alert-danger'> 4==No hay resultados con el parámetro ingresado. </label></p></center>";
                    }else{
                        // While there are results loop through them - fetching an Object (i like PHP5 btw!). overTable
                        echo "<table border='1'  class='table table-bordered table-hover' ><tr><th>Id</th><th>Cedula</th><th>Nombre</th><th>Direccion</th><th>Telefono</th></tr>";
                        while ($row = mysql_fetch_array($result)) {
                            
                             if($row["clientes_estado"]=='Activo'){
                               echo '<tr title="Clic para seleccionar" onClick="fill9E(\''.$row['clientes_nombre']."*".$row['clientes_apellido']."*".$row['clientes_cedula']."*".$row["clientes_telefono"]."*".$row['clientes_direccion']."*".$row["clientes_id_cliente"].'\');" >';

                            }else{
                                 echo '<tr>';
                            }
                            
                            
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

//30 a 35  nuevo modulo clientes 
      if($accion == 30){
          // guardar responsables
          $id_cliente = $_POST['id_cliente'];
          $id_correos_afiliado = $_POST['id_correos_afiliado'];
          $txtNombreCorreo = $_POST['txtNombreCorreo'];
          $txtCorreoFacturacion = $_POST['txtCorreoFacturacion'];
    
        
        // $sql="INSERT INTO `responsables`(`nombre_responsable`, `apellido_responsable`, `correo_responsable`, `asistente`, `id_cliente`, `id_empresa`,titulo) VALUES ('".$nombresRepresentante."','".$apellidosRepresentante."','".$correoRepresentante."','".$asistenteRepresentante."','".$id_cliente."','".$sesion_id_empresa."','".$titulo."')";
        $sql=" INSERT INTO `correos_facturacion_afiliado`( `nombre`, `correo_facturacion`, `id_cliente`, `id_empresa`) VALUES ('".$txtNombreCorreo."','".$txtCorreoFacturacion."','".$id_cliente."','".$sesion_id_empresa."')";
       
        $result = mysql_query($sql);
        if($result){
            echo '1';
            $sqlCliente="SELECT `id_cliente`, `email`, `id_empresa` FROM `clientes` WHERE id_cliente=$id_cliente";
            $resultCliente = mysql_query($sqlCliente);
            $email='';
            while($rowCliente = mysql_fetch_array($resultCliente) ){
                $email = $rowCliente['email'];
            }
            $email_completo= agregarOQuitarPuntoyComa($email).agregarOQuitarPuntoyComa($txtCorreoFacturacion);
            
            $sqlActualizarCorreo = "UPDATE `clientes` SET `email`='".$email_completo."' WHERE id_cliente=$id_cliente";
            $resultActualizarCorreo = mysql_query($sqlActualizarCorreo);
          
            
        }else{
            echo '2';
        }
          
      }
        if($accion == 31){
          
          $id_cliente = $_POST['id_cliente'];
          $id_correos_afiliado = $_POST['id_correos_afiliado'];
          $txtNombreCorreo = $_POST['txtNombreCorreo'];
          $txtCorreoFacturacion = $_POST['txtCorreoFacturacion'];
          
    $sqlCorreoAnterior="SELECT `id_correos_afiliado`, `nombre`, `correo_facturacion`, `id_cliente`, `id_empresa` FROM `correos_facturacion_afiliado` WHERE id_correos_afiliado=$id_correos_afiliado";
    $resultCorreoAnterior = mysql_query($sqlCorreoAnterior);
    $correo_anterior = '';
    while($rowCA = mysql_fetch_array($resultCorreoAnterior) ){
        $correo_anterior = $rowCA['correo_facturacion'];
    }
    $sqlCliente="SELECT `id_cliente`, `email`  FROM `clientes` WHERE id_cliente=$id_cliente";
    $resultCliente = mysql_query($sqlCliente);
    $correo_cliente = '';
    while($rowCliente = mysql_fetch_array($resultCliente) ){
        $correo_cliente = $rowCliente['email'];
    }
    
        
        // $sql="UPDATE `responsables` SET `nombre_responsable`='".$nombresRepresentante."',`apellido_responsable`='".$apellidosRepresentante."',`correo_responsable`='".$correoRepresentante."',`asistente`='".$asistenteRepresentante."',`id_cliente`='".$id_cliente."',`id_empresa`='".$sesion_id_empresa."', titulo='".$titulo."'  WHERE id_responsable =$id_representante";
         $sql="UPDATE `correos_facturacion_afiliado` SET `nombre`='".$txtNombreCorreo."',`correo_facturacion`='".$txtCorreoFacturacion."' WHERE id_correos_afiliado=$id_correos_afiliado AND id_empresa=$sesion_id_empresa ";
        $result = mysql_query($sql);
        if($result){
            echo '3';
         
                  $resultado = str_replace($correo_anterior, $txtCorreoFacturacion, $correo_cliente);
                  if(trim($resultado)!=''){
                        $sqlCliente2="UPDATE `clientes` SET `email`='".$resultado."' WHERE id_cliente=$id_cliente";
                $resultCliente2 = mysql_query($sqlCliente2);
                  }
            
        
          
            
        }else{
            echo '4';
        }
          
      }
        if($accion == 32){
            
          // eliminar  responsables
        $id_cliente = $_POST['id_cliente'];
          $id_correos_afiliado = $_POST['id_correos_afiliado'];
        
        $sqlCliente="SELECT `id_cliente`, `email`  FROM `clientes` WHERE id_cliente=$id_cliente";
    $resultCliente = mysql_query($sqlCliente);
    $correo_cliente = '';
    while($rowCliente = mysql_fetch_array($resultCliente) ){
        $correo_cliente = $rowCliente['email'];
    }
    
        $sqlCorreoAnterior="SELECT `id_correos_afiliado`, `nombre`, `correo_facturacion`, `id_cliente`, `id_empresa` FROM `correos_facturacion_afiliado` WHERE id_correos_afiliado=$id_correos_afiliado";
    $resultCorreoAnterior = mysql_query($sqlCorreoAnterior);
    $correo_anterior = '';
    while($rowCA = mysql_fetch_array($resultCorreoAnterior) ){
        $correo_anterior = $rowCA['correo_facturacion'];
    }
    
        $sql="DELETE FROM `correos_facturacion_afiliado` WHERE id_correos_afiliado=$id_correos_afiliado";
        $result = mysql_query($sql);
        if($result){
            echo '5';
             $resultado = str_replace($correo_anterior.';', '', $correo_cliente);
                  if(trim($resultado)!=''){
                        $sqlCliente2="UPDATE `clientes` SET `email`='".$resultado."' WHERE id_cliente=$id_cliente";
                $resultCliente2 = mysql_query($sqlCliente2);
                  }
        }else{
            echo '6';
        }
          
      }
      if($accion == 33)
	{
    // GUARDA MODIFICACION Clientes PAGINA: cargos.php
		try
		{
		//echo "va llerr";
        $rbCaracterIdentificacion = $_POST['rbCaracterIdentificacion'];
			$txtIdCliente = $_POST['id_cliente'];
			$txtNombre = ucwords($_POST['txtNombre']);
			$txtApellido = ucwords($_POST['txtApellido']);
			$txtDireccion = ucwords($_POST['txtDireccion']);
			$txtCedula = ucwords($_POST['txtCedula']);
			$txtTelefono = ucwords($_POST['txtTelefono']);
			 
			$txtMovil = ucwords($_POST['txtMovil']);
			$txtEmail = $_POST['txtEmail'];
			$txtCasa = ucwords($_POST['txtCasa']);
			$cmbTipoCliente = ucwords($_POST['cmbTipoCliente']);
					
		$txtNacionalidad = isset($_POST['txtNacionalidad'])?$_POST['txtNacionalidad']:'';
		
            $txtObservacion = ucwords($_POST['txtObservacion']);
            $cbprovincia = ucwords($_POST['cbprovincia']);
            $cbciudad =  (trim($_POST['cbciudad'])=='')?0:$_POST['cbciudad']; 
            $switch_estado = isset($_POST['switch-estado'])?$_POST['switch-estado']:'';
            $txtRazonSocial = $_POST['txtRazonSocial'];
            if($switch_estado==''){
                $switch_estado='Activo';
            }
             

            $credito=(trim($_POST['txtDiasCredito'])=='')?0:$_POST['txtDiasCredito'];  
            $txtLimiteCredito = $_POST['txtLimiteCredito'];
            $contribuyente_especial= $_POST['switch-contribuyente'];
            $zona= $_POST['txtZona'];
            $codigo_interno= $_POST['txtCodigoInterno'];
            $fecha_constitucion = (trim($_POST['dateConstitucion'])=='')?date('Y-m-d'):$_POST['dateConstitucion'];
           
            $cbciudad2 = (trim($_POST['cbciudad2'])=='')?0:$_POST['cbciudad2'];
            $txtDireccion2 = $_POST['txtDireccion2'];
            $txtZona2 = $_POST['txtZona2'];
            $txtCapitalSuscrito = (trim($_POST['txtCapitalSuscrito'])=='')?0:$_POST['txtCapitalSuscrito'];
            $txtGrupoSeccional =(trim($_POST['txtGrupoSeccional'])=='')?0:$_POST['txtGrupoSeccional']; 
            $txtOficialCamara = $_POST['txtOficialCamara'];
            $delegadoResponsable = $_POST['delegadoResponsable'];
            
            $txtPagarMensual = (trim($_POST['txtPagarMensual'])=='')?0:$_POST['txtPagarMensual'];
            $txtPagarAnual = (trim($_POST['txtPagarAnual'])=='')?0:$_POST['txtPagarAnual'];
            $txtValorConvenio = (trim($_POST['txtValorConvenio'])=='')?0:$_POST['txtValorConvenio'];
            
            
            
            $campo_estado='estado_afiliado';
            if($switch_estado== 'Activo' || $switch_estado == 'Inactivo'){
                $campo_estado='estado';
            }
            
            // validar cambio de estado
            $sqlVerificarCambio="SELECT `id_cliente`, $campo_estado FROM `clientes` WHERE id_cliente=$txtIdCliente ";
            $resultVerificar= mysql_query($sqlVerificarCambio);
            while($rowV = mysql_fetch_array( $resultVerificar)){
                $estado_actual = $rowV[$campo_estado];
                if($estado_actual != $switch_estado){
                    $id_cliente = $txtIdCliente;
                    $fecha_actual= date('Y-m-d h:i:s');
                    
           
                    $sqlRegistros = "SELECT 
                    SUM(registros_cuentas_por_cobrar.saldo) as total_saldo_registros  ,
                    SUM(registros_cuentas_por_cobrar.valor - registros_cuentas_por_cobrar.saldo) as total_pagado_registros
                    FROM `registros_cuentas_por_cobrar` 
                    INNER JOIN clientes ON clientes.id_cliente = registros_cuentas_por_cobrar.id_cliente 
                    WHERE registros_cuentas_por_cobrar.`id_empresa` =$sesion_id_empresa AND registros_cuentas_por_cobrar.id_cliente IS NOT NULL AND registros_cuentas_por_cobrar.saldo > 0  AND clientes.numero_afiliado != 0 and registros_cuentas_por_cobrar.id_cliente=$id_cliente";
                    $resultRegistros =mysql_query($sqlRegistros);
                    $total_registros=0;
                    $total_pagado_registros = 0;
                    while($rowReg = mysql_fetch_array($resultRegistros)  ){
                        $total_registros = $rowReg['total_saldo_registros'];
                        $total_pagado_registros =  $rowReg['total_pagado_registros'];
                    }
                     $total_pagado_registros = (trim($total_pagado_registros)!='')?$total_pagado_registros:0;
                    $total_registros = (trim($total_registros)!='')?$total_registros:0;
                    
                    $sqlCuentasCobrar="SELECT 
                    SUM(saldo) AS total_cuentas_cobrar,
                     SUM( cuentas_por_cobrar.valor - cuentas_por_cobrar.saldo ) AS total_pagado_cuentas_cobrar
                     FROM `cuentas_por_cobrar` WHERE `id_empresa` = $sesion_id_empresa AND id_cliente= $id_cliente and membresia!=0 and tipo_documento=1 and saldo>0 ";
                    $resultCuentasCobrar =mysql_query($sqlCuentasCobrar);
                    $total_cuentas_cobrar=0;
                    $total_pagado_cuentas_cobrar = 0;
                    while($rowCC = mysql_fetch_array($resultCuentasCobrar)  ){
                        $total_cuentas_cobrar = $rowCC['total_cuentas_cobrar'];
                        $total_pagado_cuentas_cobrar = $rowCC['total_pagado_cuentas_cobrar'];
                    }
                    $total_cuentas_cobrar = (trim($total_cuentas_cobrar)!='')?$total_cuentas_cobrar:0;
                    $total_pagado_cuentas_cobrar = (trim($total_pagado_cuentas_cobrar)!='')?$total_pagado_cuentas_cobrar:0;
                    
                     $sqlEstado = "INSERT INTO `historial_estados_cliente`(`estado`, `fecha_inicio`, `id_cliente`, `id_empresa`,total_registros, total_cuentas_cobrar,total_pagado_registros_cobrar,total_pagado_cuentas_cobrar) VALUES ('".$switch_estado."','".$fecha_actual."','".$id_cliente."','".$sesion_id_empresa."','".$total_registros."' , '".$total_cuentas_cobrar."', '".$total_pagado_registros."', '".$total_pagado_cuentas_cobrar."' )";
                    $resultEstado = mysql_query($sqlEstado);
            	    
                }
            }
            
          

$txtTituloRepresentanteLegal =(trim($_POST['txtTituloRepresentanteLegal'])=='')?'Indefinido':$_POST['txtTituloRepresentanteLegal']; 
            $txtPrimerNombreRepresentanteLegal =(trim($_POST['txtPrimerNombreRepresentanteLegal'])=='')?'Indefinido':$_POST['txtPrimerNombreRepresentanteLegal'];  
            $txtSegundoNombreRepresentanteLegal =(trim($_POST['txtSegundoNombreRepresentanteLegal'])=='')?'Indefinido':$_POST['txtSegundoNombreRepresentanteLegal'];  
            $txtPrimerApellidoRepresentanteLegal =(trim($_POST['txtPrimerApellidoRepresentanteLegal'])=='')?'Indefinido':$_POST['txtPrimerApellidoRepresentanteLegal']; 
            
            $txtSegundoApellidoRepresentanteLegal =(trim($_POST['txtSegundoApellidoRepresentanteLegal'])=='')?'Indefinido':$_POST['txtSegundoApellidoRepresentanteLegal']; 
            $txtCorreoRepresentanteLegal = (trim($_POST['txtCorreoRepresentanteLegal'])=='')?'indefinido@correo.com':$_POST['txtCorreoRepresentanteLegal']; 
           
            $txtDirectorRepresentanteLegal =(trim($_POST['txtDirectorRepresentanteLegal'])=='')?'Indefinido':$_POST['txtDirectorRepresentanteLegal'];  
            
           
            $vendedor_id =(trim($_POST['vendedor_id'])=='')?'0':$_POST['vendedor_id'];  
            
             $recaudador_id =(trim($_POST['recaudador_id'])=='')?'0':$_POST['recaudador_id'];  
             
    $sql3 = "update clientes SET nombre='".$txtNombre."', apellido='".$txtApellido."', direccion='".$txtDireccion."', cedula='".$txtCedula."', telefono='".$txtTelefono."', movil='".$txtMovil."', email='".$txtEmail."', tipo_cliente='0',  observacion='".$txtObservacion."' , id_ciudad='".$cbciudad."', caracter_identificacion='".$rbCaracterIdentificacion."',  nacionalidad='".$txtNacionalidad."', $campo_estado ='".$switch_estado."',razonSocial='".$txtRazonSocial."', zona= '".$zona."',codigo_interno = '".$codigo_interno."', credito= '".$credito."', contribuyente_especial = '".$contribuyente_especial."', id_membresia='".$txtLimiteCredito."',fecha_constitucion= '".$fecha_constitucion."',ciudad2 ='".$cbciudad2."',direccion2 ='".$txtDireccion2."' , zona2='". $txtZona2."' , capital_suscrito='".$txtCapitalSuscrito."' ,grupo_seciconal='".$txtGrupoSeccional."',delegado='".$delegadoResponsable."', oficial='".$txtOficialCamara."'  ,valor_convenio='". $txtValorConvenio."' ,pagar_anual='". $txtPagarAnual."' ,pagar_mensual='". $txtPagarMensual."' ,titulo_representante_legal='". $txtTituloRepresentanteLegal."' ,primer_nombre_representante_legal='". $txtPrimerNombreRepresentanteLegal."' ,segundo_nombre_representante_legal='". $txtSegundoNombreRepresentanteLegal."' ,primer_apellido_representante_legal='". $txtPrimerApellidoRepresentanteLegal."' ,segundo_apellido_representante_legal='". $txtSegundoApellidoRepresentanteLegal."' ,correo_representante_legal='". $txtCorreoRepresentanteLegal."'  ,nombre_director='". $txtDirectorRepresentanteLegal."' ,id_recaudador='". $recaudador_id."', 	id_vendedor= '". $vendedor_id."'
              where clientes.`id_empresa`='".$sesion_id_empresa."' and clientes.id_cliente=".$txtIdCliente."  ";
        	  $resp3 = mysql_query($sql3);
    //   echo $sql3;
              if($resp3)
			  {
			      if($sesion_id_empresa==41 || $sesion_id_empresa == '116' ||$sesion_id_empresa == '1827'  ){

                    // Fecha actual
                    $fechaActual2 = new DateTime();
                    
                    // Fecha de diciembre de este año
                    $fechaDiciembre = new DateTime(date("Y") . "-12-31");
                    
                    // Calcula la diferencia entre las fechas
                    $diferencia = $fechaActual2->diff($fechaDiciembre);
                    
                    // Obtiene el número de meses restantes
                    $mesesRestantes = $diferencia->format("%m");

        
	               $fechaActual = new DateTime();
                   
                    $sqlMembresia="SELECT `id_membresia`, `descripcion_membresia`, `dias_membresia`, `id_empresa` FROM `membresias` WHERE id_membresia=$txtLimiteCredito  ";
                    $resultMembresia=mysql_query($sqlMembresia);
                    $diasCredito=0;
                  while($verMembresia=mysql_fetch_array($resultMembresia)){
                      $diasCredito = intval($verMembresia['dias_membresia']);
                      }
                    $sumarDias= $diasCredito;
                    $mesesRestantes=0;
                    for ($i = 0; $i < $mesesRestantes; $i++) {
                       
                         $fechaDePago = clone $fechaActual;
                         $fechaDePago->add(new DateInterval("P{$sumarDias}D"));
                         $fechaDePagoFormateada = $fechaDePago->format("Y-m-d");
                    
                        // Aquí puedes realizar tu inserción en la base de datos
                    //   $sql4 = "INSERT INTO cuentas_por_cobrar (tipo_documento, numero_factura, referencia, valor, saldo, numero_asiento, fecha_vencimiento, id_cliente, id_empresa, estado) VALUES ('CONVENIO', '0', '" . $txtNombre . "', '" . $txtValorConvenio . "', '" . $txtValorConvenio . "', '', '" . $fechaDePagoFormateada . "', '" . $txtIdCliente . "', '" . $sesion_id_empresa . "', 'Pendientes');";
                    //     $result4 = mysql_query($sql4);
                        $sumarDias= $sumarDias+$diasCredito;
                    }

			      
			    
			      }
			      
			     
			     
                ?><?php echo 1?> <?php
              }
			  else
			  {
                ?><?php echo 2?><?php
              }

		}
		catch (Exception $e) 
		{
    // Error en algun momento.
		?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
		}
	}
	  
	 if($accion == 34)
	{
    // GUARDA MODIFICACION Clientes PAGINA: cargos.php
		try
		{
		//echo "va llerr";
        $rbCaracterIdentificacion = ($_POST['rbCaracterIdentificacion']=='')?'05':$_POST['rbCaracterIdentificacion'];
			$txtIdCliente = $_POST['id_cliente'];
			$txtNombre = ucwords($_POST['txtNombre']);
			$txtApellido = ucwords($_POST['txtApellido']);
			$txtDireccion = ucwords($_POST['txtDireccion']);
			$txtCedula = ucwords($_POST['txtCedula']);
			$txtTelefono = ucwords($_POST['txtTelefono']);
			 
			$txtMovil = ucwords($_POST['txtMovil']);
			$txtEmail = $_POST['txtEmail'];
			$txtCasa = ucwords($_POST['txtCasa']);
			$cmbTipoCliente = ucwords($_POST['cmbTipoCliente']);
					
		$txtNacionalidad = isset($_POST['txtNacionalidad'])?$_POST['txtNacionalidad']:'';
		
            $txtObservacion = ucwords($_POST['txtObservacion']);
            $cbprovincia = ucwords($_POST['cbprovincia']);
            $cbciudad = $_POST['cbciudad']==''?0:$_POST['cbciudad'];
            // $switch_estado = isset($_POST['switch-estado'])?$_POST['switch-estado']:'';
            $txtRazonSocial = $_POST['txtRazonSocial'];
         
                $switch_estado='Activo';
            
            $numeroAfiliado=0;
            $es_afiliado = $_POST['es_afiliado'];
            
             $txtFechaAfiliacion = '0000-00-00';
             $estado_afiliado = 'DESAFILIADO';
             
            if($es_afiliado=='SI'){
                
          
                    $sqlAfi="SELECT `id_cliente`, MAX(`numero_afiliado`) as numero_afiliado  FROM `clientes` WHERE id_empresa= $sesion_id_empresa ";
                    $resultAfi= mysql_query($sqlAfi);
                    while($rowAfi = mysql_fetch_array($resultAfi) ){
                        $numeroAfiliado = $rowAfi['numero_afiliado'];
                    }
                    $numeroAfiliado ++;

                  $txtFechaAfiliacion = date('Y-m-d');
                  $estado_afiliado = 'AL DIA';
            }
            
           
            $credito= $_POST['txtDiasCredito'];
            $txtLimiteCredito = $_POST['txtLimiteCredito'];
            $contribuyente_especial= $_POST['switch-contribuyente'];
            $zona= $_POST['txtZona'];
            $codigo_interno= $_POST['txtCodigoInterno'];
            $fecha_constitucion = (trim($_POST['dateConstitucion'])=='')?date('Y-m-d'):$_POST['dateConstitucion'];
           
            $cbciudad2 = (trim($_POST['cbciudad2'])=='')?0:$_POST['cbciudad2'];
            $txtDireccion2 = $_POST['txtDireccion2'];
            $txtZona2 = $_POST['txtZona2'];
            $txtCapitalSuscrito = trim($_POST['txtCapitalSuscrito'])==''?0:$_POST['txtCapitalSuscrito'];
            $txtGrupoSeccional = trim($_POST['txtGrupoSeccional'])==''?0:$_POST['txtGrupoSeccional'];
            $txtOficialCamara = trim($_POST['txtOficialCamara'])==''?0:$_POST['txtOficialCamara'];
            $delegadoResponsable = $_POST['delegadoResponsable'];
            
            $txtPagarMensual = (trim($_POST['txtPagarMensual'])=='')?0:$_POST['txtPagarMensual'];
            $txtPagarAnual = (trim($_POST['txtPagarAnual'])=='')?0:$_POST['txtPagarAnual'];
            $txtValorConvenio = (trim($_POST['txtValorConvenio'])=='')?0:$_POST['txtValorConvenio'];
            
            
// ,numero_afiliado='".$numeroAfiliado."'

 $sqlValidaCedula="SELECT `id_cliente`, `nombre`, `apellido`, `direccion`, `cedula`,`id_empresa` FROM `clientes` WHERE cedula='".$txtCedula."' AND id_empresa=$sesion_id_empresa";
            $resultValidaCedula= mysql_query($sqlValidaCedula);
            $numFilasValidaCedula = mysql_num_rows($resultValidaCedula);
            
            if($numFilasValidaCedula>0){
                
			echo '3';   
              
                exit;
            }
            
              $txtTituloRepresentanteLegal =(trim($_POST['txtTituloRepresentanteLegal'])=='')?'Indefinido':$_POST['txtTituloRepresentanteLegal']; 
            $txtPrimerNombreRepresentanteLegal =(trim($_POST['txtPrimerNombreRepresentanteLegal'])=='')?'Indefinido':$_POST['txtPrimerNombreRepresentanteLegal'];  
            $txtSegundoNombreRepresentanteLegal =(trim($_POST['txtSegundoNombreRepresentanteLegal'])=='')?'Indefinido':$_POST['txtSegundoNombreRepresentanteLegal'];  
            $txtPrimerApellidoRepresentanteLegal =(trim($_POST['txtPrimerApellidoRepresentanteLegal'])=='')?'Indefinido':$_POST['txtPrimerApellidoRepresentanteLegal']; 
            
            $txtSegundoApellidoRepresentanteLegal =(trim($_POST['txtSegundoApellidoRepresentanteLegal'])=='')?'Indefinido':$_POST['txtSegundoApellidoRepresentanteLegal']; 
            $txtCorreoRepresentanteLegal = (trim($_POST['txtCorreoRepresentanteLegal'])=='')?'indefinido@correo.com':$_POST['txtCorreoRepresentanteLegal']; 
           
            $txtDirectorRepresentanteLegal =(trim($_POST['txtDirectorRepresentanteLegal'])=='')?'Indefinido':$_POST['txtDirectorRepresentanteLegal'];  
            
           
            
            $txtFechaDesafiliacion =(trim($_POST['txtFechaDesafiliacion'])=='')?'0000-00-00':$_POST['txtFechaDesafiliacion'];   
            
            $vendedor_id =(trim($_POST['vendedor_id'])=='')?'0':$_POST['vendedor_id'];  
            
             $recaudador_id =(trim($_POST['recaudador_id'])=='')?'0':$_POST['recaudador_id'];  
             
      $sql3="INSERT INTO `clientes`( `nombre`, `apellido`, `direccion`, `cedula`, `telefono`, `movil`, `email`, `estado`, `id_ciudad`, `fecha_registro`, `numero_cargas`, `estado_civil`, `tipo`, `numero_casa`, `id_empresa`, `id_vendedor`, `caracter_identificacion`, `observacion`, `tipo_cliente`,  `nacionalidad`, `razonSocial`, `zona`, `codigo_interno`, `fecha_constitucion`, `ciudad2`, `direccion2`, `zona2`, `capital_suscrito`, `grupo_seciconal`, `delegado`, `oficial`, `id_membresia`, `pagar_anual`, `pagar_mensual`, `valor_convenio`,prop_nombre,prop_telefono,numero_afiliado, estado_afiliado,titulo_representante_legal,primer_nombre_representante_legal,segundo_nombre_representante_legal,primer_apellido_representante_legal,segundo_apellido_representante_legal,correo_representante_legal,fecha_afiliacion,fecha_desafiliacion,nombre_director,id_recaudador ) VALUES ('".$txtNombre."','".$txtApellido."','".$txtDireccion."','".$txtCedula."','".$txtTelefono."','".$txtMovil."','".$txtEmail."','".$switch_estado."','".$cbciudad."','".date('Y-m-d')."','0','Indefinido','0','0','".$sesion_id_empresa."','".$vendedor_id."','".$rbCaracterIdentificacion."','".$txtObservacion."','0','".$txtNacionalidad."','".$txtRazonSocial."','".$zona."','".$codigo_interno."','".$fecha_constitucion."','".$cbciudad2."','".$txtDireccion2."','". $txtZona2."','".$txtCapitalSuscrito."','".$txtGrupoSeccional."','".$delegadoResponsable."','".$txtOficialCamara."','".$txtLimiteCredito."','". $txtPagarAnual."','". $txtPagarMensual."' ,'". $txtValorConvenio."','','','".$numeroAfiliado."','".$estado_afiliado."','".$txtTituloRepresentanteLegal."','".$txtPrimerNombreRepresentanteLegal."','".$txtSegundoNombreRepresentanteLegal."','".$txtPrimerApellidoRepresentanteLegal."','".$txtSegundoApellidoRepresentanteLegal."','".$txtCorreoRepresentanteLegal."','".$txtFechaAfiliacion."','".$txtFechaDesafiliacion."','".$txtDirectorRepresentanteLegal."','".$recaudador_id."')";
    $resp3 = mysql_query($sql3);
$txtIdCliente = mysql_insert_id();
 // validar cambio de estado
            $sqlVerificarCambio="SELECT `id_cliente`, `estado` FROM `clientes` WHERE id_cliente=$txtIdCliente ";
            $resultVerificar= mysql_query($sqlVerificarCambio);
            while($rowV = mysql_fetch_array( $resultVerificar)){
                $estado_actual = $rowV['estado'];
                if($estado_actual != $switch_estado){
                    $fecha_actual= date('Y-m-d h:i:s');
                    $sqlEstado = "INSERT INTO `historial_estados_cliente`(`estado`, `fecha_inicio`, `id_cliente`, `id_empresa`) VALUES ('".$switch_estado."','".$fecha_actual."','".$txtIdCliente."','".$sesion_id_empresa."')";
                    $resultEstado = mysql_query($sqlEstado);
                }
            }
            
              if($resp3)
			  {
			      if($sesion_id_empresa==41 || $sesion_id_empresa == '116' ||$sesion_id_empresa == '1827'  ){

                    // Fecha actual
                    $fechaActual2 = new DateTime();
                    
                    // Fecha de diciembre de este año
                    $fechaDiciembre = new DateTime(date("Y") . "-12-31");
                    
                    // Calcula la diferencia entre las fechas
                    $diferencia = $fechaActual2->diff($fechaDiciembre);
                    
                    // Obtiene el número de meses restantes
                    $mesesRestantes = $diferencia->format("%m");

        
	               $fechaActual = new DateTime();
                   
                    $sqlMembresia="SELECT `id_membresia`, `descripcion_membresia`, `dias_membresia`, `id_empresa` FROM `membresias` WHERE id_membresia=$txtLimiteCredito  ";
                    $resultMembresia=mysql_query($sqlMembresia);
                    $diasCredito=0;
                  while($verMembresia=mysql_fetch_array($resultMembresia)){
                      $diasCredito = intval($verMembresia['dias_membresia']);
                      }
                    $sumarDias= $diasCredito;
                    for ($i = 0; $i < $mesesRestantes; $i++) {
                       
                         $fechaDePago = clone $fechaActual;
                         $fechaDePago->add(new DateInterval("P{$sumarDias}D"));
                         $fechaDePagoFormateada = $fechaDePago->format("Y-m-d");
                    
                        // Aquí puedes realizar tu inserción en la base de datos
                    //   $sql4 = "INSERT INTO cuentas_por_cobrar (tipo_documento, numero_factura, referencia, valor, saldo, numero_asiento, fecha_vencimiento, id_cliente, id_empresa, estado) VALUES ('CONVENIO', '0', '" . $txtNombre . "', '" . $txtValorConvenio . "', '" . $txtValorConvenio . "', '', '" . $fechaDePagoFormateada . "', '" . $txtIdCliente . "', '" . $sesion_id_empresa . "', 'Pendientes');";
                    //     $result4 = mysql_query($sql4);
                        $sumarDias= $sumarDias+$diasCredito;
                    }

			      
			    
			      }
			      
			     
			     
                ?><?php echo 1?> <?php
              }
			  else
			  {
                ?><?php echo 2?><?php
              }

		}
		catch (Exception $e) 
		{
    // Error en algun momento.
		?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
		}
	}
	
	if($accion == 35)
	{
	    $hoy = date('Y-m-d');
	    $id_cliente = $_POST['id_cliente'];
	    
	    $sqlVerificarCambio="SELECT `id_cliente`, `estado`,estado_afiliado,numero_afiliado FROM `clientes` WHERE id_cliente=$id_cliente ";
        $resultVerificar= mysql_query($sqlVerificarCambio);
       
        while($rowV = mysql_fetch_array( $resultVerificar)){
            $estado_actual = $rowV['estado_afiliado'];
            $numero_afiliado = $rowV['numero_afiliado'];
            if($numero_afiliado>0){
                $sql="UPDATE `clientes` SET `estado_afiliado`='AL DIA', fecha_afiliacion='".$hoy."' WHERE id_cliente=$id_cliente ";
            }else{
                 $sqlAfi="SELECT MAX(CAST(numero_afiliado AS UNSIGNED)) as num FROM `clientes` WHERE id_empresa= $sesion_id_empresa ";
                $resultAfi= mysql_query($sqlAfi);
                $numeroAfiliado=0;
                while($rowAfi = mysql_fetch_array($resultAfi) ){
                    $numeroAfiliado = $rowAfi['num'];
                }
                
                $numeroAfiliado++;
                
               $sql="UPDATE `clientes` SET `estado_afiliado`='AL DIA',`numero_afiliado`='".$numeroAfiliado."', fecha_afiliacion='".$hoy."' WHERE id_cliente=$id_cliente "; 
            }
            $result =mysql_query($sql);
            
            if($estado_actual != 'AL DIA'){
                $fecha_actual= date('Y-m-d');
                $sqlRegistros = "SELECT 
                    SUM(registros_cuentas_por_cobrar.saldo) as total_saldo_registros  ,
                    SUM(registros_cuentas_por_cobrar.valor - registros_cuentas_por_cobrar.saldo) as total_pagado_registros
                    FROM `registros_cuentas_por_cobrar` 
                    INNER JOIN clientes ON clientes.id_cliente = registros_cuentas_por_cobrar.id_cliente 
                    WHERE registros_cuentas_por_cobrar.`id_empresa` =$sesion_id_empresa AND registros_cuentas_por_cobrar.id_cliente IS NOT NULL AND registros_cuentas_por_cobrar.saldo > 0  AND clientes.numero_afiliado != 0 and registros_cuentas_por_cobrar.id_cliente=$id_cliente";
                    $resultRegistros =mysql_query($sqlRegistros);
                    $total_registros=0;
                    $total_pagado_registros = 0;
                    while($rowReg = mysql_fetch_array($resultRegistros)  ){
                        $total_registros = $rowReg['total_saldo_registros'];
                        $total_pagado_registros =  $rowReg['total_pagado_registros'];
                    }
                     $total_pagado_registros = (trim($total_pagado_registros)!='')?$total_pagado_registros:0;
                    $total_registros = (trim($total_registros)!='')?$total_registros:0;
                    
                    $sqlCuentasCobrar="SELECT 
                    SUM(saldo) AS total_cuentas_cobrar,
                     SUM( cuentas_por_cobrar.valor - cuentas_por_cobrar.saldo ) AS total_pagado_cuentas_cobrar
                     FROM `cuentas_por_cobrar` WHERE `id_empresa` = $sesion_id_empresa AND id_cliente= $id_cliente and membresia!=0 and tipo_documento=1 and saldo>0 ";
                    $resultCuentasCobrar =mysql_query($sqlCuentasCobrar);
                    $total_cuentas_cobrar=0;
                    $total_pagado_cuentas_cobrar = 0;
                    while($rowCC = mysql_fetch_array($resultCuentasCobrar)  ){
                        $total_cuentas_cobrar = $rowCC['total_cuentas_cobrar'];
                        $total_pagado_cuentas_cobrar = $rowCC['total_pagado_cuentas_cobrar'];
                    }
                    $total_cuentas_cobrar = (trim($total_cuentas_cobrar)!='')?$total_cuentas_cobrar:0;
                    $total_pagado_cuentas_cobrar = (trim($total_pagado_cuentas_cobrar)!='')?$total_pagado_cuentas_cobrar:0;
                    
                     $sqlEstado = "INSERT INTO `historial_estados_cliente`(`estado`, `fecha_inicio`, `id_cliente`, `id_empresa`,total_registros, total_cuentas_cobrar,total_pagado_registros_cobrar,total_pagado_cuentas_cobrar) VALUES ('AL DIA','".$fecha_actual."','".$id_cliente."','".$sesion_id_empresa."','".$total_registros."' , '".$total_cuentas_cobrar."', '".$total_pagado_registros."', '".$total_pagado_cuentas_cobrar."' )";
                    $resultEstado = mysql_query($sqlEstado);
                
            }
        }
            
	   
                    
	    
	    if($result){
	        echo '1';
	         // validar cambio de estado
            
            
	    }else{
	        echo '2';
	    }
	}
	
	if($accion == 36)
	{
	    $observacion    = $_POST['novedad'];
	    $id_cliente     = $_POST['id_cliente'];
	    $fecha_novedad  = $_POST['fecha'];
	    
	    
	    $sql="INSERT INTO `novedades_afiliado`( `observacion`, `id_cliente`, `fecha_novedad`) VALUES ('".$observacion."','".$id_cliente."','".$fecha_novedad."')";
	     $result =mysql_query($sql);
	    if($result){
	        echo '1';
	    }else{
	        echo '2';
	    }
	}
	   if($accion == 37){
                   
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
                       
                        $txtNombre = ( $Row[0]!='')? mysqli_real_escape_string($con,$Row[0]): 'Indefinido' ;
                        $txtApellido = ( $Row[1]!='')? mysqli_real_escape_string($con,$Row[1]): 'Indefinido' ;
                        $txtDireccion= ( $Row[2]!='')? mysqli_real_escape_string($con,$Row[2]): 'Indefinido' ;
                        $txtCedula = ( $Row[3]!='')? mysqli_real_escape_string($con,$Row[3]): '00000000' ;
                        $txtTelefono = ( trim($Row[4])!='')? mysqli_real_escape_string($con,$Row[4]): '000000000' ;
                        $txtMovil = ( trim($Row[5])!='')? mysqli_real_escape_string($con,$Row[5]): '000000000' ;
                        $txtEmail = ( $Row[6]!='')? mysqli_real_escape_string($con,$Row[6]): 'indefinido@gmail.com' ;
                        $cmbEstado=  (  trim($Row[7])!='')? mysqli_real_escape_string($con,$Row[7]): 'Activo' ;
                        $fecha_registro = date('Y-m-d');

                        // $nombre_ciudad  = (isset($Row[8]) || $Row[8]=='')? mysqli_real_escape_string($con,$Row[8]): '' ;
                        // $id_ciudad=0;
                        // if($nombre_ciudad!=''){
                        //     $nombre_ciudad= strtoupper(trim($nombre_ciudad));
                        //     $sql="SELECT `id_ciudad`, `ciudad` FROM `ciudades` WHERE  ciudad ='".$nombre_ciudad."' ";
                        //     $result[$z] = mysql_query($sql);
                        //     while($row= mysql_fetch_array( $result[$z])){
                        //         $id_ciudad=$row['id_ciudad'];
                        //     }
                        // }
                      
                       $id_ciudad= (trim($_SESSION['sesion_empresa_id_ciudad'])!='')?$_SESSION['sesion_empresa_id_ciudad']:0;
                        $numero_cargas  = ($Row[8]!='')? mysqli_real_escape_string($con,$Row[8]): '0' ;
                        $estado_civil  = ( $Row[9]!='')? mysqli_real_escape_string($con,$Row[9]): 'Indefinido' ;
                        $tipo  = ( $Row[10]!='')? mysqli_real_escape_string($con,$Row[10]): '0' ;
                        $txtNumeroCasa  = '' ;
                        $cmbVendedor  = '0' ;
                        // $rb = '0';
                        // if(isset($Row[11])){
                        //     if(strtoupper(trim($Row[11]))=='RUC'){
                        //         $rb = '04';
                        //     }
                        //     if( strtoupper(trim($Row[11]))=='CEDULA'){
                        //         $rb = '05';
                        //     }
                        //     if( strtoupper(trim($Row[11]))=='PASAPORTE'){
                        //         $rb = '06';
                        //     }
                        //     if( strtoupper(trim($Row[11]))=='CONSUMIDORFINAL' || strtoupper(trim($Row[11]))=='CONSUMIDOR FINAL'){
                        //         $rb = '07';
                        //     }

                        // }
                                          
                        // Nueva condición para determinar el valor de $rb
                        if (strlen($txtCedula) == 10) {
                            $rb = '05';
                        } elseif (strlen($txtCedula) == 13) {
                            $rb = '04';
                        }else{
                             $rb = '06';
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
                        $txtObservaciones  = ( $Row[12]!='')? mysqli_real_escape_string($con,$Row[12]): '' ;
                      
                        $sql1 = "insert into clientes ( nombre, apellido, direccion, cedula, telefono, movil, email, estado, id_ciudad, fecha_registro, numero_cargas, estado_civil, tipo, numero_casa, id_empresa, id_vendedor, caracter_identificacion, reponsable, limite_credito, descuento, dias_plazo, tipo_precio, tipo_cliente, prop_nombre, prop_telefono, observacion) values ( '".$txtNombre."', '".$txtApellido."', '".$txtDireccion."', '".$txtCedula."', '".$txtTelefono."', '".$txtMovil."', '".$txtEmail."', '".$cmbEstado."', '".$id_ciudad."', '".$fecha_registro."', '".$numero_cargas."', '".$estado_civil."', '".$tipo."', '".$txtNumeroCasa."', '".$sesion_id_empresa."', '".$cmbVendedor."', '".$rbCaracterIdentificacion."', '".$txtResponsable."', '".$txtLimiteCredito."', '".$txtDescuento."', '".$cmbDiasPlazo."', '".$cmbTipoPrecio."','".$cmbTipoCliente."','".$propNombre."','".$propTelefono."', '".$txtObservaciones."');";
			//echo $sql1;
			$result1=mysql_query($sql1);
            
                        if (! empty($result1)) {
                          $type = "success";
                          $message = "Excel importado correctamente";
                        } else {
                          $type = "error";
                          $message = "Hubo un problema al importar registros";
                        }
                        //  }
                      }
            
                    }
                    echo $message;
                  }
                  else
                  { 
                    $type = "error";
                    $message = "El archivo enviado es invalido. Por favor vuelva a intentarlo";
                  }
                }
	   if($accion == 38){
                   
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
                       $listaClientes´=array();
                       $listaClientes['cedula_no_guardados'][]='';
        $listaClientes['nombre_no_guardados'][]='';
        $listaClientes['sql_erroneo'][]='';
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
                       
                        $txtNombre = ( $Row[0]!='')? mysqli_real_escape_string($con,$Row[0]): 'Indefinido' ;
                        $txtApellido = ( $Row[1]!='')? mysqli_real_escape_string($con,$Row[1]): 'Indefinido' ;
                       
                         $txtNombreComercial= ( $Row[2]!='')? mysqli_real_escape_string($con,$Row[2]): 'Indefinido' ;
                         
                        if( $Row[3]==''){
                             echo json_encode($listaClientes);
                              exit;
                            // echo "1".$Row[3];
                           
                        }
                        
                        $txtCedula = ( trim($Row[3])!='')? mysqli_real_escape_string($con,trim($Row[3])): '00000000' ;
                         $txtCedula = trim($txtCedula);
                        $txtTelefono = ( trim($Row[4])!='')? mysqli_real_escape_string($con,$Row[4]): '000000000' ;
                        $txtMovil = ( trim($Row[5])!='')? mysqli_real_escape_string($con,$Row[5]): '000000000' ;
                        $txtEmail = ( $Row[6]!='')? mysqli_real_escape_string($con,$Row[6]): 'indefinido@gmail.com' ;
                        $cmbEstado= 'Activo' ;
                        $fecha_registro = date('Y-m-d');


                        $id_ciudad= (trim($_SESSION['sesion_empresa_id_ciudad'])!='')?$_SESSION['sesion_empresa_id_ciudad']:0;
                        $txtNacionalidad  = ($Row[8]!='')? mysqli_real_escape_string($con,$Row[8]): '0' ;
                        
                        $txtDireccion  = ( $Row[9]!='')? mysqli_real_escape_string($con,$Row[9]): 'Indefinido' ;
                         
                        $txtDireccion2  = ( $Row[10]!='')? mysqli_real_escape_string($con,$Row[10]): 'Indefinido' ;
                         $txtZona1  = ( $Row[11]!='')? mysqli_real_escape_string($con,$Row[11]): 'Indefinido' ;
                         
                          $txtZona2  = ( $Row[12]!='')? mysqli_real_escape_string($con,$Row[12]): 'Indefinido' ;
                         
                        $txtNumeroCasa  = '' ;
                        $cmbVendedor  = '0' ;
                        // $rb = '0';
                        // if(isset($Row[13])){
                        //     if(strtoupper(trim($Row[13]))=='RUC'){
                        //         $rb = '04';
                        //     }
                        //     if( strtoupper(trim($Row[13]))=='CEDULA'){
                        //         $rb = '05';
                        //     }
                        //     if( strtoupper(trim($Row[13]))=='PASAPORTE'){
                        //         $rb = '06';
                        //     }
                        //     if( strtoupper(trim($Row[13]))=='CONSUMIDORFINAL' || strtoupper(trim($Row[13]))=='CONSUMIDOR FINAL'){
                        //         $rb = '07';
                        //     }

                        // }
                        
                        
                        // Nueva condición para determinar el valor de $rb
                        if (strlen($txtCedula) == 10) {
                            $rb = '05';
                        } elseif (strlen($txtCedula) == 13) {
                            $rb = '04';
                        }else{
                             $rb = '06';
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
                        
                        $txtObservacion  = ( $Row[14]!='')? mysqli_real_escape_string($con,$Row[14]): '' ;
                        
                        $txtDiasCredito  = ( $Row[15]!='')? mysqli_real_escape_string($con,$Row[15]): '' ;
                        
                        $txtLimiteCredito  = ( $Row[16]!='')? mysqli_real_escape_string($con,$Row[16]): '0' ;
                        $txtContribuyenteEspecial = ( $Row[17]!='')? mysqli_real_escape_string($con,$Row[17]): '' ;
                         $fecha_constitucion = ( $Row[18]!='')? mysqli_real_escape_string($con,$Row[18]): '000-00-00' ;
                        
                        $fecha_constitucion = date('Y-m-d', strtotime($fecha_constitucion));
                          $txtCapitalSuscrito = ( $Row[19]!='')? mysqli_real_escape_string($con,$Row[19]): '0' ;
                          
                        $txtValorConvenio = ( $Row[20]!='')? mysqli_real_escape_string($con,$Row[20]): '0' ;
                          $delegadoResponsable = ( $Row[21]!='')? mysqli_real_escape_string($con,$Row[21]): '' ;
                      $txtPagarAnual = ( $Row[22]!='')? mysqli_real_escape_string($con,$Row[22]): '0' ;
                      $txtPagarMensual = ( $Row[23]!='')? mysqli_real_escape_string($con,$Row[23]): '0' ;
                      
                        $numeroAfiliado = ( $Row[24]!='')? $Row[24]: '0' ;
                     
                       $txtGrupoSeccional = ( $Row[25]!='')? mysqli_real_escape_string($con,$Row[25]): '0' ;
                       
                       $titulo_representante_legal = ( $Row[26]!='')? mysqli_real_escape_string($con,$Row[26]): '0' ;
                       
                       $primer_nombre_representante_legal = ( $Row[27]!='')? mysqli_real_escape_string($con,$Row[27]): 'Indefinido' ;
                       $segundo_nombre_representante_legal = ( $Row[28]!='')? mysqli_real_escape_string($con,$Row[28]): 'Indefinido' ;
                    
                        $primer_apellido_representante_legal = ( $Row[29]!='')? mysqli_real_escape_string($con,$Row[29]): 'Indefinido' ;
                       $segundo_apellido_representante_legal = ( $Row[30]!='')? mysqli_real_escape_string($con,$Row[30]): 'Indefinido' ;
                       $correo_representante_legal = ( $Row[31]!='')? mysqli_real_escape_string($con,$Row[31]): 'infinido@gmail.com' ;
                       $fecha_afiliacion = ( $Row[32]!='')? mysqli_real_escape_string($con,$Row[32]): '0000-00-00' ;
                       $fecha_desafiliacion = ( $Row[33]!='')? mysqli_real_escape_string($con,$Row[33]): '0000-00-00' ;
                       $nombre_director = ( $Row[34]!='')? mysqli_real_escape_string($con,$Row[34]): 'Indefinido' ;
                       
                    //   $txtPagarMensual = ( $Row[24]!='')? mysqli_real_escape_string($con,$Row[24]): '' ;
                      $codigo_interno=0;
                      if($numeroAfiliado!=''){
                          $estado_afiliado = 'AL DIA';
                      }else{
                          $estado_afiliado = 'DESAFILIADO';
                      }
                      
                    
                    // $txtGrupoSeccional=0;
                    $txtOficialCamara=0;
                    $txtPagarAnual = $txtPagarMensual*12;
                    
                        $sql3="INSERT INTO `clientes`( `nombre`, `apellido`, `direccion`, `cedula`, `telefono`, `movil`, `email`, `estado`, `id_ciudad`, `fecha_registro`, `numero_cargas`, `estado_civil`, `tipo`, `numero_casa`, `id_empresa`, `id_vendedor`, `caracter_identificacion`, `observacion`, `tipo_cliente`,  `nacionalidad`, `razonSocial`, `zona`, `codigo_interno`, `fecha_constitucion`, `ciudad2`, `direccion2`, `zona2`, `capital_suscrito`, `grupo_seciconal`, `delegado`, `oficial`, `id_membresia`, `pagar_anual`, `pagar_mensual`, `valor_convenio`,prop_nombre,prop_telefono,numero_afiliado, estado_afiliado,titulo_representante_legal,primer_nombre_representante_legal,segundo_nombre_representante_legal,primer_apellido_representante_legal,segundo_apellido_representante_legal,correo_representante_legal,fecha_afiliacion,fecha_desafiliacion,nombre_director) 
                        VALUES ('".$txtNombre."','".$txtApellido."','".$txtDireccion."','".$txtCedula."','".$txtTelefono."','".$txtMovil."','".$txtEmail."','".$cmbEstado."','".$id_ciudad."','".date('Y-m-d')."','0','Indefinido','0','0','".$sesion_id_empresa."','0','".$rbCaracterIdentificacion."','".$txtObservacion."','0','".$txtNacionalidad."','".$txtNombreComercial."','".$txtZona1."','".$codigo_interno."','".$fecha_constitucion."','".$id_ciudad."','".$txtDireccion2."','". $txtZona2."','".$txtCapitalSuscrito."','".$txtGrupoSeccional."','".$delegadoResponsable."','".$txtOficialCamara."','".$txtLimiteCredito."','". $txtPagarAnual."','". $txtPagarMensual."' ,'". $txtValorConvenio."','','','".$numeroAfiliado."','".$estado_afiliado."','".$titulo_representante_legal."','".$primer_nombre_representante_legal."','".$segundo_nombre_representante_legal."','".$primer_apellido_representante_legal."','".$segundo_apellido_representante_legal."','".$correo_representante_legal."','".$fecha_afiliacion."','".$fecha_desafiliacion."','".$nombre_director."')";
    $resp3 = mysql_query($sql3);
    	  $id_cliente =mysql_insert_id();   
   
if($resp3){
     $listaClientes['sql_corecto'][]=$sql3;
    $listaClientes['guardados'][]=$id_cliente;
   $cantidad=100;
    $columna=35;
    for($z=0;$z<$cantidad;$z++){
        
          $contacto =$Row[$columna];
          $correo_contacto =trim($Row[$columna+1]);
          if($correo_contacto !=''  ){
              $sql="INSERT INTO `correos_facturacion_afiliado`( `nombre`, `correo_facturacion`, `id_cliente`, `id_empresa`) VALUES ('".$contacto."','".$correo_contacto."','".$id_cliente."','".$sesion_id_empresa."')";
          $result=mysql_query($sql);
          }else{
              $cantidad=100;
              break;
          }
          
          
          $columna = $columna+2;
    }
   
}else{
        $listaClientes['cedula_no_guardados'][]=$txtCedula;
        $listaClientes['nombre_no_guardados'][]=$txtNombreComercial;
        $listaClientes['sql_erroneo'][]=$sql3;
}

    
    

            
                        if (! empty($resp3)) {
                          $type = "success";
                          $message = "Excel importado correctamente";
                        } else {
                          $type = "error";
                          $message = "Hubo un problema al importar registros";
                        }
                        //  }
                      }
            
                    }
                    echo json_encode($listaClientes);
                    // echo $message;
                  }
                  else
                  { 
                    $type = "error";
                    $message = "El archivo enviado es invalido. Por favor vuelva a intentarlo";
                  }
                }
    if($accion == 39){
                   
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
                       $listaClientes´=array();
                       $listaClientes['cedula_no_guardados'][]='';
        $listaClientes['nombre_no_guardados'][]='';
        $listaClientes['sql_erroneo'][]='';
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
                        
                       
                        $numero_afiliado =  mysqli_real_escape_string($con,$Row[0]) ;
                        
                        
                        $nombreCliente = ( $Row[1]!='')? mysqli_real_escape_string($con,$Row[1]): 'Indefinido' ;
                       
                        $documento_numero= ( $Row[2]!='')? mysqli_real_escape_string($con,$Row[2]): '0' ;
                         
                        if( $Row[0]==''){
                             echo json_encode($listaClientes);
                              exit;
                            // echo "1".$Row[3];
                           
                        }
                        $fecha_vencimiento = ( $Row[3]!='')? mysqli_real_escape_string($con,$Row[3]): '0000-00-00' ;
                        
                        
                         
                        $fecha_correponde= date('m-Y', strtotime($fecha_vencimiento));;
                        $valor = ( trim($Row[4])!='')? $Row[4]: '0' ;
                        
                        $saldo = ( trim($Row[5])!='')? mysqli_real_escape_string($con,$Row[5]): '0' ;
                        $fecha_ingreso = ( $Row[6]!='')? mysqli_real_escape_string($con,$Row[6]): '0000-00-00' ;
                       
                      $estado_afiliado = 'Activo';
                $listaClientes['filas'][]=$valor;
                 $listaClientes['num'][]=$numero_afiliado;
               if($saldo==0){
                    $estado= "Canceladas";
                }else{
                    $estado= "Pendientes";
                }
                $id_cliente='';
                    $sqlCliente = "SELECT `id_cliente`, `nombre`, `apellido` FROM `clientes` WHERE id_empresa=$sesion_id_empresa AND numero_afiliado='".$numero_afiliado."' ";
                    $resultCliente= mysql_query($sqlCliente);
                    while($row = mysql_fetch_array($resultCliente) ){
                        $id_cliente = $row['id_cliente'];
                        $nombreCliente =  $row['nombre'];
                    }
                    
                    if($id_cliente!=''){
                    $sql3="INSERT INTO `registros_cuentas_por_cobrar`( `tipo_documento`, `numero_factura`, `referencia`, `valor`, `saldo`, `fecha_vencimiento`, `id_cliente`, `id_empresa`, `estado`, `documento_numero`, `fecha_correponde`,fecha_ingreso,numero_asiento) VALUES ('CONVENIO','0','".$nombreCliente."','".$valor."','".$saldo."','".$fecha_vencimiento."','".$id_cliente."','".$sesion_id_empresa."','".$estado."','".$documento_numero."','".$fecha_correponde."','".$fecha_ingreso."',0)";
    $resp3 = mysql_query($sql3);
    $id_cliente =mysql_insert_id();   
   
if($resp3){
    $listaClientes['guardados'][]=$id_cliente;
 
   
}else{
        $listaClientes['cedula_no_guardados'][]=$documento_numero;
        $listaClientes['nombre_no_guardados'][]=$nombreCliente;
        $listaClientes['sql_erroneo'][]=$sql3;
}
                    }else{
                        $listaClientes['cedula_no_guardados'][]=$documento_numero;
        $listaClientes['nombre_no_guardados'][]=$nombreCliente;
        $listaClientes['sql_erroneo'][]=$sqlCliente;
                    }
 

                      }
            
                    }
                    echo json_encode($listaClientes);
                    // echo $message;
                  }
                  else
                  { 
                    $type = "error";
                    $message = "El archivo enviado es invalido. Por favor vuelva a intentarlo";
                  }
                }
                
	if($accion == 40)
	{
	    $id_cliente = $_POST['id_cliente'];
	    $txtmotivoDesafiliacion = $_POST['motivo'];
                   $fecha_actual= date('Y-m-d h:i:s');  
	   
	         // validar cambio de estado
            $sqlVerificarCambio="SELECT `id_cliente`, `estado_afiliado` FROM `clientes` WHERE id_cliente=$id_cliente ";
            $resultVerificar= mysql_query($sqlVerificarCambio);
            while($rowV = mysql_fetch_array( $resultVerificar)){
                
                 $estado_actual = $rowV['estado_afiliado'];
                if($estado_actual != 'DESAFILIADO'){
                     $sql="UPDATE `clientes` SET `estado_afiliado`='DESAFILIADO',`fecha_desafiliacion`='".$fecha_actual."' WHERE id_cliente=$id_cliente ";
            	    $result =mysql_query($sql);
            	    if($result){
            	        echo '1';
            	     $sqlMotivo="INSERT INTO `novedades_afiliado`( `observacion`, `id_cliente`, `fecha_novedad`, `tramite`) VALUES ('".$txtmotivoDesafiliacion."','".$id_cliente."','".date('Y-m-d')."',1) ";
	    $resultMotivo = mysql_query($sqlMotivo);
                    
                    $sqlRegistros = "SELECT 
                    SUM(registros_cuentas_por_cobrar.saldo) as total_saldo_registros  ,
                    SUM(registros_cuentas_por_cobrar.valor - registros_cuentas_por_cobrar.saldo) as total_pagado_registros
                    FROM `registros_cuentas_por_cobrar` 
                    INNER JOIN clientes ON clientes.id_cliente = registros_cuentas_por_cobrar.id_cliente 
                    WHERE registros_cuentas_por_cobrar.`id_empresa` =$sesion_id_empresa AND registros_cuentas_por_cobrar.id_cliente IS NOT NULL AND registros_cuentas_por_cobrar.saldo > 0  AND clientes.numero_afiliado != 0 and registros_cuentas_por_cobrar.id_cliente=$id_cliente";
                    $resultRegistros =mysql_query($sqlRegistros);
                    $total_registros=0;
                    $total_pagado_registros = 0;
                    while($rowReg = mysql_fetch_array($resultRegistros)  ){
                        $total_registros = $rowReg['total_saldo_registros'];
                        $total_pagado_registros =  $rowReg['total_pagado_registros'];
                    }
                     $total_pagado_registros = (trim($total_pagado_registros)!='')?$total_pagado_registros:0;
                    $total_registros = (trim($total_registros)!='')?$total_registros:0;
                    
                    $sqlCuentasCobrar="SELECT 
                    SUM(saldo) AS total_cuentas_cobrar,
                     SUM( cuentas_por_cobrar.valor - cuentas_por_cobrar.saldo ) AS total_pagado_cuentas_cobrar
                     FROM `cuentas_por_cobrar` WHERE `id_empresa` = $sesion_id_empresa AND id_cliente= $id_cliente and membresia!=0 and tipo_documento=1 and saldo>0 ";
                    $resultCuentasCobrar =mysql_query($sqlCuentasCobrar);
                    $total_cuentas_cobrar=0;
                    $total_pagado_cuentas_cobrar = 0;
                    while($rowCC = mysql_fetch_array($resultCuentasCobrar)  ){
                        $total_cuentas_cobrar = $rowCC['total_cuentas_cobrar'];
                        $total_pagado_cuentas_cobrar = $rowCC['total_pagado_cuentas_cobrar'];
                    }
                    $total_cuentas_cobrar = (trim($total_cuentas_cobrar)!='')?$total_cuentas_cobrar:0;
                    $total_pagado_cuentas_cobrar = (trim($total_pagado_cuentas_cobrar)!='')?$total_pagado_cuentas_cobrar:0;
                    
                     $sqlEstado = "INSERT INTO `historial_estados_cliente`(`estado`, `fecha_inicio`, `id_cliente`, `id_empresa`,total_registros, total_cuentas_cobrar,total_pagado_registros_cobrar,total_pagado_cuentas_cobrar) VALUES ('DESAFILIADO','".$fecha_actual."','".$id_cliente."','".$sesion_id_empresa."','".$total_registros."' , '".$total_cuentas_cobrar."', '".$total_pagado_registros."', '".$total_pagado_cuentas_cobrar."' )";
                    $resultEstado = mysql_query($sqlEstado);
            	    }else{
            	        echo '2';
            	    }
            	 
                    
                }
            }
            
	    
	}   
	
	 if($accion == 50)
	{
    // GUARDA MODIFICACION Clientes PAGINA: cargos.php
		try
		{
		//echo "va llerr";
        $rbCaracterIdentificacion = $_POST['rbCaracterIdentificacion'];
			$txtIdCliente = $_POST['id_cliente'];
			$txtNombre = ucwords($_POST['txtNombre']);
			$txtApellido = ucwords($_POST['txtApellido']);
			$txtDireccion = ucwords($_POST['txtDireccion']);
			$txtCedula = ucwords($_POST['txtCedula']);
			$txtTelefono = ucwords($_POST['txtTelefono']);
			 
			$txtMovil = ucwords($_POST['txtMovil']);
			$txtEmail = $_POST['txtEmail'];
			$txtCasa = ucwords($_POST['txtCasa']);
			$cmbTipoCliente = ucwords($_POST['cmbTipoCliente']);
					
		$txtNacionalidad = isset($_POST['txtNacionalidad'])?$_POST['txtNacionalidad']:'';
		
            $txtObservacion = ucwords($_POST['txtObservacion']);
            $cbprovincia = ucwords($_POST['cbprovincia']);
            $cbciudad = ucwords($_POST['cbciudad']);
            $switch_estado = isset($_POST['switch-estado'])?$_POST['switch-estado']:'';
            
            if($switch_estado==''){
                $switch_estado='Activo';
            }
            
            $cmbVendedor = "0";
            if(isset($_POST['vendedor_id']) ){
                if($_POST['vendedor_id']!=''){
                     $cmbVendedor = $_POST['vendedor_id'];
                }
            }
             $cmbRecaudador = "0";
            if(isset($_POST['recaudador_id']) ){
                if($_POST['recaudador_id']!=''){
                     $cmbRecaudador = $_POST['recaudador_id'];
                }
            }
             $txtLimiteCredito = "0";
            if(isset($_POST['txtLimiteCredito']) ){
                if($_POST['txtLimiteCredito']!=''){
                     $txtLimiteCredito = $_POST['txtLimiteCredito'];
                }
            }

            $numero_cargas=0;
            $estado_civil = 'Soltero';
            $tipo = '0';
            $txtNumeroCasa=0;
            $txtResponsable='';
            
                    $txtLimiteCredito = "0";
            $txtDescuento = "0";
            $cmbDiasPlazo = "0";
    
            $cmbTipoPrecio = "0";
            $cmbTipoCliente="0";

            $fecha_registro = date("Y-m-d h:i:s");
      $propNombre = '';
      $propTelefono='';
          $switch_tiene_limite = "0";
            if(isset($_POST['switch_tiene_limite']) ){
                if($_POST['switch_tiene_limite']!=''){
                     $switch_tiene_limite = $_POST['switch_tiene_limite'];
                }
            }
    $sql3 = "insert into clientes ( nombre,  apellido, direccion, cedula, telefono, movil, fecha_nacimiento,  email,        estado,  id_ciudad,  fecha_registro,  numero_cargas, estado_civil, tipo, numero_casa, id_empresa, id_vendedor, caracter_identificacion, reponsable, limite_credito, descuento, dias_plazo, tipo_precio, tipo_cliente, prop_nombre,   empresaCliente, nacionalidad, tiene_limite_credito) values ('".$txtNombre."', '".$txtApellido."', '".$txtDireccion."', '".$txtCedula."', '".$txtTelefono."', '".$txtMovil."', '".$fecha_registro."' ,        '".$txtEmail."', '".$switch_estado."', '".$cbciudad."', '".$fecha_registro."', '".$numero_cargas."', '".$estado_civil."', '".$tipo."', '".$txtNumeroCasa."', '".$sesion_id_empresa."', '".$cmbVendedor."', '".$rbCaracterIdentificacion."', '".$txtResponsable."', '".$txtLimiteCredito."', '".$txtDescuento."', '".$cmbDiasPlazo."', '".$cmbTipoPrecio."','".$cmbTipoCliente."','".$propNombre."',  '".$tipo."','".$txtNacionalidad."','".$switch_tiene_limite."');";
			  
        	  $resp3 = mysql_query($sql3);
    //   echo $sql3;
              if($resp3)
			  {
                ?><?php echo 1?> <?php
              }
			  else
			  {
                ?><?php echo 2?><?php
              }

		}
		catch (Exception $e) 
		{
    // Error en algun momento.
		?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
		}
	}
	 if($accion == 51)
	{
    // GUARDA MODIFICACION Clientes PAGINA: cargos.php
		try
		{
		//echo "va llerr";
        $rbCaracterIdentificacion = $_POST['rbCaracterIdentificacion'];
			$txtIdCliente = $_POST['id_cliente'];
			$txtNombre = ucwords($_POST['txtNombre']);
			$txtApellido = ucwords($_POST['txtApellido']);
			$txtDireccion = ucwords($_POST['txtDireccion']);
			$txtCedula = ucwords($_POST['txtCedula']);
			$txtTelefono = ucwords($_POST['txtTelefono']);
			 
			$txtMovil = ucwords($_POST['txtMovil']);
			$txtEmail = $_POST['txtEmail'];
			$txtCasa = ucwords($_POST['txtCasa']);
			$cmbTipoCliente = ucwords($_POST['cmbTipoCliente']);
					
		$txtNacionalidad = isset($_POST['txtNacionalidad'])?$_POST['txtNacionalidad']:'';
		
            $txtObservacion = ucwords($_POST['txtObservacion']);
            $cbprovincia = ucwords($_POST['cbprovincia']);
            $cbciudad = ucwords($_POST['cbciudad']);
            $switch_estado = isset($_POST['switch-estado'])?$_POST['switch-estado']:'';
            
            if($switch_estado==''){
                $switch_estado='Activo';
            }
            
            $cmbVendedor = "0";
            if(isset($_POST['vendedor_id']) ){
                if($_POST['vendedor_id']!=''){
                     $cmbVendedor = $_POST['vendedor_id'];
                }
            }
             $cmbRecaudador = "0";
            if(isset($_POST['recaudador_id']) ){
                if($_POST['recaudador_id']!=''){
                     $cmbRecaudador = $_POST['recaudador_id'];
                }
            }
             $txtLimiteCredito = "0";
            if(isset($_POST['txtLimiteCredito']) ){
                if($_POST['txtLimiteCredito']!=''){
                     $txtLimiteCredito = $_POST['txtLimiteCredito'];
                }
            }
            $switch_tiene_limite = "0";
            if(isset($_POST['switch_tiene_limite']) ){
                if($_POST['switch_tiene_limite']!=''){
                     $switch_tiene_limite = $_POST['switch_tiene_limite'];
                }
            }
           
             // $sql3 = "update clientes SET nombre='".$txtNombre."', sueldo='".$txtSueldo."', id_departamento='".$cmbDepartamento."' where id_cargo='".$txtIdCargo."'; ";
              $sql3 = "update clientes SET nombre='".$txtNombre."', apellido='".$txtApellido."', direccion='".$txtDireccion."', cedula='".$txtCedula."', telefono='".$txtTelefono."', movil='".$txtMovil."', email='".$txtEmail."', tipo_cliente='0',  observacion='".$txtObservacion."' , id_ciudad='".$cbciudad."', caracter_identificacion='".$rbCaracterIdentificacion."',  nacionalidad='".$txtNacionalidad."', estado ='".$switch_estado."', id_vendedor ='".$cmbVendedor."', limite_credito ='".$txtLimiteCredito."', tiene_limite_credito='".$switch_tiene_limite."'    where clientes.`id_empresa`='".$sesion_id_empresa."' and clientes.id_cliente=".$txtIdCliente."; ";
        	  $resp3 = mysql_query($sql3);
    //   echo $sql3;
              if($resp3)
			  {
                ?><?php echo 1?> <?php
              }
			  else
			  {
                ?><?php echo 2?><?php
              }

		}
		catch (Exception $e) 
		{
    // Error en algun momento.
		?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
		}
	}
		
    if($accion == 55){
        $idCliente = $_POST['idCliente'];

        $sqlCliente = "SELECT `id_cliente`, `nombre`, `apellido`, `direccion`, `cedula`, `telefono`, `movil`, `fecha_nacimiento`, `email`, `estado`, `id_ciudad`, `fecha_registro`, `numero_cargas`, `estado_civil`, `tipo`, `numero_casa`, `id_empresa`, `id_vendedor`, `caracter_identificacion`, `reponsable`, `limite_credito`, `descuento`, `dias_plazo`, `tipo_precio`, `observacion`, `tipo_cliente`, `prop_nombre`, `prop_telefono`, `prop_email`, `empresaCliente`, `nacionalidad`, `razonSocial`, `credito`, `contribuyente_especial`, `zona`, `codigo_interno`, `fecha_constitucion`, `ciudad2`, `direccion2`, `zona2`, `capital_suscrito`, `grupo_seciconal`, `delegado`, `oficial`, `id_membresia`,tiene_limite_credito FROM `clientes` WHERE id_cliente=$idCliente ";
        $resultCliente = mysql_query( $sqlCliente);
        $limiteCredito = 0;
        $tiene_limite_credito  = 0;
        while($rowC = mysql_fetch_array($resultCliente) ){
            $limiteCredito = $rowC['limite_credito'];
            $tiene_limite_credito  = $rowC['tiene_limite_credito'];
        }
        $limiteCredito = (trim($limiteCredito)=='')?0:$limiteCredito;
        $response['limite'] = $limiteCredito;
        $response['tiene_limite_credito'] = $tiene_limite_credito;

        $sql = "SELECT `id_cuenta_por_cobrar`, `valor`, SUM(`saldo`) as saldo_total FROM `cuentas_por_cobrar` WHERE `id_cliente`= $idCliente; ";
        $result = mysql_query( $sql );
        $saldo_total =0;
        while($row = mysql_fetch_array($result) ){
            $saldo_total = $row['saldo_total'];
        }
        $saldo_total = (trim($saldo_total)=='')?0:$saldo_total;
        $response['saldo'] = $saldo_total;
        $response['limite_actual'] = floatval($limiteCredito)-floatval($saldo_total);
        echo json_encode($response);
    }
	

if($accion == '67'){
    // echo "accion==>".$accion;
    if(isset($_POST['queryString'])) {
        $queryString = $_POST['queryString'];
        // echo "queryString==>".$queryString; // Debugging output

        // Verificar si la longitud de la cadena es mayor que 0
        if(strlen($queryString) > 0) {
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
            WHERE clientes.`id_cliente`='" . $queryString . "'";

            $result = mysql_query($query) or die(mysql_error());

            if ($result) {
                $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
                if ($numero_filas > 0) {
                    while ($row = mysql_fetch_array($result)) {
                        if ($row["clientes_estado"] == 'Activo') {
echo $row['clientes_nombre'] . "*" . $row['clientes_apellido'] . "*" . $row['clientes_cedula'] . "*" . $row["clientes_telefono"] . "*" . $row['clientes_direccion'] . "*" . $row["clientes_id_cliente"] . "*" . $row["clientes_caracter_identificacion"] . "*" . $row["clientes_id_vendedor"] . "*0";
                        } else {
                            echo '<tr>';
                        }
                    }
                } else {
                    echo 'No se encontraron resultados.';
                }
            } else {
                echo 'ERROR: Hay un problema con la consulta.';
            }
        } else {
            echo 'La longitud no es la permitida.';
        }
    } else {
        echo 'No hay ningún acceso directo a este script!';
    }
}

?>


