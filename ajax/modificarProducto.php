<?php
	require_once('../ver_sesion.php');

	//Start session
	session_start();
		
	//Include database connection details
	require_once('../conexion.php');
	   $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
	    $emision_ambiente= $_SESSION['emision_ambiente'] ;
        $emision_tipoFacturacion=$_SESSION['emision_tipoFacturacion'];
        $emision_tipoEmision=$_SESSION['emision_tipoEmision'] ;
?>

<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Modificar Producto</title>
</head>

<body> 

<?php
        $id_producto=$_POST['id_producto'];
        $sql="SELECT
         categorias.`id_categoria` AS categorias_id_categoria,
         categorias.`categoria` AS categorias_categoria,

         productos.`id_producto` AS productos_id_producto,
         productos.`producto` AS productos_producto,
         productos.`existencia_minima` AS productos_existencia_minima,
         productos.`existencia_maxima` AS productos_existencia_maxima,
         productos.`stock` AS productos_stock,
         productos.`costo` AS productos_costo,
         productos.`grupo` AS productos_grupo,
         productos.`ganancia1` AS productos_ganancia1,
         productos.`ganancia2` AS productos_ganancia2,
         productos.`id_categoria` AS productos_id_categoria,
         productos.`id_proveedor` AS productos_id_proveedor,
         productos.`promocion`  AS productos_promocion,
         productos.`codigo`     AS productos_codigo,
         productos.`codPrincipal` AS CodigoPrin,
         productos.`codAux`     AS CodigoAux,
         productos.`iva`        AS productos_iva,
         productos.`ICE`        AS productos_ICE,
         productos.`IRBPNR`     AS productos_IRBPNR,
         productos.`produccion` AS productos_produccion,
         productos.`detalle`    AS productos_detalle,
         productos.`marca`      AS productos_marca,
         productos.`modelo`     AS productos_modelo,
         productos.`tipo`       AS productos_tipo,
         productos.`color`      AS productos_color,
         productos.`precio1`    AS productos_precio1,
         productos.`precio2`    AS productos_precio2,
         productos.`precio3`    AS productos_precio3,
         productos.`precio4`    AS productos_precio4,
         productos.`precio5`    AS productos_precio5,
         productos.`precio6`    AS productos_precio6,
         productos.`caducidad`        AS productos_caducidad,
         productos.`lote`        AS productos_lote,
           productos.`proyecto`        AS productos_proyecto,
         centro_costo.`id_centro_costo` AS centro_costo_id,
         centro_costo.`descripcion` AS  centro_costo_descripcion,
  impuestos.iva as impuestos_iva
    FROM
         `categorias` categorias 
         
         RIGHT JOIN `productos` productos ON categorias.`id_categoria` = productos.`id_categoria`
          LEFT JOIN impuestos  on impuestos.id_iva = productos.iva
         LEFT JOIN `centro_costo` centro_costo ON centro_costo.`id_centro_costo` = productos.`grupo`
   
         WHERE productos.`id_producto`='".$id_producto."';";
         
// echo $sql;

        $result=mysql_query($sql);
         while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
             {
                 $precio_con_iva =$row['productos_precio1'] + ( $row['productos_precio1']* ($row['impuestos_iva']/100));
                 if (is_nan($precio_con_iva)) {
                $precio_con_iva = $row['productos_precio1'];
                } 
         ?>

<div class="modal-header">
    <h4>Aqu&iacute; puedes modificar productos o servicios</h4>   
</div>

    <form name="form" id="form" method="post" action="javascript: guardarModificarProducto(2);" >

        <div id="mensaje1" ></div>
            <input type="hidden" name="txtIdProducto" value="<?php echo $row['productos_id_producto']; ?>" />
            <input type="hidden" name="txtIdDetalle" value="<?php echo $row['detalles_id_detalle']; ?>" />
    
    <div class="modal-body row">
        
        <div class="col-lg-3">
            <div class="row">

                    <input type="hidden" tabindex="1" maxlength="20" required="required" id="txtCodigoAnterior" 
                  name="txtCodigoAnterior"   
                    value="<?php echo $row['productos_codigo']; ?>" /> 
                
                <div class="col-lg-12">
                    <label for="cmbTipoCompra" class="form-label">C&oacute;digo</label>
                    <input type="text" tabindex="1" maxlength="20" required="required" id="txtCodigo" class="form-control fs-4 p-1 required" name="txtCodigo"   
                    value="<?php echo $row['productos_codigo']; ?>" /> 
                </div>
                <div class="col-lg-12">
                    <label for="cmbTipoCompra" class="form-label">C&oacute;digo Principal</label>
                    <input type="text" tabindex="1" maxlength="20"  id="CodigoPrin" class="form-control fs-4 p-1 " name="CodigoPrin"   
                     value="<?php echo $row['CodigoPrin']; ?>" /> 
                </div>
                <div class="col-lg-12">
                    <label for="cmbTipoCompra" class="form-label">C&oacute;digo Auxiliar</label>
                    <input type="text" tabindex="1" maxlength="20" id="CodigoAux" class="form-control fs-4 p-1 " name="CodigoAux"   
                     value="<?php echo $row['CodigoAux']; ?>" /> 
                </div>
            </div>
        </div>

    <div class="col-lg-6 p-2 border-end">
        <div class="row">
            <div class="col-lg-12">
                    <label for="txtProducto" class="form-label">Nombre Producto o Servicio</label>
              <input type="text" tabindex="2" maxlength="200" style="text-transform: capitalize;" required="required" id="txtProducto" class="form-control fs-4 p-1 required"
    value="<?php echo htmlspecialchars($row['productos_producto'], ENT_QUOTES, 'UTF-8'); ?>" name="txtProducto"/>

                    <div id="noRepetirProducto"></div>
            </div>
            <div class="col-lg-12 mt-3">
                    <label for="txtProducto" class="form-label">Detalle</label>
                    <textarea type="text" tabindex="3" rows="2" id="txtDetalleProducto"  class="form-control" name="txtDetalleProducto" ><?php echo $row['productos_detalle']; ?>
                    </textarea>
            </div>
            <div class="col-lg-6">
                    <label for="txtMarca" class="form-label">Marca</label>
                    <input type="text" tabindex="2" maxlength="150" style="text-transform: capitalize;" value="<?php echo $row['productos_marca']; ?>"  id="txtMarca"  
                    class="form-control fs-4 p-1 required"  name="txtMarca" />
                 
            </div>
            <div class="col-lg-6">
                    <label for="txtModelo" class="form-label">Modelo</label>
                    <input type="text" tabindex="2" maxlength="150" style="text-transform: capitalize;"  value="<?php echo $row['productos_modelo']; ?>" id="txtModelo"  class="form-control fs-4 p-1 required" 
                    name="txtModelo" />
                   
            </div>
            <div class="col-lg-6">
                    <label for="txtTipo" class="form-label">Tipo</label>
                    <input type="text" tabindex="2" maxlength="150" style="text-transform: capitalize;"  value="<?php echo $row['productos_tipo']; ?>" id="txtTipo"  class="form-control fs-4 p-1 required" 
                    name="txtTipo"/>
                    
            </div>
            <div class="col-lg-6">
                    <label for="txtColor" class="form-label">Color</label>
                    <input type="text" tabindex="2" maxlength="150" style="text-transform: capitalize;" value="<?php echo $row['productos_color']; ?>"  id="txtColor"  class="form-control fs-4 p-1 required" 
                    name="txtColor"/>
                  
            </div>
             <div class="col-lg-6">
                    <label for="txtFechaCaducidad" class="form-label">Caducidad</label>
                    <input type="text" tabindex="2" maxlength="150" style="text-transform: capitalize;"  id="txtFechaCaducidad"  class="form-control fs-4 p-1 " 
                    name="txtFechaCaducidad"  value="<?php echo $row['productos_caducidad']; ?>"	onClick="displayCalendar(txtFechaCaducidad,'yyyy-mm-dd',this)"/>
                    
            </div>
            <div class="col-lg-6" style="display:none">
                    <label for="txtLote" class="form-label">Lote</label>
                    <input type="text" tabindex="2" maxlength="20" style="text-transform: capitalize;"  id="txtLote"  class="form-control fs-4 p-1 " value="<?php echo $row['productos_lote']; ?>"
                    name="txtLote"/>
                  
            </div>
            
            <!--<div class="col-lg-6">-->
            <!--        <label for="txtColor" class="form-label">Stock</label>-->
                    <input type="hidden" tabindex="2" maxlength="150"  value="<?php echo $row['productos_stock']; ?>"  id="txtStock"  class="form-control fs-4 p-1 required" name="txtStock"/>
            <!--</div>-->
            
        </div>
                    <div class="row">
            <div class="switch-field my-3">
            <?php
            $sqlImpuestos="SELECT `id_iva`, `iva`, `estado`, `id_empresa`, `id_plan_cuenta`, `codigo` FROM `impuestos` WHERE id_empresa='".$sesion_id_empresa."' ";
            $resultImpuestos = mysql_query($sqlImpuestos);
            while($rowImp = mysql_fetch_array($resultImpuestos) ){
                ?>
                    <input type="radio" id="radio_one_iva<?php echo $rowImp['id_iva'] ?>" name="switch-one"  onclick="calculariva(precioIva.value);" value="<?php echo $rowImp['id_iva'] ?>" data-valor="<?php echo $rowImp['iva'] ?>" <?php if($row['productos_iva']==$rowImp['id_iva']){?> checked  <?php } ?>/>
                        <label for="radio_one_iva<?php echo $rowImp['id_iva'] ?>">Tarifa <?php echo $rowImp['iva']."-".$rowImp['codigo'] ?> %</label>
                <?php
            }
            ?>
              
            </div>   
          </div> 
                <div class="row">
                    <div class="switch-field my-3">
                        <input type="radio" id="radio-one-ice" name="switch-two" value="Si" />
                        <label for="radio-one-ice">ICE</label>
                        <input type="radio" id="radio-two-ice" name="switch-two" value="No" checked />
                        <label for="radio-two-ice">NO ICE</label>
                    </div>
                </div>
                <div class="row">
                    <div class="switch-field my-3">
                        <input type="radio" id="radio-one-IRBPNR" name="switch-three" value="Si" />
                        <label for="radio-one-IRBPNR">IRBPNR</label>
                        <input type="radio" id="radio-two-IRBPNR" name="switch-three" value="No" checked />
                        <label for="radio-two-IRBPNR">NO IRBPNR</label>
                    </div>
                </div>
            </div>
       

        <div class="row" style="display:none;" >
            <div class="col-lg-6">
                <label>&Aacute;rea o grupo</label>
            </div>
            <div class="col-lg-6">
                <select tabindex="1" id="cmbCategoria" class="form-control required" name="cmbCategoria"  >
                <?php
                $sqlc="Select * From categorias where id_empresa='".$sesion_id_empresa."';";
                $resultc=mysql_query($sqlc);
                 while($rowc=mysql_fetch_array($resultc))//permite ir de fila en fila de la tabla
                     { ?> <option value="<?=$rowc['id_categoria']; ?>" <?php if($row['categorias_id_categoria']==$rowc['id_categoria']){ ?> selected <?php } ?> ><?=$rowc['categoria']; ?></option><?php } ?>
                </select>
            </div>
        </div>
        
    
    <div class="col-lg-3">
            
        <div class="row">  
         <div class="col-lg-6 mt-3">
                    <label for="txtPrecioCosto" class="form-label">Precio Costo</label>
                </div>    
             <div class="col-lg-6 mt-3">        
                    <input type="text" tabindex="4" maxlength="10"  id="txtPrecioCosto"  class="form-control"  name="txtPrecioCosto" value="<?php echo $row['productos_costo'] ?>"/>
            </div>
                <div class="col-lg-6 mt-3">
                    <label for="txtPrecioVenta1" class="form-label">Precio con IVA</label>
            </div>
             <div class="col-lg-6 mt-3">        
                    <input type="text" tabindex="4" maxlength="10"  id="precioIva"  class="form-control"  name="precioIva" value="<?php echo $precio_con_iva;?>" onKeyUp="calculariva(this.value);" />
            </div>
            
           
            <label>Precios Venta</label>
            <div class="col-lg-6 mt-3">
                    <label for="txtPrecioVenta1" class="form-label">1</label>
            </div>
            <div class="col-lg-6 mt-3">        
                    <input type="text" tabindex="4" maxlength="10"  id="txtPrecioVenta1"  class="form-control"  name="txtPrecioVenta1"  value="<?php echo $row['productos_precio1'] ?>" />
            </div>
            <div class="col-lg-6 mt-3">
                    <label for="txtPrecioVenta2" class="form-label">2</label>
            </div>
            <div class="col-lg-6 mt-3">        
                <input type="text" tabindex="4" maxlength="10" id="txtPrecioVenta2"  class="form-control"  name="txtPrecioVenta2"  value="<?php echo $row['productos_precio2'] ?>"/>
            </div>
            <div class="col-lg-6 mt-3">
                <label for="txtPrecioVenta3" class="form-label">3</label>
            </div>
            <div class="col-lg-6 mt-3">
                <input type="text" tabindex="4" maxlength="10"  id="txtPrecioVenta3"  class="form-control"  name="txtPrecioVenta3"  value="<?php echo $row['productos_precio3'] ?>" />
            </div>
            <div class="col-lg-6 mt-3">
                <label for="txtPrecioVenta4" class="form-label">4</label>
            </div>
            <div class="col-lg-6 mt-3">
                <input type="text" tabindex="4" maxlength="10" id="txtPrecioVenta4"  class="form-control"  name="txtPrecioVenta4"  value="<?php echo $row['productos_precio4'] ?>"/>
            </div>
            <div class="col-lg-6 mt-3">
                <label for="txtPrecioVenta5" class="form-label">5</label>
            </div>
            <div class="col-lg-6 mt-3">        
                <input type="text" tabindex="4" maxlength="10"  id="txtPrecioVenta5"  class="form-control"  name="txtPrecioVenta5"  value="<?php echo $row['productos_precio5']?>"/>
            </div>
            <div class="col-lg-6 mt-3">
                <label for="txtPrecioVenta6" class="form-label">6</label>
            </div>
            <div class="col-lg-6 mt-3">
                    <input type="text" tabindex="4" maxlength="10"  id="txtPrecioVenta6"  class="form-control"  name="txtPrecioVenta6"  value="<?php echo $row['productos_precio6']?>" />
            </div>
    
        </div>    
            

    <div class="row">
            <div class="col-lg-12 ">
                <div class="switch-field ">
                    <input type="radio" id="radio-one-pro" name="switch-four" value="Si" class="p-4" />
                    <label for="radio-one-pro">Combo</label>
                    <input type="radio" id="radio-two-pro" name="switch-four" value="No" class="p-4" checked  />
                    <label for="radio-two-pro">Unidad</label>
                    <input type="radio" id="radio-three-pro" name="switch-four" value="No" class="p-4" checked />
                    <label for="radio-three-pro">Otros</label> 
                </div>
            </div> 
            <div class="col-lg-12">
<label>Area</label>
<select tabindex="1" id="switch-five" class="form-control required" name="switch-five" required="required">
    <?php

    $selectedOption = $row['centro_costo_id'];

    $sqlc = "SELECT id_centro_costo, descripcion FROM centro_costo WHERE empresa = '".$sesion_id_empresa."' AND id_centro_costo != '".$selectedOption."'";
    
    $resultc = mysql_query($sqlc);

    echo "<option value='$selectedOption' selected>".$row['centro_costo_descripcion']."</option>";

    while ($rowc = mysql_fetch_array($resultc)) {
        echo "<option value='".$rowc['id_centro_costo']."'>".$rowc['descripcion']."</option>";
    }
    
    ?>
</select>

            </div>
             <?php        
            $dominio = $_SERVER['SERVER_NAME'];
            
            if( $dominio=='dcacorp.com.ec' or $dominio=='www.dcacorp.com.ec'){
            ?> 
             <div class="col-lg-12">
                <label>Proyecto</label>
                <select tabindex="1" id="proyecto" class="form-control required" name="proyecto" required="required" >
                      <option value="0">Sin selecci&oacute;n</option>
                 <?php
                $sqlp="SELECT `id_proyecto`, `nombre_proyecto`, `id_empresa` FROM `proyectos` WHERE  id_empresa='".$sesion_id_empresa."' ;";
             
                $resultp=mysql_query($sqlp);
                 while($rowp=mysql_fetch_array($resultp))//permite ir de fila en fila de la tabla
                     { ?> <option value="<?=$rowp['id_proyecto']; ?>"<?php if( $row['productos_proyecto'] == $rowp['id_proyecto']){ ?> selected <?php } ?> ><?=$rowp['nombre_proyecto']; ?></option><?php } ?>
                </select>
            </div>
            <?php      }
            ?>
        </div>
    </div>
    
       
    <div class="modal-footer mt-3">
            <button type="button" class="btn btn-default" onClick="fn_cerrar()">Cerrar</button>
            <button class="btn btn-success" value="Guardar" type="submit" id="submit"  name="btnIngresar">Guardar </button>
    </div>
    

        <?php      }  ?>
    </form>
</body>	
            <script>
            //  function calculariva(valor){
            //     valorconiva = valor ;
            //     valorIva = valorconiva/1.15 ;
            //     document.getElementById('txtPrecioVenta1').value=valorIva.toFixed(2);

            //  }
           
             
      
             
             function calculariva() {
  // Get all radio buttons with the name "switch-one"
  const radios = document.querySelectorAll('input[name="switch-one"]');

  // Find the selected radio button
  let selectedRadio;
  for (const radio of radios) {
    if (radio.checked) {
      selectedRadio = radio;
      break;
    }
  }


  if (selectedRadio) {
      let valorconiva = parseFloat(document.getElementById('precioIva').value);
        iva = selectedRadio.dataset.valor; ;
        valorIva = valorconiva/(1+(iva/100)) ;
        if (isNaN(valorIva)) {
        document.getElementById('txtPrecioVenta1').value = valorconiva.toFixed(2);
        } else {
             document.getElementById('txtPrecioVenta1').value=valorIva.toFixed(2);
          
        }
        

  } 
 
                
}

// Example usage

         </script>
</html>