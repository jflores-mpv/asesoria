<?php
    require_once('../ver_sesion.php');

    //Start session
    session_start();
        
        //Include database connection details
        require_once('../conexion.php');
        $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
        $sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];
        
        $emision_ambiente= $_SESSION['emision_ambiente'] ;
        $emision_tipoFacturacion=$_SESSION['emision_tipoFacturacion'];
        $emision_tipoEmision=$_SESSION['emision_tipoEmision'] ;

?>

<html lang="es-ES">
    
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Nuevo Producto</title>
    <link rel="stylesheet" type="text/css" href="librerias/select2/css/select2.css">
   
</head>
<body> 
<div class="modal-header">
    <h4>Aqu&iacute; puedes registrar nuevos productos o servicios</h4>   
</div>

<form name="form" id="form" method="post" class="row" action="javascript: guardar_producto_servicio(1);" >
    <div class="modal-body row">
        
        <div class="col-lg-3">
            <div class="row">
                <div class="col-lg-12">
                    <label for="cmbTipoCompra" class="form-label">C&oacute;digo</label>
                    <input type="text" tabindex="1" maxlength="20" required="required" id="txtCodigo" class="form-control fs-4 p-1 required" name="txtCodigo"   
                    onkeyup="no_repetir_codigo_producto(txtCodigo,'17')" onclick="no_repetir_codigo_producto(txtCodigo,'17')" /> 
                    <div id="noRepetirCodigoProducto"></div>
                </div>
                <div class="col-lg-12">
                    <label for="cmbTipoCompra" class="form-label">C&oacute;digo Principal</label>
                    <input type="text" tabindex="1" maxlength="20"  id="CodigoPrin" class="form-control fs-4 p-1 " name="CodigoPrin"   
                    onkeyup="no_repetir_codigo_producto(txtCodigo,'17')" onclick="no_repetir_codigo_producto(txtCodigo,'17')" /> 
                    <div id="noRepetirCodigoProducto"></div>
                </div>
                <div class="col-lg-12">
                    <label for="cmbTipoCompra" class="form-label">C&oacute;digo Auxiliar</label>
                    <input type="text" tabindex="1" maxlength="20" id="CodigoAux" class="form-control fs-4 p-1 " name="CodigoAux"   
                    onkeyup="no_repetir_codigo_producto(txtCodigo,'17')" onclick="no_repetir_codigo_producto(txtCodigo,'17')" /> 
                    <div id="noRepetirCodigoProducto"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 p-2 border-end">
            <div class="row">
            <div class="col-lg-12">
                    <label for="txtProducto" class="form-label">Nombre Producto o Servicio</label>
                    <input type="text" tabindex="2" maxlength="150" style="text-transform: capitalize;" required="required" id="txtProducto"  class="form-control fs-4 p-1 required" 
                    name="txtProducto" onkeyup="no_repetir_producto(txtProducto,'4')" onclick="no_repetir_producto(txtProducto,'4')"/>
                    <div id="noRepetirProducto"></div>
            </div>
            <div class="col-lg-12 mt-3">
                    <label for="txtProducto" class="form-label">Detalle</label>
                    <textarea type="text" tabindex="3" maxlength="300" id="txtDetalleProducto"  class="form-control" name="txtDetalleProducto" /></textarea>
            </div>
            <div class="col-lg-6">
                    <label for="txtMarca" class="form-label">Marca</label>
                    <input type="text" tabindex="2" maxlength="150" style="text-transform: capitalize;"  id="txtMarca"  class="form-control fs-4 p-1 required"  name="txtMarca" />
                 
            </div>
            <div class="col-lg-6">
                    <label for="txtModelo" class="form-label">Modelo</label>
                    <input type="text" tabindex="2" maxlength="150" style="text-transform: capitalize;"  id="txtModelo"  class="form-control fs-4 p-1 required" 
                    name="txtModelo" />
                   
            </div>
            <div class="col-lg-6">
                    <label for="txtTipo" class="form-label">Tipo</label>
                    <input type="text" tabindex="2" maxlength="150" style="text-transform: capitalize;"  id="txtTipo"  class="form-control fs-4 p-1 required" 
                    name="txtTipo"/>
                    
            </div>
            <div class="col-lg-6">
                    <label for="txtColor" class="form-label">Color</label>
                    <input type="text" tabindex="2" maxlength="150" style="text-transform: capitalize;"  id="txtColor"  class="form-control fs-4 p-1 required" 
                    name="txtColor"/>
                  
            </div>
           
            <div class="col-lg-6">
                    <label for="txtFechaCaducidad" class="form-label">Caducidad</label>
                    <input type="text" tabindex="2" maxlength="150" style="text-transform: capitalize;"  id="txtFechaCaducidad"  class="form-control fs-4 p-1 " 
                    name="txtFechaCaducidad"  value="<?php echo date("Y-m-d", time()); ?>" 	onClick="displayCalendar(txtFechaCaducidad,'yyyy-mm-dd',this)"/>
                    
            </div>
            <div class="col-lg-6" style="display:none">
                    <label for="txtLote" class="form-label">Lote</label>
                    <input type="text" tabindex="2" maxlength="20" style="text-transform: capitalize;"  id="txtLote"  class="form-control fs-4 p-1 " 
                    name="txtLote"/>
                  
            </div>
           
        </div>
        <div class="row">
            <div class="switch-field my-3">
            <?php
            $sqlImpuestos="SELECT `id_iva`, `iva`, `estado`, `id_empresa`, `id_plan_cuenta`, `codigo` FROM `impuestos` WHERE id_empresa='".$sesion_id_empresa."' ";
            $resultImpuestos = mysql_query($sqlImpuestos);
            while($rowImp = mysql_fetch_array($resultImpuestos) ){
                ?>
                    <input type="radio" id="radio_one_iva<?php echo $rowImp['id_iva'] ?>" name="switch-one" value="<?php echo $rowImp['id_iva'] ?>" data-valor="<?php echo $rowImp['iva'] ?>" onclick="calculariva(precioIva.value);" checked/>
                        <label for="radio_one_iva<?php echo $rowImp['id_iva'] ?>">Tarifa <?php echo $rowImp['iva'] ."-".$rowImp['codigo'] ?> %</label>
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
                     { ?> <option value="<?=$rowc['id_categoria']; ?>"><?=$rowc['categoria']; ?></option><?php } ?>
                </select>
            </div>
        </div>
        
    
    <div class="col-lg-3">
            
        <div class="row">    
         <!--<div class="col-lg-6 mt-3">-->
         <!--           <label for="txtPrecioCosto" class="form-label">Stock</label>-->
         <!--       </div>    -->
         <!--    <div class="col-lg-6 mt-3">        -->
                    <input type="hidden" tabindex="4" maxlength="10"  id="txtStock"  class="form-control text-right"  name="txtStock" value="0.00" />
            <!--</div>-->
            
            <div class="col-lg-6 mt-3">
                    <label for="txtPrecioCosto" class="form-label">Precio Costo</label>
                </div>    
             <div class="col-lg-6 mt-3">        
                    <input type="text" tabindex="4" maxlength="10"  id="txtPrecioCosto"  class="form-control text-right"  name="txtPrecioCosto" value="0.00" />
            </div>
            
             <label>Poner aqui el precio con IVA</label>
            
            <div class="col-lg-6 mt-3">
                    <label for="txtPrecioVenta1" class="form-label">Precio con IVA</label>
            </div>
             <div class="col-lg-6 mt-3">        
                    <input type="text" tabindex="4" maxlength="10"  id="precioIva"  class="form-control text-right"  name="precioIva" value="0.00" onKeyUp="calculariva(this.value);" />
            </div>
            
            <label>Precios Venta</label>
            
            <div class="col-lg-6 mt-3">
                    <label for="txtPrecioVenta1" class="form-label">1</label>
            </div>
            
            <div class="col-lg-6 mt-3">        
                    <input type="text" tabindex="4"   id="txtPrecioVenta1"  class="form-control text-right"  name="txtPrecioVenta1" placeholder="0.00" oninput="validarPrecioVenta(this)"/>
            </div>
            <div class="col-lg-6 mt-3">
                    <label for="txtPrecioVenta2" class="form-label">2</label>
            </div>
            <div class="col-lg-6 mt-3">         
                    <input type="text" tabindex="4"  id="txtPrecioVenta2"  class="form-control text-right"  name="txtPrecioVenta2" placeholder="0.00" oninput="validarPrecioVenta(this)"/>
            </div>
            <div class="col-lg-6 mt-3">
                    <label for="txtPrecioVenta3" class="form-label">3</label>
            </div>
            <div class="col-lg-6 mt-3">
                    <input type="text" tabindex="4"  id="txtPrecioVenta3"  class="form-control text-right"  name="txtPrecioVenta3" placeholder="0.00" oninput="validarPrecioVenta(this)" />
            </div>
            <div class="col-lg-6 mt-3">
                    <label for="txtPrecioVenta4" class="form-label">4</label>
            </div>
            <div class="col-lg-6 mt-3">
                    <input type="text" tabindex="4" id="txtPrecioVenta4"  class="form-control text-right"  name="txtPrecioVenta4" placeholder="0.00" oninput="validarPrecioVenta(this)"/>
            </div>
            <div class="col-lg-6 mt-3">
                    <label for="txtPrecioVenta5" class="form-label">5</label>
            </div>
            <div class="col-lg-6 mt-3">        
                    <input type="text" tabindex="4"   id="txtPrecioVenta5"  class="form-control text-right"  name="txtPrecioVenta5" placeholder="0.00" oninput="validarPrecioVenta(this)" />
            </div>
            <div class="col-lg-6 mt-3">
                    <label for="txtPrecioVenta6" class="form-label">6</label>
            </div>
            <div class="col-lg-6 mt-3">
                    <input type="text" tabindex="4"   id="txtPrecioVenta6"  class="form-control text-right"  name="txtPrecioVenta6" placeholder="0.00" oninput="validarPrecioVenta(this)"/>
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
                <select tabindex="1" id="switch-five" class="form-control required" name="switch-five" required="required" >
                 <?php
                $sqlc="select id_centro_costo,descripcion,tipo from centro_costo where empresa='".$sesion_id_empresa."' ;";
              
                $resultc=mysql_query($sqlc);
                 while($rowc=mysql_fetch_array($resultc))//permite ir de fila en fila de la tabla
                     { ?> <option value="<?=$rowc['id_centro_costo']; ?>"><?=$rowc['descripcion']; ?></option><?php } ?>
                </select>
            </div>

            
        </div>
    </div>
    
    
    <div class="modal-footer mt-3">
            <button type="button" class="btn btn-default" onClick="fn_cerrar()">Cerrar</button>
            <button class="btn btn-success" value="Guardar" type="submit" id="submit"  name="btnIngresar">Guardar </button>
    </div>
</form>

</body> 
         <script>
function validarPrecioVenta(input) {
    let cleanedValue = input.value.replace(/[^\d.]/g, ''); // Limpiar el valor permitiendo solo números y punto

    // Verificar si hay un punto decimal
    if (cleanedValue.includes('.')) {
        let [parteEntera, parteDecimal] = cleanedValue.split('.');

        // Eliminar ceros no significativos al inicio de la parte decimal
        parteDecimal = parteDecimal.replace(/^0+/g, '');

        // Limitar la parte entera a máximo seis dígitos
        parteEntera = parteEntera.slice(0, 6);

        // Limitar la parte decimal a máximo seis dígitos
        parteDecimal = parteDecimal.slice(0, 6);

        // Permitir la entrada de un nuevo punto si se borra el punto existente
        if (parteDecimal === '' && input.value.endsWith('.')) {
            input.value = parteEntera + '.';
        } else {
            input.value = parteEntera + (parteDecimal !== '' ? '.' + parteDecimal : '');
        }
    } else {
        // Si no hay punto decimal, limitar a seis dígitos
        input.value = cleanedValue.slice(0, 6);
    }
}


function calculariva(valor) {
    // Encuentra el input que está marcado como seleccionado
    var radios = document.getElementsByName('switch-one');
    var valorIva = 0;

    for (var i = 0; i < radios.length; i++) {
        if (radios[i].checked) {
            valorIva = parseFloat(radios[i].getAttribute('data-valor'));
            break;
        }
    }
    console.log("valorIva",valorIva);
// valorIva = '15';
    var precioIva = parseFloat(document.getElementById('precioIva').value);
    var precioSinIva = precioIva / (1 + (valorIva / 100));

    document.getElementById('txtPrecioVenta1').value = precioSinIva.toFixed(4);
}

       
       
         </script>
       
            
</html>