<?php
	require_once('../ver_sesion.php');

	//Start session
	session_start();
		
	//Include database connection details
	require_once('../conexion.php');
	   $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
	   
	$contador = 0;
	
	$sql = "SELECT * FROM impuestos where id_empresa='".$sesion_id_empresa."'";
        $result = mysql_query($sql) or die(mysql_error());
        $numero_filas = mysql_num_rows($result);

?>
<script type="text/javascript" src="js/listadoFunciones.js"></script>
<form  id="frmImpuestos" name="frmImpuestos" method="POST">



<!---->
<table id="grillaImpuestos" class="table table-hover" width="100%">
    <thead class=" border-bottom bg-light mt-3 border-top">
        <tr>
            <th><strong>Porcentaje</strong></th>
            <th><strong>C&oacute;digo</strong></th>
            <th><strong>Estado</strong></th>
            <td><strong>Cuenta contable</strong></td>
            <th><?php 
    
    if ($_SERVER['SERVER_NAME'] == 'www.contaweb.ec' or $_SERVER['SERVER_NAME'] == 'contaweb.ec' 
    or $_SERVER['SERVER_NAME'] == 'jderp.cloud' or $_SERVER['SERVER_NAME'] == 'www.jderp.cloud' 
    or $_SERVER['SERVER_NAME'] == 'www.esecofi.com' or $_SERVER['SERVER_NAME'] == 'esecofi.com'
    or $_SERVER['SERVER_NAME'] == 'www.jobch-corp.com' or $_SERVER['SERVER_NAME'] == 'jobch-corp.com'
    or $_SERVER['SERVER_NAME'] == 'www.kva-facturacion-electronica.com' or $_SERVER['SERVER_NAME'] == 'kva-facturacion-electronica.com'
    or $_SERVER['SERVER_NAME'] == 'www.dcacorp.com.ec' or $_SERVER['SERVER_NAME'] == 'dcacorp.com.ec'
    or $_SERVER['SERVER_NAME'] == 'www.digsersas.com' or $_SERVER['SERVER_NAME'] == 'digsersas.com'
    or $_SERVER['SERVER_NAME'] == 'www.contricapsas.com' or $_SERVER['SERVER_NAME'] == 'contricapsas.com'
    or $_SERVER['SERVER_NAME'] == 'www.econtweb.com' or $_SERVER['SERVER_NAME'] == 'econtweb.com'
     or $_SERVER['SERVER_NAME'] == 'www.asesoriaempresarialsas.com' or $_SERVER['SERVER_NAME'] == 'asesoriaempresarialsas.com'
    
    ) 
    
    {
    
       // Este es el código HTML que quieres incluir
       $link = '<a href="javascript: agregarImpuestos(' . $numero_filas . ');" title="Agregar nueva fila" class="btn btn-success"><span class="fa fa-plus"></span></a>';
       
       // Ahora incluimos el enlace en la página
       echo $link;
    }
?></th>
            <th width="5%"></th>
        </tr>
    </thead>
    <tbody>
    <?php     
        
        while ($row = mysql_fetch_assoc($result)){
            $id_iva = $row['id_iva'];
            $contador++;
    ?>        
    
    
        <tr class="even" id="tr_<?php echo $contador;?>">
            
            <td class="w-25"><input type="text" name="txtPorcentaje<?php echo $contador;?>" id="txtPorcentaje<?php echo $contador;?>" 
            maxlength="20" value="<?php echo $row['iva']?>" onKeyPress="return precio(event)" class='form-control' required="required"/></td>
            <td class="w-5"><input type="text" name="txtCodigo<?php echo $contador;?>" id="txtCodigo<?php echo $contador;?>" 
            maxlength="20" value="<?php echo $row['codigo']?>" onKeyPress="return precio(event)" class='form-control' required="required"/></td>
            <td class="w-25">
                <select name="txtEstado<?php echo $contador;?>" id="txtEstado<?php echo $contador;?>" class='form-control' >
              
                <option id="Activo" value="Activo" <?php if($row['estado']=='Activo'){ ?> selected <?php } ?> >Activo</option>
                <option id="Inactivo" value="Inactivo"  <?php if($row['estado']=='Inactivo'){ ?> selected <?php } ?> >Inactivo</option></select></td>

            <td>
                <select  tabindex="7" id="cmbCuentaContableI" class="form-control" name="cmbCuentaContableI" required="required">  
            <?php  echo  $sqlp="Select id_plan_cuenta,codigo,nombre,id_empresa From plan_cuentas where plan_cuentas.id_empresa='".$sesion_id_empresa."';";
            $resultp=mysql_query($sqlp); while($rowp=mysql_fetch_array($resultp)) { ?> <option value="<?=$rowp['id_plan_cuenta']; ?>" <?php if($rowp['id_plan_cuenta'] == $row['id_plan_cuenta']){ ?> selected <?php } ?>  ><?=$rowp['codigo'].' '.$rowp['nombre']; ?></option> <?php   } ?></select></td>

            <td><a href="javascript: modificar_impuestos(<?php echo $row['id_iva'];?>, 2, <?php echo $contador;?>);" title="Editar" class="btn btn-warning"><span class="fa fa-edit"></span></a></td>

            <td><a href="javascript: eliminar_impuestos(<?php echo $row['id_iva']?>, 3);" title="Eliminar"><span class="fa fa-trash"></span></a></td>
        </tr>
        <?php 
     }
       ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5"><strong colspan="5">Cantidad: </strong><input type="hidden" name="txtNumeroFila" id="txtNumeroFila" value="" /><span colspan="5" id="span_cantidadImpuestos"></span> filas.</td>
        </tr>
    </tfoot>
    
</table>
<div id="mensajeImpuestos"></div>
</form>

