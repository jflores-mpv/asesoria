<?php 
// include "../conexion.php";
    session_start();
    include('../conexion2.php');
    include('../conexion.php');
    $emision_tipoEmision=$_SESSION['emision_tipoEmision'] ;
    $emision_tipoFacturacion = $_SESSION["emision_tipoFacturacion"];
    $emision_ambiente = $_SESSION["emision_ambiente"];
    $emision_SOCIO= $_SESSION["emision_SOCIO"];
    // echo "SOCIO--".$emision_SOCIO."</br>";
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $tipoDoc= $_GET['tipoDoc'];
    $txtUsuarios= $_GET['txtUsuarios'];
    $estado= $_GET['estado'];
    $emision= $_GET['emision'];
    $dominio = $_SERVER['SERVER_NAME'];
       $matrizador= $_GET['matrizador'];
       
       if(trim($matrizador)==''){
           $matrizador=0;
       }
       $numero_libro= $_GET['numero_libro'];
       if(trim($numero_libro)==''){
           $numero_libro=0;
       }
  $txtClientes= $_GET['txtClientes'];
  $criterio_mostrar =  $_GET['criterio_mostrar'];
  
  function formatCurrency($amount) {
    return '$' . number_format($amount, 2); // 2 decimales para los centavos
}

function ceros($valor){
	for($i=1;$i<=9-strlen($valor);$i++)
		$s.="0";
	return $s.$valor;
}

    $consultaImpuestos = "SELECT `id_iva`, `iva`, `estado`, `id_empresa`,`id_plan_cuenta`, `codigo` FROM `impuestos` WHERE  id_empresa=$sesion_id_empresa ";
    $resultadoImpuestos = mysql_query( $consultaImpuestos);
    $listado_impuestos = array();
    while ($rowImpuestos = mysql_fetch_array($resultadoImpuestos)) {
         $listado_impuestos['id_iva'][] = $rowImpuestos['id_iva'];
         $listado_impuestos['iva'][]    = $rowImpuestos['iva'];
    }
     

    $sql2 ="SELECT
    ventas.`id_venta` AS ventas_id_venta,

    ventas.`numero_factura_venta` AS ventas_numero_factura_venta,
    ventas.`id_cliente` AS ventas_id_cliente,
    ventas.`fecha_venta` AS ventas_fecha_venta,
    ventas.`sub_total` AS ventas_sub_total,
    ventas.`descuento` AS descuento,
    ventas.`sub0` AS sub_0,
    ventas.`sub12` AS sub_12,
    ventas.`id_iva` AS ventas_id_iva,
    ventas.`total` AS ventas_total,
    ventas.`tipo_documento` AS ventas_tipo_documento,
    ventas.`id_empresa` AS ventas_id_empresa,

    
    ventas.`codigo_pun` AS ventas_codigo_pun,
    ventas.`codigo_lug` AS ventas_codigo_lug,
    
    ventas.`comentario` AS ventas_estado,
     ventas.`Comentario2` AS ventas_estado2,
    ventas.`estado` AS ventas_estado_anulacion,
    ventas.`tipo_documento` AS tipo_documento,
    ventas.`ClaveAcceso` AS ventas_ClaveAcceso,
    ventas.`Autorizacion` AS ventas_Autorizacion,
    establecimientos.`codigo` AS establecimientos_codigo,
    emision.`codigo` AS emision_codigo,
    
    emision.`formato` AS emision_formato,
    clientes.`cedula` AS clientes_ruc,
    clientes.`id_cliente` AS clientes_id_cliente,
    clientes.`nombre` AS clientes_nombre,
    clientes.`apellido` AS clientes_apellido,
    clientes.`direccion` AS clientes_direccion,
    clientes.`id_vendedor` AS clientes_id_vendedor,
    clientes.`cedula` AS clientes_cedula,
    clientes.`numero_casa` AS clientes_numero_casa,
     vendedores.nombre as vendedor_nombre,
       vendedores.apellidos as vendedor_apellido

FROM
    `clientes` clientes
    INNER JOIN `ventas` ventas ON clientes.`id_cliente` = ventas.`id_cliente` 
    	LEFT JOIN vendedores on vendedores.id_vendedor =  ventas.`vendedor_id_tabla`
    INNER JOIN `establecimientos` establecimientos ON establecimientos.`id` = ventas.`codigo_pun`
    INNER JOIN `emision` emision ON emision.`id` = ventas.`codigo_lug`
    LEFT JOIN ciudades ON ciudades.id_ciudad = clientes.id_ciudad
    LEFT JOIN provincias ON provincias.id_provincia = ciudades.id_provincia
    ";
    
  if ( ($matrizador!='0'||$numero_libro!='0') && ($dominio=='jderp.cloud' || $dominio=='www.jderp.cloud') ){
     if ($matrizador!='0'){
         $sql2.= " INNER JOIN(
            SELECT
                `id_info_adicional`,
                `campo`,
                `descripcion`,
                `id_venta`,
                `id_empresa`
            FROM
                `info_adicional`
            WHERE
                id_empresa = '".$sesion_id_empresa."' AND
                    info_adicional.campo = 'Matrizador' AND info_adicional.`descripcion` = '".$matrizador."'
               
        ) AS adicional
        ON
            adicional.id_venta = ventas.id_venta";
     }     
    
       if ($numero_libro!='0'){
                       $sql2.= " INNER JOIN(
                SELECT
                    `id_info_adicional`,
                    `campo`,
                    `descripcion`,
                    `id_venta`,
                    `id_empresa`
                FROM
                    `info_adicional`
                WHERE
                    id_empresa = '".$sesion_id_empresa."' AND 
                        info_adicional.campo = 'NUMERO DE LIBRO' AND info_adicional.`descripcion` LIKE '%".$numero_libro."%'
            ) AS adicional2
            ON
                adicional2.id_venta = ventas.id_venta";
           
       }
    
        
  }  
    
   $sql2.=" where ventas.`id_empresa`='".$sesion_id_empresa."'  ";
   
   
   
   if ($estado!='0'&& $estado!='D'){
       $sql2.= "and ventas.`estado`='".$estado."' "; 
   } 
    
   
   if ($emision!='0' && $emision!='GLOBAL'){
       $sql2.= " and emision.`id`='".$emision."' "; 
   } 

      $autorizacion = trim($_GET['autorizacion'])==''?0:$_GET['autorizacion'];
    if($autorizacion!='0'){
      if ($autorizacion=='1'){
            $sql2.= " and ventas.`Autorizacion` is not null "; 
       }else if($autorizacion=='2') {
           $sql2.= " and ventas.`Autorizacion` is null "; 
       }   
    }
    
     $txtFechaDesde =$_GET['txtFechaDesde']; 
    $txtFechaHasta =$_GET['txtFechaHasta']; 
   
        if ($txtFechaDesde!='' && $txtFechaHasta!='' ){
            $sql2.= " and DATE_FORMAT(ventas.`fecha_venta`,
        '%Y-%m-%d') >= '".$txtFechaDesde."' and DATE_FORMAT(ventas.`fecha_venta`,
        '%Y-%m-%d') <= '".$txtFechaHasta."' "; 
        }
    $cbprovincia =trim($_GET['cbprovincia'])==''?0:$_GET['cbprovincia']; 
    
        if ($cbprovincia!=0){
         $sql2.= " and provincias.id_provincia='".$cbprovincia."' "; 
         
         $cbciudad =$_GET['cbciudad'];
         if ($cbciudad!=0){
             $sql2.= " and ciudades.id_ciudad ='".$cbciudad."' ";
         }
        }  
        
   if ($tipoDoc!=0){
       $sql2.= "and ventas.`tipo_documento`='".$tipoDoc."' "; 
   }
       if ($txtUsuarios!=0){
       $sql2.= "and ventas.`id_usuario`='".$txtUsuarios."' "; 
   }
       if ($txtClientes!=0){
       $sql2.= "and ventas.`id_cliente`='".$txtClientes."' "; 
   }
    $vendedor_id = trim($_GET['vendedor_id']);
    if ($vendedor_id!=0){
       $sql2.= " and ventas.`vendedor_id_tabla`='".$vendedor_id."' "; 
   }
    if ($sesion_id_empresa==41){
    //   echo $sql2;
   }
   
//   echo $sql2;
$result = mysqli_query($conexion, $sql2);
$num_total_rows =mysqli_num_rows($result);

if ($num_total_rows > 0) {
    $page = false;

    if (isset($_GET["page"])) {
        $page = $_GET["page"];
    }
 
    if (!$page) {
        $start = 0;
        $page = 1;
    } else {
        $start = ($page - 1) * $criterio_mostrar;
    }
    

    $total_pages = ceil($num_total_rows / $criterio_mostrar);
 
//   echo $estado."</br>";

	
	
	
    $sql2.= "ORDER BY ventas_numero_factura_venta DESC LIMIT ".$start." , ".$criterio_mostrar ;
     

    $result=mysqli_query($conexion,$sql2);
    

    if ($result->num_rows > 0) {
        
        
        
    
          
    echo    "<div class='table-responsive'><table class='table table-bordered'>
    <thead>
        <tr>
            <th>RUC</th>
            <th>Cliente</th>
            <th>Factura</th>
            <th>Fecha </th>
            <th>Descuento</th>";
     
       foreach ($listado_impuestos['iva'] as $key => $value) {
           echo "<th>Subtotal $value %</th>";
        }
    echo    "<th>IVA</th>
            <th>Subtotal</th>
            <th>Total</th>
            <th>Estado</th>
            <th>Respuesta Sri</th>
            <th>Acciones</th>
          
        </tr>
    </thead>
    <tbody> ";
    
        while ($row = $result->fetch_assoc()) {
            
            
            $ventas_estado_anulacion = $row['ventas_estado_anulacion'];
            $subtotal_actual='0';
            $subtotal_actual0=$row['sub_0'];
            $subtotal_actual12=$row['sub_12'];
            $subtotal_actual=$row['ventas_sub_total'];
            
            $total_actual='0';
            $total_actual=$row['ventas_total'];
            
            if($ventas_estado_anulacion!='Activo'){
                $bgestado='bg-danger text-white';
                 
            }else {
                $bgestado='bg-white';
            }
            
            ?>
            
            
         <tr class="<?=$bgestado?>">
            <td><?=utf8_encode($row['clientes_ruc'])?></td>
            <td><?=utf8_encode($row['clientes_nombre']."  ".$row['clientes_apellido'])?></td>
            
            <td style="white-space: nowrap;text-align: right"><?=$row['establecimientos_codigo']?>-<?=$row['emision_codigo']?>-<?=ceros($row['ventas_numero_factura_venta'])?></td>
            <td style="white-space: nowrap;"><?=date('d-m-Y',strtotime($row['ventas_fecha_venta']))?></td>
            
            <td style="text-align: right;"><?=formatCurrency($row['descuento'])?></td>
            <?php
            $sumaIvaTotal =0;
             $sqlDetalleV="SELECT 
                 `id_detalle_venta`, 
                 `tarifa_iva`, 
                 SUM(`v_total`) as suma_total_subtotal ,
                 SUM(`total_iva`) as suma_total_iva 
                
                 FROM `detalle_ventas` 
                 WHERE id_venta=".$row['ventas_id_venta']."  GROUP BY tarifa_iva";
                 $resultV = mysql_query($sqlDetalleV);
                 $impuestos_detalle_actual = array();
                 while($rowDv = mysql_fetch_array($resultV) ){
                     $id_iva_actual = $rowDv['tarifa_iva'];
                     $impuestos_detalle_actual[$id_iva_actual]= $rowDv['suma_total_subtotal'];
                     $sumaIvaTotal += floatval($rowDv['suma_total_iva']);
                 }
                 
             foreach ($listado_impuestos['id_iva'] as $key => $value) {
                 if (array_key_exists($value, $impuestos_detalle_actual)) {
                     ?>
                      <td style="text-align: right;"><?=formatCurrency($impuestos_detalle_actual[$value])?></td>
                     <?php
                 }else{
                     ?>
                      <td style="text-align: right;"><?=formatCurrency(0)?></td>
                     <?php
                 }
                 
                 ?>
         
           <?php
        }
        ?>
   
            <td style="text-align: right;"><?=formatCurrency($sumaIvaTotal)?></td>
            <td style="text-align: right;"><?=formatCurrency($subtotal_actual)?></td>
            
            <td style="text-align:right"><?=formatCurrency($total_actual)?></td>
            <!--<td style="white-space: nowrap;"><?=$row['ventas_Autorizacion']?></td>-->
            <td style="white-space: nowrap;"><?=$row['ventas_estado']?></td>
            <td style="white-space: nowrap;"><?=$row['ventas_estado2']?></td>
            
            <td style="white-space: nowrap;">
                <?php if ($ventas_estado_anulacion == 'Activo'): ?>
                    <a onClick="autorizar('<?php echo $row['ventas_id_venta'] ?>','<?php echo $tipoDoc ?>','')" class="btn btn-success btn-sm">AUTORIZAR</a>
                <?php endif; ?>
                <a href='javascript: descargarPdf(<?php echo $row['ventas_id_venta']; ?>,1,2,1,<?php echo $tipoDoc ?>);' class="btn btn-success btn-sm">PDF F.E</a>
                <a href='javascript: descargarPdf(<?php echo $row['ventas_id_venta']; ?>,1,2,2,<?php echo $tipoDoc ?>);' class="btn btn-success btn-sm ">XML F.E</a>
                <a href='javascript: descargarPdf(<?php echo $row['ventas_id_venta']; ?>,1,2,3,<?php echo $tipoDoc ?>);' class="btn btn-success btn-sm ">XML AUT</a>

                <a onClick="pdfVentas('<?php echo $row['ventas_id_venta'] ?>','<?php echo $row['emision_formato'] ?>','<?php echo $tipoDoc ?>')" class="btn btn-success btn-sm">Comprobante</a>
            </td>
            <!--<td><small><?php echo $row['ventas_id_venta']; ?></small></td>-->
        </tr>
<?php   
$idventas = $row['ventas_id_venta'];
$sqlrERTE = "SELECT * FROM mvretencion WHERE Factura_id = $idventas";
$resultRetencion = mysqli_query($conexion, $sqlrERTE);

if ($resultRetencion) {
    if (mysqli_num_rows($resultRetencion) > 0) {
        echo "<tr>
        <td colspan='11'>
        <table class='table table-bordered w-50'>
                <tr>
                    <th>ID Retenci&oacute;n</th>
                    <th>Fecha</th>
                </tr>";
        
        while ($rowRetencion = mysqli_fetch_assoc($resultRetencion)) {
      
            echo "<tr>
                    <td>{$rowRetencion['Serie']}-{$rowRetencion['Numero']}</td>
                    <td>{$rowRetencion['Fecha']}</td>
              </tr>";
                
                $sqddetlleERTE = "SELECT * FROM dvretencion WHERE Retencion_id = $idventas";
                $resultRetencionDetalle = mysqli_query($conexion, $sqddetlleERTE);
                
                if ($resultRetencionDetalle) {
                    if (mysqli_num_rows($resultRetencionDetalle) > 0) {
                        echo "<tr>
                            <td colspan='11'>
                            <table class='table table-bordered w-50'>
                                    <tr>
                                        <th>Porcentaje</th>
                                    </tr>";
                            
                   while ($rowRetencionDetalle = mysqli_fetch_assoc($resultRetencionDetalle)) { 
                        echo '<tr>
                                <th>' . $rowRetencionDetalle['Porcentaje'] . '</th>
                              </tr>';
                    }

                            
                    
                    }
                    
                }
              
              
              
        }
echo "</table>";
        
    } else {
       
    }
} else {
    echo "Error en la consulta de retenciones: " . mysqli_error($conexion);
}
echo "</td></tr>";
?>


        <?php


        }
            
            echo   "</tbody>
            </table></div>";
    }
 
    echo '<nav>';
echo '<ul class="pagination">';
 
if ($total_pages > 1) {
    if ($page != 1) {
        echo '<li class="page-item"><a class="page-link" onClick="listar_reporte_clientes_jd('.($page-1).')" ><span aria-hidden="true">&laquo;</span></a></li>';
    }
 
    $half = floor(15 / 2); // Mitad de los botones que deseas mostrar
    $start = max(1, $page - $half);
    $end = min($total_pages, $start + 15 - 1);

    if ($end - $start < 15) {
        $start = max(1, $end - 15 + 1);
    }

    for ($i = $start; $i <= $end; $i++) {
        if ($page == $i) {
            echo '<li class="page-item active"><a class="page-link" href="#">'.$i.'</a></li>';
        } else {
            echo '<li class="page-item"><a class="page-link" onClick="listar_reporte_clientes_jd('.$i.')" >'.$i.'</a></li>';
        }
    }

    if ($page != $total_pages) {
        echo '<li class="page-item"><a class="page-link" onClick="listar_reporte_clientes_jd('.($page+1).')" ><span aria-hidden="true">&raquo;</span></a></li>';
    }
}
echo '</ul>';
echo '</nav>';

    // echo '<nav>';
    // echo '<ul class="pagination">';
 
    // if ($total_pages > 1) {
    //     if ($page != 1) {
    //         echo '<li class="page-item"><a class="page-link" onClick="listar_reporte_facturas('.($page-1).')" ><span aria-hidden="true">&laquo;</span></a></li>';
    //     }
 
    //     for ($i=1;$i<=$total_pages;$i++) {
    //         if ($page == $i) {
    //             echo '<li class="page-item active"><a class="page-link" href="#">'.$page.'</a></li>';
    //         } else {
    //             echo '<li class="page-item"><a class="page-link" onClick="listar_reporte_facturas('.$i.')" >'.$i.'</a></li>';
    //         }
    //     }
 
    //     if ($page != $total_pages) {
    //         echo '<li class="page-item"><a class="page-link" onClick="listar_reporte_facturas('.($page+1).')" ><span aria-hidden="true">&raquo;</span></a></li>';
    //     }
    // }
    // echo '</ul>';
    // echo '</nav>';
}else{ ?>
        
        <div class="row">
            <div class="col-lg-4 offset-lg-4  p-5 text-center bg-warning rounded border-0">
                <h5>No existen ventas Realizadas En el modulo</h5>
            </div>
        </div>
        
<?php    }



?>

<script>



function autorizar(idComprobante,Tipo,correo) {
   alert("AUTORIZAR");
        $.post("sql/autorizar.php", {id: ""+idComprobante+"",tipo_comprobante: ""+Tipo+"",correo: ""+correo+""}, function(data){
            console.log(data);
             if     (data==1){
                 alertify.success("Factura Autorizada");
             }else if (data==2){
                 alertify.error("Factura No Autorizada");
             }
        });
       
    
    
} 

        function pdfVentas(idVenta,formato,documento){
            console.log("documento",documento);
            console.log("formato",formato);
            
            if(formato==1){
                    if(documento==1||documento==41){
                        formatoF = 'rptFacturas_detallado.php';
                    }else if(documento==5){
                        formatoF = 'rptProforma_detallado.php';
                    }
            }
            if(formato==2){
                
                if(documento==1||documento==41){
                       formatoF = 'rptFacturas_detalladoMini.php' ;
                }else if(documento==5){
                    formatoF = 'rptProforma_detallado.php';
                }
                    
                    
            }
            if(formato==3){
                    if(documento==1||documento==41){
                       formatoF = 'rptFacturas_detalladoA5.php' ;
                }else if(documento==5){
                   formatoF = 'rptProforma_detallado.php';
                }
                    
                    
            }
            
         miUrl = "reportes/"+formatoF+"?txtComprobanteNumero="+idVenta;
         window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
        }
        
        
        
        function descargarPdf(idComprobante,Tipo,correo,doc,tipodoc) {
            console.log("doc",doc);
                $.post("sql/autorizar.php", {id: ""+idComprobante+"",tipo_comprobante: ""+Tipo+"",correo:""+correo+"",doc: ""+doc+"",tipodoc: ""+tipodoc+""}, function(data){
                       console.log(data);
            miUrl = data;
            window.open(miUrl,'facturaVenta','width=600, height=600, scrollbars=NO, titlebar=no');
                });
        } 
        


        
        function enviarCorreo(idComprobante,Tipo,correo) {
                $.post("sql/autorizar.php", {id: ""+idComprobante+"",tipo_comprobante: ""+Tipo+"",correo: ""+correo+""}, function(data){
                       console.log(data);
                });
            
        } 


</script>
    