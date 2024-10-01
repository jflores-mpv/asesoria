<?php  

    //Start session
    session_start();
    
    //require_once('../ver_sesion.php');

    //Include database connection details
    require_once('../conexion.php');
    
    $sesion_id_usuario = $_SESSION["sesion_id_usuario"];
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $id_empresa_cookies = $_COOKIE["id_empresa"];
    $_SESSION['sesion_tipo_empresa'];


    $accion = $_POST['accion'];
    
    if($accion == 1){
        $cod = $_POST['cod'];
        $cod2 = $cod +1;
        $consulta5="SELECT max(codigo) FROM plan_cuentas where codigo >= '".$cod."' and codigo < '".$cod2."' ";
        $result=mysql_query($consulta5);
        while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
            {
                $cadena=$cadena."?".$row['max(codigo)'];
            }
       echo "".$cadena;
    }

    if($accion == 2){
        // vacia la tabla plan de cuentas de la BDD
        $consulta6="TRUNCATE  `plan_cuentas`";
        $result6=mysql_query($consulta6);
        if($result6){
            echo "Se ha vaciado la tabla plan_cuentas";
        }else{
            echo "Error al vaciar la tabla plan_cuentas";
        }
    }

    if($accion == 3){
        
        //GUARDAR PLAN CUENTA
        try{
        $txtCodigo = $_POST['txtCodigo'];
        $nombre = trim($_POST['txtNombre']);// elimina espacios en blanco al inico y al final

        if($txtCodigo != "" && $nombre != ""){
            
            $txtCodigo = $_POST['txtCodigo'];
            $nombre = ucwords($nombre);
            //$tipo = $_POST['cmbTipo'];
            $tipo = $_POST['switch-one'];
            $nivel = 0;
            $txtCuentaBanco = $_POST['txtCuentaBanco'];  
            

            //$categoria = $_POST['cmbCategoria'];

            /*
            $clase = filter_var($clasificacion, FILTER_SANITIZE_NUMBER_INT);
            switch ($clase) {
                case "1":
                    $clasifica = "Activo";
                    break;
                case "2":
                    $clasifica = "Pasivo";
                    break;
                case "3":
                    $clasifica = "Patrimonio";
                    break;
                case "4":
                    $clasifica = "Gastos";
                    break;
                case "5":
                    $clasifica = "Ingresos";
                    break;
                case "6":
                    $clasifica = "Cuentas Contingentes";
                    break;
                case "7":
                    $clasifica = "Cuentas de Orden";
                    break;
            }
            */

            // FUNCION PARA AUMENTAR EL . ASI EL EL USUARIO HAYA PUESTO EL PUNTO O NO
            $cadena9=filter_var($txtCodigo, FILTER_SANITIZE_NUMBER_INT);// elimina el . y solo coje numeros  //echo preg_replace('/[^0-9]/','',$cadena);
            //$cadena9 = split('[.]',$txtCodigo);
            $nivel = strlen($cadena9); // longitud de l acadena
           /*
            $tamano = strlen($cadena9); // longitud de l acadena
            $caracter = "";
            $conDigito = 0;
            $cont = 0;
            for($i=0; $i<$tamano; $i++){
                if($i == 0 or $i == 1){// primero y segundo digito
                    $caracter = $caracter.$cadena9[$i]."";
                    $cont++;
                }else if($conDigito == 0){//tercer digito en adelante
                    $caracter = $caracter.$cadena9[$i];
                    $conDigito++;
                }else{
                    $caracter = $caracter.$cadena9[$i]."";
                    $cont++;
                    $conDigito = 0;
                }
            }
            $nivel = $cont;
           */
            //permite sacar el id maximo de plan_cuentas
            try {
                $sqli="Select max(id_plan_cuenta) From plan_cuentas";
                $result=mysql_query($sqli);
                $id_plan_cuenta=0;
                while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
                {
                                $id_plan_cuenta=$row['max(id_plan_cuenta)'];
                }
                $id_plan_cuenta++;

            }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en el id max: <?php echo "".$ex ?></p></div> <?php }

                //   $sql = "insert into plan_cuentas (id_plan_cuenta, codigo, nombre, clasificacion, tipo, categoria, nivel, total, id_empresa, cuenta_banco,borrar) 
                //   values ('".$id_plan_cuenta."','".$cadena9."','".$nombre."','','".$tipo."','','".$nivel."',0, '".$sesion_id_empresa."', '".$txtCuentaBanco."', 1)";
                
                 $cmbFormatoCheque = trim($_POST['cmbFormatoCheque']);
            $cmbFormatoCheque = ($cmbFormatoCheque =='')?0:$cmbFormatoCheque ;
                  $sql = "insert into plan_cuentas (id_plan_cuenta, codigo, nombre, clasificacion, tipo, categoria, nivel, total, id_empresa, cuenta_banco,borrar,tipo_cheque) 
                  values ('".$id_plan_cuenta."','".$cadena9."','".$nombre."','','".$tipo."','','".$nivel."',0, '".$sesion_id_empresa."', '".$txtCuentaBanco."', 1,'".$cmbFormatoCheque."')";
                  $resp = mysql_query($sql);
echo $sql;
                  if($resp){
                      ?><div class="alert alert-success"><p>Cuenta registrada exitosamente</p></div><?php
                      }else{
                          ?> <div class='alert alert-danger'><p>Error al guarda los datos: problemas en la consulta</p></div> <?php                           
                      }

        }else{ ?><div class='transparent_ajax_error'><p>Fallo en el envio del Formulario: No hay datos para guardar</p></div><?php }

        }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }

    }

    if($accion == 4){
        //GUARDA LA MODIFICACION DEL PLAN DE CUENTAS        
        try{
        $txtCodigo = $_POST['txtCodigo'];
        $nombre = trim($_POST['txtNombre']); // elimina espacios en blanco al inico y al final
        $nivel = 0;
        if($txtCodigo != ""){
             if($nombre != ""){
                 
                $id_plan_cuenta = $_POST['txtIdPlanCuenta']; 
                $txtCodigo = $_POST['txtCodigo'];
                $nombre = ucwords($nombre);
                $tipo = $_POST['cmbTipo'];
                $txtCuentaBanco = $_POST['txtCuentaBanco'];
                /*
                $clase = filter_var($clasificacion, FILTER_SANITIZE_NUMBER_INT);
                switch ($clase) {
                    case "1":
                        $clasifica = "Activo";
                        break;
                    case "2":
                        $clasifica = "Pasivo";
                        break;
                    case "3":
                        $clasifica = "Patrimonio";
                        break;
                    case "4":
                        $clasifica = "Gastos";
                        break;
                    case "5":
                        $clasifica = "Ingresos";
                        break;
                    case "6":
                        $clasifica = "Cuentas Contingentes";
                        break;
                    case "7":
                        $clasifica = "Cuentas de Orden";
                        break;
                }*/

                // FUNCION PARA AUMENTAR EL . ASI EL EL USUARIO HAYA PUESTO EL PUNTO O NO
                $cadena9=filter_var($txtCodigo, FILTER_SANITIZE_NUMBER_INT);// elimina el . y solo coje numeros  //echo preg_replace('/[^0-9]/','',$cadena);
//              $cadena9 = split('[.]',$txtCodigo);
                $tamano = strlen($cadena9); // longitud de l acadena
                $caracter = "";                
                $conDigito = 0;
                $cont = 0;
                for($i=0; $i<$tamano; $i++){
                    if($i == 0 or $i == 1){// primero y segundo digito
                        $caracter = $caracter.$cadena9[$i]."";
                        $cont++;
                    }else if($conDigito == 0){//tercer digito en adelante
                        $caracter = $caracter.$cadena9[$i];
                        $conDigito++;
                    }else{
                        $caracter = $caracter.$cadena9[$i]."";
                        $cont++;
                        $conDigito = 0;
                    }
                }                           
                $nivel = $cont;
                // $sql = "update plan_cuentas set  codigo='".$caracter."', nombre='".$nombre."', tipo='".$tipo."', nivel='".$tamano."', id_empresa='".$sesion_id_empresa."', cuenta_banco='".$txtCuentaBanco."' where id_plan_cuenta='".$id_plan_cuenta."';";
 $cmbFormatoCheque = trim($_POST['cmbFormatoCheque']);
                $cmbFormatoCheque = ($cmbFormatoCheque =='')?0:$cmbFormatoCheque ;
                $sql = "update plan_cuentas set  codigo='".$caracter."', nombre='".$nombre."', tipo='".$tipo."', nivel='".$tamano."', id_empresa='".$sesion_id_empresa."', cuenta_banco='".$txtCuentaBanco."',tipo_cheque='".$cmbFormatoCheque."'  where id_plan_cuenta='".$id_plan_cuenta."';";

                $resp = mysql_query($sql);

              if($resp){
                  ?>4<?php
              } else{ ?>3<?php  }

              } else{ ?> 2<?php }

           } else{ ?> 1<?php }

        }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
    }

    if($accion == 5){
        //elimina una cuenta contable 
        $id_cuenta = $_POST['id_cuenta'];
        $sql2 = "SELECT total,id_empresa,codigo FROM plan_cuentas where id_plan_cuenta='".$id_cuenta."'";
        $result=mysql_query($sql2);
        while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
            {
                $cadena = $row['total'];
                $codigo = $row['codigo'];
            }
            

            $sqlSubCuentas="SELECT codigo,total,id_empresa,
                            (SELECT count(codigo) as sub_cuentas FROM plan_cuentas
                                WHERE plan_cuentas.id_empresa = plan_origen.id_empresa and 
                                 plan_cuentas.codigo LIKE '".$codigo."%"."' and plan_cuentas.id_empresa=plan_origen.id_empresa
                            ) as nro_subcuentas
                        FROM plan_cuentas AS plan_origen WHERE id_plan_cuenta = '".$id_cuenta."'";            

            $rs_Subcuentas=mysql_query($sqlSubCuentas);
            while($row=mysql_fetch_array($rs_Subcuentas)){
                    $nro_subcuentas = $row['nro_subcuentas']-1;
            }                                  

            if($cadena == 0 and $nro_subcuentas ==0 ){
                $sql = sprintf("delete from plan_cuentas where id_plan_cuenta=%d",(int)$_POST['id_cuenta']);
                if(!mysql_query($sql)) { 
                    echo "Ocurrio un error\n$sql";
                }
                echo "La Cuenta ha sido Eliminada.";
            }                
            elseif ($cadena == 0 and $nro_subcuentas > 0 ) {
                    echo "Imposible eliminar la cuenta por que hay ".$nro_subcuentas." subcuentas asociadas";                
            }
            elseif ($cadena !=0  and $nro_subcuentas > 0 ) {
                    echo "Imposible eliminar la cuenta con movimientos y ".$nro_subcuentas." subcuentas asociadas";
            }
            elseif ($cadena !=0  and $nro_subcuentas == 0 ) {
                    echo "Imposible eliminar la cuenta con movimientos";
            }                                                   
    }
//   if($accion == 3){
//        $clasificacion = $_POST['clasificacion'];
//        $consulta7="select CHARACTER_LENGTH(codigo) as max_cod, codigo from plan_cuentas where clasificacion ='".$clasificacion."' and (CHARACTER_LENGTH(codigo))=(select max(CHARACTER_LENGTH(codigo)) from plan_cuentas where clasificacion ='".$clasificacion."');";
//        $result7=mysql_query($consulta7);
//        while($row7=mysql_fetch_array($result7))//permite ir de fila en fila de la tabla
//            {
//                $cadena7=$cadena7."?".$row7['codigo'];
//            }
//       echo "".$cadena7;
//   }
//
//   if($accion == 4){
//       $clasificacion = $_POST['clasificacion'];
//       $nivel = $_POST['nivel'];
//        $consulta8="SELECT max(codigo) FROM plan_cuentas where clasificacion ='".$clasificacion."' and nivel ='".$nivel."'";
//        $result8=mysql_query($consulta8);
//        while($row8=mysql_fetch_array($result8))//permite ir de fila en fila de la tabla
//            {
//                $cadena8=$cadena8."?".$row8['max(codigo)'];
//            }
//       echo "".$cadena8;
//   }
//
//   if($accion == 5){
//       $clasificacion = $_POST['clasificacion'];
//       $nivel = $_POST['nivel'];
//        $consulta8="SELECT codigo FROM plan_cuentas where clasificacion ='".$clasificacion."' and nivel ='".$nivel."' ORDER BY codigo asc limit 1";
//        $result8=mysql_query($consulta8);
//        while($row8=mysql_fetch_array($result8))//permite ir de fila en fila de la tabla
//            {
//                $cadena8=$cadena8."?".$row8['codigo'];
//            }
//       echo "".$cadena8;
//   }
   

    

    if($accion == 10){
        // VALIDACIONES PARA QUE EL CODIGO NO SE REPITA
        try{
        
        if(isset ($_POST['codigo'])){
            $codigo = $_POST['codigo'];
            // FUNCION PARA AUMENTAR EL . ASI EL EL USUARIO HAYA PUESTO EL PUNTO O NO
            $cadena9=filter_var($codigo, FILTER_SANITIZE_NUMBER_INT);// elimina el . y solo coje numeros  //echo preg_replace('/[^0-9]/','',$cadena);
            $tamano = strlen($cadena9); // longitud de l acadena
            $caracter = "";
            $conDigito = 0;
            $cont = 0;
            for($i=0; $i<$tamano; $i++){
                if($i == 0 or $i == 1){// primero y segundo digito
                    $caracter = $caracter.$cadena9[$i]."";
                    $cont++;
                }else if($conDigito == 0){//tercer digito en adelante
                    $caracter = $caracter.$cadena9[$i];
                    $conDigito++;
                }else{
                    $caracter = $caracter.$cadena9[$i]."";
                    $cont++;
                    $conDigito = 0;
                }
            }
            $sql = "SELECT codigo from plan_cuentas where id_empresa='".$sesion_id_empresa."' and codigo='".$caracter."'";
                      //echo " consulta ".$sql;
            $resp = mysql_query($sql);
            $entro=0;
            while($row=mysql_fetch_array($resp))//permite ir de fila en fila de la tabla
            {
                $var1=$row["codigo"];
            }
          
            if($var1==$caracter){
               if($var1=="" && $codigo==""){
                 $entro=0;                 
               }else{
                $entro=1;
               }
            }
            echo $entro;
        }

        }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }

    }

    if($accion == 11){
        try
        {
        // VALIDACIONES PARA QUE EL NOMBRE NO SE REPITA

         if(isset ($_POST['nombre'])){
          $nombre = $_POST['nombre'];
          $sql = "SELECT nombre from plan_cuentas where id_empresa='".$sesion_id_empresa."' and nombre='".$nombre."'";
        //          echo " consulta ".$sql;
          $resp = mysql_query($sql);
          $entro=0;
          while($row=mysql_fetch_array($resp))//permite ir de fila en fila de la tabla
                    {
                        $var1=$row["nombre"];
                    }
            $nombre_bdd = strtolower($var1);// para convertir a minusculas y poder comparar
            $nombre_formulario = strtolower($nombre);// para convertir a minusculas y poder comparar
          if($nombre_bdd == $nombre_formulario){
                   if($var1==""&&$nombre==""){
                     $entro=0;                     
                  }else{
                      $entro=1;
                  }           
          }
         echo $entro;
         }

        }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
    }
    
   /*  if($accion == 122){
        // IMPORTA PLAN DE CUENTAS MODELO DE LA BASE DE DATOS
        $consulta12="SELECT codigo, nombre, clasificacion, tipo, categoria, nivel, total, id_empresa FROM plan_cuentas WHERE id_empresa='1';";
        $result12=mysql_query($consulta12) or die(mysql_error());
        $numero_filas12 = mysql_num_rows($result12); // obtenemos el número de filas
        if($numero_filas12 == 0){
            echo "<div class='transparent_ajax_error'><p>No hay Plan de Cuentas para Importar.</p></div>";
        }else{
            while($row12=mysql_fetch_array($result12))//permite ir de fila en fila de la tabla
            {
                $sql121 = "insert into plan_cuentas (codigo, nombre, clasificacion, tipo, categoria, nivel, total, id_empresa) values ('".$row12["codigo"]."','".$row12["nombre"]."','".$row12["clasificacion"]."','".$row12["tipo"]."','".$row12["categoria"]."','".$row12["nivel"]."','0','".$sesion_id_empresa."')";
                $result121=mysql_query($sql121) or die(mysql_error());
            }
            echo "<div class='transparent_ajax_correcto'><p>Termino la Importaci&oacute;n</p></div>";
        }
        
    } */

	if($accion == 12)
	{
	   // echo "opcion 12";
        // IMPORTAR PLAN DE CUENTAS MODELO DE LA BASE DE DATOS
        $consulta12="SELECT codigo, nombre, clasificacion, tipo, categoria, nivel, total, id_empresa 
        FROM plan_cuentas WHERE id_empresa='1';";
        $result12=mysql_query($consulta12) or die(mysql_error());
        $numero_filas12 = mysql_num_rows($result12); // obtenemos el número de filas
        if($numero_filas12 == 0)
		{
            echo "<div class='alert alert-danger'><p>No hay Plan de Cuentas para Importar.</p></div>";
        }
		else
		{
			$entro=0;
			while($row12=mysql_fetch_array($result12))//permite ir de fila en fila de la tabla
            {
				//Verifica si la cuenta ya existe en el plan de cuentas
			
				$lcCodigo=$row12["codigo"];
				//echo "codigo".$lcCodigo;
				$sql = "SELECT codigo, id_empresa from plan_cuentas where codigo='".$lcCodigo."' and id_empresa='".$sesion_id_empresa."'; ";
				//echo $sql;
				$resp = mysql_query($sql);
					$xcodigo="";
					$xempresa="";
				
				while($row=mysql_fetch_array($resp))//permite ir de fila en fila de la tabla
				{
					$xcodigo=$row["codigo"];
					$xempresa=$row["id_empresa"];
				}
				if(($xcodigo==$lcCodigo) and ($xempresa==$sesion_id_empresa))
				{
					$entro=1;
				}
			}	
			if ($entro==1)
			{
			   // alert("entro");
				?> <div class="alert alert-danger"><p>Plan de cuenta ya esta registrado 
					<?php echo "".$ex ?></p></div> 
				<?php 
			}	
			else
			{
		$consulta12="SELECT codigo, nombre, clasificacion, tipo, categoria, nivel, total, id_empresa 
        FROM plan_cuentas WHERE id_empresa='1';";
        $result12=mysql_query($consulta12) or die(mysql_error());
        		while($row12=mysql_fetch_array($result12))    //permite ir de fila en fila de la tabla
				{
				  //  echo "ammmmaaaeeeeeeeeeeeea";
			
                 $borrar=1;
                 if ($_SESSION['sesion_tipo_empresa']==6){
                    $borrar=0;
                 }
				  $sql121 = "insert into plan_cuentas (codigo, nombre, clasificacion, tipo, categoria, nivel, total,id_empresa,borrar)
				   values ('".$row12["codigo"]."','".$row12["nombre"]."','".$row12["clasificacion"]."','".$row12["tipo"]."','".$row12["categoria"]."','".$row12["nivel"]."','0','".$sesion_id_empresa."','".$borrar."')";
				
			//	 echo "depues de insertar plan de cuentas";
			//	 echo "$sql121";
				
				  $result121=mysql_query($sql121) or die(mysql_error());
				 // echo $result121;
				}
				
                 if ($_SESSION['sesion_tipo_empresa']==5 or $_SESSION['sesion_tipo_empresa']==6 or $_SESSION['sesion_tipo_empresa']==7 ){
                    $sqli="Select max(id) as MaxID from enlaces_compras";
                    $result=mysql_query($sqli);
                    $id_enlace_compra=0;
                    while($row=mysql_fetch_array($result)){
                         $id_enlace_compra=$row['MaxID'];
                    }
                //    echo $id_enlace_compra;
                    $id_enlace_compra++;
            
            $sql_enlaces_compra="SELECT          enlaces_compras_mae.codigo_plan_cuenta,
                                                 enlaces_compras_mae.nombre,
                                                 enlaces_compras_mae.porcentaje,
                                                 enlaces_compras_mae.tipo,
                                                 enlaces_compras_mae.codigo,
                                                 enlaces_compras_mae.codigo_sri,
                                                 enlaces_compras_mae.formula,
                                                 plan_cuentas.id_plan_cuenta as cuenta_contable,
                                                 enlaces_compras_mae.inventario_producto,
                                                 enlaces_compras_mae.iva,
                                                 enlaces_compras_mae.otros_gastos,
                                                 enlaces_compras_mae.iva_credito_tributario,
                                                 enlaces_compras_mae.ice,plan_cuentas.id_empresa,
                                                 enlaces_compras_mae.ice,
                                                 plan_cuentas.id_empresa,
                                                 enlaces_compras_mae.tipo_cpra
                                                 	
                                        FROM enlaces_compras_mae 
                                        INNER JOIN plan_cuentas ON enlaces_compras_mae.codigo_plan_cuenta = plan_cuentas.codigo
                                        WHERE plan_cuentas.id_empresa = '".$sesion_id_empresa."';";

                    $res_enlaces_compra=mysql_query($sql_enlaces_compra) or die(mysql_error());

                    while($enlace=mysql_fetch_array($res_enlaces_compra))    //permite ir de fila en fila de la tabla
                    {
                
                  $ins_enlace_compra = "insert into enlaces_compras (id,nombre,porcentaje,tipo,codigo,codigo_sri,formula, cuenta_contable,inventario_producto,iva,otros_gastos,iva_credito_tributario,ice,id_empresa,tipo_cpra)
                  values ('".$id_enlace_compra."','".$enlace["nombre"]."','".$enlace["porcentaje"]."','".$enlace["tipo"]."','".$enlace["codigo"]."','".$enlace["codigo_sri"]."','".$enlace["formula"]."','".$enlace["cuenta_contable"]."','".$enlace["inventario_producto"]."','".$enlace["iva"]."','".$enlace["otros_gastos"]."','".$enlace["iva_credito_tributario"]."','".$enlace["ice"]."','".$sesion_id_empresa."','".$enlace["tipo_cpra"]."')";
                      $res_ins_enlace=mysql_query($ins_enlace_compra) or die(mysql_error());
                      $id_enlace_compra++;
                    }
                }
            
                /* Crear Impuesto al Iva en Ventas*/
                $sql_impuesto="INSERT INTO impuestos (id_iva,iva,estado,id_empresa,id_plan_cuenta) 
                            SELECT max(id_iva) + 1 AS id_iva,12 AS iva,'Activo' AS estado,'".$sesion_id_empresa."' AS id_empresa, (
                                   SELECT plan_cuentas.id_plan_cuenta  FROM plan_cuentas
                                   WHERE plan_cuentas.codigo = '21005001' and plan_cuentas.id_empresa='".$sesion_id_empresa."') as id_plan_cuenta 
                                   FROM impuestos";
                $rs_impuesto=mysql_query($sql_impuesto) or die(mysql_error());

                /* Crear Vendedor*/
                $sql_vendedor="INSERT INTO vendedores (id_vendedor,codigo,nombre,apellidos,direccion,telefono,comision,id_empresa) 
                                             select max(id_vendedor)+1 as id_vendedor,'001' as codigo,'VENDEDOR' as nombre,'VENDEDOR' as apellidos,
                                             '' as  direccion, '' as telefono, 0 as comision,'".$sesion_id_empresa."'  as id_empresa from vendedores ";
                $rs_vendedor=mysql_query($sql_vendedor) or die(mysql_error());

                /* Crear Categoria/Grupo */
                $sql_categoria="INSERT INTO categorias (id_categoria,categoria,id_empresa) select max(id_categoria)+1 as id_categoria,'PRODUCTOS' as categoria,'".$sesion_id_empresa."'  as id_empresa from categorias ";
                $rs_categoria=mysql_query($sql_categoria) or die(mysql_error());

                $sql_categoria="INSERT INTO categorias (id_categoria,categoria,id_empresa) select max(id_categoria)+1 as id_categoria,'SERVICIOS' as categoria,'".$sesion_id_empresa."'  as id_empresa from categorias ";
                $rs_categoria=mysql_query($sql_categoria) or die(mysql_error());


                /* Crear Unidad de Medida*/
                $sql_unidad="INSERT INTO unidades (id_unidad,nombre,id_empresa) select max(id_unidad)+1 as id_unidad,'UNIDAD' as nombre,'".$sesion_id_empresa."' as id_empresa from unidades";
                $rs_unidad=mysql_query($sql_unidad) or die(mysql_error());

                /* Crear tipo de Servicio*/
                $sql_tipo_servicio="INSERT INTO tipo_servicios (id_tipo_servicio,nombre,id_empresa) select max(id_tipo_servicio)+1 AS id_tipo_servicio ,'TIPO DE SERVICIO' as nombre,'".$sesion_id_empresa."' as id_empresa from tipo_servicios";
                $rs_tipo_servicio=mysql_query($sql_tipo_servicio) or die(mysql_error());

                /* Crear los Servicio*/
                $sqli="Select max(id_servicio) as MaxID from SERVICIOS";
                $result=mysql_query($sqli);
                $id_servicio=0;
                while($row=mysql_fetch_array($result)){
                     $id_servicio=$row['MaxID'];
                }
                $id_servicio++;
                $sql_nuevo_servicio="SELECT 0 as id_servicio,sermae.codigo,sermae.nombre,categorias.id_categoria,
                               unidades.id_unidad,tipo.id_tipo_servicio,format((0 + ( RAND() * (50))),4) AS precio_venta1,
                               'Si' AS iva,plan.id_empresa,plan.id_plan_cuenta
                    FROM       servicios_mae AS sermae
                    INNER JOIN plan_cuentas AS plan ON sermae.codigo_cuenta_contable = plan.codigo
                    INNER JOIN categorias ON sermae.nombre = categorias.categoria AND plan.id_empresa = categorias.id_empresa
                    INNER JOIN unidades ON sermae.unidad = unidades.nombre AND plan.id_empresa = unidades.id_empresa
                    INNER JOIN tipo_servicios AS tipo ON sermae.tipo = tipo.nombre AND plan.id_empresa = tipo.id_empresa
                    WHERE      plan.id_empresa = '".$sesion_id_empresa."'  GROUP BY   sermae.id_servicio;";
                $res_nuevo_servicio=mysql_query($sql_nuevo_servicio) or die(mysql_error());
                while($enlace=mysql_fetch_array($res_nuevo_servicio)){    //permite ir de fila en fila de la tabla
                        $ins_nuevo_servicio = "insert into 
                        servicios ( codigo,nombre,id_categoria,id_unidad,id_tipo_servicio,precio_venta1,iva,id_empresa,id_plan_cuenta)
                           values ('".$enlace["codigo"]."','".$enlace["nombre"]."','".$enlace["id_categoria"]."',
                           '".$enlace["id_unidad"]."','".$enlace["id_tipo_servicio"]."','".$enlace["precio_venta1"]."',
                           '".$enlace["iva"]."', '".$sesion_id_empresa."','".$enlace["id_plan_cuenta"]."')";
                      $res_ins_nuevo_servicio=mysql_query($ins_nuevo_servicio) or die(mysql_error());
                      $id_servicio++;
                }
                
              //  echo $sql_nuevo_servicio;

                /* Crear formas de Pago*/
                if ($_SESSION['sesion_tipo_empresa']==5 or $_SESSION['sesion_tipo_empresa']==6 ){
                    
                    $sqli="Select max(id_forma_pago) as MaxID from formas_pago ";
                    $result=mysql_query($sqli);
                    $id_forma_de_pago=0;
                    while($row=mysql_fetch_array($result)){
                       $id_forma_de_pago=$row['MaxID'];
                    }
                    $id_forma_de_pago++;
                    $sql_nueva_forma_de_pago="SELECT 0 as id_forma_pago, formas_pago_mae.nombre,plan_cuentas.id_plan_cuenta, '".$sesion_id_empresa."' as id_empresa,formas_pago_mae.id_tipo_movimiento,formas_pago_mae.diario,formas_pago_mae.ingreso,formas_pago_mae.egreso,formas_pago_mae.pagar,formas_pago_mae.tipo
                                              FROM formas_pago_mae
                                              INNER JOIN plan_cuentas ON formas_pago_mae.codigo = plan_cuentas.codigo
                                              WHERE plan_cuentas.id_empresa = '".$sesion_id_empresa."'
                                              GROUP BY formas_pago_mae.codigo";
                    $res_nueva_forma_de_pago=mysql_query($sql_nueva_forma_de_pago) or die(mysql_error());
                    while($enlace=mysql_fetch_array($res_nueva_forma_de_pago)){    //permite ir de fila en fila de la tabla
                          $ins_nueva_forma_de_pago = "insert into 
                          formas_pago ( id_forma_pago,nombre,id_plan_cuenta,id_empresa,id_tipo_movimiento,diario,ingreso,egreso,pagar,tipo)
                             values ('".$id_forma_de_pago."','".$enlace["nombre"]."','".$enlace["id_plan_cuenta"]."','".$sesion_id_empresa."','".$enlace["id_tipo_movimiento"]."',
                             '".$enlace["diario"]."','".$enlace["ingreso"]."','".$enlace["egreso"]."','".$enlace["pagar"]."','".$enlace["tipo"]."')";
                          $res_ins_nueva_forma_de_pago=mysql_query($ins_nueva_forma_de_pago) or die(mysql_error());
                          $id_forma_de_pago++;
                    }
                }

                echo "<div class='alert alert-success'><p>Termino la Importaci&oacute;n</p></div>";
				
			}
        }      
    }
	
    if($accion == 13){
        // ELIMINAR PLAN DE CUENTAS MODELO DE LA BASE DE DATOS
        $consulta13="delete from plan_cuentas WHERE id_empresa='".$sesion_id_empresa."';";
        $result13=mysql_query($consulta13) or die(mysql_error());
        echo "<div class='transparent_ajax_correcto'><p>Se elimino Plan de Cuentas.</p></div>";
    }

    if($accion == 14){
        $cadena14 = "";
        $consulta14="SELECT * FROM plan_cuentas where id_empresa ='".$sesion_id_empresa."' order by codigo asc;";
        $result14=mysql_query($consulta14);
        while($row14=mysql_fetch_array($result14))//permite ir de fila en fila de la tabla
            {
                $cadena14=$cadena14."?".$row14['id_plan_cuenta']."?".$row14['codigo']." - ".$row14['nombre'];
            }
       echo $cadena14;
    }
    
      if($accion == 20){
        $sql_periodo_contable = "";
        $sql_periodo_contable="SELECT id_periodo_contable FROM periodo_contable where id_empresa ='".$sesion_id_empresa."' limit 1";
        $res_periodo_contable=mysql_query($sql_periodo_contable);
        while($row_periodo_contable=mysql_fetch_array($res_periodo_contable)){
                $periodo_contable = $row_periodo_contable['id_periodo_contable'];
        }

        $sql_r1="delete from plan_cuentas where id_empresa = '".$sesion_id_empresa."';";
        $sql_r2="delete from productos where id_empresa = '".$sesion_id_empresa."'";
        $sql_r3="delete from servicios where id_empresa = '".$sesion_id_empresa."';";
        $sql_r4="delete from enlaces_compras where id_empresa = '".$sesion_id_empresa."';";
        $sql_r5="delete from categorias where id_empresa = '".$sesion_id_empresa."';";
        $sql_r43="DELETE dcretencion, mcretencion FROM mcretencion INNER JOIN dcretencion ON dcretencion.Retencion_id = mcretencion.Id WHERE mcretencion.Factura_id in (SELECT compras.id_compra from compras WHERE compras.id_empresa=$sesion_id_empresa)";
        $sql_r6="delete from compras where id_empresa = '".$sesion_id_empresa."';";
        $sql_r7="delete from comprobantes where id_empresa = '".$sesion_id_empresa."';";
        $sql_r8="delete from cuentas_por_cobrar where id_empresa = '".$sesion_id_empresa."';";
        $sql_r9="delete from cuentas_por_pagar where id_empresa = '".$sesion_id_empresa."';";
        $sql_r10="delete from formas_pago where id_empresa = '".$sesion_id_empresa."';";
        $sql_r11="delete from impuestos where id_empresa = '".$sesion_id_empresa."';";
        $sql_r12="delete from inventarios_bodega where id_empresa = '".$sesion_id_empresa."';";
        $sql_r13="delete from kardes where id_empresa = '".$sesion_id_empresa."';";
        $sql_r14="delete from pedidos where id_empresa = '".$sesion_id_empresa."';";
        $sql_r15="delete from proveedores where id_empresa = '".$sesion_id_empresa."';";
        $sql_r16="delete from vendedores where id_empresa = '".$sesion_id_empresa."';";
        $sql_r17="delete from clientes where id_empresa = '".$sesion_id_empresa."';";
        $sql_r18="delete from tipo_servicios where id_empresa = '".$sesion_id_empresa."';";
        $sql_r19="delete from unidades where id_empresa = '".$sesion_id_empresa."';";
        $sql_r20="delete from libro_diario where id_periodo_contable = '".$periodo_contable."';";
        $sql_r21="delete from ventas where id_empresa = '".$sesion_id_empresa."';";
        $sql_r22="delete from detalle_ventas where id_empresa = '".$sesion_id_empresa."';";
        $sql_r23="delete from mayorizacion where id_periodo_contable = '".$periodo_contable."';";
        $sql_r24="delete from detalle_compras where id_empresa = '".$sesion_id_empresa."';";
        $sql_r25="delete from devolucion where id_empresa = '".$sesion_id_empresa."';";
        
        $sql_r26="delete from detalles where id_empresa = '".$sesion_id_empresa."';";
        
        $sql_r27="delete from detalle_libro_diario where id_periodo_contable = '".$periodo_contable."';";
        $sql_r28="delete from detalle_ingresos where id_empresa = '".$sesion_id_empresa."';";
        $sql_r29="delete from ingresos where id_empresa = '".$sesion_id_empresa."';";
        $sql_r30="delete from egresos where id_empresa = '".$sesion_id_empresa."';";
        $sql_r31="delete from detalle_egresos where id_empresa = '".$sesion_id_empresa."';";
        $sql_r32="delete from centro_costo where empresa = '".$sesion_id_empresa."';";
        $sql_r33="delete from cobrospagos where id_empresa = '".$sesion_id_empresa."';";
        
    //     $sql_r34 = "DELETE rol_pagos, detalle_rol_pagos
    //   FROM rol_pagos
    //   INNER JOIN detalle_rol_pagos ON detalle_rol_pagos.id_rol_pago = rol_pagos.id_rol_pago
    //   WHERE rol_pagos.id_empresa = $sesion_id_empresa;";
        
        // $sql_r35="DELETE FROM `enlace_rol_pagos` WHERE `id_empresa` =$sesion_id_empresa";
        
        //   $sql_r36="DELETE FROM `pagosDecimos` WHERE `id_empresa`=$sesion_id_empresa ";


        // $sql_r37="DELETE rolpagosEmpleados
        // FROM empleados
        // INNER JOIN rolpagosEmpleados ON rolpagosEmpleados.id_empleado = empleados.id_empleado
        // WHERE empleados.id_empresa = $sesion_id_empresa;";

        // $sql_r38="DELETE gastos_personales
        // FROM empleados
        // INNER JOIN gastos_personales ON gastos_personales.id_empleado = empleados.id_empleado
        // WHERE empleados.id_empresa =$sesion_id_empresa;";

        // $sql_r39="DELETE vacaciones_empleados
        // FROM empleados
        // INNER JOIN vacaciones_empleados ON vacaciones_empleados.id_empleado = empleados.id_empleado
        // WHERE empleados.id_empresa =$sesion_id_empresa;";

        // $sql_r40="DELETE FROM `empleados` WHERE `id_empresa`=$sesion_id_empresa; ";
        // $sql_r41="DELETE FROM `transferencias` WHERE `id_empresa`=$sesion_id_empresa";
        //  $sql_r42="DELETE FROM `detalle_transferencia` WHERE `id_empresa`=$sesion_id_empresa";
        
        $sql_r44="DELETE FROM `anexosCreados` WHERE `id_empresa`=$sesion_id_empresa";
       
        mysql_query($sql_r1);
        mysql_query($sql_r2);
        mysql_query($sql_r3);
        mysql_query($sql_r4);
        mysql_query($sql_r5);
          mysql_query($sql_r43);
        mysql_query($sql_r6);
        mysql_query($sql_r7);
        mysql_query($sql_r8);
        mysql_query($sql_r9);
        mysql_query($sql_r10);
        mysql_query($sql_r11);
        mysql_query($sql_r12);
        mysql_query($sql_r13);
        mysql_query($sql_r14);
        mysql_query($sql_r15);
        mysql_query($sql_r16);
        mysql_query($sql_r17);
        mysql_query($sql_r18);
        mysql_query($sql_r19);
        mysql_query($sql_r20);
        mysql_query($sql_r21);
        mysql_query($sql_r22);
        mysql_query($sql_r23);
        mysql_query($sql_r24);
        mysql_query($sql_r25);
        mysql_query($sql_r26);
        mysql_query($sql_r27);
        mysql_query($sql_r28);
        mysql_query($sql_r29);
        mysql_query($sql_r30);
        mysql_query($sql_r31);
        mysql_query($sql_r32);
        mysql_query($sql_r33);
        //  mysql_query($sql_r34);
        //  mysql_query($sql_r35);
        // mysql_query($sql_r36);
        // mysql_query($sql_r37);
        // mysql_query($sql_r38);
        // mysql_query($sql_r39);
        // mysql_query($sql_r40);
         // mysql_query($sql_r41);
        // mysql_query($sql_r42);
           mysql_query($sql_r44);

        $sql_r45="DELETE detalle_bancos , bancos FROM bancos INNER JOIN detalle_bancos ON detalle_bancos.id_bancos = bancos.id_bancos WHERE bancos.id_periodo_contable =$periodo_contable";
        mysql_query($sql_r45);
        
        $sql_bodegas = "DELETE cantBodegas FROM cantBodegas INNER JOIN bodegas ON cantBodegas.idBodega = bodegas.id WHERE bodegas.id_empresa = '".$sesion_id_empresa."' ";

        //   $sql_bodegas="DELETE cantbodegas, bodegas FROM `bodegas` INNER JOIN cantBodegas ON cantBodegas.idBodega = bodegas.id WHERE bodegas.id_empresa ='".$sesion_id_empresa."' ";
        $res_sql_bodegas=mysql_query($sql_bodegas);
        
        if ($res_sql_bodegas){
           echo "<div class='alert alert-success'><p>Operacion completada</p></div>";
        }else{
            echo 'Operación Fallida Repita el Proceso';
        }
        
        
        
         $sql="INSERT INTO `bitacora`( `id_usuario`, `descripcion`, `fecha_accion`, `id_empresa`, `modulo`, `registro`) VALUES ('$sesion_id_usuario','Reiniciar','".date('Y-m-d')."','$sesion_id_empresa','Plan de cuentas','-1')";
    $result = mysql_query($sql);
    
     
    //         $sql_r33="SELECT id_bancos from bancos where id_periodo_contable = '".$periodo_contable."';";
//       	$resultado11=mysql_query($sql_r33);
// 		$idBancos=0;
// 		while($row=mysql_fetch_array($resultado11))  //permite ir de fila en fila de la tabla
// 		{
// 			    $idBancos=$row['id_bancos'];
// 				$sql12="delete FROM `detalle_bancos` where id_bancos='". $idBancos ."' ;";
// 		        $resultado2=mysql_query($sql12);
// 		      //  echo $sql12;
// 		}	
        
//         $sql_r34="delete from bancos where id_periodo_contable = '".$periodo_contable."';";
//         mysql_query($sql_r34);
        
           
        
        // $sql_bodegas="SELECT * FROM bodegas where id_empresa ='".$sesion_id_empresa."' ";
        // $res_sql_bodegas=mysql_query($sql_bodegas);
        // while($row_sql_bodegas=mysql_fetch_array($res_sql_bodegas)){
        //         $idbodegaeliminar = $row_sql_bodegas['id'];
        //          $sql_r35="delete from cantBodegas where idBodega = '".$idbodegaeliminar."';";
        //          $resultado35=mysql_query($sql_r35);
        // }    
        
        
        
    }  
    
	if($accion == 25)
	{
	   // echo "opcion 12";
        // IMPORTAR PLAN DE CUENTAS MODELO DE LA BASE DE DATOS
        $consulta12="SELECT codigo, descripcion, signo, tipoCuenta
        FROM plan_cuentas_libro;";
        $result12=mysql_query($consulta12) or die(mysql_error());
        $numero_filas12 = mysql_num_rows($result12); // obtenemos el número de filas
        if($numero_filas12 == 0)
		{
            echo "<div class='alert alert-danger'><p>No hay Plan de Cuentas para Importar.</p></div>";
        }
		else
		{
			$entro=0;
			while($row12=mysql_fetch_array($result12))//permite ir de fila en fila de la tabla
            {
				//Verifica si la cuenta ya existe en el plan de cuentas
			
				$lcCodigo=$row12["codigo"];
				//echo "codigo".$lcCodigo;
				$sql = "SELECT codigo, id_empresa from plan_cuentas where codigo='".$lcCodigo."' and id_empresa='".$sesion_id_empresa."'; ";
				//echo $sql;
				$resp = mysql_query($sql);
					$xcodigo="";
					$xempresa="";
				
				while($row=mysql_fetch_array($resp))//permite ir de fila en fila de la tabla
				{
					$xcodigo=$row["codigo"];
					$xempresa=$row["id_empresa"];
				}
				if(($xcodigo==$lcCodigo) and ($xempresa==$sesion_id_empresa))
				{
					$entro=1;
				}
			}	
			if ($entro==1)
			{
			   // alert("entro");
				?> <div class="alert alert-danger"><p>Plan de cuenta ya esta registrado 
					<?php echo "".$ex ?></p></div> 
				<?php 
			}	
			else
			{
		$consulta12="SELECT codigo, descripcion, signo, tipoCuenta
        FROM plan_cuentas_libro ;";
        $result12=mysql_query($consulta12) or die(mysql_error());
        	    
			   // echo "ammmmaaaa";
				while($row12=mysql_fetch_array($result12))    //permite ir de fila en fila de la tabla
				{
				  //  echo "ammmmaaaeeeeeeeeeeeea";
			
                 $borrar=1;
                 if ($_SESSION['sesion_tipo_empresa']==6){
                    $borrar=0;
                 }
				  $sql121 = "insert into plan_cuentas (codigo, nombre,  tipo, nivel,id_empresa,borrar)
				   values ('".$row12["codigo"]."','".$row12["descripcion"]."','".$row12["tipoCuenta"]."','".$row12["signo"]."','".$sesion_id_empresa."','".$borrar."')";
  				  $result121=mysql_query($sql121) or die(mysql_error());
				}
               

                /* Crear Impuesto al Iva en Ventas*/
                $sql_impuesto="INSERT INTO impuestos (id_iva,iva,estado,id_empresa,id_plan_cuenta) 
                            SELECT max(id_iva) + 1 AS id_iva,12 AS iva,'Activo' AS estado,'".$sesion_id_empresa."' AS id_empresa, (
                                   SELECT plan_cuentas.id_plan_cuenta  FROM plan_cuentas
                                   WHERE plan_cuentas.codigo = '2107' and plan_cuentas.id_empresa='".$sesion_id_empresa."') as id_plan_cuenta 
                                   FROM impuestos";
                $rs_impuesto=mysql_query($sql_impuesto) or die(mysql_error());

                /* Crear Vendedor*/
                $sql_vendedor="INSERT INTO vendedores (id_vendedor,codigo,nombre,apellidos,direccion,telefono,comision,id_empresa) 
                                             select max(id_vendedor)+1 as id_vendedor,'001' as codigo,'VENDEDOR' as nombre,'VENDEDOR' as apellidos,
                                             '' as  direccion, '' as telefono, 0 as comision,'".$sesion_id_empresa."'  as id_empresa from vendedores ";
                $rs_vendedor=mysql_query($sql_vendedor) or die(mysql_error());

                /* Crear Categoria/Grupo */
                $sql_categoria="INSERT INTO categorias (id_categoria,categoria,id_empresa) select max(id_categoria)+1 as id_categoria,'PRODUCTOS' as categoria,'".$sesion_id_empresa."'  as id_empresa from categorias ";
                $rs_categoria=mysql_query($sql_categoria) or die(mysql_error());

                $sql_categoria="INSERT INTO categorias (id_categoria,categoria,id_empresa) select max(id_categoria)+1 as id_categoria,'SERVICIOS' as categoria,'".$sesion_id_empresa."'  as id_empresa from categorias ";
                $rs_categoria=mysql_query($sql_categoria) or die(mysql_error());


                /* Crear Unidad de Medida*/
                $sql_unidad="INSERT INTO unidades (id_unidad,nombre,id_empresa) select max(id_unidad)+1 as id_unidad,'UNIDAD' as nombre,'".$sesion_id_empresa."' as id_empresa from unidades";
                $rs_unidad=mysql_query($sql_unidad) or die(mysql_error());

                /* Crear tipo de Servicio*/
                $sql_tipo_servicio="INSERT INTO tipo_servicios (id_tipo_servicio,nombre,id_empresa) select max(id_tipo_servicio)+1 AS id_tipo_servicio ,'TIPO DE SERVICIO' as nombre,'".$sesion_id_empresa."' as id_empresa from tipo_servicios";
                $rs_tipo_servicio=mysql_query($sql_tipo_servicio) or die(mysql_error());

                /* Crear los Servicio*/
                $sqli="Select max(id_servicio) as MaxID from SERVICIOS";
                $result=mysql_query($sqli);
                $id_servicio=0;
                while($row=mysql_fetch_array($result)){
                     $id_servicio=$row['MaxID'];
                }
                $id_servicio++;
                $sql_nuevo_servicio="SELECT 0 as id_servicio,sermae.codigo,sermae.nombre,categorias.id_categoria,
                               unidades.id_unidad,tipo.id_tipo_servicio,format((0 + ( RAND() * (50))),4) AS precio_venta1,
                               'Si' AS iva,plan.id_empresa,plan.id_plan_cuenta
                    FROM       servicios_mae AS sermae
                    INNER JOIN plan_cuentas AS plan ON sermae.codigo_cuenta_contable = plan.codigo
                    INNER JOIN categorias ON sermae.nombre = categorias.categoria AND plan.id_empresa = categorias.id_empresa
                    INNER JOIN unidades ON sermae.unidad = unidades.nombre AND plan.id_empresa = unidades.id_empresa
                    INNER JOIN tipo_servicios AS tipo ON sermae.tipo = tipo.nombre AND plan.id_empresa = tipo.id_empresa
                    WHERE      plan.id_empresa = '".$sesion_id_empresa."'  GROUP BY   sermae.id_servicio;";
                $res_nuevo_servicio=mysql_query($sql_nuevo_servicio) or die(mysql_error());
                while($enlace=mysql_fetch_array($res_nuevo_servicio)){    //permite ir de fila en fila de la tabla
                        $ins_nuevo_servicio = "insert into 
                        servicios ( codigo,nombre,id_categoria,id_unidad,id_tipo_servicio,precio_venta1,iva,id_empresa,id_plan_cuenta)
                           values ('".$enlace["codigo"]."','".$enlace["nombre"]."','".$enlace["id_categoria"]."',
                           '".$enlace["id_unidad"]."','".$enlace["id_tipo_servicio"]."','".$enlace["precio_venta1"]."',
                           '".$enlace["iva"]."', '".$sesion_id_empresa."','".$enlace["id_plan_cuenta"]."')";
                      $res_ins_nuevo_servicio=mysql_query($ins_nuevo_servicio) or die(mysql_error());
                      $id_servicio++;
                }

                if ($_SESSION['sesion_tipo_empresa']==5 or $_SESSION['sesion_tipo_empresa']==6 or $_SESSION['sesion_tipo_empresa']==7 ){
                    $sqli="Select max(id) as MaxID from enlaces_compras";
                    $result=mysql_query($sqli);
                    $id_enlace_compra=0;
                    while($row=mysql_fetch_array($result)){
                         $id_enlace_compra=$row['MaxID'];
                    }
               // echo $id_enlace_compra;
                    $id_enlace_compra++;
            
            $sql_enlaces_compra="SELECT          enlaces_compras_libro.codigo_plan_cuenta,
                                                 enlaces_compras_libro.nombre,
                                                 enlaces_compras_libro.porcentaje,
                                                 enlaces_compras_libro.tipo,
                                                 enlaces_compras_libro.codigo,
                                                 enlaces_compras_libro.codigo_sri,
                                                 enlaces_compras_libro.formula,
                                                 plan_cuentas.id_plan_cuenta as cuenta_contable,
                                                 enlaces_compras_libro.inventario_producto,
                                                 enlaces_compras_libro.iva,
                                                 enlaces_compras_libro.otros_gastos,
                                                 enlaces_compras_libro.iva_credito_tributario,
                                                 enlaces_compras_libro.ice,plan_cuentas.id_empresa,
                                                 enlaces_compras_libro.ice,
                                                 plan_cuentas.id_empresa,
                                                 enlaces_compras_libro.tipo_cpra
                                                 	
                                        FROM enlaces_compras_libro
                                        INNER JOIN plan_cuentas ON enlaces_compras_libro.codigo_plan_cuenta = plan_cuentas.codigo
                                        WHERE plan_cuentas.id_empresa = '".$sesion_id_empresa."';";

                    $res_enlaces_compra=mysql_query($sql_enlaces_compra) or die(mysql_error());

                    while($enlace=mysql_fetch_array($res_enlaces_compra))    //permite ir de fila en fila de la tabla
                    {
                
                  $ins_enlace_compra = "insert into enlaces_compras (id,nombre,porcentaje,tipo,codigo,codigo_sri,formula, cuenta_contable,inventario_producto,iva,otros_gastos,iva_credito_tributario,ice,id_empresa,tipo_cpra)
                  values ('".$id_enlace_compra."','".$enlace["nombre"]."','".$enlace["porcentaje"]."','".$enlace["tipo"]."','".$enlace["codigo"]."','".$enlace["codigo_sri"]."','".$enlace["formula"]."','".$enlace["cuenta_contable"]."','".$enlace["inventario_producto"]."','".$enlace["iva"]."','".$enlace["otros_gastos"]."','".$enlace["iva_credito_tributario"]."','".$enlace["ice"]."','".$sesion_id_empresa."','".$enlace["tipo_cpra"]."')";
                      $res_ins_enlace=mysql_query($ins_enlace_compra) or die(mysql_error());
                      $id_enlace_compra++;
             //    echo "</br>"."enlace compras".  $ins_enlace_compra;   
                      
                    }
                }
                /* Crear formas de Pago*/
                if ($_SESSION['sesion_tipo_empresa']==5 or $_SESSION['sesion_tipo_empresa']==6 ){
                    
                    $sqli="Select max(id_forma_pago) as MaxID from formas_pago ";
                    $result=mysql_query($sqli);
                    $id_forma_de_pago=0;
                    while($row=mysql_fetch_array($result)){
                       $id_forma_de_pago=$row['MaxID'];
                    }
                    $id_forma_de_pago++;
                    $sql_nueva_forma_de_pago="SELECT 0 as id_forma_pago, 
                    formas_pago_libro.nombre,
                    plan_cuentas.id_plan_cuenta, '".$sesion_id_empresa."' as id_empresa,
                    formas_pago_libro.id_tipo_movimiento,formas_pago_libro.diario,formas_pago_libro.ingreso,formas_pago_libro.egreso,formas_pago_libro.pagar
                                              FROM formas_pago_libro
                                              INNER JOIN plan_cuentas ON formas_pago_libro.codigo = plan_cuentas.codigo
                                              WHERE plan_cuentas.id_empresa = '".$sesion_id_empresa."'
                                              GROUP BY formas_pago_libro.codigo";
                    $res_nueva_forma_de_pago=mysql_query($sql_nueva_forma_de_pago) or die(mysql_error());
                    while($enlace=mysql_fetch_array($res_nueva_forma_de_pago)){    //permite ir de fila en fila de la tabla
                          $ins_nueva_forma_de_pago = "insert into 
                          formas_pago ( id_forma_pago,nombre,id_plan_cuenta,id_empresa,id_tipo_movimiento,diario,ingreso,egreso,pagar)
                             values ('".$id_forma_de_pago."','".$enlace["nombre"]."','".$enlace["id_plan_cuenta"]."','".$sesion_id_empresa."','".$enlace["id_tipo_movimiento"]."',
                             '".$enlace["diario"]."','".$enlace["ingreso"]."','".$enlace["egreso"]."','".$enlace["pagar"]."')";
                          $res_ins_nueva_forma_de_pago=mysql_query($ins_nueva_forma_de_pago) or die(mysql_error());
                          $id_forma_de_pago++;
                    echo $ins_nueva_forma_de_pago;
                        
                    }
                }

                echo "<div class='alert alert-success'><p>Termino la Importaci&oacute;n</p></div>";
				
			}
        }      
    }
    
    
    
    if($accion == 26)
	{
	   // echo "opcion 12";
        // IMPORTAR PLAN DE CUENTAS MODELO DE LA BASE DE DATOS
        $consulta12="SELECT codigo, descripcion, signo, tipoCuenta FROM plancuentasniff ;";
        $result12=mysql_query($consulta12) or die(mysql_error());
        $numero_filas12 = mysql_num_rows($result12); // obtenemos el número de filas
        if($numero_filas12 == 0)
		{
            echo "<div class='alert alert-danger'><p>No hay Plan de Cuentas para Importar.</p></div>";
        }
		else
		{
			$entro=0;
			while($row12=mysql_fetch_array($result12))//permite ir de fila en fila de la tabla
            {
				//Verifica si la cuenta ya existe en el plan de cuentas
			
				$lcCodigo=$row12["codigo"];
				//echo "codigo".$lcCodigo;
				$sql = "SELECT codigo, id_empresa from plan_cuentas where codigo='".$lcCodigo."' and id_empresa='".$sesion_id_empresa."'; ";
				//echo $sql;
				$resp = mysql_query($sql);
					$xcodigo="";
					$xempresa="";
				
				while($row=mysql_fetch_array($resp))//permite ir de fila en fila de la tabla
				{
					$xcodigo=$row["codigo"];
					$xempresa=$row["id_empresa"];
				}
				if(($xcodigo==$lcCodigo) and ($xempresa==$sesion_id_empresa))
				{
					$entro=1;
				}
			}	
			if ($entro==1)
			{
			   // alert("entro");
				?> <div class="alert alert-danger"><p>Plan de cuenta ya esta registrado 
					<?php echo "".$ex ?></p></div> 
				<?php 
			}	
			else
			{
		$consulta12="SELECT codigo, descripcion, tipoCuenta, signo,caja FROM plancuentasniff ;";
        $result12=mysql_query($consulta12) or die(mysql_error());
        
				while($row12=mysql_fetch_array($result12))    //permite ir de fila en fila de la tabla
				{
			
                 $borrar=1;
                 if ($_SESSION['sesion_tipo_empresa']==6){
                    $borrar=0;
                 }
				  $sql121 = "insert into plan_cuentas (codigo, nombre,  tipo, nivel,id_empresa,borrar,caja)
				   values ('".$row12["codigo"]."','".$row12["descripcion"]."','".$row12["tipoCuenta"]."','".$row12["signo"]."','".$sesion_id_empresa."','".$borrar."','".$row12["caja"]."')";
				  
				  $result121=mysql_query($sql121) or die(mysql_error());
				}
                
$sql_impuesto = "INSERT INTO impuestos (id_iva, iva, estado, id_empresa, id_plan_cuenta, codigo) 
                 SELECT MAX(id_iva) + 1 AS id_iva, 
                        15 AS iva, 
                        'Activo' AS estado, 
                        '".$sesion_id_empresa."' AS id_empresa, 
                        (SELECT plan_cuentas.id_plan_cuenta  
                         FROM plan_cuentas 
                         WHERE plan_cuentas.codigo = '2010701013' 
                         AND plan_cuentas.id_empresa='".$sesion_id_empresa."') AS id_plan_cuenta, 
                        4 AS codigo 
                 FROM impuestos";

$rs_impuesto = mysql_query($sql_impuesto) or die(mysql_error());


$sql_impuesto0 = "INSERT INTO impuestos (id_iva, iva, estado, id_empresa, id_plan_cuenta, codigo) 
                  SELECT MAX(id_iva) + 1 AS id_iva, 
                         0 AS iva, 
                         'Activo' AS estado, 
                         '".$sesion_id_empresa."' AS id_empresa, 
                         (SELECT plan_cuentas.id_plan_cuenta  
                          FROM plan_cuentas 
                          WHERE plan_cuentas.codigo = '2010701013' 
                          AND plan_cuentas.id_empresa='".$sesion_id_empresa."') AS id_plan_cuenta, 
                         0 AS codigo 
                  FROM impuestos";

$rs_impuesto0 = mysql_query($sql_impuesto0) or die(mysql_error());

                

                $sql_area="INSERT INTO centro_costo (id_bodega,codigo,descripcion,empresa,id_cuenta,tipo,img) 
                           values( '1' ,'1' ,'Inventario' ,'".$sesion_id_empresa."', (SELECT plan_cuentas.id_plan_cuenta  FROM plan_cuentas
                                   WHERE plan_cuentas.codigo = '1010306' and plan_cuentas.id_empresa='".$sesion_id_empresa."') ,'1',NULL)";
                $rs_area=mysql_query($sql_area) or die(mysql_error());
                
                $sql_areaS="INSERT INTO centro_costo (id_bodega,codigo,descripcion,empresa,id_cuenta,tipo,img) 
                           values( '2' ,'2' ,'Servicios' ,'".$sesion_id_empresa."', (SELECT plan_cuentas.id_plan_cuenta  FROM plan_cuentas
                                   WHERE plan_cuentas.codigo = '4102' and plan_cuentas.id_empresa='".$sesion_id_empresa."') ,'2',NULL)";
                $rs_areaS=mysql_query($sql_areaS) or die(mysql_error());

                $sql14 = "insert into bodegas( detalle, id_empresa) values ('Bodega Principal', '".$sesion_id_empresa."'); ";
                $result14 = mysql_query($sql14);
                
                /* Crear Vendedor*/
                $sql_vendedor="INSERT INTO vendedores (id_vendedor,codigo,nombre,apellidos,direccion,telefono,comision,id_empresa) 
                                             select max(id_vendedor)+1 as id_vendedor,'001' as codigo,'VENDEDOR' as nombre,'VENDEDOR' as apellidos,
                                             '' as  direccion, '' as telefono, 0 as comision,'".$sesion_id_empresa."'  as id_empresa from vendedores ";
                $rs_vendedor=mysql_query($sql_vendedor) or die(mysql_error());
                
                
                /* Crear Categoria/Grupo */
                $sql_categoria="INSERT INTO categorias (id_categoria,categoria,id_empresa) select max(id_categoria)+1 as id_categoria,'PRODUCTOS' as categoria,'".$sesion_id_empresa."'  as id_empresa from categorias ";
                $rs_categoria=mysql_query($sql_categoria) or die(mysql_error());

                $sql_categoria="INSERT INTO categorias (id_categoria,categoria,id_empresa) select max(id_categoria)+1 as id_categoria,'SERVICIOS' as categoria,'".$sesion_id_empresa."'  as id_empresa from categorias ";
                $rs_categoria=mysql_query($sql_categoria) or die(mysql_error());


                /* Crear Unidad de Medida*/
                $sql_unidad="INSERT INTO unidades (id_unidad,nombre,id_empresa) select max(id_unidad)+1 as id_unidad,'UNIDAD' as nombre,'".$sesion_id_empresa."' as id_empresa from unidades";
                $rs_unidad=mysql_query($sql_unidad) or die(mysql_error());

                /* Crear tipo de Servicio*/
                $sql_tipo_servicio="INSERT INTO tipo_servicios (id_tipo_servicio,nombre,id_empresa) select max(id_tipo_servicio)+1 AS id_tipo_servicio ,'TIPO DE SERVICIO' as nombre,'".$sesion_id_empresa."' as id_empresa from tipo_servicios";
                $rs_tipo_servicio=mysql_query($sql_tipo_servicio) or die(mysql_error());

                /* Crear los Servicio*/
                $sqli="Select max(id_servicio) as MaxID from SERVICIOS";
                $result=mysql_query($sqli);
                $id_servicio=0;
                while($row=mysql_fetch_array($result)){
                     $id_servicio=$row['MaxID'];
                }
                $id_servicio++;
                $sql_nuevo_servicio="SELECT 0 as id_servicio,sermae.codigo,sermae.nombre,categorias.id_categoria,
                               unidades.id_unidad,tipo.id_tipo_servicio,format((0 + ( RAND() * (50))),4) AS precio_venta1,
                               'Si' AS iva,plan.id_empresa,plan.id_plan_cuenta
                    FROM       servicios_mae AS sermae
                    INNER JOIN plan_cuentas AS plan ON sermae.codigo_cuenta_contable = plan.codigo
                    INNER JOIN categorias ON sermae.nombre = categorias.categoria AND plan.id_empresa = categorias.id_empresa
                    INNER JOIN unidades ON sermae.unidad = unidades.nombre AND plan.id_empresa = unidades.id_empresa
                    INNER JOIN tipo_servicios AS tipo ON sermae.tipo = tipo.nombre AND plan.id_empresa = tipo.id_empresa
                    WHERE      plan.id_empresa = '".$sesion_id_empresa."'  GROUP BY   sermae.id_servicio;";
                $res_nuevo_servicio=mysql_query($sql_nuevo_servicio) or die(mysql_error());
                while($enlace=mysql_fetch_array($res_nuevo_servicio)){    //permite ir de fila en fila de la tabla
                        $ins_nuevo_servicio = "insert into 
                        servicios ( codigo,nombre,id_categoria,id_unidad,id_tipo_servicio,precio_venta1,iva,id_empresa,id_plan_cuenta)
                           values ('".$enlace["codigo"]."','".$enlace["nombre"]."','".$enlace["id_categoria"]."',
                           '".$enlace["id_unidad"]."','".$enlace["id_tipo_servicio"]."','".$enlace["precio_venta1"]."',
                           '".$enlace["iva"]."', '".$sesion_id_empresa."','".$enlace["id_plan_cuenta"]."')";
                      $res_ins_nuevo_servicio=mysql_query($ins_nuevo_servicio) or die(mysql_error());
                      $id_servicio++;
                }
                
                 
                	
                 if ($_SESSION['sesion_tipo_empresa']==5 or $_SESSION['sesion_tipo_empresa']==6 or $_SESSION['sesion_tipo_empresa']==7 ){
                    $sqli="Select max(id) as MaxID from enlaces_compras";
                    $result=mysql_query($sqli);
                    $id_enlace_compra=0;
                    while($row=mysql_fetch_array($result)){
                         $id_enlace_compra=$row['MaxID'];
                    }
               // echo $id_enlace_compra;
                    $id_enlace_compra++;
            
            $sql_enlaces_compra="SELECT          enlaces_compras_niff.codigo_plan_cuenta,
                                                 enlaces_compras_niff.nombre,
                                                 enlaces_compras_niff.porcentaje,
                                                 enlaces_compras_niff.tipo,
                                                 enlaces_compras_niff.codigo,
                                                 enlaces_compras_niff.codigo_sri,
                                                 enlaces_compras_niff.formula,
                                                 plan_cuentas.id_plan_cuenta as cuenta_contable,
                                                 enlaces_compras_niff.inventario_producto,
                                                 enlaces_compras_niff.iva,
                                                 enlaces_compras_niff.otros_gastos,
                                                 enlaces_compras_niff.iva_credito_tributario,
                                                 enlaces_compras_niff.ice,plan_cuentas.id_empresa,
                                                 enlaces_compras_niff.ice,
                                                 plan_cuentas.id_empresa,
                                                 enlaces_compras_niff.tipo_cpra
                                                 	
                                        FROM enlaces_compras_niff
                                        INNER JOIN plan_cuentas ON enlaces_compras_niff.codigo_plan_cuenta = plan_cuentas.codigo
                                        WHERE plan_cuentas.id_empresa = '".$sesion_id_empresa."';";

                    $res_enlaces_compra=mysql_query($sql_enlaces_compra) or die(mysql_error());

                    while($enlace=mysql_fetch_array($res_enlaces_compra))    //permite ir de fila en fila de la tabla
                    {
                
                  $ins_enlace_compra = "insert into enlaces_compras (id,nombre,porcentaje,tipo,codigo,codigo_sri,formula, cuenta_contable,inventario_producto,iva,otros_gastos,iva_credito_tributario,ice,id_empresa,tipo_cpra)
                  values ('".$id_enlace_compra."','".$enlace["nombre"]."','".$enlace["porcentaje"]."','".$enlace["tipo"]."','".$enlace["codigo"]."','".$enlace["codigo_sri"]."','".$enlace["formula"]."','".$enlace["cuenta_contable"]."','".$enlace["inventario_producto"]."','".$enlace["iva"]."','".$enlace["otros_gastos"]."','".$enlace["iva_credito_tributario"]."','".$enlace["ice"]."','".$sesion_id_empresa."','".$enlace["tipo_cpra"]."')";
                      $res_ins_enlace=mysql_query($ins_enlace_compra) or die(mysql_error());
                      $id_enlace_compra++;
                echo "</br>"."enlace compras".  $ins_enlace_compra;   
                      
                    }
                    
                     echo "</br>"."select compras".  $sql_enlaces_compra;  
                }
            
                
                
                
                if ($_SESSION['sesion_tipo_empresa']==5 or $_SESSION['sesion_tipo_empresa']==6 ){
                    
                    $sqli="Select max(id_forma_pago) as MaxID from formas_pago";
                    $result=mysql_query($sqli);
                    $id_forma_de_pago=0;
                    while($row=mysql_fetch_array($result)){
                       $id_forma_de_pago=$row['MaxID'];
                    }
                    $id_forma_de_pago++;
                    $sql_nueva_forma_de_pago="SELECT 0 as id_forma_pago, formas_pago_niff.nombre,plan_cuentas.id_plan_cuenta,
                    '".$sesion_id_empresa."' as id_empresa,
                    formas_pago_niff.id_tipo_movimiento,formas_pago_niff.diario,formas_pago_niff.ingreso,formas_pago_niff.egreso,
                    formas_pago_niff.pagar
                                              FROM formas_pago_niff
                                              INNER JOIN plan_cuentas ON formas_pago_niff.codigo = plan_cuentas.codigo
                                              WHERE plan_cuentas.id_empresa = '".$sesion_id_empresa."'
                                              GROUP BY formas_pago_niff.codigo";
                    $res_nueva_forma_de_pago=mysql_query($sql_nueva_forma_de_pago) or die(mysql_error());
                    while($enlace=mysql_fetch_array($res_nueva_forma_de_pago)){    //permite ir de fila en fila de la tabla
                          $ins_nueva_forma_de_pago = "insert into 
                          formas_pago ( id_forma_pago,nombre,id_plan_cuenta,id_empresa,id_tipo_movimiento,diario,ingreso,egreso,pagar)
                             values ('".$id_forma_de_pago."','".$enlace["nombre"]."','".$enlace["id_plan_cuenta"]."','".$sesion_id_empresa."','".$enlace["id_tipo_movimiento"]."',
                             '".$enlace["diario"]."','".$enlace["ingreso"]."','".$enlace["egreso"]."','".$enlace["pagar"]."')";
                          $res_ins_nueva_forma_de_pago=mysql_query($ins_nueva_forma_de_pago) or die(mysql_error());
                          $id_forma_de_pago++;
                    }
                }

              

                echo "<div class='alert alert-success'><p>Terminó la Importaci&oacute;n</p></div>";
				
			}
        }      
    }


if($accion == 35){
	   // echo "opcion 12";
        // IMPORTAR PLAN DE CUENTAS MODELO DE LA BASE DE DATOS
        
        
     echo   $consulta12="SELECT codigo, descripcion, signo, tipoCuenta FROM plancuentasci ;";
        $result12=mysql_query($consulta12) or die(mysql_error());
        $numero_filas12 = mysql_num_rows($result12); // obtenemos el número de filas
        if($numero_filas12 == 0)
		{
            echo "<div class='alert alert-danger'><p>No hay Plan de Cuentas para Importar.</p></div>";
        }
		else
		{
			$entro=0;
            while ($row12 = mysqli_fetch_array($result12)) {
                
                $lcCodigo = $row12["codigo"];
            
             echo   $sql = "SELECT codigo, id_empresa FROM plan_cuentas WHERE  id_empresa = '$sesion_id_empresa'";
                $result = mysqli_query($conexion, $sql);
            
                $row = mysqli_fetch_array($result);
            
                if ($row["id_empresa"] == $sesion_id_empresa) {
                    $entro = 1;
                }
            }	
			if ($entro==1)
			{
			   // alert("entro");
				?> <div class="alert alert-danger"><p>Plan de cuenta ya esta registrado 
					<?php echo "".$ex ?></p></div> 
				<?php 
			}	
			else
			{
        		$consulta12="SELECT codigo, descripcion, tipoCuenta, signo,caja FROM plancuentasci ;";
                $result12=mysql_query($consulta12) or die(mysql_error());
        
				while($row12=mysql_fetch_array($result12))    //permite ir de fila en fila de la tabla
				{
			
                 $borrar=1;
                 if ($_SESSION['sesion_tipo_empresa']==6){
                    $borrar=0;
                 }
                 
				  $sql121 = "insert into plan_cuentas (codigo, nombre,  tipo, nivel,id_empresa,borrar,caja)
				   values ('".$row12["codigo"]."','".$row12["descripcion"]."','".$row12["tipoCuenta"]."','".$row12["signo"]."','".$sesion_id_empresa."','".$borrar."','".$row12["caja"]."')";
				  
				  $result121=mysql_query($sql121) or die(mysql_error());
				}
                

                /* Crear Impuesto al Iva en Ventas*/
                $sql_impuesto="INSERT INTO impuestos (id_iva,iva,estado,id_empresa,id_plan_cuenta) 
                            SELECT max(id_iva) + 1 AS id_iva,12 AS iva,'Activo' AS estado,'".$sesion_id_empresa."' AS id_empresa, (
                                   SELECT plan_cuentas.id_plan_cuenta  FROM plan_cuentas
                                   WHERE plan_cuentas.codigo = '201070101' and plan_cuentas.id_empresa='".$sesion_id_empresa."') as id_plan_cuenta 
                                   FROM impuestos";
                $rs_impuesto=mysql_query($sql_impuesto) or die(mysql_error());
                
                

                $sql_area="INSERT INTO centro_costo (id_bodega,codigo,descripcion,empresa,id_cuenta,tipo,img) 
                           values( '1' ,'1' ,'Inventario' ,'".$sesion_id_empresa."', (SELECT plan_cuentas.id_plan_cuenta  FROM plan_cuentas
                                   WHERE plan_cuentas.codigo = '1010301' and plan_cuentas.id_empresa='".$sesion_id_empresa."') ,'1',NULL)";
                $rs_area=mysql_query($sql_area) or die(mysql_error());
                
                $sql_areaS="INSERT INTO centro_costo (id_bodega,codigo,descripcion,empresa,id_cuenta,tipo,img) 
                           values( '2' ,'2' ,'Servicios' ,'".$sesion_id_empresa."', (SELECT plan_cuentas.id_plan_cuenta  FROM plan_cuentas
                                   WHERE plan_cuentas.codigo = '40101' and plan_cuentas.id_empresa='".$sesion_id_empresa."') ,'2',NULL)";
                $rs_areaS=mysql_query($sql_areaS) or die(mysql_error());


                /* Crear Vendedor*/
                $sql_vendedor="INSERT INTO vendedores (id_vendedor,codigo,nombre,apellidos,direccion,telefono,comision,id_empresa) 
                                             select max(id_vendedor)+1 as id_vendedor,'001' as codigo,'VENDEDOR' as nombre,'VENDEDOR' as apellidos,
                                             '' as  direccion, '' as telefono, 0 as comision,'".$sesion_id_empresa."'  as id_empresa from vendedores ";
                $rs_vendedor=mysql_query($sql_vendedor) or die(mysql_error());
                
                
                /* Crear Categoria/Grupo */
                $sql_categoria="INSERT INTO categorias (id_categoria,categoria,id_empresa) select max(id_categoria)+1 as id_categoria,'PRODUCTOS' as categoria,'".$sesion_id_empresa."'  as id_empresa from categorias ";
                $rs_categoria=mysql_query($sql_categoria) or die(mysql_error());

                $sql_categoria="INSERT INTO categorias (id_categoria,categoria,id_empresa) select max(id_categoria)+1 as id_categoria,'SERVICIOS' as categoria,'".$sesion_id_empresa."'  as id_empresa from categorias ";
                $rs_categoria=mysql_query($sql_categoria) or die(mysql_error());
                
                
                $sql14 = "insert into bodegas( detalle, id_empresa) values ('Bodega Principal', '".$sesion_id_empresa."'); ";
                $result14 = mysql_query($sql14);

                /* Crear Unidad de Medida*/
                $sql_unidad="INSERT INTO unidades (id_unidad,nombre,id_empresa) select max(id_unidad)+1 as id_unidad,'UNIDAD' as nombre,'".$sesion_id_empresa."' as id_empresa from unidades";
                $rs_unidad=mysql_query($sql_unidad) or die(mysql_error());

                /* Crear tipo de Servicio*/
                $sql_tipo_servicio="INSERT INTO tipo_servicios (id_tipo_servicio,nombre,id_empresa) select max(id_tipo_servicio)+1 AS id_tipo_servicio ,'TIPO DE SERVICIO' as nombre,'".$sesion_id_empresa."' as id_empresa from tipo_servicios";
                $rs_tipo_servicio=mysql_query($sql_tipo_servicio) or die(mysql_error());

                /* Crear los Servicio*/
                $sqli="Select max(id_servicio) as MaxID from SERVICIOS";
                $result=mysql_query($sqli);
                $id_servicio=0;
                while($row=mysql_fetch_array($result)){
                     $id_servicio=$row['MaxID'];
                }
                $id_servicio++;
                $sql_nuevo_servicio="SELECT 0 as id_servicio,sermae.codigo,sermae.nombre,categorias.id_categoria,
                               unidades.id_unidad,tipo.id_tipo_servicio,format((0 + ( RAND() * (50))),4) AS precio_venta1,
                               'Si' AS iva,plan.id_empresa,plan.id_plan_cuenta
                               
                    FROM       servicios_mae AS sermae
                    
                    INNER JOIN plan_cuentas AS plan ON sermae.codigo_cuenta_contable = plan.codigo
                    INNER JOIN categorias ON sermae.nombre = categorias.categoria AND plan.id_empresa = categorias.id_empresa
                    INNER JOIN unidades ON sermae.unidad = unidades.nombre AND plan.id_empresa = unidades.id_empresa
                    INNER JOIN tipo_servicios AS tipo ON sermae.tipo = tipo.nombre AND plan.id_empresa = tipo.id_empresa
                    WHERE      plan.id_empresa = '".$sesion_id_empresa."'  GROUP BY   sermae.id_servicio;";
                $res_nuevo_servicio=mysql_query($sql_nuevo_servicio) or die(mysql_error());
                while($enlace=mysql_fetch_array($res_nuevo_servicio)){    //permite ir de fila en fila de la tabla
                        $ins_nuevo_servicio = "insert into 
                        servicios ( codigo,nombre,id_categoria,id_unidad,id_tipo_servicio,precio_venta1,iva,id_empresa,id_plan_cuenta)
                           values ('".$enlace["codigo"]."','".$enlace["nombre"]."','".$enlace["id_categoria"]."',
                           '".$enlace["id_unidad"]."','".$enlace["id_tipo_servicio"]."','".$enlace["precio_venta1"]."',
                           '".$enlace["iva"]."', '".$sesion_id_empresa."','".$enlace["id_plan_cuenta"]."')";
                      $res_ins_nuevo_servicio=mysql_query($ins_nuevo_servicio) or die(mysql_error());
                      $id_servicio++;
                }
                
                 
                	
                 if ($_SESSION['sesion_tipo_empresa']==5 or $_SESSION['sesion_tipo_empresa']==6 or $_SESSION['sesion_tipo_empresa']==7 ){
                    $sqli="Select max(id) as MaxID from enlaces_compras";
                    $result=mysql_query($sqli);
                    $id_enlace_compra=0;
                    while($row=mysql_fetch_array($result)){
                         $id_enlace_compra=$row['MaxID'];
                    }
               // echo $id_enlace_compra;
                    $id_enlace_compra++;
            
            $sql_enlaces_compra="SELECT          enlaces_compras_niff.codigo_plan_cuenta,
                                                 enlaces_compras_niff.nombre,
                                                 enlaces_compras_niff.porcentaje,
                                                 enlaces_compras_niff.tipo,
                                                 enlaces_compras_niff.codigo,
                                                 enlaces_compras_niff.codigo_sri,
                                                 enlaces_compras_niff.formula,
                                                 plan_cuentas.id_plan_cuenta as cuenta_contable,
                                                 enlaces_compras_niff.inventario_producto,
                                                 enlaces_compras_niff.iva,
                                                 enlaces_compras_niff.otros_gastos,
                                                 enlaces_compras_niff.iva_credito_tributario,
                                                 enlaces_compras_niff.ice,plan_cuentas.id_empresa,
                                                 enlaces_compras_niff.ice,
                                                 plan_cuentas.id_empresa,
                                                 enlaces_compras_niff.tipo_cpra
                                                 	
                                        FROM enlaces_compras_niff
                                        INNER JOIN plan_cuentas ON enlaces_compras_niff.codigo_plan_cuenta = plan_cuentas.codigo
                                        WHERE plan_cuentas.id_empresa = '".$sesion_id_empresa."';";

                    $res_enlaces_compra=mysql_query($sql_enlaces_compra) or die(mysql_error());

                    while($enlace=mysql_fetch_array($res_enlaces_compra))    //permite ir de fila en fila de la tabla
                    {
                
                  $ins_enlace_compra = "insert into enlaces_compras (id,nombre,porcentaje,tipo,codigo,codigo_sri,formula, cuenta_contable,inventario_producto,iva,otros_gastos,iva_credito_tributario,ice,id_empresa,tipo_cpra)
                  values ('".$id_enlace_compra."','".$enlace["nombre"]."','".$enlace["porcentaje"]."','".$enlace["tipo"]."','".$enlace["codigo"]."','".$enlace["codigo_sri"]."','".$enlace["formula"]."','".$enlace["cuenta_contable"]."','".$enlace["inventario_producto"]."','".$enlace["iva"]."','".$enlace["otros_gastos"]."','".$enlace["iva_credito_tributario"]."','".$enlace["ice"]."','".$sesion_id_empresa."','".$enlace["tipo_cpra"]."')";
                      $res_ins_enlace=mysql_query($ins_enlace_compra) or die(mysql_error());
                      $id_enlace_compra++;
                echo "</br>"."enlace compras".  $ins_enlace_compra;   
                      
                    }
                    
                     echo "</br>"."select compras".  $sql_enlaces_compra;  
                }
            
                
                
                
                if ($_SESSION['sesion_tipo_empresa']==5 or $_SESSION['sesion_tipo_empresa']==6 ){
                    
                    $sqli="Select max(id_forma_pago) as MaxID from formas_pago";
                    $result=mysql_query($sqli);
                    $id_forma_de_pago=0;
                    while($row=mysql_fetch_array($result)){
                       $id_forma_de_pago=$row['MaxID'];
                    }
                    $id_forma_de_pago++;
                    $sql_nueva_forma_de_pago="SELECT 0 as id_forma_pago, formas_pago_niff.nombre,plan_cuentas.id_plan_cuenta,
                    '".$sesion_id_empresa."' as id_empresa,
                    formas_pago_niff.id_tipo_movimiento,formas_pago_niff.diario,formas_pago_niff.ingreso,formas_pago_niff.egreso,
                    formas_pago_niff.pagar
                                              FROM formas_pago_niff
                                              INNER JOIN plan_cuentas ON formas_pago_niff.codigo = plan_cuentas.codigo
                                              WHERE plan_cuentas.id_empresa = '".$sesion_id_empresa."'
                                              GROUP BY formas_pago_niff.codigo";
                    $res_nueva_forma_de_pago=mysql_query($sql_nueva_forma_de_pago) or die(mysql_error());
                    while($enlace=mysql_fetch_array($res_nueva_forma_de_pago)){    //permite ir de fila en fila de la tabla
                          $ins_nueva_forma_de_pago = "insert into 
                          formas_pago ( id_forma_pago,nombre,id_plan_cuenta,id_empresa,id_tipo_movimiento,diario,ingreso,egreso,pagar)
                             values ('".$id_forma_de_pago."','".$enlace["nombre"]."','".$enlace["id_plan_cuenta"]."','".$sesion_id_empresa."','".$enlace["id_tipo_movimiento"]."',
                             '".$enlace["diario"]."','".$enlace["ingreso"]."','".$enlace["egreso"]."','".$enlace["pagar"]."')";
                          $res_ins_nueva_forma_de_pago=mysql_query($ins_nueva_forma_de_pago) or die(mysql_error());
                          $id_forma_de_pago++;
                    }
                }

              

                echo "<div class='alert alert-success'><p>Terminó la Importaci&oacute;n</p></div>";
				
			}
        }      
    }
    
 //IMPORTAR PLAN DE CUENTAS BANCARIO
    if($accion == 27)
	{
	   // echo "opcion 12";
        // IMPORTAR PLAN DE CUENTAS MODELO DE LA BASE DE DATOS
        $consulta12="SELECT codigo, descripcion, signo, tipoCuenta FROM planCuentasBancos ;";
        $result12=mysql_query($consulta12) or die(mysql_error());
        $numero_filas12 = mysql_num_rows($result12); // obtenemos el número de filas
        if($numero_filas12 == 0)
		{
            echo "<div class='alert alert-danger'><p>No hay Plan de Cuentas para Importar.</p></div>";
        }
		else
		{
			$entro=0;
			while($row12=mysql_fetch_array($result12))//permite ir de fila en fila de la tabla
            {
				//Verifica si la cuenta ya existe en el plan de cuentas
			
				$lcCodigo=$row12["codigo"];
				//echo "codigo".$lcCodigo;
				$sql = "SELECT codigo, id_empresa from plan_cuentas where codigo='".$lcCodigo."' and id_empresa='".$sesion_id_empresa."'; ";
				//echo $sql;
				$resp = mysql_query($sql);
					$xcodigo="";
					$xempresa="";
				
				while($row=mysql_fetch_array($resp))//permite ir de fila en fila de la tabla
				{
					$xcodigo=$row["codigo"];
					$xempresa=$row["id_empresa"];
				}
				if(($xcodigo==$lcCodigo) and ($xempresa==$sesion_id_empresa))
				{
					$entro=1;
				}
			}	
			if ($entro==1)
			{
			   // alert("entro");
				?> <div class="alert alert-danger"><p>Plan de cuenta ya esta registrado 
					<?php echo "".$ex ?></p></div> 
				<?php 
			}	
			else
			{
		$consulta12="SELECT codigo, descripcion, tipoCuenta, signo FROM planCuentasBancos ;";
        $result12=mysql_query($consulta12) or die(mysql_error());
        
				while($row12=mysql_fetch_array($result12))    //permite ir de fila en fila de la tabla
				{
			
                 $borrar=1;
                 if ($_SESSION['sesion_tipo_empresa']==6){
                    $borrar=0;
                 }
				  $sql121 = "insert into plan_cuentas (codigo, nombre,  tipo, nivel,id_empresa,borrar)
				   values ('".$row12["codigo"]."','".$row12["descripcion"]."','".$row12["tipoCuenta"]."','".$row12["signo"]."','".$sesion_id_empresa."','".$borrar."')";
				  
				  $result121=mysql_query($sql121) or die(mysql_error());
				}
                

                /* Crear Impuesto al Iva en Ventas*/
                $sql_impuesto="INSERT INTO impuestos (id_iva,iva,estado,id_empresa,id_plan_cuenta) 
                            SELECT max(id_iva) + 1 AS id_iva,12 AS iva,'Activo' AS estado,'".$sesion_id_empresa."' AS id_empresa, (
                                   SELECT plan_cuentas.id_plan_cuenta  FROM plan_cuentas
                                   WHERE plan_cuentas.codigo = '21005001' and plan_cuentas.id_empresa='".$sesion_id_empresa."') as id_plan_cuenta 
                                   FROM impuestos";
                $rs_impuesto=mysql_query($sql_impuesto) or die(mysql_error());

                /* Crear Vendedor*/
                $sql_vendedor="INSERT INTO vendedores (id_vendedor,codigo,nombre,apellidos,direccion,telefono,comision,id_empresa) 
                                             select max(id_vendedor)+1 as id_vendedor,'001' as codigo,'VENDEDOR' as nombre,'VENDEDOR' as apellidos,
                                             '' as  direccion, '' as telefono, 0 as comision,'".$sesion_id_empresa."'  as id_empresa from vendedores ";
                $rs_vendedor=mysql_query($sql_vendedor) or die(mysql_error());

                /* Crear Categoria/Grupo */
                $sql_categoria="INSERT INTO categorias (id_categoria,categoria,id_empresa) select max(id_categoria)+1 as id_categoria,'PRODUCTOS' as categoria,'".$sesion_id_empresa."'  as id_empresa from categorias ";
                $rs_categoria=mysql_query($sql_categoria) or die(mysql_error());

                $sql_categoria="INSERT INTO categorias (id_categoria,categoria,id_empresa) select max(id_categoria)+1 as id_categoria,'SERVICIOS' as categoria,'".$sesion_id_empresa."'  as id_empresa from categorias ";
                $rs_categoria=mysql_query($sql_categoria) or die(mysql_error());


                /* Crear Unidad de Medida*/
                $sql_unidad="INSERT INTO unidades (id_unidad,nombre,id_empresa) select max(id_unidad)+1 as id_unidad,'UNIDAD' as nombre,'".$sesion_id_empresa."' as id_empresa from unidades";
                $rs_unidad=mysql_query($sql_unidad) or die(mysql_error());

                /* Crear tipo de Servicio*/
                $sql_tipo_servicio="INSERT INTO tipo_servicios (id_tipo_servicio,nombre,id_empresa) select max(id_tipo_servicio)+1 AS id_tipo_servicio ,'TIPO DE SERVICIO' as nombre,'".$sesion_id_empresa."' as id_empresa from tipo_servicios";
                $rs_tipo_servicio=mysql_query($sql_tipo_servicio) or die(mysql_error());

                /* Crear los Servicio*/
                $sqli="Select max(id_servicio) as MaxID from SERVICIOS";
                $result=mysql_query($sqli);
                $id_servicio=0;
                while($row=mysql_fetch_array($result)){
                     $id_servicio=$row['MaxID'];
                }
                $id_servicio++;
                $sql_nuevo_servicio="SELECT 0 as id_servicio,sermae.codigo,sermae.nombre,categorias.id_categoria,
                               unidades.id_unidad,tipo.id_tipo_servicio,format((0 + ( RAND() * (50))),4) AS precio_venta1,
                               'Si' AS iva,plan.id_empresa,plan.id_plan_cuenta
                    FROM       servicios_mae AS sermae
                    INNER JOIN plan_cuentas AS plan ON sermae.codigo_cuenta_contable = plan.codigo
                    INNER JOIN categorias ON sermae.nombre = categorias.categoria AND plan.id_empresa = categorias.id_empresa
                    INNER JOIN unidades ON sermae.unidad = unidades.nombre AND plan.id_empresa = unidades.id_empresa
                    INNER JOIN tipo_servicios AS tipo ON sermae.tipo = tipo.nombre AND plan.id_empresa = tipo.id_empresa
                    WHERE      plan.id_empresa = '".$sesion_id_empresa."'  GROUP BY   sermae.id_servicio;";
                $res_nuevo_servicio=mysql_query($sql_nuevo_servicio) or die(mysql_error());
                while($enlace=mysql_fetch_array($res_nuevo_servicio)){    //permite ir de fila en fila de la tabla
                        $ins_nuevo_servicio = "insert into 
                        servicios ( codigo,nombre,id_categoria,id_unidad,id_tipo_servicio,precio_venta1,iva,id_empresa,id_plan_cuenta)
                           values ('".$enlace["codigo"]."','".$enlace["nombre"]."','".$enlace["id_categoria"]."',
                           '".$enlace["id_unidad"]."','".$enlace["id_tipo_servicio"]."','".$enlace["precio_venta1"]."',
                           '".$enlace["iva"]."', '".$sesion_id_empresa."','".$enlace["id_plan_cuenta"]."')";
                      $res_ins_nuevo_servicio=mysql_query($ins_nuevo_servicio) or die(mysql_error());
                      $id_servicio++;
                }
        
                echo "<div class='alert alert-success'><p>Terminó la Importaci&oacute;n</p></div>";
				
			}
        }      
    }
     //IMPORTAR PLAN DE CUENTAS GUBERNAMENTAL
    if($accion == 28)
	{
	   // echo "opcion 12";
        // IMPORTAR PLAN DE CUENTAS MODELO DE LA BASE DE DATOS
        $consulta12="SELECT codigo, descripcion, signo, tipoCuenta FROM planCuentasGob ;";
        $result12=mysql_query($consulta12) or die(mysql_error());
        $numero_filas12 = mysql_num_rows($result12); // obtenemos el número de filas
        if($numero_filas12 == 0)
		{
            echo "<div class='alert alert-danger'><p>No hay Plan de Cuentas para Importar.</p></div>";
        }
		else
		{
			$entro=0;
			while($row12=mysql_fetch_array($result12))//permite ir de fila en fila de la tabla
            {
				//Verifica si la cuenta ya existe en el plan de cuentas
			
				$lcCodigo=$row12["codigo"];
				//echo "codigo".$lcCodigo;
				$sql = "SELECT codigo, id_empresa from plan_cuentas where codigo='".$lcCodigo."' and id_empresa='".$sesion_id_empresa."'; ";
				//echo $sql;
				$resp = mysql_query($sql);
					$xcodigo="";
					$xempresa="";
				
				while($row=mysql_fetch_array($resp))//permite ir de fila en fila de la tabla
				{
					$xcodigo=$row["codigo"];
					$xempresa=$row["id_empresa"];
				}
				if(($xcodigo==$lcCodigo) and ($xempresa==$sesion_id_empresa))
				{
					$entro=1;
				}
			}	
			if ($entro==1)
			{
			   // alert("entro");
				?> <div class="alert alert-danger"><p>Plan de cuenta ya esta registrado 
					<?php echo "".$ex ?></p></div> 
				<?php 
			}	
			else
			{
		$consulta12="SELECT codigo, descripcion, tipoCuenta, signo FROM planCuentasGob ;";
        $result12=mysql_query($consulta12) or die(mysql_error());
        
				while($row12=mysql_fetch_array($result12))    //permite ir de fila en fila de la tabla
				{
			
                 $borrar=1;
                 if ($_SESSION['sesion_tipo_empresa']==6){
                    $borrar=0;
                 }
				  $sql121 = "insert into plan_cuentas (codigo, nombre,  tipo, nivel,id_empresa,borrar)
				   values ('".$row12["codigo"]."','".$row12["descripcion"]."','".$row12["tipoCuenta"]."','".$row12["signo"]."','".$sesion_id_empresa."','".$borrar."')";
				  
				  $result121=mysql_query($sql121) or die(mysql_error());
				}
                

                /* Crear Impuesto al Iva en Ventas*/
                $sql_impuesto="INSERT INTO impuestos (id_iva,iva,estado,id_empresa,id_plan_cuenta) 
                            SELECT max(id_iva) + 1 AS id_iva,12 AS iva,'Activo' AS estado,'".$sesion_id_empresa."' AS id_empresa, (
                                   SELECT plan_cuentas.id_plan_cuenta  FROM plan_cuentas
                                   WHERE plan_cuentas.codigo = '21005001' and plan_cuentas.id_empresa='".$sesion_id_empresa."') as id_plan_cuenta 
                                   FROM impuestos";
                $rs_impuesto=mysql_query($sql_impuesto) or die(mysql_error());

                /* Crear Vendedor*/
                $sql_vendedor="INSERT INTO vendedores (id_vendedor,codigo,nombre,apellidos,direccion,telefono,comision,id_empresa) 
                                             select max(id_vendedor)+1 as id_vendedor,'001' as codigo,'VENDEDOR' as nombre,'VENDEDOR' as apellidos,
                                             '' as  direccion, '' as telefono, 0 as comision,'".$sesion_id_empresa."'  as id_empresa from vendedores ";
                $rs_vendedor=mysql_query($sql_vendedor) or die(mysql_error());

                /* Crear Categoria/Grupo */
                $sql_categoria="INSERT INTO categorias (id_categoria,categoria,id_empresa) select max(id_categoria)+1 as id_categoria,'PRODUCTOS' as categoria,'".$sesion_id_empresa."'  as id_empresa from categorias ";
                $rs_categoria=mysql_query($sql_categoria) or die(mysql_error());

                $sql_categoria="INSERT INTO categorias (id_categoria,categoria,id_empresa) select max(id_categoria)+1 as id_categoria,'SERVICIOS' as categoria,'".$sesion_id_empresa."'  as id_empresa from categorias ";
                $rs_categoria=mysql_query($sql_categoria) or die(mysql_error());


                /* Crear Unidad de Medida*/
                $sql_unidad="INSERT INTO unidades (id_unidad,nombre,id_empresa) select max(id_unidad)+1 as id_unidad,'UNIDAD' as nombre,'".$sesion_id_empresa."' as id_empresa from unidades";
                $rs_unidad=mysql_query($sql_unidad) or die(mysql_error());

                /* Crear tipo de Servicio*/
                $sql_tipo_servicio="INSERT INTO tipo_servicios (id_tipo_servicio,nombre,id_empresa) select max(id_tipo_servicio)+1 AS id_tipo_servicio ,'TIPO DE SERVICIO' as nombre,'".$sesion_id_empresa."' as id_empresa from tipo_servicios";
                $rs_tipo_servicio=mysql_query($sql_tipo_servicio) or die(mysql_error());

                /* Crear los Servicio*/
                $sqli="Select max(id_servicio) as MaxID from SERVICIOS";
                $result=mysql_query($sqli);
                $id_servicio=0;
                while($row=mysql_fetch_array($result)){
                     $id_servicio=$row['MaxID'];
                }
                $id_servicio++;
                $sql_nuevo_servicio="SELECT 0 as id_servicio,sermae.codigo,sermae.nombre,categorias.id_categoria,
                               unidades.id_unidad,tipo.id_tipo_servicio,format((0 + ( RAND() * (50))),4) AS precio_venta1,
                               'Si' AS iva,plan.id_empresa,plan.id_plan_cuenta
                    FROM       servicios_mae AS sermae
                    INNER JOIN plan_cuentas AS plan ON sermae.codigo_cuenta_contable = plan.codigo
                    INNER JOIN categorias ON sermae.nombre = categorias.categoria AND plan.id_empresa = categorias.id_empresa
                    INNER JOIN unidades ON sermae.unidad = unidades.nombre AND plan.id_empresa = unidades.id_empresa
                    INNER JOIN tipo_servicios AS tipo ON sermae.tipo = tipo.nombre AND plan.id_empresa = tipo.id_empresa
                    WHERE      plan.id_empresa = '".$sesion_id_empresa."'  GROUP BY   sermae.id_servicio;";
                $res_nuevo_servicio=mysql_query($sql_nuevo_servicio) or die(mysql_error());
                while($enlace=mysql_fetch_array($res_nuevo_servicio)){    //permite ir de fila en fila de la tabla
                        $ins_nuevo_servicio = "insert into 
                        servicios ( codigo,nombre,id_categoria,id_unidad,id_tipo_servicio,precio_venta1,iva,id_empresa,id_plan_cuenta)
                           values ('".$enlace["codigo"]."','".$enlace["nombre"]."','".$enlace["id_categoria"]."',
                           '".$enlace["id_unidad"]."','".$enlace["id_tipo_servicio"]."','".$enlace["precio_venta1"]."',
                           '".$enlace["iva"]."', '".$sesion_id_empresa."','".$enlace["id_plan_cuenta"]."')";
                      $res_ins_nuevo_servicio=mysql_query($ins_nuevo_servicio) or die(mysql_error());
                      $id_servicio++;
                }
        
                echo "<div class='alert alert-success'><p>Terminó la Importaci&oacute;n</p></div>";
				
			}
        }      
    }
    
      if($accion == 29)
	{
	    
                /* Crear Vendedor*/
                $sql_vendedor="INSERT INTO vendedores (id_vendedor,codigo,nombre,apellidos,direccion,telefono,comision,id_empresa) 
                                             select max(id_vendedor)+1 as id_vendedor,'001' as codigo,'VENDEDOR' as nombre,'VENDEDOR' as apellidos,
                                             '' as  direccion, '' as telefono, 0 as comision,'".$sesion_id_empresa."'  as id_empresa from vendedores ";
                $rs_vendedor=mysql_query($sql_vendedor) or die(mysql_error());

                /* Crear Categoria/Grupo */
                $sql_categoria="INSERT INTO categorias (id_categoria,categoria,id_empresa) select max(id_categoria)+1 as id_categoria,'PRODUCTOS' as categoria,'".$sesion_id_empresa."'  as id_empresa from categorias ";
                $rs_categoria=mysql_query($sql_categoria) or die(mysql_error());

                $sql_categoria="INSERT INTO categorias (id_categoria,categoria,id_empresa) select max(id_categoria)+1 as id_categoria,'SERVICIOS' as categoria,'".$sesion_id_empresa."'  as id_empresa from categorias ";
                $rs_categoria=mysql_query($sql_categoria) or die(mysql_error());


                /* Crear Unidad de Medida*/
                $sql_unidad="INSERT INTO unidades (id_unidad,nombre,id_empresa) select max(id_unidad)+1 as id_unidad,'UNIDAD' as nombre,'".$sesion_id_empresa."' as id_empresa from unidades";
                $rs_unidad=mysql_query($sql_unidad) or die(mysql_error());

                /* Crear tipo de Servicio*/
                $sql_tipo_servicio="INSERT INTO tipo_servicios (id_tipo_servicio,nombre,id_empresa) select max(id_tipo_servicio)+1 AS id_tipo_servicio ,'TIPO DE SERVICIO' as nombre,'".$sesion_id_empresa."' as id_empresa from tipo_servicios";
                $rs_tipo_servicio=mysql_query($sql_tipo_servicio) or die(mysql_error());

                /* Crear los Servicio*/
                $sqli="Select max(id_servicio) as MaxID from SERVICIOS";
                $result=mysql_query($sqli);
                $id_servicio=0;
                while($row=mysql_fetch_array($result)){
                     $id_servicio=$row['MaxID'];
                }
                $id_servicio++;
                $sql_nuevo_servicio="SELECT 0 as id_servicio,sermae.codigo,sermae.nombre,categorias.id_categoria,
                               unidades.id_unidad,tipo.id_tipo_servicio,format((0 + ( RAND() * (50))),4) AS precio_venta1,
                               'Si' AS iva,plan.id_empresa,plan.id_plan_cuenta
                    FROM       servicios_mae AS sermae
                    INNER JOIN plan_cuentas AS plan ON sermae.codigo_cuenta_contable = plan.codigo
                    INNER JOIN categorias ON sermae.nombre = categorias.categoria AND plan.id_empresa = categorias.id_empresa
                    INNER JOIN unidades ON sermae.unidad = unidades.nombre AND plan.id_empresa = unidades.id_empresa
                    INNER JOIN tipo_servicios AS tipo ON sermae.tipo = tipo.nombre AND plan.id_empresa = tipo.id_empresa
                    WHERE      plan.id_empresa = '".$sesion_id_empresa."'  GROUP BY   sermae.id_servicio;";
                $res_nuevo_servicio=mysql_query($sql_nuevo_servicio) or die(mysql_error());
                while($enlace=mysql_fetch_array($res_nuevo_servicio)){    //permite ir de fila en fila de la tabla
                        $ins_nuevo_servicio = "insert into 
                        servicios ( codigo,nombre,id_categoria,id_unidad,id_tipo_servicio,precio_venta1,iva,id_empresa,id_plan_cuenta)
                           values ('".$enlace["codigo"]."','".$enlace["nombre"]."','".$enlace["id_categoria"]."',
                           '".$enlace["id_unidad"]."','".$enlace["id_tipo_servicio"]."','".$enlace["precio_venta1"]."',
                           '".$enlace["iva"]."', '".$sesion_id_empresa."','".$enlace["id_plan_cuenta"]."')";
                      $res_ins_nuevo_servicio=mysql_query($ins_nuevo_servicio) or die(mysql_error());
                      $id_servicio++;
                }

                echo "<div class='alert alert-success'><p>Terminó la Importaci&oacute;n </p></div>";
				
			}
          
    if($accion == 30){
        
        
	   // echo "opcion 12";
        // IMPORTAR PLAN DE CUENTAS MODELO DE LA BASE DE DATOS
        $consulta12="SELECT codigo, descripcion, signo, tipoCuenta FROM planCuentasDillon ;";
        $result12=mysql_query($consulta12) or die(mysql_error());
        $numero_filas12 = mysql_num_rows($result12); // obtenemos el número de filas
        if($numero_filas12 == 0)
		{
            echo "<div class='alert alert-danger'><p>No hay Plan de Cuentas para Importar.</p></div>";
        }
		else
		{
			$entro=0;
			while($row12=mysql_fetch_array($result12))//permite ir de fila en fila de la tabla
            {
				//Verifica si la cuenta ya existe en el plan de cuentas
			
				$lcCodigo=$row12["codigo"];
				//echo "codigo".$lcCodigo;
				$sql = "SELECT codigo, id_empresa from plan_cuentas where codigo='".$lcCodigo."' and id_empresa='".$sesion_id_empresa."'; ";
				//echo $sql;
				$resp = mysql_query($sql);
					$xcodigo="";
					$xempresa="";
				
				while($row=mysql_fetch_array($resp))//permite ir de fila en fila de la tabla
				{
					$xcodigo=$row["codigo"];
					$xempresa=$row["id_empresa"];
				}
				if(($xcodigo==$lcCodigo) and ($xempresa==$sesion_id_empresa))
				{
					$entro=1;
				}
			}	
			if ($entro==1)
			{
			   // alert("entro");
				?> <div class="alert alert-danger"><p>Plan de cuenta ya esta registrado 
					<?php echo "".$ex ?></p></div> 
				<?php 
			}	
			else
			{
		$consulta12="SELECT codigo, descripcion, tipoCuenta, signo FROM planCuentasDillon ;";
        $result12=mysql_query($consulta12) or die(mysql_error());
        
				while($row12=mysql_fetch_array($result12))    //permite ir de fila en fila de la tabla
				{
			
                 $borrar=1;
                 if ($_SESSION['sesion_tipo_empresa']==6){
                    $borrar=0;
                 }
				  $sql121 = "insert into plan_cuentas (codigo, nombre,  tipo, nivel,id_empresa,borrar)
				   values ('".$row12["codigo"]."','".$row12["descripcion"]."','".$row12["tipoCuenta"]."','".$row12["signo"]."','".$sesion_id_empresa."','".$borrar."')";
				  
				  $result121=mysql_query($sql121) or die(mysql_error());
				}
                

                /* Crear Impuesto al Iva en Ventas*/
                $sql_impuesto="INSERT INTO impuestos (id_iva,iva,estado,id_empresa,id_plan_cuenta) 
                            SELECT max(id_iva) + 1 AS id_iva,12 AS iva,'Activo' AS estado,'".$sesion_id_empresa."' AS id_empresa, (
                                   SELECT plan_cuentas.id_plan_cuenta  FROM plan_cuentas
                                   WHERE plan_cuentas.codigo = '211020351' and plan_cuentas.id_empresa='".$sesion_id_empresa."') as id_plan_cuenta 
                                   FROM impuestos";
                $rs_impuesto=mysql_query($sql_impuesto) or die(mysql_error());

                /* Crear Vendedor*/
                $sql_vendedor="INSERT INTO vendedores (id_vendedor,codigo,nombre,apellidos,direccion,telefono,comision,id_empresa) 
                                             select max(id_vendedor)+1 as id_vendedor,'001' as codigo,'VENDEDOR' as nombre,'VENDEDOR' as apellidos,
                                             '' as  direccion, '' as telefono, 0 as comision,'".$sesion_id_empresa."'  as id_empresa from vendedores ";
                $rs_vendedor=mysql_query($sql_vendedor) or die(mysql_error());

                /* Crear Categoria/Grupo */
                $sql_categoria="INSERT INTO categorias (id_categoria,categoria,id_empresa) select max(id_categoria)+1 as id_categoria,'PRODUCTOS' as categoria,'".$sesion_id_empresa."'  as id_empresa from categorias ";
                $rs_categoria=mysql_query($sql_categoria) or die(mysql_error());

                $sql_categoria="INSERT INTO categorias (id_categoria,categoria,id_empresa) select max(id_categoria)+1 as id_categoria,'SERVICIOS' as categoria,'".$sesion_id_empresa."'  as id_empresa from categorias ";
                $rs_categoria=mysql_query($sql_categoria) or die(mysql_error());


                /* Crear Unidad de Medida*/
                $sql_unidad="INSERT INTO unidades (id_unidad,nombre,id_empresa) select max(id_unidad)+1 as id_unidad,'UNIDAD' as nombre,'".$sesion_id_empresa."' as id_empresa from unidades";
                $rs_unidad=mysql_query($sql_unidad) or die(mysql_error());

                /* Crear tipo de Servicio*/
                $sql_tipo_servicio="INSERT INTO tipo_servicios (id_tipo_servicio,nombre,id_empresa) select max(id_tipo_servicio)+1 AS id_tipo_servicio ,'TIPO DE SERVICIO' as nombre,'".$sesion_id_empresa."' as id_empresa from tipo_servicios";
                $rs_tipo_servicio=mysql_query($sql_tipo_servicio) or die(mysql_error());

                /* Crear los Servicio*/
                $sqli="Select max(id_servicio) as MaxID from SERVICIOS";
                $result=mysql_query($sqli);
                $id_servicio=0;
                while($row=mysql_fetch_array($result)){
                     $id_servicio=$row['MaxID'];
                }
                $id_servicio++;
                $sql_nuevo_servicio="SELECT 0 as id_servicio,sermae.codigo,sermae.nombre,categorias.id_categoria,
                               unidades.id_unidad,tipo.id_tipo_servicio,format((0 + ( RAND() * (50))),4) AS precio_venta1,
                               'Si' AS iva,plan.id_empresa,plan.id_plan_cuenta
                    FROM       servicios_mae AS sermae
                    INNER JOIN plan_cuentas AS plan ON sermae.codigo_cuenta_contable = plan.codigo
                    INNER JOIN categorias ON sermae.nombre = categorias.categoria AND plan.id_empresa = categorias.id_empresa
                    INNER JOIN unidades ON sermae.unidad = unidades.nombre AND plan.id_empresa = unidades.id_empresa
                    INNER JOIN tipo_servicios AS tipo ON sermae.tipo = tipo.nombre AND plan.id_empresa = tipo.id_empresa
                    WHERE      plan.id_empresa = '".$sesion_id_empresa."'  GROUP BY   sermae.id_servicio;";
                $res_nuevo_servicio=mysql_query($sql_nuevo_servicio) or die(mysql_error());
                while($enlace=mysql_fetch_array($res_nuevo_servicio)){    //permite ir de fila en fila de la tabla
                        $ins_nuevo_servicio = "insert into 
                        servicios ( codigo,nombre,id_categoria,id_unidad,id_tipo_servicio,precio_venta1,iva,id_empresa,id_plan_cuenta)
                           values ('".$enlace["codigo"]."','".$enlace["nombre"]."','".$enlace["id_categoria"]."',
                           '".$enlace["id_unidad"]."','".$enlace["id_tipo_servicio"]."','".$enlace["precio_venta1"]."',
                           '".$enlace["iva"]."', '".$sesion_id_empresa."','".$enlace["id_plan_cuenta"]."')";
                      $res_ins_nuevo_servicio=mysql_query($ins_nuevo_servicio) or die(mysql_error());
                      $id_servicio++;
                }
                	
                 if ($_SESSION['sesion_tipo_empresa']==5 or $_SESSION['sesion_tipo_empresa']==6 or $_SESSION['sesion_tipo_empresa']==7 ){
                    $sqli="Select max(id) as MaxID from enlaces_compras";
                    $result=mysql_query($sqli);
                    $id_enlace_compra=0;
                    while($row=mysql_fetch_array($result)){
                         $id_enlace_compra=$row['MaxID'];
                    }
               // echo $id_enlace_compra;
                    $id_enlace_compra++;
            
            $sql_enlaces_compra="SELECT          enlaces_compras_dillon.codigo_plan_cuenta,
                                                 enlaces_compras_dillon.nombre,
                                                 enlaces_compras_dillon.porcentaje,
                                                 enlaces_compras_dillon.tipo,
                                                 enlaces_compras_dillon.codigo,
                                                 enlaces_compras_dillon.codigo_sri,
                                                 enlaces_compras_dillon.formula,
                                                 plan_cuentas.id_plan_cuenta as cuenta_contable,
                                                 enlaces_compras_dillon.inventario_producto,
                                                 enlaces_compras_dillon.iva,
                                                 enlaces_compras_dillon.otros_gastos,
                                                 enlaces_compras_dillon.iva_credito_tributario,
                                                 enlaces_compras_dillon.ice,plan_cuentas.id_empresa,
                                                 enlaces_compras_dillon.ice,
                                                 plan_cuentas.id_empresa,
                                                 enlaces_compras_dillon.tipo_cpra
                                                 	
                                        FROM enlaces_compras_dillon 
                                        INNER JOIN plan_cuentas ON enlaces_compras_dillon.codigo_plan_cuenta = plan_cuentas.codigo
                                        WHERE plan_cuentas.id_empresa = '".$sesion_id_empresa."';";

                    $res_enlaces_compra=mysql_query($sql_enlaces_compra) or die(mysql_error());

                    while($enlace=mysql_fetch_array($res_enlaces_compra))    //permite ir de fila en fila de la tabla
                    {
                
                  $ins_enlace_compra = "insert into enlaces_compras (id,nombre,porcentaje,tipo,codigo,codigo_sri,formula, cuenta_contable,inventario_producto,iva,otros_gastos,iva_credito_tributario,ice,id_empresa,tipo_cpra)
                  values ('".$id_enlace_compra."','".$enlace["nombre"]."','".$enlace["porcentaje"]."','".$enlace["tipo"]."','".$enlace["codigo"]."','".$enlace["codigo_sri"]."','".$enlace["formula"]."','".$enlace["cuenta_contable"]."','".$enlace["inventario_producto"]."','".$enlace["iva"]."','".$enlace["otros_gastos"]."','".$enlace["iva_credito_tributario"]."','".$enlace["ice"]."','".$sesion_id_empresa."','".$enlace["tipo_cpra"]."')";
                      $res_ins_enlace=mysql_query($ins_enlace_compra) or die(mysql_error());
                      $id_enlace_compra++;
             //    echo "</br>"."enlace compras".  $ins_enlace_compra;   
                      
                    }
                }
            
                
                
                
                if ($_SESSION['sesion_tipo_empresa']==5 or $_SESSION['sesion_tipo_empresa']==6 ){
                    
                    $sqli="Select max(id_forma_pago) as MaxID from formas_pago";
                    $result=mysql_query($sqli);
                    $id_forma_de_pago=0;
                    while($row=mysql_fetch_array($result)){
                       $id_forma_de_pago=$row['MaxID'];
                    }
                    $id_forma_de_pago++;
                    $sql_nueva_forma_de_pago="SELECT 0 as id_forma_pago, formas_pago_dillon.nombre,plan_cuentas.id_plan_cuenta, '".$sesion_id_empresa."' as id_empresa,
                    formas_pago_dillon.id_tipo_movimiento,formas_pago_dillon.diario,formas_pago_dillon.ingreso,formas_pago_dillon.egreso,formas_pago_dillon.pagar
                                              FROM formas_pago_dillon
                                              INNER JOIN plan_cuentas ON formas_pago_dillon.codigo = plan_cuentas.codigo
                                              WHERE plan_cuentas.id_empresa = '".$sesion_id_empresa."'
                                              GROUP BY formas_pago_dillon.codigo";
                    $res_nueva_forma_de_pago=mysql_query($sql_nueva_forma_de_pago) or die(mysql_error());
                    while($enlace=mysql_fetch_array($res_nueva_forma_de_pago)){    //permite ir de fila en fila de la tabla
                          $ins_nueva_forma_de_pago = "insert into 
                          formas_pago ( id_forma_pago,nombre,id_plan_cuenta,id_empresa,id_tipo_movimiento,diario,ingreso,egreso,pagar)
                             values ('".$id_forma_de_pago."','".$enlace["nombre"]."','".$enlace["id_plan_cuenta"]."','".$sesion_id_empresa."','".$enlace["id_tipo_movimiento"]."',
                             '".$enlace["diario"]."','".$enlace["ingreso"]."','".$enlace["egreso"]."','".$enlace["pagar"]."')";
                          $res_ins_nueva_forma_de_pago=mysql_query($ins_nueva_forma_de_pago) or die(mysql_error());
                          $id_forma_de_pago++;
                    }
                }

              

                echo "<div class='alert alert-success'><p>Terminó la Importaci&oacute;n</p></div>";
				
			}
        }      
    }
    
     if($accion == 31){
        
        
	   // echo "opcion 12";
        // IMPORTAR PLAN DE CUENTAS MODELO DE LA BASE DE DATOS
        $consulta12="SELECT codigo, descripcion, signo, tipoCuenta FROM plancuentasContribec ;";
        $result12=mysql_query($consulta12) or die(mysql_error());
        $numero_filas12 = mysql_num_rows($result12); // obtenemos el número de filas
        if($numero_filas12 == 0)
		{
            echo "<div class='alert alert-danger'><p>No hay Plan de Cuentas para Importar.</p></div>";
        }
		else
		{
			$entro=0;
			while($row12=mysql_fetch_array($result12))//permite ir de fila en fila de la tabla
            {
				//Verifica si la cuenta ya existe en el plan de cuentas
			
				$lcCodigo=$row12["codigo"];
				//echo "codigo".$lcCodigo;
				$sql = "SELECT codigo, id_empresa from plan_cuentas where codigo='".$lcCodigo."' and id_empresa='".$sesion_id_empresa."'; ";
				//echo $sql;
				$resp = mysql_query($sql);
					$xcodigo="";
					$xempresa="";
				
				while($row=mysql_fetch_array($resp))//permite ir de fila en fila de la tabla
				{
					$xcodigo=$row["codigo"];
					$xempresa=$row["id_empresa"];
				}
				if(($xcodigo==$lcCodigo) and ($xempresa==$sesion_id_empresa))
				{
					$entro=1;
				}
			}	
			if ($entro==1)
			{
			   // alert("entro");
				?> <div class="alert alert-danger"><p>Plan de cuenta ya esta registrado 
					<?php echo "".$ex ?></p></div> 
				<?php 
			}	
			else
			{
		$consulta12="SELECT codigo, descripcion, tipoCuenta, signo FROM plancuentasContribec ;";
        $result12=mysql_query($consulta12) or die(mysql_error());
        
				while($row12=mysql_fetch_array($result12))    //permite ir de fila en fila de la tabla
				{
			
                 $borrar=1;
                 if ($_SESSION['sesion_tipo_empresa']==6){
                    $borrar=0;
                 }
				  $sql121 = "insert into plan_cuentas (codigo, nombre,  tipo, nivel,id_empresa,borrar)
				   values ('".$row12["codigo"]."','".$row12["descripcion"]."','".$row12["tipoCuenta"]."','".$row12["signo"]."','".$sesion_id_empresa."','".$borrar."')";
				  
				  $result121=mysql_query($sql121) or die(mysql_error());
				}
                

                /* Crear Impuesto al Iva en Ventas*/
                $sql_impuesto="INSERT INTO impuestos (id_iva,iva,estado,id_empresa,id_plan_cuenta) 
                            SELECT max(id_iva) + 1 AS id_iva,12 AS iva,'Activo' AS estado,'".$sesion_id_empresa."' AS id_empresa, (
                                   SELECT plan_cuentas.id_plan_cuenta  FROM plan_cuentas
                                   WHERE plan_cuentas.codigo = '211020351' and plan_cuentas.id_empresa='".$sesion_id_empresa."') as id_plan_cuenta 
                                   FROM impuestos";
                $rs_impuesto=mysql_query($sql_impuesto) or die(mysql_error());

                /* Crear Vendedor*/
                $sql_vendedor="INSERT INTO vendedores (id_vendedor,codigo,nombre,apellidos,direccion,telefono,comision,id_empresa) 
                                             select max(id_vendedor)+1 as id_vendedor,'001' as codigo,'VENDEDOR' as nombre,'VENDEDOR' as apellidos,
                                             '' as  direccion, '' as telefono, 0 as comision,'".$sesion_id_empresa."'  as id_empresa from vendedores ";
                $rs_vendedor=mysql_query($sql_vendedor) or die(mysql_error());

                /* Crear Categoria/Grupo */
                $sql_categoria="INSERT INTO categorias (id_categoria,categoria,id_empresa) select max(id_categoria)+1 as id_categoria,'PRODUCTOS' as categoria,'".$sesion_id_empresa."'  as id_empresa from categorias ";
                $rs_categoria=mysql_query($sql_categoria) or die(mysql_error());

                $sql_categoria="INSERT INTO categorias (id_categoria,categoria,id_empresa) select max(id_categoria)+1 as id_categoria,'SERVICIOS' as categoria,'".$sesion_id_empresa."'  as id_empresa from categorias ";
                $rs_categoria=mysql_query($sql_categoria) or die(mysql_error());


                /* Crear Unidad de Medida*/
                $sql_unidad="INSERT INTO unidades (id_unidad,nombre,id_empresa) select max(id_unidad)+1 as id_unidad,'UNIDAD' as nombre,'".$sesion_id_empresa."' as id_empresa from unidades";
                $rs_unidad=mysql_query($sql_unidad) or die(mysql_error());

                /* Crear tipo de Servicio*/
                $sql_tipo_servicio="INSERT INTO tipo_servicios (id_tipo_servicio,nombre,id_empresa) select max(id_tipo_servicio)+1 AS id_tipo_servicio ,'TIPO DE SERVICIO' as nombre,'".$sesion_id_empresa."' as id_empresa from tipo_servicios";
                $rs_tipo_servicio=mysql_query($sql_tipo_servicio) or die(mysql_error());

                /* Crear los Servicio*/
                $sqli="Select max(id_servicio) as MaxID from SERVICIOS";
                $result=mysql_query($sqli);
                $id_servicio=0;
                while($row=mysql_fetch_array($result)){
                     $id_servicio=$row['MaxID'];
                }
                $id_servicio++;
                
                $sql_nuevo_servicio="SELECT 0 as id_servicio,sermae.codigo,sermae.nombre,categorias.id_categoria,
                               unidades.id_unidad,tipo.id_tipo_servicio,format((0 + ( RAND() * (50))),4) AS precio_venta1,
                               'Si' AS iva,plan.id_empresa,plan.id_plan_cuenta
                    FROM       servicios_mae AS sermae
                    INNER JOIN plan_cuentas AS plan ON sermae.codigo_cuenta_contable = plan.codigo
                    INNER JOIN categorias ON sermae.nombre = categorias.categoria AND plan.id_empresa = categorias.id_empresa
                    INNER JOIN unidades ON sermae.unidad = unidades.nombre AND plan.id_empresa = unidades.id_empresa
                    INNER JOIN tipo_servicios AS tipo ON sermae.tipo = tipo.nombre AND plan.id_empresa = tipo.id_empresa
                    WHERE      plan.id_empresa = '".$sesion_id_empresa."'  GROUP BY   sermae.id_servicio;";
                $res_nuevo_servicio=mysql_query($sql_nuevo_servicio) or die(mysql_error());
                while($enlace=mysql_fetch_array($res_nuevo_servicio)){    //permite ir de fila en fila de la tabla
                        $ins_nuevo_servicio = "insert into 
                        servicios ( codigo,nombre,id_categoria,id_unidad,id_tipo_servicio,precio_venta1,iva,id_empresa,id_plan_cuenta)
                           values ('".$enlace["codigo"]."','".$enlace["nombre"]."','".$enlace["id_categoria"]."',
                           '".$enlace["id_unidad"]."','".$enlace["id_tipo_servicio"]."','".$enlace["precio_venta1"]."',
                           '".$enlace["iva"]."', '".$sesion_id_empresa."','".$enlace["id_plan_cuenta"]."')";
                      $res_ins_nuevo_servicio=mysql_query($ins_nuevo_servicio) or die(mysql_error());
                      $id_servicio++;
                }
                	
            /*     if ($_SESSION['sesion_tipo_empresa']==5 or $_SESSION['sesion_tipo_empresa']==6 or $_SESSION['sesion_tipo_empresa']==7 ){
                    $sqli="Select max(id) as MaxID from enlaces_compras";
                    $result=mysql_query($sqli);
                    $id_enlace_compra=0;
                    while($row=mysql_fetch_array($result)){
                         $id_enlace_compra=$row['MaxID'];
                    }
               // echo $id_enlace_compra;
                    $id_enlace_compra++;
            
            $sql_enlaces_compra="SELECT          enlaces_compras_dillon.codigo_plan_cuenta,
                                                 enlaces_compras_dillon.nombre,
                                                 enlaces_compras_dillon.porcentaje,
                                                 enlaces_compras_dillon.tipo,
                                                 enlaces_compras_dillon.codigo,
                                                 enlaces_compras_dillon.codigo_sri,
                                                 enlaces_compras_dillon.formula,
                                                 plan_cuentas.id_plan_cuenta as cuenta_contable,
                                                 enlaces_compras_dillon.inventario_producto,
                                                 enlaces_compras_dillon.iva,
                                                 enlaces_compras_dillon.otros_gastos,
                                                 enlaces_compras_dillon.iva_credito_tributario,
                                                 enlaces_compras_dillon.ice,plan_cuentas.id_empresa,
                                                 enlaces_compras_dillon.ice,
                                                 plan_cuentas.id_empresa,
                                                 enlaces_compras_dillon.tipo_cpra
                                                 	
                                        FROM enlaces_compras_dillon 
                                        INNER JOIN plan_cuentas ON enlaces_compras_dillon.codigo_plan_cuenta = plan_cuentas.codigo
                                        WHERE plan_cuentas.id_empresa = '".$sesion_id_empresa."';";

                    $res_enlaces_compra=mysql_query($sql_enlaces_compra) or die(mysql_error());

                    while($enlace=mysql_fetch_array($res_enlaces_compra))    //permite ir de fila en fila de la tabla
                    {
                
                  $ins_enlace_compra = "insert into enlaces_compras (id,nombre,porcentaje,tipo,codigo,codigo_sri,formula, cuenta_contable,inventario_producto,iva,otros_gastos,iva_credito_tributario,ice,id_empresa,tipo_cpra)
                  values ('".$id_enlace_compra."','".$enlace["nombre"]."','".$enlace["porcentaje"]."','".$enlace["tipo"]."','".$enlace["codigo"]."','".$enlace["codigo_sri"]."','".$enlace["formula"]."','".$enlace["cuenta_contable"]."','".$enlace["inventario_producto"]."','".$enlace["iva"]."','".$enlace["otros_gastos"]."','".$enlace["iva_credito_tributario"]."','".$enlace["ice"]."','".$sesion_id_empresa."','".$enlace["tipo_cpra"]."')";
                      $res_ins_enlace=mysql_query($ins_enlace_compra) or die(mysql_error());
                      $id_enlace_compra++;
             //    echo "</br>"."enlace compras".  $ins_enlace_compra;   
                      
                    }
                } */
            
                
                
                
             /*   if ($_SESSION['sesion_tipo_empresa']==5 or $_SESSION['sesion_tipo_empresa']==6 ){
                    
                    $sqli="Select max(id_forma_pago) as MaxID from formas_pago";
                    $result=mysql_query($sqli);
                    $id_forma_de_pago=0;
                    while($row=mysql_fetch_array($result)){
                       $id_forma_de_pago=$row['MaxID'];
                    }
                    $id_forma_de_pago++;
                    $sql_nueva_forma_de_pago="SELECT 0 as id_forma_pago, formas_pago_dillon.nombre,plan_cuentas.id_plan_cuenta, '".$sesion_id_empresa."' as id_empresa,
                    formas_pago_dillon.id_tipo_movimiento,formas_pago_dillon.diario,formas_pago_dillon.ingreso,formas_pago_dillon.egreso,formas_pago_dillon.pagar
                                              FROM formas_pago_dillon
                                              INNER JOIN plan_cuentas ON formas_pago_dillon.codigo = plan_cuentas.codigo
                                              WHERE plan_cuentas.id_empresa = '".$sesion_id_empresa."'
                                              GROUP BY formas_pago_dillon.codigo";
                    $res_nueva_forma_de_pago=mysql_query($sql_nueva_forma_de_pago) or die(mysql_error());
                    while($enlace=mysql_fetch_array($res_nueva_forma_de_pago)){    //permite ir de fila en fila de la tabla
                          $ins_nueva_forma_de_pago = "insert into 
                          formas_pago ( id_forma_pago,nombre,id_plan_cuenta,id_empresa,id_tipo_movimiento,diario,ingreso,egreso,pagar)
                             values ('".$id_forma_de_pago."','".$enlace["nombre"]."','".$enlace["id_plan_cuenta"]."','".$sesion_id_empresa."','".$enlace["id_tipo_movimiento"]."',
                             '".$enlace["diario"]."','".$enlace["ingreso"]."','".$enlace["egreso"]."','".$enlace["pagar"]."')";
                          $res_ins_nueva_forma_de_pago=mysql_query($ins_nueva_forma_de_pago) or die(mysql_error());
                          $id_forma_de_pago++;
                    }
                } */

              

                echo "<div class='alert alert-success'><p>Terminó la Importaci&oacute;n</p></div>";
				
			}
        }      
    }
    
  
?>