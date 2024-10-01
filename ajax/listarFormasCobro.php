<?php


        //Start session
	session_start();

        $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];
	include "../conexion.php";
	$contador = 0;

	$sql = "SELECT
		enlaces_compras.`id` AS enlaces_compras_id,
		enlaces_compras.`nombre` AS enlaces_compras_nombre,
		enlaces_compras.`cuenta_contable` AS enlaces_compras_id_plan_cuenta,
		enlaces_compras.`id_empresa` AS enlaces_compras_id_empresa,
		enlaces_compras.`tipo_cpra` AS enlaces_compras_tipo_mov_cpra,
		enlaces_compras.`porcentaje` AS enlaces_compras_porcentaje,
		enlaces_compras.`codigo_sri` AS enlaces_compras_codigo_sri,
		enlaces_compras.`codigo` AS enlaces_compras_codigo,
		plan_cuentas.`id_plan_cuenta` AS plan_cuentas_id_plan_cuenta,
		plan_cuentas.`codigo` AS plan_cuentas_codigo,
		plan_cuentas.`nombre` AS plan_cuentas_nombre,
		plan_cuentas.`clasificacion` AS plan_cuentas_clasificacion,
		plan_cuentas.`tipo` AS plan_cuentas_tipo,
		plan_cuentas.`categoria` AS plan_cuentas_categoria,
		plan_cuentas.`nivel` AS plan_cuentas_nivel,
		plan_cuentas.`total` AS plan_cuentas_total,
		plan_cuentas.`id_empresa` AS plan_cuentas_id_empresa,
		plan_cuentas.`cuenta_banco` AS plan_cuentas_cuenta_banco,
		tipo_movim_cpra.`id_tipo_mov_cpra` AS tipo_movimientos_id_tipo_movimiento,
        tipo_movim_cpra.`nombre` AS tipo_movimientos_nombre,
        tipo_movim_cpra.`id_empresa` AS tipo_movimientos_id_empresa
	FROM
     `plan_cuentas` plan_cuentas INNER JOIN `enlaces_compras` enlaces_compras 
		ON plan_cuentas.`id_plan_cuenta` = enlaces_compras.`cuenta_contable`
		INNER JOIN `tipo_movim_cpra` tipo_movim_cpra 
		ON enlaces_compras.`tipo_cpra` = tipo_movim_cpra.`id_tipo_mov_cpra` 
        where enlaces_compras.`id_empresa`='".$sesion_id_empresa."';";

        $result = mysql_query($sql) or die(mysql_error());
        $numero_filas = mysql_num_rows($result);
	//	echo "numero de filas";
	//	echo $numero_filas;
		
?>
<table id="grillaFormaPago" class="table table-bordered" width="100%">
    <thead>
        <tr>
            <!--<th style="width:15%"><strong>Ide</strong></th>-->
            <th style="width:30%"><strong>Nombre</strong></th>
            <th style="width:10%"><strong>C&oacute;digo</strong></th>
            <th style="width:30%"><strong>Cuenta Contable</strong></th>
            <th style="width:10%"><strong>Tipo movimiento</strong></th>
            <th><strong>Porcentaje</strong></th>
            <th><strong>Codigo</strong></th>
			<th><strong>Codigo Sri</strong></th>
			
           
            <!--<th><a href="javascript: listar_formas_cobro();" title="Actalizar">-->
            
			<button type="button" class="btn btn-default" aria-label="Left Align">
				<span class="glyphicon glyphicon-repeat" aria-hidden="true"></span>
			</button>
                
            </a></th>
            <th width="3%"><a href="javascript: agregarFormaCobros(<?php echo $numero_filas; ?>);" title="Agregar nueva fila">
            <button type="button" class="btn btn-default" aria-label="Left Align">
  <span class="fa fa-plus" aria-hidden="true"></span>
</button>
            
            </a></th>
        </tr>
    </thead>
    <tbody>
    <?php     
        if($numero_filas == 0){
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
				<input style="width: 100%" type="hidden" name="txtIdFormaCobro<?php echo $contador;?>" id="txtIdFormaCobro<?php echo $contador;?>" value="<?php echo $row['enlaces_compras_id'];?>" class="form-control" disabled="true" />    
				<input type="hidden" readonly="readonly" id="txtIdCuenta<?php echo $contador;?>" name="txtIdCuenta<?php echo $contador;?>" value="<?php echo $row['plan_cuentas_id_plan_cuenta']?>" class="text_input3"/> 
                <input style="width: 100%" type="text"  name="txtNombre<?php echo $contador;?>" id="txtNombre<?php echo $contador;?>" maxlength="200" value="<?php echo $row['enlaces_compras_nombre']?>" style="text-transform: capitalize; " class='form-control' required="required" onkeyup=""/></td>

                <td >
                    <input style="width: 100%;" type="search" id="txtCodigo<?php echo $contador;?>" name="txtCodigo<?php echo $contador;?>" class="form-control" value="<?php echo $row['plan_cuentas_codigo']?>" onclick="lookup2(this.value,<?php echo $contador;?>, 5);" onKeyUp="lookup2(this.value,<?php echo $contador;?>, 5);"  autocomplete="off"  placeholder="Buscar.."   />
                     <div class="suggestionsBox" id="suggestions<?php echo $contador;?>" style="display: none; width: 300px "> <div class="suggestionList" id="autoSuggestionsList<?php echo $contador;?>"> &nbsp; </div> </div>
                </td>
                <td >
                    <input type="search" id="txtCuenta2<?php echo $contador;?>" name="txtCuenta2<?php echo $contador;?>" value="<?php echo $row['plan_cuentas_nombre']." ".$row['plan_cuentas_cuenta_banco']?>"  style="margin: 0px; width: 100%;" class="form-control" autocomplete="off"   />
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
                    <input type="text" id="txtCodigoSri2<?php echo $contador;?>" name="txtCodigoSri2<?php echo $contador;?>" value="<?php echo $row['enlaces_compras_codigo']?>"  style="margin: 0px; width: 100%;" class="form-control" autocomplete="off"   />
                </td>
               
               
			    <td >
                    <input type="text" id="txtCodigoSri<?php echo $contador;?>" name="txtCodigoSri<?php echo $contador;?>" value="<?php echo $row['enlaces_compras_codigo_sri']?>"  style="margin: 0px; width: 100%;" class="form-control" autocomplete="off"   />
                </td>
               
                <td>
                      <a href="javascript: modificar_forma_cobro(<?php echo $row['enlaces_compras_id']?>, 2, <?php echo $contador;?>);" title="Editar"><span class='fa fa-edit' aria-hidden='true'></a>
                </td>
                <td>
                    <a href="javascript: eliminar_forma_cobro(<?php echo $row['enlaces_compras_id']?>, 7);" title="Eliminar"><span class='fa fa-trash' aria-hidden='true'></a>
                </td>

            </tr>
            <?php
         }

       }
    ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="10"><strong >Cantidad: </strong><span  id="span_cantidadFormaCobro"></span> filas.</td>
        </tr>
    </tfoot>
    
</table>

