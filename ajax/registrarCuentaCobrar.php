<?php
	
    //Start session
    session_start();

    //Include database connection details
    require_once('../conexion.php');
  $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
	 
    date_default_timezone_set('America/Guayaquil');
			$selecion = $_GET['switch-four'];
              $dominio = $_SERVER['SERVER_NAME'];
        $verFuncion = "";
 
?>

<html>
<head>

<title>Registrar Cuenta Cobrar</title>

<script type="text/javascript">
    $(document).ready(function()
	{
		cargarFormasPago(5);
	});
</script>
    
</head>

<body onload="">
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <a href="javascript: fn_cerrar();"><button type="button" class="close" ><span aria-hidden="true">&times;</span></button></a>
        <h4 class="modal-title" id="myModalLabel">Registrar Cuenta por Cobrar</h4>
      </div>
      <div class="modal-body g-3">
        <form name="frmRegistrarCuentaCobrar" id="frmRegistrarCuentaCobrar" method="post" action="javascript: guardar_cuenta_cobrar(6);" >
        <input type="hidden" value="<?php echo $selecion; ?>" id="seleccion"  name="seleccion"  />
        <div id="mensajeCuentaCobrar" ></div>
        <div class="path2"></div>

        <div class="row my-2">
            
            <div class="col-lg-4 offset-lg-1">    
            
                <input type="radio" class="btn-check col" name="switch-saldo-anticipo" id="saldo-outlined" value="1" autocomplete="off" checked onclick="<?php echo $verFuncion ?>">
                <label class="btn btn-outline-success" for="saldo-outlined">Saldo Inicial</label>
                
                <input type="radio" class="btn-check col" name="switch-saldo-anticipo" id="anticipo-outlined" value="2"  autocomplete="off" onclick="<?php echo $verFuncion ?>" >
                <label class="btn btn-outline-success" for="anticipo-outlined">Anticipo</label>
                
            
            </div>
       
          <div class="row my-2" id="tipos_anticipos" style="display:none">
             
        </div>
        <div class="row g-3 align-items-center">
            
                      <?php
                        $tipo= '';
                        if($selecion==2){
                            $tipo= 'Clientes (requerido)';
                            $sqlp="SELECT
                            `id_cliente` AS id,
                            CONCAT(`nombre`,' ',apellido) AS nombre
                        FROM
                            `clientes`
                        WHERE
                            id_empresa='".$sesion_id_empresa."';";

                        }else if($selecion==1){
                            $tipo= 'Proveedores (requerido)';
                            $sqlp="SELECT
                            `id_proveedor` as id,
                            `nombre_comercial`as nombre
                        FROM
                            `proveedores`
                        WHERE
                            `id_empresa`='".$sesion_id_empresa."';";

                        }else if($selecion==3){
                            $tipo= 'Leads (requerido)';
                            $sqlp="SELECT
                            `id` as id,
                            CONCAT(`name`,' ',apellido) AS nombre
                        FROM
                            `leads`
                        WHERE
                            `id_empresa`='".$sesion_id_empresa."';";

                        }
                        else if($selecion==4){
                            $tipo= 'Empleados (requerido)';
                            $sqlp="SELECT
                            `id_empleado` as id,
                            CONCAT(`nombre`,' ',apellido) AS nombre
                        FROM
                            `empleados`
                        WHERE
                            `id_empresa`='".$sesion_id_empresa."';";

                        }
                        ?>
            
            
          <div class="col-3">
             <label for="cmbProveedor"><?php echo $tipo; ?></label><br>
          </div>
          <div class="col-auto">
            <select  tabindex="7" id="cmbProveedor"  name="cmbProveedor" required="required" class="form-control" >
                <?php
               
				// echo $sqlp;
                $resultp=mysql_query($sqlp);
                 while($rowp=mysql_fetch_array($resultp))//permite ir de fila en fila de la tabla
                     {
                     ?>
                       <option value="<?=$rowp['id']; ?>"><?=$rowp['nombre']; ?></option>
                     <?php
                     }
                  ?>
            </select>
          </div>
        </div>
        
            <div class="row g-3 align-items-center mt-2">
              <div class="col-3">
                <label for="inputPassword6" class="col-form-label">Comprobante:</label>
              </div>
              <div class="col-auto">
                    <input type="text" tabindex="2" value="" id="txtComprobante"  name="txtComprobante" title="Numero comprobante" required="required" autocomplete="off" class="form-control"    />
              </div>
            </div>
            
            
            <div class="row g-3 align-items-center mt-2">
              <div class="col-3">
                <label for="inputPassword6" class="col-form-label">Forma de pago:</label>
              </div>
              <div class="col-6">
                <select  tabindex="7" id="txtFormaPago"  name="txtFormaPago" required="required" class="form-control w-100" >
                    <option value="0">Ninguna</option>
                <?php
                           $sqlpformas="SELECT* 
                        FROM
                            `enlaces_compras`
                        WHERE
                            id_empresa='".$sesion_id_empresa."'  AND ( tipo='cheque' or tipo='efectivo' or tipo='credito');";
                $resultFormas=mysql_query($sqlpformas);
                 while($rowpFormas=mysql_fetch_array($resultFormas))//permite ir de fila en fila de la tabla
                     {
                     ?>
                       <option value="<?=$rowpFormas['id']; ?>"><?=$rowpFormas['nombre']; ?></option>
                     <?php
                     }
                  ?>
            </select>
              </div>
            </div>
            
            
            <div class="row g-3 align-items-center mt-2">
              <div class="col-3">
                <label for="inputPassword6" class="col-form-label">Referencia-Numero Cheque:</label>
              </div>
              <div class="col-auto">
                   <input type="text" tabindex="3" value="" id="txtReferencia"  name="txtReferencia" autocomplete="off" class="form-control"   />
              </div>
            </div>
            
            <div class="row g-3 align-items-center mt-2">
              <div class="col-3">
                <label for="inputPassword6" class="col-form-label">Fecha de Emision :</label>
              </div>
              <div class="col-auto">
                    <input type="text" tabindex="4" value="<?php echo date("Y-m-d",time()); ?>" id="txtFecha"  name="txtFecha" placeholder="Fecha de pago" required="required" autocomplete="off" maxlength="10"  onClick="displayCalendar(txtFecha,'yyyy-mm-dd',this)"  class="form-control"  />
              </div>
            </div>
            <div class="row g-3 align-items-center mt-2">
              <div class="col-3">
                <label for="inputPassword6" class="col-form-label">Fecha de Vencimiento:</label>
              </div>
              <div class="col-auto">
                            <input type="text" tabindex="5" value="<?php echo date("Y-m-d",time()); ?>" id="txtFechaVencimiento"  name="txtFechaVencimiento" placeholder="Fecha de vencimiento" required="required" autocomplete="off" maxlength="10"  onClick="displayCalendar(txtFechaVencimiento,'yyyy-mm-dd',this)" class="form-control"  />
              </div>
            </div>
            

            
            <div class="row g-3 align-items-center mt-2">
              <div class="col-3">
                <label for="inputPassword6" class="col-form-label">Importe:</label>
              </div>
              <div class="col-auto">
                    <input type="text" tabindex="6"  id="txtTotal"  name="txtTotal" title="Importe"  autocomplete="off" placeholder="Importe" class="form-control"    />
              </div>
              <div class="col-auto">
                <span id="passwordHelpInline" class="form-text">
                  <div id="validaPagoMin"></div>
                </span>
              </div>
            </div>
            
             <div class="row g-3 align-items-center mt-2">
              <div class="col-auto">
                   <input style="width: 210px;" type="submit" value="Guardar" tabindex="10" id="submitPCC" class="btn btn-success" name="btnEnviar"  />
              </div>
              <div class="col-auto">
                   <input style="width: 150px;" name="cancelar" type="button" id="submit" tabindex="11" value="Cerrar" onclick="javascript: fn_cerrar();" class="btn btn-success"/>
              </div>
            </div>

            <div class="path2"></div>
                       
        </form>        

    </div>	<!-- end contactForm -->
</div>	<!-- end contentLeft -->
</div><!-- END contentTop4 -->
</body>    
</html>
