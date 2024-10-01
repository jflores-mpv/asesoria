<?php

	//Start session
	session_start();

	require_once('ver_sesion.php');

	//Include database connection details
	require_once('conexion.php');

        $cookie_tipo = $_COOKIE['tipo_cookie'];
        $cookie_id_empleado = $_COOKIE['id_empleado_cookie'];

        $nombre = $_SESSION['sesion_nombre'];
        $apellido = $_SESSION['sesion_apellido'];
        $sesion_tipo = $_SESSION["sesion_tipo"];
        $sesion_id_empleado = $_SESSION["sesion_id_empleado"];
        $sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
        $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
        $sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];

        //PERMISOS AL MODULO ASIENTOS CONTABLES
        $sesion_bancos_guardar = $_SESSION['sesion_bancos_guardar'];


?>

<html>
<head>
 <link rel="shortcut icon" href="images/logo.png">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!-- estas 3 librerias bootstrap coje del internet, pero tambien estan descargadas: css/bootstrap.min.css  js/jquery.min.js  js/bootstrap.min.js-->
<link rel="stylesheet" href="css/bootstrap.min.css">
<script type="" src="js/jquery.min.js"></script>
<script type="" src="js/bootstrap.min.js"></script>

<title>INVENTARIO SERVICIOS</title>

    <!-- STAR estilo para la plantilla  -->

    <!-- estilos para la plantilla -->
    <link rel="stylesheet" type="text/css" media="screen" href="css/screen.css" /><!--estilo para el menu y busqueda-->
    <link rel="stylesheet" type="text/css" href="css/style.css" media="screen" />

     <!-- START ESTILOS Y CLASES PARA AJAX -->
    <script language="javascript" type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
    <script language="javascript" type="text/javascript" src="js/jquery.blockUI.js"></script>
    <script language="javascript" type="text/javascript" src="js/jquery.validate.1.5.2.js"></script>

    
    <script language="javascript" type="text/javascript" src="js/funciones.js"></script>

     <!-- estilos para el listado de las tablas  -->
     <link href="css/listadoTablas.css" rel="stylesheet" type="text/css" />

       <!-- estilos para el calendario -->
    <link type="text/css" rel="stylesheet" href="css/calendario.css" media="screen"/>
    <script language="javascript" type="text/javascript" src="js/calendario.js"></script>

    
     <script type="text/javascript" src="js/condominios.js"></script>

    <script type="text/javascript">

        $(document).ready(function(){

            mostrarAreaGrupo(8);
            mostrarTablaUnidades(9);
            mostrarTablaTipoServicio(10);
            mostrarTablaProveedores(6);
            listar_servicios();
            muestra_iva_actual(4);

            mostrarPlanCuentas(14);
        });

    </script>


    <!--END estilo para  el menu -->

</head>

<body onload="">
 


<div class="transbox" style="margin-left:10%">

    <div id="contentTop" style="width: 980px;">
             <a href="menuPrincipalCondominios.php"><img style="margin-left: 80%" src="images/cerrar2.png" width="16" height="16" alt="cerrar" title="Cerrar" /></a>

            <div id="mensaje3" ></div>
            <form name="frmServicios" id="frmServicios" method="post" action="javascript: guardar_servicios(1);">
                <div style=" " class="socialMiddle"  >

                    <table border="0" width="100%">
                        <tbody>
                            <tr>
                                <td width="25%"><div style="margin-top: 0px;" class="middle"><h2><strong>Servicios </strong></h2></div></td>

                                <td align="right">
                                    <select style="width: 50%"  name="cmbCuentaContable" id="cmbCuentaContable" class="text_input1"></select>
                                </td>

                            </tr>
                        </tbody>
                    </table>

                    <div class="path2"></div>

                    <table border="0" width="100%">
                        <tbody>
                            <tr>
                                <td width="25%">
                                    <input style="width: 150px;" type="submit" value="Guardar" id="btnGuardar" class="btn btn-primary" name="btnGuardar" onclick="guardar();" />
                                </td>

                                <td align="">

                                </td>

                            </tr>
                        </tbody>
                    </table>

                    <br>

                    <table border="0" width="100%">

                        <tbody>
                            <tr>
                                <td><label>C&oacute;digo:</label> </td>
                                <td>
                                    <input type="text" class="text_input1" name="txtCodigoS" id="txtCodigoS" placeholder="Codigo" required="required"  title="Codigo"/>
                                </td>
                                <td><label>Precio venta 1:</label> </td>
                                <td>
                                    <input type="text" class="text_input1" name="txtPrecioVenta1S" id="txtPrecioVenta1S" placeholder="Precio venta 1" title="Precio venta 1"/>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><label>Descripci&oacute;n:</label></td>
                                <td>
                                    <input type="text" class="text_input" autofocus name="txtNombreS" required="required"  id="txtNombreS" placeholder="Descripci&oacute;n" title="Descripci&oacute;n"/>
                                </td>
                                <td><label>Precio venta 2:</label> </td>
                                <td>
                                    <input type="text" class="text_input" name="txtPrecioVenta2S" id="txtPrecioVenta2S" placeholder="Precio venta 2" title="Precio venta 2"/>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><label>&Aacute;rea o Grupo:</label></td>
                                <td>
                                    <select name="cmbCategoria" id="cmbCategoria" class="text_input">
                                        <option></option>
                                        <option></option>
                                    </select>
                                    <a href="javascript: area_grupo();" title="Agregar nueva &Aacute;rea o Grupo"><img src="images/add.png"></a>
                                </td>
                                <td><label>Precio venta 3:</label> </td>
                                <td>
                                    <input type="text" class="text_input" name="txtPrecioVenta3S" id="txtPrecioVenta3S" placeholder="Precio venta 3" title="Precio venta 3"/>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><label>Unidad:</label></td>
                                <td>
                                    <select name="cmbUnidad" id="cmbUnidad" class="text_input">
                                        <option></option>
                                        <option></option>
                                    </select>
                                    <a href="javascript: unidades();" title="Agregar nuevo cargo"><img src="images/add.png"></a>
                                </td>
                                <td><label>Precio venta 4:</label> </td>
                                <td>
                                    <input type="text" class="text_input" name="txtPrecioVenta4S" id="txtPrecioVenta4S" placeholder="Precio venta 4" title="Precio venta 4"/>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><label>Tipo Servicio:</label></td>
                                <td>
                                    <select name="cmbTipoServicio" id="cmbTipoServicio" class="text_input">
                                        <option></option>
                                        <option></option>
                                    </select>
                                    <a href="javascript: tipoServicio();" title="Agregar Tipo Servicio"><img src="images/add.png"></a>
                                </td>
                                <td><label>Precio venta 5:</label> </td>
                                <td>
                                    <input type="text" class="text_input" name="txtPrecioVenta5S" id="txtPrecioVenta5S" placeholder="Precio venta 5" title="Precio venta 5"/>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><label>Paga IVA:</label></td>
                                <td>
                                    <input type="checkbox" name="checkIva" id="checkIva" value="ON"  /> <label>IVA</label>
                                    <label id="ivaActual"> </label><label>%</label>
                                    <a href="javascript: impuestos();" title="Agregar nueva &Aacute;rea o Grupo"><img src="images/add.png"></a>
                                </td>
                                <td><label>Precio venta 6:</label> </td>
                                <td>
                                    <input type="text" class="text_input" name="txtPrecioVenta6S" id="txtPrecioVenta6S" placeholder="Precio venta 6" title="Precio venta 6"/>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td ><label>Observaci&oacute;n:</label></td>
                                <td colspan="3">
                                    <textarea style="width: 100%; height: 60px; " type="text"  id="txtDescripcionS" class="text_input" name="txtDescripcionS" placeholder="Ingrese una Descripci&oacute;n " title="Descripci&oacute;n "  autocomplete="off"  maxlength="250" ></textarea> <!-- solo alcanza a 250 caracteres -->
                                </td>
                                
                            </tr>
                        </tbody>
                    </table>


                    <select style="display: none" name="criterio_mostrar" id="criterio_mostrar">
                        <option value="100000000000000000">Todos</option>

                    </select>

                </div>

                <div id="listar_servicios"></div>

            </form>
            

        </div>

        <div id="menu1" class="tab-pane fade"> <!--  MENU 2 -->
            <div id="mensaje2" ></div>
            
            
        </div>

        <div id="menu2" class="tab-pane fade"> <!--  MENU 3 -->

            <!-- ***************************************** INGRESO DE PRODUCTOS ************************************ -->
            <div id="mensaje1" ></div>

            <form name="frmIngresoProductos" id="frmIngresoProductos" method="post" action="javascript: guardar_producto(document.forms['frmIngresoProductos']);">

             <!-- PERMISOS PARA EL MODULO ASIENTOS CONTABLES -->
             <input type="hidden" id="txtPermisosBancosGuardar" name="txtPermisosBancosGuardar" value="<?php echo $sesion_bancos_guardar; ?>" />

              <div style=" " class="socialMiddle"  >
                <table border="0" width="100%">
                    <tbody>
                        <tr>
                            <td width="25%"><div style="margin-top: 0px;" class="middle"><h2><strong>Ingreso de Productos </strong></h2></div></td>

                            <td align="">

                            </td>

                        </tr>
                    </tbody>
                </table>

                <div class="path2"></div>

                <table border="0">

                    <tbody>
                        <tr>
                            <td>
                                <label>Bodega 1:</label>
                                <input type="text" class="text_input2" name="txtCodigo" id="txtCodigo" placeholder="Codigo producto" title="Codigo"/>
                            </td>
                            <td>
                                <label>Bodega 2:</label>
                                <input type="text" class="text_input2" name="txtCodigo" id="txtCodigo" placeholder="Codigo producto" title="Codigo"/>
                            </td>
                            <td>
                                <label>Bodega 3:</label>
                                <input type="text" class="text_input2" name="txtCodigo" id="txtCodigo" placeholder="Codigo producto" title="Codigo"/>
                            </td>
                            <td>
                                <label>Bodega 4:</label>
                                <input type="text" class="text_input2" name="txtCodigo" id="txtCodigo" placeholder="Codigo producto" title="Codigo"/>
                            </td>
                            <td>
                                <label>Bodega 5:</label>
                                <input type="text" class="text_input2" name="txtCodigo" id="txtCodigo" placeholder="Codigo producto" title="Codigo"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>Bodega 6:</label>
                                <input type="text" class="text_input2" name="txtCodigo" id="txtCodigo" placeholder="Codigo producto" title="Codigo"/>
                            </td>
                            <td>
                                <label>Bodega 7:</label>
                                <input type="text" class="text_input2" name="txtCodigo" id="txtCodigo" placeholder="Codigo producto" title="Codigo"/>
                            </td>
                            <td>
                                <label>Bodega 8:</label>
                                <input type="text" class="text_input2" name="txtCodigo" id="txtCodigo" placeholder="Codigo producto" title="Codigo"/>
                            </td>
                            <td>
                                <label>Bodega 9:</label>
                                <input type="text" class="text_input2" name="txtCodigo" id="txtCodigo" placeholder="Codigo producto" title="Codigo"/>
                            </td>
                            <td>
                                <label>Bodega 10:</label>
                                <input type="text" class="text_input2" name="txtCodigo" id="txtCodigo" placeholder="Codigo producto" title="Codigo"/>
                            </td>
                        </tr>


                    </tbody>
                </table>
                <br>

                <table border="0" width="100%">

                        <tbody>
                            <tr>
                                <td><label>C&oacute;digo:</label> </td>
                                <td>
                                    <input type="text" class="text_input1" name="txtCodigo" id="txtCodigo" placeholder="Codigo producto" title="Codigo"/>
                                </td>
                                <td><label>Costo compra:</label> </td>
                                <td>
                                    <input type="text" class="text_input1" name="txtCodigo" id="txtCodigo" placeholder="Codigo producto" title="Codigo"/>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><label>Descripci&oacute;n:</label></td>
                                <td>
                                    <input type="text" class="text_input1" autofocus name="txtDescripcion" id="txtDescripcion" placeholder="Descripcion producto" title="Descripcion"/>
                                </td>
                                <td><label>Promedio:</label> </td>
                                <td>
                                    <input type="text" class="text_input1" name="txtCodigo" id="txtCodigo" placeholder="Codigo producto" title="Codigo"/>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><label>Marca:</label></td>
                                <td>
                                    <input type="text" class="text_input1" autofocus name="txtMarca" id="txtMarca" placeholder="Marca producto" title="Marca"/>
                                </td>

                                <td><label>Precio venta 1:</label> </td>
                                <td>
                                    <input type="text" class="text_input1" name="txtCodigo" id="txtCodigo" placeholder="Codigo producto" title="Codigo"/>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><label>&Aacute;rea o Grupo:</label></td>
                                <td>
                                    <select name="cmbCategoria" id="cmbCategoria" class="text_input">

                                    </select>
                                    <a href="javascript: area_grupo();" title="Agregar nuevo cargo"><img src="images/add.png"></a>
                                </td>

                                <td><label>Precio venta 2:</label> </td>
                                <td>
                                    <input type="text" class="text_input1" name="txtCodigo" id="txtCodigo" placeholder="Codigo producto" title="Codigo"/>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><label>Unidad:</label></td>
                                <td>
                                    <select name="cmbUnidad" id="cmbUnidad" class="text_input">
                                        <option></option>
                                        <option></option>
                                    </select>
                                    <a href="javascript: unidades();" title="Agregar nuevo cargo"><img src="images/add.png"></a>
                                </td>

                                <td><label>Precio venta 3:</label> </td>
                                <td>
                                    <input type="text" class="text_input1" name="txtCodigo" id="txtCodigo" placeholder="Codigo producto" title="Codigo"/>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><label>Proveedor:</label></td>
                                <td>
                                    <select name="cmbProveedor" id="cmbProveedor" class="text_input">
                                        <option></option>
                                        <option></option>
                                    </select>
                                    <a href="javascript: nuevoProveedor();" title="Agregar nuevo cargo"><img src="images/add.png"></a>
                                </td>

                                <td><label>Precio venta 4:</label> </td>
                                <td>
                                    <input type="text" class="text_input1" name="txtCodigo" id="txtCodigo" placeholder="Codigo producto" title="Codigo"/>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><label>Paga IVA:</label></td>
                                <td>
                                    <input type="checkbox" name="checkIVA" id="checkIVA" value="ON" /> IVA
                                </td>

                                <td><label>Precio venta 5:</label> </td>
                                <td>
                                    <input type="text" class="text_input1" name="txtCodigo" id="txtCodigo" placeholder="Codigo producto" title="Codigo"/>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><label>Observaci&oacute;n:</label></td>
                                <td>
                                    <textarea style="width: 100%; height: 60px; margin-top: 0px; " type="text"  id="txtDescripcion" class="text_input4" name="txtDescripcion" placeholder="Ingrese un detalle " title="Descripci&oacute;n "  autocomplete="off"  maxlength="250" ></textarea> <!-- solo alcanza a 250 caracteres -->
                                </td>
                                <td><label>Precio venta 6:</label> </td>
                                <td>
                                    <input type="text" class="text_input1" name="txtCodigo" id="txtCodigo" placeholder="Codigo producto" title="Codigo"/>
                                </td>
                            </tr>
                        </tbody>
                    </table>



                    <br>

                    <br>

                    <div id=""></div>

                <br><br>
            </div>
            </form>

           

        </div>

        



    </div>


        <div id="div_oculto" class="caja" style="display: none;"></div>


         </div>	<!-- end #content -->
</div>

<?php// include("bag.php"); ?>



</body>
</html>

