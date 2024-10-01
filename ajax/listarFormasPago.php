<?php


        //Start session
	session_start();

        $sesion_id_empresa = $_SESSION["sesion_id_empresa"];

	include "../conexion.php";
	$contador = 0;
	$sql = "SELECT
     formas_pago.`id_forma_pago` AS formas_pago_id_forma_pago,
     formas_pago.`nombre` AS formas_pago_nombre,
     formas_pago.`id_plan_cuenta` AS formas_pago_id_plan_cuenta,
     formas_pago.`id_empresa` AS formas_pago_id_empresa,
     formas_pago.`id_tipo_movimiento` AS formas_pago_id_tipo_movimiento,
     formas_pago.`diario` AS formas_pago_diario,
     formas_pago.`ingreso` AS formas_pago_ingreso,
     formas_pago.`egreso` AS formas_pago_egreso,
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
     tipo_movimientos.`id_tipo_movimiento` AS tipo_movimientos_id_tipo_movimiento,
     tipo_movimientos.`nombre` AS tipo_movimientos_nombre,
     tipo_movimientos.`id_empresa` AS tipo_movimientos_id_empresa
FROM
     `plan_cuentas` plan_cuentas INNER JOIN `formas_pago` formas_pago ON plan_cuentas.`id_plan_cuenta` = formas_pago.`id_plan_cuenta`
     INNER JOIN `tipo_movimientos` tipo_movimientos ON formas_pago.`id_tipo_movimiento` = tipo_movimientos.`id_tipo_movimiento`
     
        where formas_pago.`id_empresa`='".$sesion_id_empresa."';";

        $result = mysql_query($sql) or die(mysql_error());
        $numero_filas = mysql_num_rows($result);

?>
<table id="grillaFormaPago" class="table table-bordered" width="100%" border="1">
    <thead>
        <tr>
            <th ><strong>Id</strong></th>
            <th ><strong>Nombre</strong></th>
            <th><strong>C&oacute;digo</strong></th>
            <th><strong>Cuenta Contable</strong></th>
            <th><strong>Tipo movimiento</strong></th>
            <th><strong>Diario</strong></th>
            <th><strong>Ingreso</strong></th>
            <th><strong>Egreso</strong></th>
            <th><a href="javascript: listar_formas_pago();" title="Actalizar"><button type="button" class="btn btn-default" aria-label="Left Align">
                    <span class="glyphicon glyphicon-repeat" aria-hidden="true"></span></button>
                
            </a></th>
            <th width="3%"><a href="javascript: agregarFormaPagos(<?php echo $numero_filas; ?>);" title="Agregar nueva fila">
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
        }else{
            while ($row = mysql_fetch_assoc($result)){
            $id_forma_pago = $row['formas_pago_id_forma_pago'];
            $contador++;

            ?>
            <tr class="even" id="tr_<?php echo $contador;?>">
                <td width="5%"><input style="width: 100%" type="text" name="txtIdFormaPago<?php echo $contador;?>" id="txtIdFormaPago<?php echo $contador;?>" value="<?php echo $row['formas_pago_id_forma_pago'];?>" class="form-control" disabled="true" />    <input type="hidden" readonly="readonly" id="txtIdCuenta<?php echo $contador;?>" name="txtIdCuenta<?php echo $contador;?>" value="<?php echo $row['plan_cuentas_id_plan_cuenta']?>" class="text_input3"/> </td>
                <td width="35%"><input style="width: 100%" type="text"  name="txtNombre<?php echo $contador;?>" id="txtNombre<?php echo $contador;?>" maxlength="200" value="<?php echo $row['formas_pago_nombre']?>" style="text-transform: capitalize; " class='form-control' required="required" onkeyup=""/></td>

                <td width="15%">
                    <input style="width: 100%;" type="search" id="txtCodigo<?php echo $contador;?>" name="txtCodigo<?php echo $contador;?>" class="form-control" value="<?php echo $row['plan_cuentas_codigo']?>" onclick="lookup2(this.value,<?php echo $contador;?>, 5);" onKeyUp="lookup2(this.value,<?php echo $contador;?>, 5);"  autocomplete="off"  placeholder="Buscar.."   />
                     <div class="suggestionsBox" id="suggestions<?php echo $contador;?>" style="display: none; width: 300px "> <div class="suggestionList" id="autoSuggestionsList<?php echo $contador;?>"> &nbsp; </div> </div>
                </td>
                <td width="25%">
                    <input type="search" id="txtCuenta2<?php echo $contador;?>" name="txtCuenta2<?php echo $contador;?>" value="<?php echo $row['plan_cuentas_nombre']." ".$row['plan_cuentas_cuenta_banco']?>"  style="margin: 0px; width: 100%;" class="form-control" autocomplete="off"   />
                </td>
                <td width="25%">
                    <select  name="cmbTipoMovimientoFVC<?php echo $contador;?>" id="cmbTipoMovimientoFVC<?php echo $contador;?>" class="form-control" style="margin: 0px; width: 100%;" ondblclick="cargarTipoMovimiento(6, <?php echo $contador;?>)"  >
                        <option value="<?php echo $row['tipo_movimientos_id_tipo_movimiento']?>"><?php echo $row['tipo_movimientos_nombre']?></option>
                    </select>
                </td>
                <td align="center">
                    <?php
                    if($row['formas_pago_diario'] == "Si"){
                        ?><input type="checkbox" name="checkDiario<?php echo $contador;?>" id="checkDiario<?php echo $contador;?>" checked value="ON" /><?php
                    }else{
                        ?><input type="checkbox" name="checkDiario<?php echo $contador;?>" id="checkDiario<?php echo $contador;?>" value="ON" /><?php
                    }
                    ?>
                    
                </td>
                <td align="center">
                    <?php
                    if($row['formas_pago_ingreso'] == "Si"){
                        ?><input type="checkbox" name="checkIngreso<?php echo $contador;?>" id="checkIngreso<?php echo $contador;?>" checked value="ON" /><?php
                    }else{
                        ?><input type="checkbox" name="checkIngreso<?php echo $contador;?>" id="checkIngreso<?php echo $contador;?>" value="ON" /><?php
                    }
                    ?>
                </td>
                <td align="center">
                    <?php
                    if($row['formas_pago_egreso'] == "Si"){
                        ?><input type="checkbox" name="checkEgreso<?php echo $contador;?>" id="checkEgreso<?php echo $contador;?>" checked value="ON" /><?php
                    }else{
                        ?><input type="checkbox" name="checkEgreso<?php echo $contador;?>" id="checkEgreso<?php echo $contador;?>" value="ON" /><?php
                    }
                    ?>
                </td>
                <td>
                      <a href="javascript: modificar_forma_pago(<?php echo $row['formas_pago_id_forma_pago']?>, 2, <?php echo $contador;?>);" title="Editar"><span class='fa fa-edit' aria-hidden='true'></a>
                </td>
                <td>
                    <a href="javascript: eliminar_forma_pago(<?php echo $row['formas_pago_id_forma_pago']?>, 3);" title="Eliminar"><span class='fa fa-trash' aria-hidden='true'></a>
                </td>

            </tr>
            <?php
         }

       }
    ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="10"><strong >Cantidad: </strong><span  id="span_cantidadFormaPago"></span> filas.</td>
        </tr>
    </tfoot>
    
</table>

