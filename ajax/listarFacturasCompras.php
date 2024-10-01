<?php 
// include "../conexion.php";
    session_start();
include('../conexion2.php');

 include ("../conexion.php");

    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
   
 $txtProveedor= $_GET["txtProveedor"];

function ceros($valor){
    $s='';
	for($i=1;$i<=9-strlen($valor);$i++)
		$s.="0";
	return $s.$valor;
}
		if ($_GET['criterio_mostrar']!='0'){
	$numeroRegistros= $_GET['criterio_mostrar'];
		}else{
		    $numeroRegistros= 5;
		}


$sql = "SELECT
     compras.`id_compra` AS compras_id_compra,
     compras.`fecha_compra` AS compras_fecha_compra,
     compras.`total` AS compras_total,
     
     compras.`sub_total` AS compras_sub_total,
     
     compras.`id_iva` AS compras_id_iva,
     compras.`id_proveedor` AS compras_id_proveedor,
     compras.`numero_factura_compra` AS compras_numero_factura_compra,
     
     compras.`numSerie` AS compras_numSerie,
     compras.`txtEmision` AS compras_txtEmision,
     compras.`txtNum` AS compras_txtNum,
     
     compras.`subtotal0` AS compras_subtotal0,
     compras.`subtotal12` AS compras_subtotal12,
     compras.subtotal12*12/100 AS compras_iva,
     
     compras.TipoComprobante AS TipoComprobante,
     compras.autorizacion AS autorizacion,
     
     compras.Observaciones AS Observaciones,
     compras.Observaciones2 AS Observaciones2,
     
     
     proveedores.`id_proveedor` AS proveedores_id_proveedor,
     proveedores.`nombre_comercial` AS proveedores_nombre_comercial,
     proveedores.`nombre` AS proveedores_nombre,
     proveedores.`direccion` AS proveedores_direccion,
     proveedores.`ruc` AS proveedores_ruc,
     proveedores.`telefono` AS proveedores_telefono,
     proveedores.`movil` AS proveedores_movil,
     proveedores.`fax` AS proveedores_fax,
     proveedores.`email` AS proveedores_email,
     proveedores.`web` AS proveedores_web,
     proveedores.`observaciones` AS proveedores_observaciones,
     proveedores.`id_ciudad` AS proveedores_id_ciudad
   
     
FROM
     `compras` compras 

INNER JOIN `proveedores` proveedores ON compras.`id_proveedor` = proveedores.`id_proveedor` ";


	    if($_GET['estado_factura']!='0'){
	       $sql.= " LEFT JOIN cobrospagos on cobrospagos.id_factura = compras.id_compra AND cobrospagos.documento=1 ";  
	    }


     
    $sql.= " where compras.`id_empresa`='".$sesion_id_empresa."' AND
       DATE_FORMAT(compras.fecha_compra, '%Y-%m-%d') >='".$_GET['txtFechaDesde']."' and
       DATE_FORMAT(compras.fecha_compra, '%Y-%m-%d')<='".$_GET['txtFechaHasta']."'  ";
     
	if ($txtProveedor!=0){
		$sql.= "and compras.`id_proveedor`='".$txtProveedor."' "; 
	}
	
	if ($_GET['txtnumfac']!=0){
		$sql.= "and compras.`txtNum` LIKE '%".$_GET['txtnumfac']."' "; 
	}
	

	    
	    if($_GET['estado_factura']=='Pagadas'){
	       $sql.= " AND  cobrospagos.id_factura!=0 ";  
	    }else if($_GET['estado_factura']=='NoPagadas'){
	       $sql.= " AND  cobrospagos.id_factura=0 ";  
	    }
	

        $sql .=" and compras.codSustento='".intval($_GET['codSustento'])."'and compras.TipoComprobante='".intval($_GET['txtTipoComprobante'])."'  ";


	    if($_GET['estado_factura']!='0' ){
	       $sql.= " GROUP BY  compras.id_compra ";  
	    }

 $result = mysql_query($sql);
        $num_total_rows = mysql_num_rows($result);



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
        $start = ($page - 1) * $numeroRegistros;
    }
    
    // echo "start===>".$start;
    //calculo el total de paginas
    $total_pages = ceil($num_total_rows / $numeroRegistros);
 


// 	$sql.= " ORDER BY compras_numero_factura_compra DESC LIMIT ".$start." , ".$numeroRegistros ; 
    	$sql.= "ORDER BY ".$_GET['criterio_ordenar_por']." ".$_GET['criterio_orden']." LIMIT ".$start." , ".$numeroRegistros ; 
    
    $result=mysqli_query($conexion,$sql);
    
// echo $sql;

    
    if ($result->num_rows > 0) {
        
        
	            
        while ($row = $result->fetch_assoc()) {
            
             $compras_id_compra = $row['compras_id_compra'];
             $TipoComprobante = $row['TipoComprobante'];
             
            // echo "<ID>".$compras_id_compra;
            
        $sqlIdentificadorPagado = "SELECT `id_factura`FROM `cobrospagos` WHERE id_factura=$compras_id_compra and documento=1";

        $row_cnt=mysqli_query($conexion,$sqlIdentificadorPagado);
    

    
    if ($row_cnt->num_rows > 0) {
            
                    $class='bg-white';   
		            
		        } 
	            else{  
	                $class='bg-danger';       
	                
	            }
            
            ?>
            
            <div class="row border rounded p-2 my-2 <?php echo $class ?>">
                <div class="col-lg-12">
                    <div class="row">
                        
                        <div class="col-lg-1 "><strong><?=$row['compras_numero_factura_compra']?></strong></div>
                        
                        <div class="col-lg-4 "><strong><?=$row['proveedores_nombre_comercial']?></strong></div>
                        
                        <div class="col-lg-1 "><strong><?=$compras_id_compra?></strong></div>
                        <div class="col-lg-1 text-right border-start border-end"><small><?=$row['compras_total']?></small></div>
                        <div class="col-lg-1 text-right border-end"><small><?=$row['compras_subtotal0']?></small></div>
                        <div class="col-lg-1 text-right border-end"><small><?=$row['compras_subtotal12']?></small></div>
                        <div class="col-lg-1 text-right border-end"><small><?=$row['compras_sub_total']?></small></div>
                        <div class="col-lg-2 text-center">
                            
                            <a href="nuevaFacturaCompra.php?numeroCompra=<?php echo $row['compras_numero_factura_compra'] ?>&idCompra=<?php echo $compras_id_compra ?>" value="Generar XML" class="btn btn-warning btn-sm w-100">Revisar Compra</a>
                            

        <button class="btn btn-warning mt-2" onClick="imprimirCodigos('<?php echo $compras_id_compra?>','detalle_compras','')"> Codigos</button>
        
                            </div> 

                          
                    </div>    
                    
                    <div class="row my-2">   
                    
                        <div class="col-lg-2"><small><?=$row['compras_numSerie']?>-<?=$row['compras_txtEmision']?>-<?=ceros($row['compras_txtNum'])?></small></div>
                        <div class="col-lg-2"><small><?=$row['compras_fecha_compra']?></small></div>

                        <?php if($TipoComprobante=='3'){ ?>
                                <div class="col-lg-2"><a  onClick="autorizar('<?php echo $compras_id_compra ?>','33','')" value="Generar pdf" class="btn btn-success btn-sm w-100">AUTORIZAR</a></div>
                                <div class="col-lg-2 text-center"><a href='javascript: descargarPdfRet(<?php echo $compras_id_compra; ?>,33,2,1);' value="Generar XML" class="btn btn-success btn-sm w-100">XML</a></div> 
                                <div class="col-lg-2 text-center"><a href='javascript: descargarPdfRet(<?php echo $compras_id_compra; ?>,33,2,3);' value="Generar XML" class="btn btn-success btn-sm w-100">XML AUT</a></div> 
                                <div class="col-lg-2 text-center"><a href='javascript: descargarPdfRet(<?php echo $compras_id_compra; ?>,33,2,2);' value="Generar XML" class="btn btn-success btn-sm w-100">PDF</a></div> 
                                <div class="col-lg-12  my-2">Autorizacion Liquidacion de compras:<?=$row['autorizacion']?></div> 
                                <div class="col-lg-12 my-2">Estado:<?=$row['Observaciones']?></div> 
                        
                        <?php if($row['Observaciones2']!=''){ ?>
                                <div class="col-lg-12 my-2">
                                        <?=$row['Observaciones2']?>
                        
                                </div>
                        <?php   } }else{ ?>
                        
                            Autorizacion: <?php echo $row['autorizacion'] ?>
                            
                        <?php    }  
                        
                        ?>
                    </div>
                    
                    
                    
                      <?php        
       
            
            if( $_GET['txtTipoComprobante']=='00'){
    ?>   
     <div class="col-lg-2"><a  onClick="pdfAbonoOrdenCompra(<?php echo $row['compras_id_compra'] ?>,<?php echo $row['proveedores_id_proveedor'] ?> )" value="Generar pdf" class="btn btn-success btn-sm w-100">PDF ORDEN DE COMPRA</a></div>
     <?php    }   ?>        
            
            <?php 
                
            $sql2 = "SELECT  mcretencion.`Id` AS mcretencion_id_compra, Observaciones,Observaciones2,Numero,Serie,anulado,autorizacion,fecha from mcretencion 
                where Factura_id = '$compras_id_compra' ";

                $qry2=mysqli_query($conexion,$sql2);
            
                while ($row2 = mysqli_fetch_array($qry2)) { ?>
                 <div class="row  py-3 rounded bg-light">
                    <div class="col-lg-3 my-2 "><strong><?=$row2['Serie']."-".ceros($row2['Numero']) ?></strong></div>
                    
                    <div class="col-lg-7 my-2 ">
                        
                        <strong>
                                    <?php if($row2['autorizacion']==''){ ?>
                                        No Autorizada - No enviada al sri
                                    <?php    }else{ ?>
                                        Autorizacion: <?=$row2['autorizacion']?>
                                    <?php    }  ?>
                        </strong>
                    
                    </div>
                    
                    <div class="col-lg-2 my-2">
                        <?=$row2['fecha']?>
                    </div>
                    
                    <div class="col-lg-12">
                        
                            
                        
                        
                          <?php 
                    
                            $sqlRETENCION= "SELECT * from dcretencion where Retencion_id = '".$row2['mcretencion_id_compra']."'  ";

                            $qryRetecnion=mysqli_query($conexion,$sqlRETENCION);
                            
                            ?>
                            
                            <div class="row  text-center mb-3">
                                <div class="col-lg-3 border ">Base</div>
                                <div class="col-lg-3 border">Codigo</div>
                                
                                <div class="col-lg-3 border">
                                    
                                   Tipo
                                
                                </div>

                                <div class="col-lg-3 border">Porcentaje</div>
                            </div>
                            
                            <?php 
                    
                            while ($rowretencion = mysqli_fetch_array($qryRetecnion)) { ?>
                            
                            <div class="row  text-center mb-3">
                                <div class="col-lg-3 border "><?=$rowretencion['BaseImp']?></div>
                                <div class="col-lg-3 border"><?=$rowretencion['CodImp']?></div>
                                
                                <div class="col-lg-3 border">
                                    
                                    <?php if($rowretencion['TipoImp']=='1'){ ?>
                                        Renta
                                    <?php    }else{ ?>
                                        IVA
                                    <?php    }  ?>
                                
                                </div>

                                <div class="col-lg-3 border"><?=$rowretencion['Porcentaje']?></div>
                            </div>
                             <?php }  ?>
                            
                            <div class="row">
                                <?php
                                
                                    if($row2['Observaciones']!=''){ ?>
                                        <div class="col-lg-8 "><strong><?=$row2['Observaciones']?></strong></div>
                                        <div class="col-lg-4 "><strong><?=$row2['Observaciones2']?></strong></div>
                                    <?php     }  ?>
                            </div>
                            <div class="row mt-2">
                                <div class="col-lg-1 text-center"><a href='javascript: descargarPdfRet(<?php echo $row2['mcretencion_id_compra']; ?>,7,2,2);' value="Generar XML" class="btn btn-success btn-sm w-100">PDF</a></div> 
                                <div class="col-lg-2"><a  onClick="autorizar('<?php echo $row2['mcretencion_id_compra'] ?>','7','')" value="Generar pdf" class="btn btn-success btn-sm w-100">AUTORIZAR</a></div>
                                
                                <?php
                                if($sesion_id_empresa=='41'){
                                    ?>
                                     <div class="col-lg-1"><a  onClick="autorizar('<?php echo $row2['mcretencion_id_compra']; ?>','7','')" value="Generar pdf" class="btn btn-success btn-sm w-100">2.0</a></div>
                                    <?php
                                }
                                ?>
                                
                                


                <div class="col-lg-1 text-center"><a href='javascript: descargarPdfRet(<?php echo $row2['mcretencion_id_compra']; ?>,7,2,1);' value="Generar XML" class="btn btn-success btn-sm w-100">XML</a></div> 
                            
                            <?php  if($row2['anulado']=='0' ){ ?>
                            
                                <div class="col-lg-2 text-center"><a href='javascript: anularRetencion(<?php echo $row2['mcretencion_id_compra']; ?>,15);' value="Generar XML" class="btn btn-success btn-sm w-100">Anular</a></div> 
                            <?php     } else{
                            echo " <div class='col-lg-2 text-center'>RETENCION ANULADA</div> ";
                            } ?>
                           
                            <?php  if($row2['autorizacion']==''){ ?>
                                <div class="col-lg-2 text-center"><a href='javascript: preguntarEliminarRetencion(<?php echo $row2['mcretencion_id_compra']; ?>,16);' value="Generar XML" class="btn btn-danger btn-sm w-100">Borrar</a></div> 
                            <?php     }  ?>
                            
                            
                            
                            
                            <div class="col-lg-1 text-center bg-warning"> <?php echo $row2['mcretencion_Id']; ?></div> 
                            
                                <div class="col-lg-2 text-center"><a href="nuevaFacturaCompra.php?numeroCompra=<?php echo $row['compras_numero_factura_compra'] ?>&idCompra=<?php echo $compras_id_compra ?>" value="Generar XML" class="btn btn-warning btn-sm w-100">Revisar Compra</a></div> 
                            
                            </div>

                          
                    </div>
                 </div>
                
            <?php } ?>
            
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
        echo '<li class="page-item"><a class="page-link" onClick="listarFacturasCompras('.($page-1).')" ><span aria-hidden="true">&laquo;</span></a></li>';
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
            echo '<li class="page-item"><a class="page-link" onClick="listarFacturasCompras('.$i.')" >'.$i.'</a></li>';
        }
    }

    if ($page != $total_pages) {
        echo '<li class="page-item"><a class="page-link" onClick="listarFacturasCompras('.($page+1).')" ><span aria-hidden="true">&raquo;</span></a></li>';
    }
}
echo '</ul>';
echo '</nav>';

    // echo '<nav>';
    // echo '<ul class="pagination">';
 
    // if ($total_pages > 1) {
    //     if ($page != 1) {
    //         echo '<li class="page-item"><a class="page-link" onClick="listarFacturasCompras('.($page-1).')" ><span aria-hidden="true">&laquo;</span></a></li>';
    //     }
 
    //     for ($i=1;$i<=$total_pages;$i++) {
    //         if ($page == $i) {
    //             echo '<li class="page-item active"><a class="page-link" href="#">'.$page.'</a></li>';
    //         } else {
    //             echo '<li class="page-item"><a class="page-link" onClick="listarFacturasCompras('.$i.')" >'.$i.'</a></li>';
    //         }
    //     }
 
    //     if ($page != $total_pages) {
    //         echo '<li class="page-item"><a class="page-link" onClick="listarFacturasCompras('.($page+1).')" ><span aria-hidden="true">&raquo;</span></a></li>';
    //     }
    // }
    // echo '</ul>';
    // echo '</nav>';
}else{ ?>
        
        <div class="row">
            <div class="col-lg-4 offset-lg-4  p-5 text-center bg-warning rounded border-0">
                <h5>No existen compras Realizadas</h5>
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
       
    listarFacturasCompras(1);
    
} 

function revisar_compra(numeroCompra){
    window.location.href = "nuevaFacturaCompra.php?numeroCompra="+numeroCompra;
}
function pdfVentas(idVenta,formato){
    
    console.log("formato",formato);
    
    if(formato==1){
        formatoF = 'rptFacturas_detallado.php';
    }
    if(formato==2){
        formatoF = 'rptFacturas_detalladoMini.php' ;
    }
    if(formato==3){
        formatoF = 'rptFacturas_detalladoA5.php' ;
    }
    

 miUrl = "reportes/"+formatoF+"?txtComprobanteNumero="+idVenta;
 window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
}



function descargarPdf(idComprobante,Tipo,correo) {
        $.post("sql/autorizar.php", {id: ""+idComprobante+"",tipo_comprobante: ""+Tipo+"",correo: ""+correo+""}, function(data){
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
function descargarPdfRet(idComprobante,Tipo,correo,tipodoc) {
    
    // console.log(idComprobante);
    // console.log(Tipo);
    // console.log(correo);
    // console.log(tipodoc);
        $.post("sql/autorizar.php", {id: ""+idComprobante+"",tipo_comprobante: ""+Tipo+"",correo: ""+correo+"",tipodoc: ""+tipodoc+""}, function(data){
    
        console.log(data);
    
        miUrl = data;
    
        window.open(miUrl,'facturaVenta','width=600, height=600, scrollbars=NO, titlebar=no');
    });
} 

function anularRetencion(id){

     $.post("sql/facturaCompra.php", {txtAccion: 15,tipo_comprobante: ""+id+""}, function(data){
                if(data=1){
        		   alertify.success("Retencion Anulada");
        		}else{
        		   alertify.danger("Retencion no anulada"); 
        		}
        });
         listarFacturasCompras(1);
}
function preguntarEliminarRetencion(id){
    
    alertify.confirm('Eliminar Datos', '¿Esta seguro de eliminar este registro?', 
		function(){  
            $.post("sql/facturaCompra.php", {txtAccion: 16,tipo_comprobante: ""+id+""}, function(data){
                if(data==2){
                    eliminarRetencion(id);
        		}else{
                    anularRetencion(id);
        		}
        });
     }
		, function(){ alertify.error('Se cancelo')});
   
}
function eliminarRetencion(id){
    $.post("sql/facturaCompra.php", {txtAccion: 17,tipo_comprobante: ""+id+""}, function(data){
                if(data==1){
        		   alertify.success("Retencion eliminada");
        		}else{
        		   alertify.danger("Retencion no eliminada"); 
        		}
        });
        listarFacturasCompras(1);
}



</script>
    