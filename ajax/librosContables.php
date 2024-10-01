<?php

	require_once('../ver_sesion.php');

	//Start session
	session_start();
		
	//Include database connection details
	require_once('../conexion.php');

        $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
        $sesion_id_periodo_contable = $_SESSION["sesion_id_periodo_contable"];
        $sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];

        
			
?>
<html lang="es-ES">
    
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Reportes Contable</title>
 
</head>

<body onload="" class="textVerde">
    
    <form id="frm_libro" name="frm_libro" method="post">
        <div class="modal-header">
            <a href="javascript: fn_cerrar();"><button type="button" class="close" ><span aria-hidden="true">&times;</span></button></a>
            <h3>Reportes Contables</h3>
        </div>
                <div class="row my-3">
                    <div class="col-lg-4 text-center">
                        <label>Fecha desde</label>
                        <input type="hidden" name="txtIdPeriodoContable" id="txtIdPeriodoContable" value="<?php echo $sesion_id_periodo_contable;  ?>"  />
                        <input class="form-control text-center"  type="text" tabindex="1" id="txtFechaDesde" value="<?php echo date("Y-m-d",time()); ?>" required="required" name="txtFechaDesde" onClick="displayCalendar(txtFechaDesde,'yyyy-mm-dd',this)" onchange="validaFechas(txtFechaDesde.value, txtFechaHasta.value);" placeholder="Fecha inicio"  autocomplete="off"/>
                        <div id="mensajefecha_desde" ></div>
                    </div>
                    <div class="col-lg-4 text-center">
                        <label>Fecha hasta:</label>
                        <input class="form-control text-center" type="text" tabindex="2" id="txtFechaHasta" value="<?php echo date("Y-m-d",time()); ?>" required="required" name="txtFechaHasta" onClick="displayCalendar(txtFechaHasta,'yyyy-mm-dd',this)" onchange="validaFechas(txtFechaDesde.value, txtFechaHasta.value);" placeholder="Fecha tope"  autocomplete="off"/>
                        <div id="mensajefecha_hasta" ></div>
                    </div>
                    <div class="col-lg-3 text-center">
                        <a class="text-decoration-none card p-2 " onclick="javascript: abrirPdfBalanceComprobacion();" id="submit" type="submit" > Balance de Comprobaci&oacute;n</a>
                    </div>
                   
                        <div class="col-lg-1 text-center">
                            <button type="button" class="btn btn-success"  onclick="javascript: excelBalanceComprobacion();">
                      <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                       </button>
                    </div>
                     
                     
                </div>    
                <div class="row my-3 text-center">
           
                    <div class="col-lg-4">
                        <label>Centro de Costos </label>
                            <select  name="centro" id="centro" class="form-control"  required="required" >
                            <option value="0">Selecciona una opcion</option>
                            <?php
                                $est2="Select id_centro_costo,descripcion From centro_costo where empresa='".$sesion_id_empresa."';";
                                
                                $resultest2=mysql_query($est2);
                                while($rowu=mysql_fetch_array($resultest2))
                               
                                { ?>  
                                
                                <option value="<?=$rowu['id_centro_costo']; ?>"><?=$rowu['descripcion']; ?></option>  
                                
                                <?php  }  ?>
                            </select>
                    </div>
                    <a class="col-lg-3 offset-lg-4 text-decoration-none card p-2" onclick="javascript: abrirPdfEstadoResultados();" id="submit" type="submit" > Estado de Resultados</a>
                    
                        <div class="col-lg-1 text-center">
                            <button type="button" class="btn btn-success"  onclick="javascript: excelEstadoResultados();">
                      <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                       </button>
                    </div>
                    
                </div>
                <div class="row my-3 text-center">
                        <a class="col-lg-3 offset-lg-8 text-decoration-none card p-2" onclick="javascript: abrirPdfBalanceSituacion();" id="submit" type="submit" > Estado de Situaci&oacute;n</a>
                        
                        <div class="col-lg-1 text-center">
                            <button type="button" class="btn btn-success"  onclick="javascript: excelEstadoSituacion();">
                      <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                       </button>
                    </div>
                      
                </div>
                <div class="row my-3">   
                    <div class="col-lg-4">
                        <label>Ordenar por:</label>
                        <select  class="form-control required" name="criterio_ordenar_por" id="criterio_ordenar_por">
                                    <option value=" libro_diario.`numero_asiento` ">Numero</option>
                                    <option value=" libro_diario.`fecha` ">Fecha</option>
                                </select>
                    </div>
                    
                     <div class="col-lg-4">
                        <label>Cantidad:</label>
                        	<select class="form-control" name="criterio_mostrar" id="criterio_mostrar">
                                	<option value="1">1</option>
                                	<option value="2">2</option>
                                	<option value="5">5</option>
                                	<option value="10">10</option>
                                	<option value="20">20</option>
                                        <option value="40">40</option>
                                	<option value="80">80</option>
                                        <option value="1000000000000000000" selected="selected">Todos</option>
                                </select>
                    </div>
                    
                    
                </div>
                

                 <div class="row my-3">
                       <div class="col-lg-4">
                            <label>Orden</label>
                            <select class="form-control required" name="criterio_orden" id="criterio_orden">
                                    <option value="asc">Ascendente</option>
                                    <option value="desc">Descendente</option>
                                </select>
                    </div>
                    <div class="col-lg-4">
                        <label>Asiento Libro Diario</label>
                        <input class="form-control text-center" tabindex="3" name="criterio_usu_per" type="search" id="criterio_usu_per"  onKeyPress="return soloNumeros(evt)"/>
                    </div>
                    
                    <div class="col-lg-3 ">      
                        <a class=" text-decoration-none card p-2 m-1 text-center" onclick="javascript: abrirPdfLibroDiario();" id="submit" type="submit" > Libro Diario</a>
                    </div>
                     
                        <div class="col-lg-1 text-center">
                            <button type="button" class="btn btn-success"  onclick="javascript: excelLibroDiario();">
                      <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                       </button>
                    </div>
                       
                 </div>   
                    <div class="row my-3">
                     <div class="col-lg-8">
                        <label>Cuenta contable Libro Mayor: </label>
                        <input type="hidden" name="txtIdPeriodoContable" id="txtIdPeriodoContable" value="<?php echo $sesion_id_periodo_contable;  ?>"  />
                            <input type="hidden" id="txtIdPlanCuenta" value="" class="form-control required" name="txtIdPlanCuenta" />
                            <input type="search" tabindex="3" size="22" id="txtPlanCuenta" class="form-control"  name="txtPlanCuenta" placeholder="Buscar cuenta" onclick="lookup3(this.value,1);" onKeyUp="lookup3(this.value,1);" onBlur="fill3();" onchange="limpiar_id('txtIdPlanCuenta')" autocomplete="off"/>
                            <div class="suggestionsBox" id="suggestions" style="display: none;">
                            <div class="suggestionList" id="autoSuggestionsList"></div></div>
                    </div>
                     <div class="col-lg-3">   
                            <a class=" text-decoration-none card p-2 m-1 text-center" onclick="javascript: abrirPdfLibroMayorReporte();" id="submit" type="submit" > Libro Mayor</a>

                     </div>
                     
                        <div class="col-lg-1 text-center">
                            <button type="button" class="btn btn-success"  onclick="javascript: excelLibroMayor();">
                      <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                       </button>
                    </div>
                       
                    
                </div>
                <div class="modal-footer text-center">
                </div>
                    


            </form>

       
<script type=""> 


    function abrirPdfBalanceSituacion(){

    miUrl = "reportes/rptBalanceSituacion.php?txtFechaDesde="+document.getElementById("txtFechaDesde").value+"&txtFechaHasta="+document.getElementById("txtFechaHasta").value+"&cmbCentro="+document.getElementById("centro").value;;
        console.log("URL",miUrl);
        window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
    }
    
    function abrirPdfEstadoResultados(){
    miUrl = "reportes/rptEstadoResultados.php?txtFechaDesde="+document.getElementById("txtFechaDesde").value+"&txtFechaHasta="+document.getElementById("txtFechaHasta").value;
    //?txtIdPlanCuenta="+document.getElementById("txtIdPlanCuenta").value+"&txtPeriodoContable="+document.getElementById("txtPeriodoContable").value+"&criterio_ordenar_por="+document.getElementById("criterio_ordenar_por").value+"&criterio_orden="+document.getElementById("criterio_orden").value+"&criterio_mostrar="+document.getElementById("criterio_mostrar").value
     window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
    }
    
    function abrirPdfBalanceComprobacion(){

    miUrl = "reportes/rptBalanceComprobacion.php?criterio_ordenar_por="+document.getElementById("criterio_ordenar_por").value+"&criterio_orden="+document.getElementById("criterio_orden").value+"&criterio_mostrar="+document.getElementById("criterio_mostrar").value+"&txtFechaDesde="+document.getElementById("txtFechaDesde").value+"&txtFechaHasta="+document.getElementById("txtFechaHasta").value;

     window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
    }

function abrirPdfLibroMayorReporte(){
console.log("MAYOR");
    miUrl = "reportes/rptLibroMayor.php?txtIdPlanCuenta="+document.getElementById("txtIdPlanCuenta").value+"&criterio_ordenar_por="+document.getElementById("criterio_ordenar_por").value+"&criterio_orden="+document.getElementById("criterio_orden").value+"&criterio_mostrar="+document.getElementById("criterio_mostrar").value+"&txtFechaDesde="+document.getElementById("txtFechaDesde").value+"&txtFechaHasta="+document.getElementById("txtFechaHasta").value;

     window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
    }
    

function abrirPdfLibroDiario(){

    miUrl = "reportes/rpt_libro_diario_basico.php?txtIdPeriodoContable="+document.getElementById("txtIdPeriodoContable").value+"&criterio_usu_per="+document.getElementById("criterio_usu_per").value+"&criterio_ordenar_por="+document.getElementById("criterio_ordenar_por").value+"&criterio_orden="+document.getElementById("criterio_orden").value+"&criterio_mostrar="+document.getElementById("criterio_mostrar").value+"&fecha_desde="+document.getElementById("txtFechaDesde").value+"&fecha_hasta="+document.getElementById("txtFechaHasta").value;
         console.log("miUrl",miUrl);
         window.open(miUrl,'informe del Libro Diario','width=600, height=600, scrollbars=NO, titlebar=no');

}

function TipoReporte(){
    tipo_reporte = $('#cmbTipoReporte').val();
}

</script>

</body>

</html>