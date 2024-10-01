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
    $sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];

    //PERMISOS AL MODULO ASIENTOS CONTABLES
    $sesion_bancos_guardar = $_SESSION['sesion_bancos_guardar'];
?>

<html lang="es-ES">
    
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Cuentas por cobrar</title>
    <script src="https://kit.fontawesome.com/7816c50242.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="librerias/bootstrap/css/bootstrap.css">  
    <script src="librerias/jquery-3.2.1.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="librerias/bootstrap/css/style.css"> 
    <script src="librerias/bootstrap/js/bootstrap.bundle.min.js" ></script>
     <!-- START ESTILOS Y CLASES PARA AJAX -->
    <script language="javascript" type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
    <script language="javascript" type="text/javascript" src="js/jquery.blockUI.js"></script>
    <script language="javascript" type="text/javascript" src="js/jquery.validate.1.5.2.js"></script>        
    <script language="javascript" type="text/javascript" src="js/index.js"></script>

    <!-- condominios -->
    <script language="javascript" type="text/javascript" src="js/condominios.js"></script>
    <script language="javascript" type="text/javascript" src="js/ctasxcobrar.js"></script>
    <script language="javascript" type="text/javascript" src="js/ventas_educativo.js"></script>
	<script language="javascript" type="text/javascript" src="js/compras.js"></script>
    <!-- validaciones -->
    <script language="javascript" type="text/javascript" src="js/validaciones.js"></script>
    <link rel="stylesheet" type="text/css" href="librerias/alertifyjs/css/alertify.css">
    <script src="librerias/alertifyjs/alertify.js"></script>
    <link rel="stylesheet" type="text/css" href="librerias/select2/css/select2.css"/>
    <link type="text/css" rel="stylesheet" href="css/calendario.css" media="screen"/>
    <link type="text/css" rel="stylesheet" href="css/style.css" media="screen"/>
    <script language="javascript" type="text/javascript" src="js/calendario.js"></script>
    
    <script type="text/javascript">
        $(document).ready(function(){
			listar_cuentas_por_cobrar(1);
        });
    </script>

    <!--END estilo para  el menu -->
</head>

<body onload="">
    <div class="wrapper d-flex align-items-stretch celeste">
    
    <nav id="sidebar" ><?php  {include("menusNiveles.php");      }   ?></nav>
    
    <div id="content"  class="p-0 m-0">
    
    <div class="row m-0"><?php  {include("header.php");      }   ?>  </div>
     <form name="frmCuentasCobrar" id="frmCuentasCobrar" method="post" action="javascript: listar_bancos_CB(document.forms['frmConciliacionBancaria']);">
    <div class="row  mt-3 bg-white p-2">  
    <div id="mensaje1" ></div>
       
     
    <div class="input-group mb-3 ">
        
        <span class="input-group-text" id="basic-addon1">Buscar</span>
        <input type="text" name="txtBuscarCuentasCobrar" id="txtBuscarCuentasCobrar" value="" class="form-control "  onkeyup="listar_cuentas_por_cobrar(1);"/>
        
        <span class="input-group-text " id="basic-addon1">N&uacute;mero</span>
        <input type="text" name="txtNumeroCuentasCobrar" id="txtNumeroCuentasCobrar" value="" class="form-control  "  onkeyup="listar_cuentas_por_cobrar(1);"/>
        
        
     <div>
            <input type="radio" class="btn-check" name="switch_tipo_fecha" id="radio_emision" value="Emision" autocomplete="off"  onchange="listar_cuentas_por_cobrar(1);">
            <label class="btn btn-outline-success" for="radio_emision">Fecha Emisi&oacute;n</label>
            
            <input type="radio" class="btn-check" name="switch_tipo_fecha" id="radio_vencimiento" value="Vencimiento" autocomplete="off" onchange="listar_cuentas_por_cobrar(1);" checked>
            <label class="btn btn-outline-success" for="radio_vencimiento">Fecha Vencimiento/Pago</label>
        </div>
        
        <span class="input-group-text" id="basic-addon1">Fecha desde: </span>
        <input type="text" tabindex="1" id="txtFechaDesde" value="<?php echo date('Y')."-".date('m')."-"."01" ; ?>"   class="form-control " required="required" name="txtFechaDesde" onClick="displayCalendar(txtFechaDesde,'yyyy-mm-dd',this)" onchange="validaFechas(txtFechaDesde.value, txtFechaHasta.value);  listar_cuentas_por_cobrar(1);" placeholder="Fecha inicio"  autocomplete="off"/>
        <div id="mensajefecha_desde" ></div>
        
        <span class="input-group-text" id="basic-addon1">Fecha hasta: </span>
        <input type="text" tabindex="2" id="txtFechaHasta" value="<?php echo date("Y-m-d",time()); ?>" class="form-control " required="required" name="txtFechaHasta" onClick="displayCalendar(txtFechaHasta,'yyyy-mm-dd',this)" onchange="validaFechas(txtFechaDesde.value, txtFechaHasta.value); listar_cuentas_por_cobrar(1);" placeholder="Fecha tope"  autocomplete="off"/>
        <div id="mensajefecha_hasta" ></div>
        
    </div>
    
    <div class="input-group mb-3 ">    
        
        <span class="input-group-text" id="basic-addon1">Mostrar </span>
        <select name="criterio_mostrar" class="form form-control w-25" onchange="listar_cuentas_por_cobrar(1);">
            <option value="10000000000000000">Todos</option> 
            <option value="5">5</option> 
            <option value="10">10</option> 
             <option value="50">50</option> 
            <option value="100">100</option> 
        </select>
        <span class="input-group-text" id="basic-addon1">Listado por: </span>
        
        <select name="listado_por" class="form form-control w-25" onchange="listar_cuentas_por_cobrar(1);">
                <option value="Clientes">Clientes</option> 
                <option value="Facturas">Facturas</option> 
        </select>
        <?php        
            $dominio = $_SERVER['SERVER_NAME'];
            $listado = "listar_cuentas_por_cobrar(1);";
                 $listado2 = "listar_cuentas_por_cobrar(1);";
               if($dominio=='jderp.cloud' or $dominio=='www.jderp.cloud' ){
                   ?>
                    <span class="input-group-text" id="basic-addon1">Vendedor: </span>
        
        <select name="id_vendedor" class="form form-control w-25" onchange="listar_cuentas_por_cobrar(1);">
             <option value="0">Ninguno</option>
            <?php
              if($sesion_id_empresa == 116  || $sesion_id_empresa ==1827){
                         $sqltrans="Select * From vendedores where id_empresa='".$sesion_id_empresa."' AND estado='Activo' AND (tipo_vendedor='ambos' or tipo_vendedor='vendedor');";
                    }else{
                         $sqltrans="Select * From vendedores where id_empresa='".$sesion_id_empresa."' AND estado='Activo';";
                    }
                    $sqltrans1=mysql_query($sqltrans);
                    while($rowtrans=mysql_fetch_array($sqltrans1))
                         {          
                    ?>
                          <option value="<?=$rowtrans['id_vendedor']; ?>"><?=$rowtrans['nombre'].' '.$rowtrans['apellidos']; ?></option>
                    <?php } ?>
               
        </select>
        <?php
               }   
                 
             if($dominio=='dcacorp.com.ec' or $dominio=='www.dcacorp.com.ec' ){
                 $listado = "comboAnticipos('P');";
                 $listado2 = "comboAnticipos('C');";
                 ?>
                 <span class="input-group-text" id="basic-addon1">Tipo Anticipo </span>
        <select name="tipo_anticipo" id="tipo_anticipo" class="form form-control w-25" onchange="listar_cuentas_por_cobrar(1);">
            <option value="0">Ninguno</option> 
            <?php
            $sqlAnt="SELECT `id_tipo_anticipo`, `nombre_anticipo`, `id_empresa`, `tipo`, `objetivo` FROM `tipo_anticipo` WHERE id_empresa=$sesion_id_empresa AND tipo='C' and objetivo='P' ";
            $resultAnt = mysql_query($sqlAnt);
            while($rowAnt = mysql_fetch_array($resultAnt) ){
                ?>
                <option value="<?php echo $rowAnt['id_tipo_anticipo'];  ?>"><?php echo $rowAnt['nombre_anticipo'];  ?></option> 
                <?php
            }
            ?>
        </select>
                 <?php
             }
        ?>
         
        
        
        <a type="button" class="btn btn-outline-secondary"  id="botonSaldo" name="botonSaldo" onClick="href:registrarCtasxCobrar()"/>
           Saldo Inicial 
        </a>   
        
        <a   type="button" class="btn btn-outline-secondary" id="btnNuevaFactura" name="btnNuevaFactura"  onClick="javascript:pdfCuentaCobrar(<?php echo $sesion_id_empresa?>)"/>
            Reporte
        </a> 

        <a type="button"  class="btn btn-outline-secondary" id="btnNuevaFactura" name="btnNuevaFactura"  onClick="javascript:reporteExcelCuentasCobrar()"/>
            Reporte Excel 
        </a> 

    	
    </div>

            <select name="criterio_ordenar_por" style="display: none">
                <option value=" cuentas_por_cobrar.`id_cuenta_por_cobrar` ">Id</option>
            </select>
            <select name="criterio_orden" style="display: none">
                <option value=" asc ">asc</option>
                <option value=" desc ">desc</option>
            </select>
    
        
    </div>
     <div class="row text-center py-3">
                
             <div class="col-lg-5 offset-lg-2">    
             
            
            
                <input type="radio" class="btn-check col" name="switch-four" id="Proveedores-outlined" value="1" autocomplete="off"  onChange="<?php echo $listado; ?>botonSaldoInicial()" onclick="" >
                <label class="btn btn-outline-success" for="Proveedores-outlined">Proveedores</label>
                
                <input type="radio" class="btn-check col" name="switch-four" id="Clientes-outlined" value="2"  autocomplete="off" checked onChange="<?php echo $listado2; ?>botonSaldoInicial()" >
                <label class="btn btn-outline-success" for="Clientes-outlined">Clientes</label>
                
                <!--<input type="radio" class="btn-check col" name="switch-four" id="Leads-outlined" value="3"  autocomplete="off" onChange="listar_cuentas_por_cobrar(1);botonSaldoInicial()"  >-->
                <!--<label class="btn btn-outline-success" for="Leads-outlined">Leads</label>-->
                
                <input type="radio" class="btn-check col" name="switch-four" id="Empleados-outlined" value="4"  autocomplete="off" onChange="listar_cuentas_por_cobrar(1);botonSaldoInicial()"  >
                <label class="btn btn-outline-success" for="Empleados-outlined">Empleados</label>
            
            </div>
            
            <div class="col-lg-4 offset-lg-1">    
            
                    <?php        
            $dominio = $_SERVER['SERVER_NAME'];
            
            if(  $dominio=='contaweb.ec' or $dominio=='www.contaweb.ec'){
                ?>
                 <input type="radio" class="btn-check col" name="switch-estado" id="Todos-outlined" value="Todos" autocomplete="off" checked onChange="listar_cuentas_por_cobrar(1)">
                <label class="btn btn-outline-success" for="Todos-outlined">Todos</label>
                <?php
            }
        ?>
        
                <input type="radio" class="btn-check col" name="switch-estado" id="Pendientes-outlined" value="Pendientes" autocomplete="off" checked onChange="listar_cuentas_por_cobrar(1);">
                <label class="btn btn-outline-success" for="Pendientes-outlined">Pendientes</label>
                
                <input type="radio" class="btn-check col" name="switch-estado" id="Canceladas-outlined" value="Canceladas"  autocomplete="off" onChange="listar_cuentas_por_cobrar(1);" >
                <label class="btn btn-outline-success" for="Canceladas-outlined">Canceladas</label>
                
            
            </div>
        </div>  
         <div id="div_listar_cuentas_por_cobrar" class="bg-white rounded mx-5"></div>
     </form>
        
         
       
        
    
       
       
</div>

        <div id="div_oculto"  style="display: none;"></div>
    	<script src="librerias/bootstrap/js/main.js"></script>       
<script type="">  
 
    // function pdfCuentaCobrar(empresa){
    //     console.log(empresa);
    //      miUrl = "reportes/rptCuentaCobrar.php?empresa="+empresa;
    //     window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
    // }
    function pdfCuentaCobrar(empresa){
        console.log(empresa);
         var str = $("#frmCuentasCobrar").serialize();
         miUrl = "reportes/rptCuentaCobrar.php?empresa="+empresa+"&"+str;
        window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
    }
    const eliminarCuentaPorCobrar =  (cuenta)=>{
        alertify.confirm('Eliminar cuenta por cobrar', 'Esta seguro de eliminar este registro?', 
		function(){ 
            $.ajax({
		url: 'sql/cuentas_por_cobrar.php',
		type: 'post',
		data: 'idCuenta='+cuenta+'&txtAccion=5',
		success: function(data){
            let response = data.trim()
            if(response==1){
                alertify.success('Se elimino correctamente la cuenta por cobrar.');
            }else if(response==3){
                alertify.error('Existe un pago realizado en esa cuentas por cobrar.')
            }else if(response==4){
                alertify.error('La cuenta por cobrar pertenece a una venta que no ha sido anulada o eliminada, no se puede eliminar.')
            }else if(response==5){
                alertify.error('La cuenta por cobrar pertenece a una compra que no ha sido anulada o eliminada, no se puede eliminar.')
            }else{
                alertify.error('Hubo un error al eliminar la cuenta por cobrar.')
            }
      listar_cuentas_por_cobrar(1);
           }
       });
		}
		, function(){ alertify.error('Se cancelo')});

}

function pdfAbonoCancelado( idDetalles,tipo,abonado=0, unico=0){
        //  id,tipo, idCuentas
       let id= frmCobrarCuentaCobrarV.txtIdCliente.value;
//  let tipo = frmCobrarCuentaCobrarV.txtTipoPago.value;

 let idCuentas= '0';
 $("input[type=checkbox]:checked[name=checkCobrar[]]").each(function() {
//   alert("Seleccionado el input " + $(this).val());
idCuentas = idCuentas+','+$(this).val();

});
idCuentas.substring(2);
         
         miUrl = "reportes/rptAbonosCuentasCobrar.php?id="+id+"&switch-four="+tipo+"&checkCobrar="+idCuentas+"&idDetalles="+idDetalles+"&abonado="+abonado+"&unico="+unico;
        window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
    }
    
     function botonSaldoInicial(){
        console.log('btnSaldoInicial')
        let boton = document.getElementById('botonSaldo');
        let checkbox = document.getElementById("radio-lead");
        if(boton && checkbox){
            if(checkbox.checked){
                boton.style.display='none';
            }else{
                boton.style.display='block';
            }
           
        }
    }


const reporteExcelCuentasCobrar=()=>{
    let str = $("#frmCuentasCobrar").serialize();
    let miUrl = "reportes/excel_cuentas_cobrar_facturas.php?"+str;
    // console.log(miUrl);
window.open(miUrl);
}

function verAnticipos(){
   
     
        let checkbox = document.getElementById("anticipo-outlined");
        let divAnt = document.getElementById("tipos_anticipos");
            if(checkbox.checked){
                divAnt.style.display='block';
            }else{
                divAnt.style.display='none';
            }
          
    }
function comboAnticipos(valor){
    codigo= valor;
    ajax3=objetoAjax();
    ajax3.open("POST", "sql/busquedas.php",true);
    ajax3.onreadystatechange=function() {
        if (ajax3.readyState==4) {
            var respuesta=ajax3.responseText;
            asignaAnticipos(respuesta);
        }
    }
    ajax3.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax3.send("accion=14&codigo="+codigo)
}

function asignaAnticipos(cadena){
    array = cadena.split( "?" );
    limite=array.length;
    cont=1;
    limpiaAnticipos();
    tipo_anticipo.options[0] = new Option("Seleccionar Anticipo","0");
    for(i=1;i<limite;i=i+2){
        tipo_anticipo.options[cont] = new Option(array[i+1], array[i]);
        cont++;
    }
    listar_cuentas_por_cobrar(1);
}

function limpiaAnticipos()
{
    for (m=tipo_anticipo.options.length-1;m>=0;m--){
        tipo_anticipo.options[m]=null;
    }
} 

function imprimirAnticipo(numero_asiento){
     miUrl = "reportes/rptComprobanteEgreso.php?txtAsientoNumero="+numero_asiento;
    window.open(miUrl,'noimporta','width=600, height=500, scrollbars=NO, titlebar=no');
}
</script>

</body>
</html>

