<?php 
session_start();
    include('../conexion2.php');
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
    $txtFechaDesde =$_GET['txtFechaDesde']; 
    $txtFechaHasta =$_GET['txtFechaHasta']; 
    $criterio_mostrar  =$_GET['criterio_mostrar'];  
    $criterio_orden =$_GET['criterio_orden'];
    $criterio_ordenar_por =$_GET['criterio_ordenar_por']; 
    function formatCurrency($amount) {
        return '$' . number_format($amount, 2); // 2 decimales para los centavos
    }
    
  $txtClientes= $_GET['txtClientes'];
    // echo "sesion id empresa==>".$sesion_id_empresa."<==>";
    $sql2 ="SELECT
    ventas.`id_venta` AS ventas_id_venta,

    ventas.`numero_factura_venta` AS ventas_numero_factura_venta,
    ventas.`id_cliente` AS ventas_id_cliente,
   DATE_FORMAT(ventas.`fecha_venta`,
        '%d-%m-%Y') AS ventas_fecha_venta,
    ventas.`sub_total` AS ventas_sub_total,
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
    
    clientes.`id_cliente` AS clientes_id_cliente,
    clientes.`nombre` AS clientes_nombre,
    clientes.`apellido` AS clientes_apellido,
    clientes.`direccion` AS clientes_direccion,
    clientes.`cedula` AS clientes_cedula,
    clientes.`numero_casa` AS clientes_numero_casa
    
    
    
FROM
    `clientes` clientes
    INNER JOIN `ventas` ventas ON clientes.`id_cliente` = ventas.`id_cliente` 
    INNER JOIN `establecimientos` establecimientos ON establecimientos.`id` = ventas.`codigo_pun`
    INNER JOIN `emision` emision ON emision.`id` = ventas.`codigo_lug`
    LEFT JOIN ciudades ON ciudades.id_ciudad = clientes.id_ciudad
    LEFT JOIN provincias ON provincias.id_provincia = ciudades.id_provincia

    where ventas.`id_empresa`='".$sesion_id_empresa."'  ";
   
   
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
     $cbprovincia =$_GET['cbprovincia']; 
    
        if ($cbprovincia!=0){
         $sql2.= " and provincias.id_provincia='".$cbprovincia."' "; 
         
         $cbciudad =$_GET['cbciudad'];
         if ($cbciudad!=0){
             $sql2.= " and ciudades.id_ciudad ='".$cbciudad."' ";
         }
        }  
    
    
    
   if ($tipoDoc!=0){
       $sql2.= " and ventas.`tipo_documento`='".$tipoDoc."' "; 
   }
       if ($txtUsuarios!=0){
       $sql2.=  " and ventas.`id_usuario`='".$txtUsuarios."' "; 
   }
       if ($txtClientes!=0){
       $sql2.= " and ventas.`id_cliente`='".$txtClientes."' "; 
   }

    //  if($sesion_id_empresa==41){
    //       echo $sql2;
    //  }
$result = mysqli_query($conexion, $sql2);
$num_total_rows =mysqli_num_rows($result);

if ($num_total_rows > 0) {
    $page = false;
//  echo "PAGE==>".$_GET["page"];
    //examino la pagina a mostrar y el inicio del registro a mostrar
    if (isset($_GET["page"])) {
        $page = $_GET["page"];
    }
 
    if (!$page) {
        $start = 0;
        $page = 1;
    } else {
        $start = ($page - 1) * $criterio_mostrar;
    }
    
    // echo "start===>".$start;
    //calculo el total de paginas
    $total_pages = ceil($num_total_rows / $criterio_mostrar);
 
//   echo $estado."</br>";

	
	
	
    $sql2.= "ORDER BY $criterio_ordenar_por $criterio_orden LIMIT ".$start." , ".$criterio_mostrar ;
     
    // echo $sql2;
    
    $result=mysqli_query($conexion,$sql2);
    

    if ($result->num_rows > 0) {
        
          
        
        // if ($row_cnt> 0) {
        
        // echo '<ul class="row bg-danger">';
        while ($row = $result->fetch_assoc()) {
            
               $ventas_estado_anulacion = $row['ventas_estado_anulacion'];
            $subtotal_actual='0';
            $subtotal_actual=$row['ventas_sub_total'];
            
            $total_actual='0';
            $total_actual=$row['ventas_total'];
            
            if($ventas_estado_anulacion!='Activo'){
                $bgestado='bg-danger text-white';
                 
            }else {
                $bgestado='bg-white';
            }
            
            ?>
            
            
          <div class="row accordion pt-1 my-2 rounded <?php echo $bgestado?>">
            <div class="col-lg-8">
                  
                    <div class="row ">
                
                        <!--<div class="col-lg-1"><?php echo $tipoDoc?></div>-->
                        <div class="col-lg-12"><?=utf8_encode($row['clientes_nombre']."  ".$row['clientes_apellido'])?></div>
                        
                        <div class="col-lg-3"><?=$row['establecimientos_codigo']?>-<?=$row['emision_codigo']?>-<?=$row['ventas_numero_factura_venta']?></div>
                        <div class="col-lg-3"><?=($row['ventas_fecha_venta'])?></div>
                        <div class="col-lg-3 text-center"><?=formatCurrency($subtotal_actual)?></div>
                        <!--<div class="col-lg-1 "><?=formatCurrency($row['ventas_sub_total'])?></div>-->
                    	<div class="col-lg-1 "><?=formatCurrency($total_actual)?></div>
                </div>
                <div class="row">
                        <div class="col-lg-2">Autorizacion:</div>
                        <div class="col-lg-8"><?=$row['ventas_Autorizacion']?></div>
                </div>
                
                <div class="row">
                 <div class="col-lg-2">Estado:</div>
                        <div class="col-lg-10"><?=$row['ventas_estado']?></div>
                     <div class="col-lg-3">Respuesta Sri:</div>   
                     <div class="col-lg-9"><?=$row['ventas_estado2']?></div>
                </div>         
            </div>
            <div class="col-lg-2">
                <div class="row">
                    
            <?php        
                if($ventas_estado_anulacion=='Activo'){
              ?>
                <div class="col-lg-12"><a  onClick="autorizar('<?php echo $row['ventas_id_venta'] ?>','<?php echo $tipoDoc ?>','')" value="Generar pdf" class="btn btn-success btn-sm w-100">AUTORIZAR</a></div>

             <?php }else { }  ?>
                    
                    
            <div class="col-lg-12 mt-2"><a  href='javascript: descargarPdf(<?php echo $row['ventas_id_venta']; ?>,1,2,1,<?php echo $tipoDoc ?>);' value="Generar XML" class="btn btn-success btn-sm w-100">PDF F.E</a></div> 
            <div class="col-lg-12 mt-2"><a  href='javascript: descargarPdf(<?php echo $row['ventas_id_venta']; ?>,1,2,2,<?php echo $tipoDoc ?>);' value="Generar XML" class="btn btn-success btn-sm w-100">XML F.E</a></div> 

               </div>
            </div>
            
            <div class="col-lg-2">
                <div class="row">
                    <div class="col-lg-12"><a  onClick="enviarCorreo('<?php echo $row['ventas_id_venta'] ?>','1','1')" value="Generar pdf" class="btn btn-success btn-sm w-100">EMAIL</a></div>
                    <div class="col-lg-12 mt-2"><a  onClick="pdfVentas('<?php echo $row['ventas_id_venta'] ?>','<?php echo $row['emision_formato'] ?>','<?php echo $tipoDoc ?>')" value="Generar pdf" class="btn btn-success btn-sm w-100">Comprobante</a></div>
                    <div class="col-lg-12 mt-2"><small><?php echo $row['ventas_id_venta']; ?></small>
                    
                    <?php if($sesion_id_empresa=='116' or $sesion_id_empresa=='2427' ){ ?>
                        
                        <a  onClick="descargarPDFEnServidor('<?php echo $row['ventas_id_venta'] ?>','<?php echo $row['emision_formato'] ?>','<?php echo $tipoDoc ?>')" value="Generar pdf" class="btn btn-success btn-sm w-100">imprimir</a>
                <?php    } ?>
                    
                    
                    </div>

                </div>
            </div>
        </div>
            
            
            <?php
            
        }
        // echo '</ul>';
    }
 
    echo '<nav>';
echo '<ul class="pagination">';
 
if ($total_pages > 1) {
    if ($page != 1) {
        echo '<li class="page-item"><a class="page-link" onClick="listar_reporte_facturas('.($page-1).')" ><span aria-hidden="true">&laquo;</span></a></li>';
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
            echo '<li class="page-item"><a class="page-link" onClick="listar_reporte_facturas('.$i.')" >'.$i.'</a></li>';
        }
    }

    if ($page != $total_pages) {
        echo '<li class="page-item"><a class="page-link" onClick="listar_reporte_facturas('.($page+1).')" ><span aria-hidden="true">&raquo;</span></a></li>';
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
                <h5>No existen ventas Realizadas</h5>
            </div>
        </div>
        
<?php    }



?>
    <script src="https://cdn.jsdelivr.net/npm/print-js"></script>

<script>



function autorizar(idComprobante, Tipo, correo) {
    alert("AUTORIZAR");
    console.log({
        id: idComprobante,
        tipo_comprobante: Tipo,
        correo: correo
    });

    $.post("sql/autorizar.php", {
        id: idComprobante,
        tipo_comprobante: Tipo,
        correo: correo
    }, function(data) {
        console.log(data);
        if (data == 1) {
            alertify.success("Factura Autorizada");
            listar_reporte_facturas(1);
        } else if (data == 2) {
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
        
function descargarPDFEnServidor(idVenta, formato, documento) {
    let formatoF = '';

    // Construir la URL para descargar el PDF desde el servidor
    const miUrl = "reportes/rptFacturas_detalladoMini.php?txtComprobanteNumero="+idVenta;
    console.log("miUrl",miUrl);
    // Hacer una solicitud para descargar el PDF
    fetch(miUrl)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la descarga del PDF');
            }
            return response.blob();
        })
        .then(blob => {
            // Crear un FormData para enviar el archivo al servidor
            const formData = new FormData();
            formData.append('archivo', blob, `comprobante_${idVenta}.pdf`); // Nombre del archivo y campo en FormData

            // Hacer una solicitud POST al script PHP en el servidor para guardar el archivo
            fetch('../sql/guardar_pdf.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al guardar el PDF en el servidor');
                }
                
                console.log('PDF guardado correctamente en el servidor');
                imprimirPDF('comprobante_' + idVenta);

            })
            .catch(error => {
                console.error('Error en la solicitud de guardar PDF:', error);
            });
        })
        .catch(error => {
            console.error('Error en la descarga del PDF:', error);
        });
}



function imprimirPDF(nombreArchivo) {
  const urlPDF = `../tickets/${nombreArchivo}.pdf`;
  console.log('urlPDF', urlPDF);

  try {
    printJS(urlPDF);
  } catch (error) {
    console.error('Error al imprimir:', error);
    // Optional: Handle the error more gracefully, like showing an alert to the user
    alert('Ha ocurrido un error al imprimir el PDF. Inténtalo de nuevo más tarde.');
  }
}

// function imprimirPDF(nombreArchivo) {
//     const urlPDF = `../tickets/${nombreArchivo}.pdf`;
// console.log('urlPDF',urlPDF);
//     printJS(urlPDF);
// }
</script>   

 
        <script>
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

    