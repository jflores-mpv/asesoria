<?php 
	session_start();
	require_once "../conexion2.php";
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
 ?>
 
 <div class="row">
	<div class="col-sm-12 table-responsive">

     <table  class="table table-bordered table-responsive" >
        <thead>
            <tr>
            <td>#</td>
            <td>Sustento</td>
            <td>Id</td>
            <td>Número</td>
            <td>Comprobante</td>
            <td>Parte rel</td>
            <td>Tipo</td>
            <td>Razón Social</td>
            <td>Fecha Compra</td>
            <td>Serie</td>
            <td>Emisión</td>
            <td>Num Fac</td>
            <td>Autorización</td>
            <td>Base S/IVA</td>
            <td>Base 0%</td>
            <td>Base 12%</td>
            <td>Base S/IVA</td>
            <td>ICE</td>
            <td>IVA</td>
            <td>IVA 10%</td>
            <td>IVA 20%</td>
            <td>IVA 30%</td>
            <td>IVA 50%</td>
            <td>IVA 100%</td>
            <td>Pago a residente o no residente</td>
            <td>Tipos de reg��men fiscal del exterior</td>
   <td>         
Pa&iacute;s de residencia o
establecimiento
permanente a qui&eacute;n se
efect&uacute;a el pago r&eacute;gimen
general</td>
<td>Pa&iacute;s de residencia o
establecimiento
permanente a qui&eacute;n se
efect&uacute;a el pago para&iacute;so
fiscal</td>
<td>Denominaci&oacute;n del r&eacute;gimen
fiscal preferente o
jurisdicci&oacute;n de menor
imposici&oacute;n</td>
<td>Pa&iacute;s de residencia o
establecimiento
permanente a qui&eacute;n se
efect&uacute;a el pago</td>
<td>Aplica Convenio de Doble
Tributaci&oacute;n en el pago </td>
<td>Pago al exterior sujeto a
retenci&oacute;n en aplicaci&oacute;n a la
norma legal</td>
<td>El pago es a un r&eacute;gimen
fiscal preferente o de
menor imposici&oacute;n?</td>
            
</tr>
        </thead>
        <tbody>
			<?php 

			{
						$sql="SELECT numero_factura_compra,codSustento,proveedores.rbCaracterIdentificacion, 
						proveedores.ruc,compras.tipoComprobante,parteRel, id_tipo_proveedor,proveedores.nombre_comercial,
						fecha_compra,numSerie,txtEmision,txtNum,autorizacion, sub_total*id_iva/100 
						
						
						from compras,proveedores
					
						
						where compras.id_empresa=1479 and compras.id_proveedor=proveedores.id_proveedor order by numero_factura_compra  " ;

				}

				$result=mysqli_query($conexion,$sql);
				while($ver=mysqli_fetch_row($result)){ 

					$datos=     $ver[0]."||".
					            $ver[1]."||".
					            $ver[2]."||".
					            $ver[3]."||".
					            $ver[4]."||".
					            $ver[5]."||".
					            $ver[6]."||".
					            $ver[7]."||".
					            $ver[8]."||".
					            $ver[9]."||".
					            $ver[10]."||".
					            $ver[11]."||".
					            $ver[11];
			 ?>
 

            <tr>
                <td><?php echo $ver[0] ?></td>
                <td><?php echo $ver[1] ?></td>
                <td><?php echo $ver[2] ?></td>
                <td><?php echo $ver[3] ?></td>
                
                <td><?php echo $ver[4] ?></td>
                <td><?php echo $ver[5] ?></td>
                <td><?php echo $ver[6] ?></td>
                <td><?php echo $ver[7] ?></td>
                <td><?php echo $ver[8] ?></td>
                <td><?php echo $ver[9] ?></td>
                <td><?php echo $ver[10] ?></td>
                <td><?php echo $ver[11] ?></td>
                <td><?php echo $ver[12] ?></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><?php echo $ver[13] ?></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                 <td></td>
            </tr>

 
 			<?php 
		} ?>
			 
			             
        </tbody>
    </table>
</div>
</div>