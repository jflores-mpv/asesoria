<?php
	require_once('../ver_sesion.php');

	//Start session
	session_start();	
	//Include database connection details
	require_once('../conexion.php');

    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
    $sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
    $sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];

?>



<html>
<head>
    <title>Formas de cobros</title>
      
    
</head>

<body>
 
<?php
$contador = 0;
	$sql = "SELECT
		centro_costo.`id_centro_costo` AS centro_id,
		centro_costo.`codigo` AS centro_codigo,
		centro_costo.`descripcion` AS centro_descripcion,
		centro_costo.`id_cuenta` AS centro_id_cuenta,
		centro_costo.`tipo` AS centro_tipo,
		centro_costo.`tarifa` AS tarifa,
		centro_costo.`predeterminado` AS predeterminado,
		
		plan_cuentas.`id_plan_cuenta` AS plan_cuentas_id_plan_cuenta,
		plan_cuentas.`codigo` AS plan_cuentas_codigo,
		plan_cuentas.`nombre` AS plan_cuentas_nombre,
		
		
		tipos_compras.`descripcion` AS descripcion
		
		
	FROM
     `centro_costo` centro_costo 
    INNER JOIN `plan_cuentas` plan_cuentas ON plan_cuentas.`id_plan_cuenta` = centro_costo.`id_cuenta`
    INNER JOIN `tipos_compras` tipos_compras ON centro_costo.`tipo` = tipos_compras.`id_tipo_cpra`
    
     where centro_costo.`empresa`='".$sesion_id_empresa."';";

        $result = mysql_query($sql) or die(mysql_error());
        $numero_filas = mysql_num_rows($result);

?>

<table id="grillaCentros" class="table table-hover" >
    <thead>
        <tr>
            <th style="width:10%"><strong>C&oacute;digo</strong></th>
            <th style="width:25%"><strong>Nombre</strong></th>
            <th style="width:15%"><strong>C&oacute;digo</strong></th>
            <th style="width:15%"><strong>Cuenta Contable</strong></th>
            <th style="width:10%"><strong>Tipo</strong></th>
              <?php if ($sesion_tipo_empresa=='HOTEL'){ ?>
            <th style="width:10%"><strong>Tarifa</strong></th>
            <?php } ?>
            <th width="5%">
                <a href="javascript: agregarCentroCosto(<?php echo $numero_filas; ?>);" title="Agregar nueva fila">
                <span class=" btn btn-success" aria-hidden="true">+</span></a>
            </th>
            <th width="5%"></th>
            <th width="10%"></th>
            <th width="5%"></th>
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
            $contador++;

            ?>
            <tr class="even" id="tr_<?php echo $contador;?>">
                <td >
				<input style="width: 100%" type="text" name="txtIdFormaCobro<?php echo $contador;?>" id="txtIdFormaCobro<?php echo $contador;?>" value="<?php echo $row['centro_codigo'];?>" class="form-control"  />    
				<input type="hidden" readonly="readonly" id="txtIdCuentaA<?php echo $contador;?>" name="txtIdCuentaA<?php echo $contador;?>" value="<?php echo $row['centro_id']?>" class="text_input3 bg-success"/> </td>
                
                
                <td><input style="width: 100%" type="text"  name="txtDescripcion<?php echo $contador;?>" id="txtDescripcion<?php echo $contador;?>" maxlength="200" value="<?php echo $row['centro_descripcion']?>" style="text-transform: capitalize; " class='form-control' required="required" onkeyup=""/></td>

                <td >
                    <input style="width: 100%;" type="search" id="txtCodigo<?php echo $contador;?>" name="txtCodigo<?php echo $contador;?>" class="form-control" value="<?php echo $row['plan_cuentas_codigo']?>" onChange="lookup2(this.value,<?php echo $contador;?>, 5);" onKeyUp="lookup2(this.value,<?php echo $contador;?>, 5);"  autocomplete="off"  placeholder="Buscar.."   />
                     <div class="suggestionsBox" id="suggestions<?php echo $contador;?>" style="display: none; width: 300px "> <div class="suggestionList" id="autoSuggestionsList<?php echo $contador;?>"> &nbsp; </div> </div>
                </td>
                <td >
                    <input type="hidden" id="txtIdCuenta<?php echo $contador;?>" name="txtIdCuenta<?php echo $contador;?>" value="<?php echo $row['plan_cuentas_id_plan_cuenta']?>"   />
                    <input type="search" id="txtCuenta2<?php echo $contador;?>" name="txtCuenta2<?php echo $contador;?>" value="<?php echo $row['plan_cuentas_nombre']?>"  style="margin: 0px; width: 100%;" class="form-control" autocomplete="off" onclick='lookup2(this.value,"+nFilasCentro+", 5);' onKeyUp='lookup2(this.value,"+nFilasCentro+", 5);'  />
                </td>
                <td >
                    <!--input type="text" id="tipo<?php echo $contador;?>" name="tipo<?php echo $contador;?>" value="<?php echo $row['plan_cuentas_id_plan_cuenta']?>"   /> -->
            <select tabindex="1" id="cmbTipoCompra<?php echo $contador;?>" class="form-select required" name="cmbTipoCompra<?php echo $contador;?>" required="required" >
                <!--<option type="hidden" class="celeste " value=""><?=$row['descripcion']; ?></option> -->
                
                <?php
			    $sqlc="Select * From tipos_compras ;";
                $resultc=mysql_query($sqlc);
                 while($rowc=mysql_fetch_array($resultc))
                     { ?> 
<option value="<?=$rowc['id_tipo_cpra']; ?>" <?php if ($row['centro_tipo'] == $rowc['id_tipo_cpra']) { echo "selected class='celeste' "; } else { } ?>><?=$rowc['descripcion'];?></option>
                    <?php } ?>
                        
            </select>
                </td>

                 <?php if ($sesion_tipo_empresa=='HOTEL'){ ?>
                <td>
                   <input type="checkbox" class="form-check-input" id="hotel<?php echo $contador;?>" name="hotel<?php echo $contador;?>"  <?php if($row['tarifa']=='1'){ ?> checked <?php } ?> >
                </td>
                <?php } ?>
                
                <td>
                      <a href="javascript: modificar_centro(<?php echo $row['centro_id']?>, 20, <?php echo $contador;?>);" title="Editar"><span class='btn fa fa-edit' aria-hidden='true'></a>
                </td>
                <td>
                        <?php
                      if($row['predeterminado']=='0'){
                        ?>
                         <a href="javascript: area_predeterminada(<?php echo $row['centro_id']?>, 20, <?php echo $contador;?>);" title="Predeterminada"><span class='fa fa-check' aria-hidden='true'></a>
                        <?php
                      }
                      ?>
                </td>
                
                <form method="post" action="#" enctype="multipart/form-data">
                    <td>
                    <input type="file" class="form-control m-1" name="image" id="image">
                     </td>
                     <td>
                    <a onClick="imagenC(<?php echo $row['centro_id'] ?>)" title="Editar"><span class='btn fa fa-image' aria-hidden='true'></a>     
                     </td>
                </form> 
               
                

           <!--     <td> 
                    <a href="javascript: eliminar_forma_pago(<?php echo $row['formas_pago_id_forma_pago']?>, 3);" title="Eliminar"><span class='fa fa-erase' aria-hidden='true'></a>
                </td> -->

            </tr>
            <?php
         }

       }
    ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="10"><strong >Cantidad: </strong><span  id="span_centros"></span> filas.</td>
        </tr>
    </tfoot>
    
</table>

<script>
    function imagenC(idProducto) {
        alert(idProducto);
        var formData = new FormData();
        var files = $('#image')[0].files[0];
        console.log(files);
        formData.append('file',files);
        formData.append('idProducto',idProducto);
        $.ajax({
            url: '../sql/imagenCategoria.php',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log(response);
                if (response != 0) {
                    $(".card-img-top").attr("src", response);
                    alertify.success(response);
                    fn_cerrar();
                } else {
                    alert('Formato de imagen incorrecto.');
                    alertify.danger("Formato Incorrecto");
                }
            }
        });
        return false;
        
    }
</script>

</html>
