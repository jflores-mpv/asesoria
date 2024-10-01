<?php

    include "../conexion.php";
        //Start session
	session_start();

    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];
	
	$contador = 0;

	$sql = "SELECT
		enlaces_compras.`id` AS enlaces_compras_id,
		enlaces_compras.`nombre` AS enlaces_compras_nombre,
		enlaces_compras.`cuenta_contable` AS enlaces_compras_id_plan_cuenta,
		enlaces_compras.`id_empresa` AS enlaces_compras_id_empresa,
		enlaces_compras.`tipo_cpra` AS enlaces_compras_tipo_mov_cpra,
		enlaces_compras.`porcentaje` AS enlaces_compras_porcentaje,
		enlaces_compras.`codigo_sri` AS enlaces_compras_codigo_sri,
		tipo_movim_cpra.`id_tipo_mov_cpra` AS tipo_movimientos_id_tipo_movimiento,
        tipo_movim_cpra.`nombre` AS tipo_movimientos_nombre,
        tipo_movim_cpra.`id_empresa` AS tipo_movimientos_id_empresa
	FROM
        `enlaces_compras` enlaces_compras 
		INNER JOIN `tipo_movim_cpra` tipo_movim_cpra 
		ON enlaces_compras.`tipo_cpra` = tipo_movim_cpra.`id_tipo_mov_cpra` 
        where enlaces_compras.`id_empresa`='".$sesion_id_empresa."';";

        $result = mysql_query($sql) or die(mysql_error());
        $numero_filas = mysql_num_rows($result);

       // echo $sql;
	//	echo "numero de filas";
	//	echo $numero_filas;
		
?>
<table id="grillaFormaPago2" class="table table-bordered" width="100%">
    <thead>
        <tr>
            <th style="width:15%"><strong>Ide</strong></th>
            <th style="width:30%"><strong>Nombre</strong></th>
            <th style="width:10%"><strong>Tipo movimiento</strong></th>
            <th><strong>Porcentaje</strong></th>
			<th><strong>Codigo Sri</strong></th>
			
           
            <th>

                <a href="javascript: listar_formas_cobro2();" title="Actalizar">
            
			<button type="button" class="btn btn-default" aria-label="Left Align">
				<span class="glyphicon glyphicon-repeat" aria-hidden="true"></span>
			</button>
                
            </a>

        </th>
            <th width="3%">

                <a href="javascript: agregarFormaCobros2(<?php echo $numero_filas; ?>);" title="Agregar nueva fila">
            
                    <button type="button" class="btn btn-default" aria-label="Left Align">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </button>
            
                </a>
            </th>
        </tr>
    </thead>
    <tbody>
    <?php     
        if($numero_filas== 0){
            ?><tr>
                <td colspan="6" align="center"><h5>La busqueda no tuvo resultados</h5></td>
            </tr><?php
        }
		else
		{
            while ($row = mysql_fetch_assoc($result)){
            $id_forma_cobro = $row['enlaces_compras_id'];
			//echo "id forma cobro";
			//echo $id_forma_cobro;
            $contador++;

            ?>

            <tr class="even" id="tr_<?php echo $contador;?>">
                <td >
				<input style="width: 100%" type="text" name="txtIdFormaCobro<?php echo $contador;?>" id="txtIdFormaCobro<?php echo $contador;?>" value="<?php echo $row['enlaces_compras_id'];?>" class="form-control" disabled="true" />    
				 </td>
                <td><input style="width: 100%" type="text"  name="txtNombre<?php echo $contador;?>" id="txtNombre<?php echo $contador;?>" maxlength="200" value="<?php echo $row['enlaces_compras_nombre']?>" style="text-transform: capitalize; " class='form-control' required="required" onkeyup=""/>
                </td>

                <td >
                    <select  name="cmbTipoMovimientoFVC<?php echo $contador;?>" id="cmbTipoMovimientoFVC<?php echo $contador;?>" class="form-control" style="margin: 0px; width: 100%;" ondblclick="cargarTipoMovCompra(6, <?php echo $contador;?>)"  >
                        <option value="<?php echo $row['tipo_movimientos_id_tipo_movimiento']?>"><?php echo $row['tipo_movimientos_nombre']?></option>
                    </select>
                </td>
               
			    <td >
                    <input type="text" id="txtPorcentaje<?php echo $contador;?>" name="txtPorcentaje<?php echo $contador;?>" value="<?php echo $row['enlaces_compras_porcentaje']?>"  style="margin: 0px; width: 100%;" class="form-control" autocomplete="off"   />
                </td>
               
			    <td >
                    <input type="text" id="txtCodigoSri<?php echo $contador;?>" name="txtCodigoSri<?php echo $contador;?>" value="<?php echo $row['enlaces_compras_codigo_sri']?>"  style="margin: 0px; width: 100%;" class="form-control" autocomplete="off"   />
                </td>
               
                <td>
                      <a href="javascript: modificar_forma_cobro2(<?php echo $row['enlaces_compras_id']?>, 2, <?php echo $contador;?>);" title="Editar"><span class='glyphicon glyphicon-edit' aria-hidden='true'></a>
                </td>
                <td>
                    <a href="javascript: eliminar_forma_pago(<?php echo $row['formas_pago_id_forma_pago']?>, 3);" title="Eliminar"><span class='glyphicon glyphicon-delete' aria-hidden='true'></a>
                </td>

            </tr>
            <?php
         }

       }
    ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="10"><strong >Cantidad: </strong>
            <span  id="span_cantidadFormaCobro2"></span> filas.</td>
        </tr>
    </tfoot>
    
</table>

