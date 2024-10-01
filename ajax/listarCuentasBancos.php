<?php

	include "../conexion.php";
	include "../clases/paginado_basico.php";
	include "../clases/paginado_PHPPaging.lib.php";

	header ("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada
	header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos
	header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE
	header ("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE

    date_default_timezone_set('America/Guayaquil');

    $id_banco = $_GET['id_banco'];
    // echo "BANCOS".$id_banco."</br>";
	$paging = new PHPPaging;
	
	$sql = "SELECT
             detalle_bancos.`id_detalle_banco` AS detalle_bancos_id_detalle_banco,
             detalle_bancos.`tipo_documento` AS detalle_bancos_tipo_documento,
             detalle_bancos.`numero_documento` AS detalle_bancos_numero_documento,
             detalle_bancos.`detalle` AS detalle_bancos_detalle,
             detalle_bancos.`valor` AS detalle_bancos_valor,
             detalle_bancos.`fecha_cobro` AS detalle_bancos_fecha_cobro,
             detalle_bancos.`fecha_vencimiento` AS detalle_bancos_fecha_vencimiento,
             detalle_bancos.`id_bancos` AS detalle_bancos_id_bancos,
             detalle_bancos.`estado` AS detalle_bancos_estado,
             detalle_bancos.`fecha` AS detalle_bancos_fecha
        FROM
             `detalle_bancos` detalle_bancos
             WHERE detalle_bancos.`id_bancos`='".$id_banco."'  and detalle_bancos.`estado`='".$_GET['cmdEstado']."' ";

                
        	    if ($_GET['opt'] == "Todos"){
        	    
                    $sql .= "order by detalle_bancos.`id_detalle_banco` asc ";
                     
                }else if ($_GET['opt'] == "Deposito" ){
                     
                     $sql .= "and detalle_bancos.`tipo_documento`='Deposito' ";
                     
                }else if($_GET['opt'] == "Cheque" ){
                     
                     $sql .= "and detalle_bancos.`tipo_documento`='Cheque' ";
        
                }else if($_GET['opt'] == "NotaCredito" ){
                     
                     $sql .= "and detalle_bancos.`tipo_documento`='Nota de Credito' ";
        
                }else if($_GET['opt'] == "NotaDebito" ){
                     
                     $sql .= "and detalle_bancos.`tipo_documento`='Nota de Debito' ";
        
                }else if($_GET['opt'] == "Transferencia" ){
                     
                     $sql .= "and detalle_bancos.`tipo_documento`='Transferencia' ";
        
                }else if($_GET['opt'] == "TransferenciaC" ){
                     
                     $sql .= "and detalle_bancos.`tipo_documento`='TransferenciaC' ";
        
                }
        
	
    	$paging->agregarConsulta($sql); 
    	$paging->div('div_listar_detallesCuentaBancos');
    	$paging->modo('desarrollo'); 
    	if (isset($_GET['criterio_mostrar']))
    		$paging->porPagina(fn_filtro((int)$_GET['criterio_mostrar']));
    	$paging->verPost(true);
    	$paging->mantenerVar("Todos", "opt");
    	$paging->ejecutar();       
        $result=mysql_query($sql);
        
        // echo $sql;
        
?>
<table id="grilla" class="table">
    <thead>
        <tr>
            <th><strong>Tipo Cpte.</strong></th>
            <th><strong>Valor.</strong></th>
            <th><strong>N&uacute;mero Cpte.</strong></th>
            <th><strong>Visto</strong></th>
            <th><strong>Fecha</strong></th>
            <th><strong>Fecha Emisi&oacute;n</strong></th>
            <th><strong>Fecha Vencimiento</strong></th>
            <th><strong>Referencia</strong></th>
            
        </tr>
    </thead>
    <tbody>
    <?php

        $contador = 0;
        $totalCheque = 0;
        $totalDeposito = 0;
        $totalNotaCredito = 0;
        $totalNotaDebito = 0;
        
        $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
        
        while ($row = $paging->fetchResultado()){
            
            $contador++;
            $detalle_bancos_id_detalle_banco = $row['detalle_bancos_id_detalle_banco'];
            $detalle_bancos_valor = $row['detalle_bancos_valor'];
            $detalle_bancos_tipo_documento = $row['detalle_bancos_tipo_documento'];

            if($detalle_bancos_tipo_documento == "Cheque"){
                $totalCheque = $totalCheque + $detalle_bancos_valor;
            }
            if($detalle_bancos_tipo_documento == "Deposito"){
                $totalDeposito = $totalDeposito + $detalle_bancos_valor;
            }
            if($detalle_bancos_tipo_documento == "Nota de Credito"){
                $totalNotaCredito = $totalNotaCredito + $detalle_bancos_valor;
            }
            if($detalle_bancos_tipo_documento == "Nota de Debito"){
                $totalNotaDebito = $totalNotaDebito + $detalle_bancos_valor;
            }
            if($detalle_bancos_tipo_documento == "Transferencia"){
                $totalTransferencia = $totalTransferencia+ $detalle_bancos_valor;
            }
            if($detalle_bancos_tipo_documento == "Transferenciac"){
                $totalTransferenciaC = $totalTransferenciaC + $detalle_bancos_valor;
            }
            
    if($contador%2==0){
    ?>
    
        <tr class="odd" id="tr_<?=$row['detalle_bancos_id_detalle_banco']?>">
            <td><?=$row['detalle_bancos_tipo_documento']?></td>
            <td><?=number_format($row['detalle_bancos_valor'], 2, ',', ' ');?></td>
            <td><?=$row['detalle_bancos_numero_documento']?></td>
            <?php
            $detalle_bancos_estado = $row['detalle_bancos_estado'];
            if($detalle_bancos_estado == "Conciliado"){
                ?><td><input type="checkbox" checked name="checkVisto" id="checkVisto"  value="<?=$row['detalle_bancos_id_detalle_banco']?>" onchange="" /></td><?php
            }else{
                ?><td><input type="checkbox" name="checkVisto" id="checkVisto"  value="<?=$row['detalle_bancos_id_detalle_banco']?>" onchange="" /></td><?php
            }
            ?>
            
            <td><?=$row['detalle_bancos_fecha']?></td>
            <td><?=$row['detalle_bancos_fecha_cobro']?></td>
            <td><?=$row['detalle_bancos_fecha_vencimiento']?></td>
            <td><?=$row['detalle_bancos_detalle']?></td>
            
        </tr>
         <?php }

       if($contador%2==1){
       ?>
        <tr  class="even" id="tr_<?=$row['detalle_bancos_id_detalle_banco']?>">

            <td><?=$row['detalle_bancos_tipo_documento']?></td>
            <td><?=number_format($row['detalle_bancos_valor'], 2, ',', ' ');?></td>
            <td><?=$row['detalle_bancos_numero_documento']?></td>
            <?php
            $detalle_bancos_estado = $row['detalle_bancos_estado'];
            if($detalle_bancos_estado == "Conciliado"){
                ?><td><input type="checkbox" checked name="checkVisto" id="checkVisto" value="<?=$row['detalle_bancos_id_detalle_banco']?>" onchange="" /></td><?php
            }else{
                ?><td><input type="checkbox" name="checkVisto" id="checkVisto"  value="<?=$row['detalle_bancos_id_detalle_banco']?>" onchange="" /></td><?php
            }
            ?>
            <td><?=$row['detalle_bancos_fecha']?></td>
            <td><?=$row['detalle_bancos_fecha_cobro']?></td>
            <td><?=$row['detalle_bancos_fecha_vencimiento']?></td>
            <td><?=$row['detalle_bancos_detalle']?></td>
        </tr>
        <?php }
     }
     
       
       ?>
    <input type="hidden" name="txtTotalCheque"          id="txtTotalCheque"         value="<?php echo $totalCheque; ?>" />
    <input type="hidden" name="txtTotalDeposito"        id="txtTotalDeposito"       value="<?php echo $totalDeposito; ?>" />
    <input type="hidden" name="txtTotalNotaDebito"      id="txtTotalNotaDebito"     value="<?php echo $totalNotaCredito; ?>" />
    <input type="hidden" name="txtTotalNotaCredito"     id="txtTotalNotaCredito"    value="<?php echo $totalNotaDebito; ?>" />
    <input type="hidden" name="txtTotalTransferencia"   id="txtTotalTransferencia"  value="<?php echo $totalTransferencia; ?>" />
    <input type="hidden" name="txtTotalTransferenciaC"  id="txtTotalTransferenciaC"  value="<?php echo $totalTransferenciaC; ?>" />
    
    <input type="hidden" name="txtNumeroFilas" id="txtNumeroFilas" value="<?php echo $numero_filas; ?>" />
    

    </tbody>

    
</table>

