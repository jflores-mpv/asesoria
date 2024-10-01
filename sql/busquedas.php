<?php

//	require_once('../ver_sesion.php');

	//Start session
	session_start();

	//Include database connection details
	require_once('../conexion.php');
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];

   // FILTRAR LOS COMBOS PARA PAIS, PROVINCI Y CIUDAD

    try
    {
        $accion = $_POST['accion'];

        //Busqueda de paises
        if($accion == 1)
		{
           $cadena="";
           //consulta
            try {
               $consulta5="SELECT * FROM paises order by pais asc;";
                  echo $consulta5;
                $result=mysql_query($consulta5);
                while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
                    {
                        $cadena=$cadena."?".$row['id_pais']."?".$row['pais'];
                    }
               echo "".$cadena;

            }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
        }



           //Busqueda de provincias
        if($accion == 2){
           $cadena="";
           $codigo = $_POST['codigo'];
           echo "CODIGO".$codigo;
           //consulta
            try {
               $consulta5="SELECT * FROM provincias WHERE id_pais='".$codigo."' order by provincia asc;";
                echo $consulta5;
                $result=mysql_query($consulta5);
                while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
                    {
                        $cadena=$cadena."?".$row['id_provincia']."?".$row['provincia'];
                    }
               echo "".$cadena;

            }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
          }

          //Busqueda de provincias
        if($accion == 3){
           $cadena="";
           $codigo = $_POST['codigo'];
           //consulta
            try {
               $consulta5="SELECT * FROM ciudades WHERE id_provincia='".$codigo."' order by ciudad asc;";
                $result=mysql_query($consulta5);
                while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
                    {
                        $cadena=$cadena."?".$row['id_ciudad']."?".$row['ciudad'];
                    }
               echo "".$cadena;

            }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
          }

           //Busqueda de instituciones educativa
        if($accion == 4){
           $cadena="";
           $codigo = $_POST['codigo'];
           //consulta
            try {
               $consulta5="SELECT * FROM instituciones_educativas WHERE id_ciudad='".$codigo."' order by nombre asc;";
                $result=mysql_query($consulta5);
                while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
                    {
                        $cadena=$cadena."?".$row['id_institucion_educativa']."?".$row['nombre'];
                    }
               echo "".$cadena;

            }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
          }
              if($accion == 5)
		{
           $cadena="";
           //consulta
            try {
               $consulta5="SELECT * FROM tipo_cliente order by tcl_detalle asc;";
				echo $consulta5;
                $result=mysql_query($consulta5);
                while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
                    {
                        $cadena=$cadena."?".$row['tcl_id']."?".$row['tcl_detalle'];
                    }
               echo "".$cadena;
            }catch(Exception $ex) 
			{ ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
        }

	  
	  
	          //buscar enlaces de retencion de iva
        if($accion == 6)
		{
           $cadenaEnlaceIva="";
           $codigo = $_POST['codigo'];
           //consulta
            try 
			{
                $consulta6='SELECT * FROM enlaces_compras WHERE tipo="retencion-iva" AND id_empresa="'.$sesion_id_empresa.'" order by nombre asc';
				echo $consulta6;
                $result6=mysql_query($consulta6);
                while($row6=mysql_fetch_array($result6))//permite ir de fila en fila de la tabla
                {
                    $cadenaEnlaceIva=$cadenaEnlaceIva."?".$row6['id']."?".$row6['nombre'];
                }
               echo "".$cadenaEnlaceIva;

            }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
          }
		  
		  
          if($accion == 7){
           $cadenaEnlaceFuente="";
           $codigo = $_POST['codigo'];
           //consulta
            try {
               $consulta7='SELECT * FROM enlaces_compras WHERE tipo="retencion-fuente" AND id_empresa="'.$sesion_id_empresa.'" order by nombre asc';
                $result7=mysql_query($consulta7);
                while($row7=mysql_fetch_array($result7))//permite ir de fila en fila de la tabla
                    {
                        $cadenaEnlaceFuente=$cadenaEnlaceFuente."?".$row7['id']."?".$row7['nombre'];
                    }
               echo "".$cadenaEnlaceFuente;

            }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
          }
		  
		  
		  
		if($accion == 9)
		{
			echo "estoy de sqk";
           $cadena="";
           $codigo = $_POST['codigo'];
           //consulta
            try 
			{
            //    $consulta7='SELECT * FROM plan_cuentas WHERE id_empresa="'.$sesion_id_empresa.'" and codigo > 99 order by nombre asc;';
				$consulta7='SELECT * FROM plan_cuentas WHERE id_empresa="'.$sesion_id_empresa.'" order by nombre asc;';
                            
			  echo $consulta7;
				$result7=mysql_query($consulta7);
                while($row7=mysql_fetch_array($result7))//permite ir de fila en fila de la tabla
                {
					$id = $row7['codigo'];
                    if ($id == $idAnterior) 
					{
                        continue;
                    }
					else
					{
                        $cadena=$cadena."?".$row7['id_plan_cuenta']."?".$row7['codigo']." - ".$row7['nombre'];
                        $idAnterior = $row7['codigo'];
                    }
                }
               echo "".$cadena;

            }
			catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
        }
    
	if($accion == 10)
		{
		
           $cadena="";
           $codigo = $_POST['codigo'];
           
                 //consulta
            try 
			{
            //    $consulta7='SELECT * FROM plan_cuentas WHERE id_empresa="'.$sesion_id_empresa.'" and codigo > 99 order by nombre asc;';
			$consulta7='SELECT * FROM cargos WHERE  id_empresa="'.$sesion_id_empresa.'" ';
                            if($codigo!=0){
                                	$consulta7.=' AND  id_departamento='.$codigo.' ';
                            }
            $consulta7.=' order by nombre_cargo asc;';         
			 // echo $consulta7;
				$result7=mysql_query($consulta7);
                while($row7=mysql_fetch_array($result7))//permite ir de fila en fila de la tabla
                {
					$id = $row7['id_cargo'];
                    if ($id == $idAnterior) 
					{
                        continue;
                    }
					else
					{
                        $cadena=$cadena."?".$row7['id_cargo']."?".$row7['id_cargo']." - ".$row7['nombre_cargo'];
                        $idAnterior = $row7['id_cargo'];
                    }
                }
               echo "".$cadena;

            }
			catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
           
         
        }
    
    
        if($accion == 11){
            $cadena="";
            $tipo = $_POST['tipo'];
            //consulta
             try {
                $consulta5="SELECT `id_centro_costo`, `codigo`, `descripcion` FROM `centro_costo` WHERE `empresa`=".$sesion_id_empresa." AND tipo =$tipo  " ;
                 $result=mysql_query($consulta5);
                 while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
                     {
                         $cadena=$cadena."?".$row['id_centro_costo']."?".$row['descripcion'];
                     }
                echo "".$cadena;
 
             }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
           }

    }
    catch (Exception $e) {
    // Error en algun momento.
       ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e ?></p></div> <?php
    }


 if($accion == 12)
		{
           $cadena="";
            $id_seccion = $_POST['codigo'];
           //consulta
            try {
               $consulta5="SELECT `id_curso`, `descripcion`, `id_empresa`, `id_seccion` FROM `curso_colegio` WHERE id_empresa=$sesion_id_empresa AND id_seccion=$id_seccion";
                //   echo $consulta5;
                $result=mysql_query($consulta5);
                while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
                    {
                        $cadena=$cadena."?".$row['id_curso']."?".utf8_encode($row['descripcion']);
                    }
               echo "".$cadena;

            }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
        }
 if($accion == 13)
		{
		    $id_curso = $_POST['codigo'];
           $cadena="";
           //consulta
            try {
               $consulta5="SELECT `id_paralelo`, `paralelo`, `id_curso`, `valor_mensual`, `id_empresa` FROM `paralelo_colegio` WHERE id_empresa=$sesion_id_empresa AND  id_curso=$id_curso";
                //   echo $consulta5;
                $result=mysql_query($consulta5);
                while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
                    {
                        $cadena=$cadena."?".$row['id_paralelo']."?".utf8_encode($row['paralelo']);
                    }
               echo "".$cadena;

            }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
        }
        
if($accion == 14)
		{
           $cadenaEnlaceIva="";
           $codigo = $_POST['codigo'];
           //consulta
            try 
			{
                $consulta6='SELECT `id_tipo_anticipo`, `nombre_anticipo`, `id_empresa`, `tipo`, `objetivo` FROM `tipo_anticipo`  WHERE objetivo="'.$codigo.'" AND tipo="C" AND id_empresa="'.$sesion_id_empresa.'" order by nombre_anticipo asc';
				echo $consulta6;
                $result6=mysql_query($consulta6);
                while($row6=mysql_fetch_array($result6))//permite ir de fila en fila de la tabla
                {
                    $cadenaEnlaceIva=$cadenaEnlaceIva."?".$row6['id_tipo_anticipo']."?".$row6['nombre_anticipo'];
                }
               echo "".$cadenaEnlaceIva;

            }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
          }
if($accion == 15)
		{
           $cadenaEnlaceIva="";
           $codigo = $_POST['codigo'];
           //consulta
            try 
			{
                $consulta6='SELECT `id_tipo_anticipo`, `nombre_anticipo`, `id_empresa`, `tipo`, `objetivo` FROM `tipo_anticipo`  WHERE objetivo="'.$codigo.'" AND tipo="P" AND id_empresa="'.$sesion_id_empresa.'" order by nombre_anticipo asc';
				echo $consulta6;
                $result6=mysql_query($consulta6);
                while($row6=mysql_fetch_array($result6))//permite ir de fila en fila de la tabla
                {
                    $cadenaEnlaceIva=$cadenaEnlaceIva."?".$row6['id_tipo_anticipo']."?".$row6['nombre_anticipo'];
                }
               echo "".$cadenaEnlaceIva;

            }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex ?></p></div> <?php }
          }
?>


