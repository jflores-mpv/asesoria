<?php 

session_start();
require_once('../conexion.php');
  
$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
// 	echo $sesion_id_empresa;
//	$sesion_id_periodo_contable=$_SESSION["sesion_id_empresa"];
    $sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
	$sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];
	
	$emision_tipoFacturacion = $_SESSION["emision_tipoFacturacion"];
	
    $emision_tipoEmision=$_SESSION['emision_tipoEmision'] ;
    
	$emision_codigo = $_SESSION["emision_codigo"];
	$establecimiento_codigo = $_SESSION["establecimiento_codigo"];

// function actualizarBodegas($sesion_id_empresa,$codigo, $stock){

    
    $existenciasCantBodegas=0;
    
    $sqlEmpresa="SELECT  `id_empresa` FROM `empresa`";
    // echo $sqlEmpresa."</br>";
    $resultEmpresa= mysql_query($sqlEmpresa);
    $cantidadBodegas = mysql_num_rows($resultEmpresa);

    while ($rowEmpresa= mysql_fetch_array($resultEmpresa)){
        
        $id_empresa = $rowEmpresa['id_empresa'];
    //   echo "<div style='background-color:pink;color:white'> $id_empresa</div></br>"; 
        
            // $sqlProductos = "SELECT codigo,stock FROM `productos` where id_empresa='".$id_empresa."' and tipos_compras='1'  ";
            // $resultProductos = mysql_query($sqlProductos);
            // while($rowProductos = mysql_fetch_array($resultProductos)){
            //     $codigo = $rowProductos['codigo'];
            //     $stock = $rowProductos['stock'];
            // }
            
            
           
            
            $sqlEmpresaBodegaProductos="SELECT `id`,`codigo`, `id_empresa`,`cantidad`,`stock`,`tipos_compras` FROM `productos` 
            LEFT JOIN `cantBodegas` cantBodegas ON cantBodegas.idProducto=productos.codigo
            where productos.id_empresa='".$id_empresa."' GROUP BY productos.`codigo`  ";
            
            $resultEmpresaBodegaProductos= mysql_query($sqlEmpresaBodegaProductos);
                while($rowBodegaProductos = mysql_fetch_array($resultEmpresaBodegaProductos)){
                
                $codigoProducto = $rowBodegaProductos['codigo'];
                $cantidad = $rowBodegaProductos['cantidad'];
                // $cantidadstock = $rowBodegaProductos['stock'];
                $cantidadstock = '0';
                $idCantBodega = $rowBodegaProductos['id'];
                $tipos_compras = $rowBodegaProductos['tipos_compras'];
                
                // echo "<div style='background-color:green;color:white;padding:10px;width;50%'>
                // $sqlEmpresaBodegaProductos</div></br>"; 
                
                $sqlEmpresaBodega="SELECT `id`, `detalle`, `id_empresa` FROM `bodegas` where id_empresa='".$id_empresa."' LIMIT 1";
                    $resultBodegaEmpresa= mysql_query($sqlEmpresaBodega);
                    $cantidadBodegaEmpresa = mysql_num_rows($resultBodegaEmpresa);
                    while($rowBODEGAS = mysql_fetch_array($resultBodegaEmpresa)){
                        $idBoega = $rowBODEGAS['id'];
                    }
                
                $sqlCantidad= "SELECT SUM(stock) as suma FROM productos WHERE productos.codigo='".$codigoProducto."' 
                AND productos.id_empresa = '".$id_empresa."'";
                $resultCantidad = mysql_query($sqlCantidad);
 while($rowSumaStock= mysql_fetch_array($resultCantidad) ){
     $sumaStockProducto= $rowSumaStock['suma'];
 }
            // $sumaStockProducto =is_null($sumaStockProducto)?0:$sumaStockProducto;
                
                //  echo "<div style='background-color:GREEN;color:black'>producto ==$codigoProducto== CON ==$sumaStockProducto== $sqlCantidad</div></br>";
                
                $sqlInsert="INSERT INTO `cantBodegas`( `idBodega`, `idProducto`, `cantidad`) 
                VALUES ('".$idBoega."','".$codigoProducto."','$sumaStockProducto')";
                $resultInsert= mysql_query($sqlInsert);
                
                if($resultInsert){
                      echo "<div style='background-color:GREEN;color:black'>producto insertada ==$codigo== CON ==$sumaStockProducto== </div></br>";
                      
                }else{
                    echo "<div style='background-color:red;color:white'>CANTIDAD no insertada ==$sqlInsert========= $codigoProducto ==== $sumaStockProducto</div></br>";
                }
                
                // echo "<div style='background-color:green;color:white;padding:10px;width;50%'>
                // $id_empresa == $idCantBodega == $codigoProducto===$cantidad ==$cantidadstock == $tipos_compras </div></br>"; 
                

                    
                // echo "<div style='background-color:blue;color:white;padding:10px;width;50%'>$id_empresa bodegas ==  $idBoega</div></br>"; 
                            
      
                
                
                
                
                
                }
                
            // $cantidadresultEmpresaBodegaProductos = mysql_num_rows($resultEmpresaBodegaProductos);
            // if($cantidadresultEmpresaBodegaProductos>0){
            //     echo "<div style='background-color:yellow;color:black;padding:10px;width;50%'>$codigoProducto hay en bodega </div></br>";
                
            //     // $sqlUPdate="UPDATE cantBodegas set cantidad='".$cantidadstock."' where id='".$idCantBodega."'  ";
                
            //     // $resultIsqlUPdate= mysql_query($sqlUPdate);
            //     // if($resultIsqlUPdate){
                   
            //     //     echo "<div style='background-color:GREEN;color:black'> $codigoProducto===$cantidad actualizada ==$cantidadstock</div></br>";
            //     // }else{
            //     //      echo "<div style='background-color:RED;color:white'>no se actualizo $sqlUPdate</div></br>";
            //     // }
                 
       
                 
            // }else{
                
     
            // }
           
            
            
    }             
            
          
            
            
            
            
            
            
            
            
            
            //     $sqlDELETE="SELECT `id`,`codigo`, `id_empresa`,`cantidad`,`stock`,`tipos_compras` FROM `cantBodegas` 
            // INNER JOIN `productos` productos ON cantBodegas.idProducto=productos.codigo
            // where productos.tipos_compras!='1' ORDER BY productos.`codigo`";
                
            //     $resultIDELETE= mysql_query($sqlDELETE);
            //      while($rowtIDELETE = mysql_fetch_array($resultIDELETE)){
            //     $codigoProducto2 = $rowtIDELETE['codigo'];
            //     $cantidad2 = $rowtIDELETE['cantidad'];
            //     $cantidadstock2 = $rowtIDELETE['stock'];
            //     $idCantBodega2 = $rowtIDELETE['id'];
            //     $tipos_compras2 = $rowtIDELETE['tipos_compras'];
            //      echo "<div style='background-color:pin;color:white;padding:10px;width;50%'>$id_empresa == $idCantBodega2 == $codigoProducto2===$cantidad2 ==$cantidadstock2 == $tipos_compras2</div></br>";
            
            //     $sqlDELETE2="SELECT `id`,`codigo`, `id_empresa`,`cantidad`,`stock`,`tipos_compras` FROM `cantBodegas` 
            // INNER JOIN `productos` productos ON cantBodegas.idProducto=productos.codigo
            // where productos.tipos_compras!='1' ORDER BY productos.`codigo`";
                
            //     $resultIDELETE2= mysql_query($sqlDELETE2);
                
                
            //     if($resultIDELETE2){
                   
            //         echo "<div style='background-color:green;color:black'>$idCantBodega2 eliminado</div></br>";
            //     }else{
            //          echo "<div style='background-color:green;color:white'>$idCantBodega2 no se elimino</div></br>";
            //     }
                
                   
                // $sqlUPdate="UPDATE cantBodegas set cantidad='".$cantidadstock."' where id='".$idCantBodega."'  ";
                
                // $resultIsqlUPdate= mysql_query($sqlUPdate);
                // if($resultIsqlUPdate){
                   
                //     echo "<div style='background-color:GREEN;color:black'>$id_empresa == $idCantBodega == $codigoProducto===$cantidad ==$cantidadstock</div></br>";
                // }else{
                //      echo "<div style='background-color:green;color:white'>no se actualizo</div></br>";
                // }
                
                
                
                
                
            
            
            
    // }    
            
            
            
                
            
//           echo $sqlBodegasCant = "SELECT cantBodegas.id,idProducto,cantidad,idBodega,bodegas.id_empresa
// FROM `cantBodegas`
// INNER JOIN `bodegas` bodegas ON cantBodegas.idBodega=bodegas.id

// WHERE idProducto='".$codigo."' and bodegas.id_empresa='".$id_empresa."' ";

//                 while($rowBodegasCan = mysql_fetch_array($sqlBodegasCant)){
//                         $idcantbodega =   $rowBodegasCan['id'];
//                         $idProducto =   $rowBodegasCan['idProducto'];
//                         $cantidad   =   $rowBodegasCan['cantidad'];
//                         $idBodega   =   $rowBodegasCan['idBodega'];
//                 }
//                 echo "<div style='background-color:green;color:white'>$idcantbodega===$idProducto===$cantidad == $idBodega</div></br>"; 
            
// 		}    
            
            // $fila=mysql_num_rows($sql);
            
            
                    
            // if ($fila>0){
            //     echo "<div style='background-color:green;color:white'>$idProducto===$cantidad===$idBodega</div></br>";
            //         // $result = mysql_query($sql);
            //         // while($row = mysql_fetch_array($result)){
                                    
            //         //         $cantidad = $row['cantidad'];
            //         //         $codigo = $row['idProducto'];
            //         //             $sqlUPdate="UPDATE cantBodegas set cantidad='".$stock."' where idProducto='".$codigo."'  ";
            //         //             $resultIsqlUPdate= mysql_query($sqlUPdate);
            //         //             if($resultIsqlUPdate){
            //         //                 echo "<div style='background-color:GREEN;color:black'>CANTIDAD ACTUALIZADA ==$cantidad== CON ==$stock== </div></br>";
            //         //             }else{
            //         //                  echo "<div style='background-color:green;color:white'>ESTAN IGUALES</div></br>";
            //         //             }
                                
            //         //         }
                            
            //         }else{
            //              echo "<div style='background-color:green;color:white'>no hay en bodega</div></br>";
            //             // $sqlInsert="INSERT INTO `cantBodegas`( `idBodega`, `idProducto`, `cantidad`) 
            //             //   VALUES ('".$idBodega."','".$codigo."','$stock')";
            //             //     $resultInsert= mysql_query($sqlInsert);
            //             //     if($resultInsert){
            //             //           echo "<div style='background-color:GREEN;color:black'>CANTIDAD insertada ==$codigo== CON ==$stock== </div></br>";
            //             //     }else{
            //             //         echo "<div style='background-color:red;color:black'>CANTIDAD no insertada ==$sqlInsert==</div></br>";
            //             //     }
            //         }
                       
		
		

            
    

?>

 