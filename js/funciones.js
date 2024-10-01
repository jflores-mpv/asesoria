function objetoAjax(){
    var xmlhttp=false;
    try {
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
        try {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (E) {
            xmlhttp = false;
        }
    }

    if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
        xmlhttp = new XMLHttpRequest();
    }
    return xmlhttp;
}


// CARGAR COMBOBOX PAIS, PROVINCIA, CIUDAD
function cargar(){
    combopais(1);
    funCombo1(2);
    
    //cargarBarras();
}

function mostrarcombo()
{
    var valorcombo=document.form.cbpais.value;
    document.form.opcion.value=valorcombo;
    var valorcombo1=document.form.cbprovincia.value;
    document.form.opcion1.value=valorcombo1;
    var valorcombo2=document.form.cbciudad.value;
    document.form.opcion2.value=valorcombo2;
}

function combopais(aux){

    ajax1=objetoAjax();
    ajax1.open("POST", "sql/busquedas.php",true);
    ajax1.onreadystatechange=function(){         
        if (ajax1.readyState==4){
            var respuesta=ajax1.responseText;            
            asignapais(respuesta);
            console.log("PAIS",respuesta);
        }
    }
    ajax1.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax1.send("accion="+aux)
}

function asignapais(cadena){  
    array = cadena.split( "?" );
    limite=array.length;
    cont=1;
    
    limpiapais();
    document.form.cbpais.options[0] = new Option("Seleccione Pais","0");
  for(i=1;i<limite;i=i+2){
    document.form.cbpais.options[cont] = new Option(array[i+1], array[i]);
    cont++;
}
    //document.getElementById("cbpais").selectedIndex = "3";
    document.form.cbpais.selectedIndex = '1'; // seleccion para ecuador
    comboprovincia(2);
}

function limpiapais()
{

    for (m=document.form.cbpais.options.length-1;m>=0;m--){        
        document.form.cbpais.options[m]=null;
    }
}

function comboprovincia(aux){
     if (document.getElementById('cbpais').value){
        codigo=cbpais.value;
    }else{
        codigo=document.form.opcion.value;
    }
   ajax=objetoAjax();
    ajax.open("POST", "sql/busquedas.php",true);
    ajax.onreadystatechange=function(){
        if (ajax.readyState==4){
            var respuesta=ajax.responseText;            
            asignaprovincia(respuesta);
            console.log("CIUDAD",respuesta);
        }
    }
    ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax.send("accion="+aux+"&codigo="+codigo)
}

function asignaprovincia(cadena){
    array = cadena.split( "?" );
    limite=array.length;
    cont=1;
    document.form.opcion1.value="0";
    limpiaprovincia();
    document.form.cbprovincia.options[0] = new Option("Seleccione Provincia","0");
    for(i=1;i<limite;i=i+2){
        document.form.cbprovincia.options[cont] = new Option(array[i+1], array[i]);
        cont++;
    }    
}

function limpiaprovincia()
{
    for (m=document.form.cbprovincia.options.length-1;m>=0;m--){
        document.form.cbprovincia.options[m]=null;
    }
}

function combociudad(aux){
    codigo= document.form.cbprovincia.value;
    ajax3=objetoAjax();
    ajax3.open("POST", "sql/busquedas.php",true);
    ajax3.onreadystatechange=function() {
        if (ajax3.readyState==4) {
            var respuesta=ajax3.responseText;
            asignaciudad(respuesta);
        }
    }
    ajax3.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax3.send("accion="+aux+"&codigo="+codigo)
}

function asignaciudad(cadena){    
    array = cadena.split( "?" );
    limite=array.length;
    cont=1;
    document.form.opcion2.value="0";
    limpiaciudad();
    document.form.cbciudad.options[0] = new Option("Seleccione Ciudad","0");
    for(i=1;i<limite;i=i+2){
        document.form.cbciudad.options[cont] = new Option(array[i+1], array[i]);
        cont++;
    }
}

function limpiaciudad()
{
    for (m=document.form.cbciudad.options.length-1;m>=0;m--){
        document.form.cbciudad.options[m]=null;
    }
}

function cargaCuentasBancos(aux){

    ajax4=objetoAjax();
    ajax4.open("POST", "sql/bancos.php",true);
    ajax4.onreadystatechange=function(){
        if (ajax4.readyState==4){
            var respuesta=ajax4.responseText;
            asignaCuentasBancos(respuesta);            
            //console.log("respuesta",respuesta);
        }
    }
    ajax4.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax4.send("accion="+aux)
}

function asignaCuentasBancos(cadena){
    array = cadena.split( "?" );
    limite=array.length;
    cont=1;

    //limpiaCuentasBancos();
    document.frmConciliacionBancaria.cmbNombreCuentaCB.options[0] = new Option("Seleccione Cuenta","0");
    for(i=1;i<limite;i=i+2){
        document.frmConciliacionBancaria.cmbNombreCuentaCB.options[cont] = new Option(array[i+1], array[i]);
        cont++;
    }
    
}


function cargarDetallesCuentaBancos(aux, id_banco){
    console.log("aux",aux);
    console.log("id_banco",id_banco);
    var str = $("#frmConciliacionBancaria").serialize();
    
    $.ajax({
        url: 'ajax/listarCuentasBancos.php',
        type: 'get',
        data: str+"&aux="+aux+"&id_banco="+id_banco,
        success: function(data){
            
            $("#div_listar_detallesCuentaBancos").html(data);

            calcularCheque();
            calcularDeposito();
            calcularNotaCredito();
            calcularNotaDebito();
            calcularTransferencia();
            calcularTransferenciaC();
            saldoTotalConciliado(3);
        }

    });
}

//FIN CARGAR COMBOBOX

// reloj
function mueveReloj(){
    momentoActual = new Date()
    hora = momentoActual.getHours()
    minuto = momentoActual.getMinutes()
    segundo = momentoActual.getSeconds()

    str_segundo = new String (segundo)
    if (str_segundo.length == 1)
     segundo = "0" + segundo

 str_minuto = new String (minuto)
 if (str_minuto.length == 1)
     minuto = "0" + minuto

 str_hora = new String (hora)
 if (str_hora.length == 1)
     hora = "0" + hora

 horaImprimible = hora + " : " + minuto + " : " + segundo

 document.form_reloj.reloj.value = horaImprimible

 setTimeout("mueveReloj()",1000)
}
// FIN RELOJ
//
// saca los datos de l atabla empresa para mostrarlos pagina:empresa.php
//function consulta_empresa(consulta)
//{
//
//    ajax2=objetoAjax();
//    ajax2.open("POST", "sql/Empresa.php",true);
//    ajax2.onreadystatechange=function() {
//        if (ajax2.readyState==4) {
//            var respuesta2=ajax2.responseText;
////            alert("consulta: "+consulta+" rp consulta: "+respuesta2);
//            asignar_formulario(respuesta2);
//        }
//    }
//    ajax2.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
//    ajax2.send("accion="+consulta);
//
//}


// acsigna al formulario de la empresa pagina: empresa.php
//function asignar_formulario(cadena){
//
//    array = cadena.split("?");
//    var fecha = array[13];
//    document.form.txtFechaInicio.value = comvertirFecha(fecha);
//    document.form.txtInfGeneral.value = array[14];
//    document.form.txtPerfilEmpresa.value = array[15];
//    document.form.txtDescripcion.value = array[16];
//    document.form.txtMision.value = array[17];
//    document.form.txtVision.value = array[18];
//    var idEmpleado = document.form.txtIdEmpleado.value = array[19];
//    filtrar_empleado(idempleado);
//    document.form.txtIdEmpresa.value = array[1];
//    document.form.txtRuc.value = array[2];
//    document.form.txtNombreEmpresa.value = array[3];
//    document.form.txtDireccion.value = array[4];
//    document.form.cbpais.value = array[5];
//
//    comboprovincia(2);
//    mostrarcombo();
//    document.form.cbprovincia.value = array[6];
//// NO FUNCIONA EL SELECCIONAR EL ITEM DEL COMBO BOX
//
//
//   // document.getElementById("cbpais").selectedIndex = array[5];
//
//    //seleccionar(array[5]);
//
//    document.form.cbprovincia.value = array[6];
//    seleccionar(array[6]);
//
//
//
//    //document.form.cbprovincia.selected = '2';
//
////    mostrarcombo();
////    combociudad(3);
////
////    document.form.cbciudad.value = array[7];
////    mostrarcombo();
//
//
//
//
//
//    document.form.txtTelefono1.value = array[8];
//    document.form.txtTelefono2.value = array[9];
//    document.form.txtEmail.value = array[10];
//    document.form.txtWeb.value = array[11];
//    document.getElementById("imagen").innerHTML="<img src='"+array[12]+"'>";
//    document.form.txtImagen.value = array[12];
//
//
//}



//function filtrar_empleado(id){
//// funcion para que saque el nombre del usurio de l atabla empleados y los muestre en formulario pagina: empresa.php
//    ajax5=objetoAjax();
//    ajax5.open("POST", "sql/empleados.php",true);
//    ajax5.onreadystatechange=function() {
//        if (ajax5.readyState==4) {
//            var respuesta3=ajax5.responseText;
//            asignar_empleado(respuesta3);
//        }
//    }
//    ajax5.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
//    ajax5.send("id_empleado="+id+"&txtAccion=5");
//}

//function asignar_empleado(cadena){
//    array1 = cadena.split("?");
//    document.form.txtNombreResponsable.value = array1[2]+" "+array1[3];
//}


function upload_files()
{
    var consulta = document.form.txtAction.value;
    var txtImagen = document.form.txtImagen.value;
    ajax6=objetoAjax();
    ajax6.open("POST", "sql/upload.php", true);
    ajax6.onreadystatechange=function(){
        if (ajax6.readyState==4) {
            var respuesta2=ajax6.responseText;
            alert("upload_files: "+txtImagen+" rp consulta: "+respuesta2);
//          asignar_formulario(respuesta2);
document.getElementById("respuestaImg").innerHTML=""+respuesta2;
}
}
ajax6.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
ajax6.send("accion="+consulta+"&txtImagen="+txtImagen);
}

// Funcion para conertir una fecha en numeros a letras - de 10/04/2013 a - miercoles 10 de abril del 2013
function comvertirFecha(fecha){ 
    var cadena=fecha.split("-");// elimina el /
    var dia1 = cadena[2];// guarda en variable
    var dia=parseFloat(dia1)// elima el cero
    var mes1 = cadena[1];// guarda en variable
    var mes=parseFloat(mes1)// elima el cero
    var ano = cadena[0];// guarda en variable
    var mesletra = "";
    switch(mes)
    {
        case 1:mesletra="Enero";break;
        case 2:mesletra="Febrero";break;
        case 3:mesletra="Marzo";break;
        case 4:mesletra="Abril";break;
        case 5:mesletra="Mayo";break;
        case 6:mesletra="Junio";break;
        case 7:mesletra="Julio";break;
        case 8:mesletra="Agosto";break;
        case 9:mesletra="Septiembre";break;
        case 10:mesletra="Octubre";break;
        case 11:mesletra="Noviembre";break;
        case 12:mesletra="Diciembre";break;
    }
    var fechanueva = dia+" de "+mesletra+" del "+ano;
    return fechanueva;
    /*  // para convertir un numero de la semanaen en letras
    var dayArray = new Array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo');
    var day = dayArray[dia];    */
}


// STAR para imprimir los periodos contables en el combobox pagina: compararPeriodoContable.php
function mostrarcombo1(form)
{        
         //retorna en compararPeriodoContable.php
         var valorcombo=form.elements['cmbperiodo1'].value;
         form.elements['txtIdPeriodo1'].value=valorcombo;
         var valorcombo1=form.elements['cmbperiodo2'].value;
         form.elements['txtIdPeriodo2'].value=valorcombo1;

     }

     function funCombo1(aux, form){
        ajax4=objetoAjax();
        ajax4.open("POST", "sql/periodo_contable.php",true);
        ajax4.onreadystatechange=function() {
            if (ajax4.readyState==4) {
                var respuesta=ajax4.responseText;
                asignaCombo1(respuesta,form);
                asignarCombo2(respuesta);
            }
        }
        ajax4.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
        ajax4.send("accion="+aux)
    }

    function asignaCombo1(cadena, form){
        array = cadena.split( "?" );
        limite=array.length-1;    
        cont=1;
        limpiaCombo1(form);
        form.elements['cmbperiodo1'].options[0] = new Option("Seleccione Periodo","0");
        for(i=0;i<limite;i=i+3){
            form.elements['cmbperiodo1'].options[cont] = new Option(comvertirFecha(array[i+2])+"  /  "+comvertirFecha(array[i+3]), array[i+1]);
        form.elements['cmbperiodo1'].options[cont].title = comvertirFecha(array[i+2])+"  /  "+comvertirFecha(array[i+3]);// codigo para tooltip cuando el mouse esta over
        cont++;
    }
}

function limpiaCombo1(form)
{
    for (m=form.elements['cmbperiodo1'].options.length-1;m>=0;m--){
        form.elements['cmbperiodo1'].options[m]=null;
    }
}


function asignarCombo2(cadena){
    array = cadena.split( "?" );
    limite=array.length-1;
    cont=1;
    limpiaCombo2();
    document.form.cmbperiodo2.options[0] = new Option("Seleccione Periodo","0");
    for(i=0;i<limite;i=i+3){
        document.form.cmbperiodo2.options[cont] = new Option(comvertirFecha(array[i+2])+"  /  "+comvertirFecha(array[i+3]), array[i+1]);
        document.form.cmbperiodo2.options[cont].title = comvertirFecha(array[i+2])+"  /  "+comvertirFecha(array[i+3]);// codigo para tooltip cuando el mouse esta over
        cont++;
    }
}

function limpiaCombo2()
{
    for (m=document.form.cmbperiodo2.options.length-1;m>=0;m--){
        document.form.cmbperiodo2.options[m]=null;
    }
}
// FIN para imprimir los periodos contables en el combobox pagina: compararPeriodoContable.php


// STAR carga las barras para comparar periodos contables pagina: compararPeriodoContable.php
function cargarBarras(ingresosDesde, gastosDesde, ingresosHasta, gastosHasta){
//        var ingresosDesde1 = 10;
//        var gastosDesde1 = 20;
//        var ingresosHasta1 = 50;
//        var gastosHasta1 = 8;

//alert(valorcombo1+" "+valorcombo2);

    //alert("cadena: "+ingresosDesde+" "+gastosDesde+" "+ingresosHasta+" "+gastosHasta);
    var chart;

    var chartData = [{
        country: "Ingresos",
        visits:  ingresosDesde,
        color: "#FF0F00"
    }, {
        country: "Gastos",
        visits:  gastosDesde,
        color: "#FF0F00"
    },{
        country: "Ingresos",
        visits:  ingresosHasta,
        color: "#ADD981"
    },{
        country: "Gastos",
        visits:  gastosHasta,
        color: "#FF0F00"
    }];


    AmCharts.ready(function () {
        // SERIAL CHART
        chart = new AmCharts.AmSerialChart();
        chart.dataProvider = chartData;
        chart.categoryField = "country";
        chart.startDuration = 2;

        // AXES
        // category
        var categoryAxis = chart.categoryAxis;
        categoryAxis.labelRotation = 1;
        categoryAxis.gridPosition = "start";

        // value
        // in case you don't want to change default settings of value axis,
        // you don't need to create it, as one value axis is created automatically.

        // GRAPH
        var graph = new AmCharts.AmGraph();
        graph.valueField = "visits";
        graph.colorField = "color";
        graph.balloonText = "[[category]]: [[value]]";
        graph.type = "column";
        graph.lineAlpha = 0;
        graph.fillAlphas = 0.7;
        chart.addGraph(graph);

        chart.write("chartdiv");
    });
}

 // STAR carga las barras para comparar periodos contables pagina: compararPeriodoContable.php
 function generarBarras(desde, hasta, accion){
    //desde = dede.value;
    //alert("desde="+desde.value+" hasta="+hasta.value+" accion="+accion);
    ajax7=objetoAjax();
    ajax7.open("POST", "sql/periodo_contable.php",true);
    ajax7.onreadystatechange=function() {
        if (ajax7.readyState==4) {
            var respuesta7=ajax7.responseText;            
            divideCadenaMayor(respuesta7);            
        }
    }
    ajax7.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax7.send("desde="+desde.value+"&hasta="+hasta.value+"&accion="+accion);
}

function divideCadenaMayor(cadenaMayor){     
   array = cadenaMayor.split( "/" );
   cadenaDesde = array[0];
   cadenaHasta = array[1];

    // dividir Cadena Desde
    arrayDesde = cadenaDesde.split( "?" );
    ingresosDesde = arrayDesde[3];
    gastosDesde = arrayDesde[4];

     // dividir Cadena Hasta
     arrayHasta = cadenaHasta.split( "?" );
     ingresosHasta = arrayHasta[3];
     gastosHasta = arrayHasta[4];
     cargarBarras(ingresosDesde,gastosDesde,ingresosHasta,gastosHasta);
 }

/* //ELIMINAR
// Funcion para sacar los datos de la tabla periodo_contable pagina: libroDiario.php, rolPagos.php
 function periodoContable(accion){
     
    ajax8=objetoAjax();
    ajax8.open("POST", "sql/periodo_contable.php",true);
    ajax8.onreadystatechange=function() {
        if (ajax8.readyState==4) {
            var respuesta8=ajax8.responseText;            
            var cadena8=respuesta8.split("?");// elimina el ?
            var idPeridoContable8 = cadena8[1];
            var fechadesde8 = comvertirFecha(cadena8[2]);
            var fechahasta8 = comvertirFecha(cadena8[3]);  
            // rol de pagos
            document.getElementById("txtIdPeriodoContable").value= idPeridoContable8;
            
            document.frmNuevaTransaccion.txtPeriodo.value = fechadesde8+" al "+fechahasta8;
            document.frmNuevaTransaccion.txtPeriodo.title = fechadesde8+" al "+fechahasta8;
            document.frmNuevaTransaccion.txtIdPeriodoContable.value = idPeridoContable8;
            document.frmNuevaTransaccion.txtFechaDesde.value = cadena8[2];
        }
    }
    ajax8.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax8.send("accion="+accion);
 }
 */

/* // FUNCION PARA MOSTRAR LA NUMERACION PARA UN PLAN DE CUENTAS PAGINA: nuevoPlanCuentas.php y modificarplanCuentas.php
function codigoBase(){
    var valorcombo=document.getElementById("cmbClasificacion").value;
    var cod=0;
        if(valorcombo == 'Activo'){
             cod = "1";
        }if(valorcombo == 'Pasivo'){
             cod = "2";
        }if(valorcombo == 'Patrimonio'){
             cod = "3";
        }if(valorcombo == 'Gastos'){
             cod = "4";
        }if(valorcombo == 'Ingresos'){
             cod = "5";
        }if(valorcombo == 'Cuentas Contingentes'){
             cod = "6";
        }if(valorcombo == 'Cuentas de orden'){
             cod = "7";
        }    
        document.form.txtCodigo.value = cod;
//         clasificacion = document.form.txtCodigo.value;

        ajax9=objetoAjax();
        ajax9.open("POST", "sql/plan_cuentas.php",true);
        ajax9.onreadystatechange=function() {            
            if (ajax9.readyState==4) {                
                var respuesta9=ajax9.responseText;
                var cadena9=respuesta9.split("?");// elimina el ? split("?")                
                if(cadena9[1] == undefined){                    
                    limpiarcombo();
                     document.form.cmbNivel.options[0] = new Option("Seleccione nivel","0");                     
                     document.form.cmbNivel.options[1] = new Option(1, 1);                    
                }
                convertir = cadena9[1].replace(/\D/g,'');                
                limpiarcombo();
                document.form.cmbNivel.options[0] = new Option("Seleccione nivel","0");
                for(i = 1; i <= convertir.length+1; i++) {
                    document.form.cmbNivel.options[i] = new Option(i, i);
                }
            }
        }
        ajax9.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
        ajax9.send("accion="+3+"&clasificacion="+valorcombo);
 }

function limpiarcombo()
{
    for (m=document.form.cmbNivel.options.length-1;m>=0;m--){
        document.form.cmbNivel.options[m]=null;
    }
}
*/

/*
//funcion para filtrar por nivel de codigo pagina: nuevoPlanCuentas.php
function nivelCodigo(){
    var codigo=document.getElementById("txtCodigo").value;
    var codigoEntero = 0;
    codigoEntero = parseInt(codigo);
    var clasificacion=document.getElementById("cmbClasificacion").value;
    var nivel=document.getElementById("cmbNivel").value;

    ////
    ajax10=objetoAjax();
    ajax10.open("POST", "sql/plan_cuentas.php",true);
    ajax10.onreadystatechange=function() {
        if (ajax10.readyState==4) {
            var respuesta10=ajax10.responseText;
            
            var cadena10=respuesta10.split("?");// elimina el ?
            
            convertir = cadena10[1].replace(/\D/g,'');
            //alert(convertir);
              if(convertir.length == 0){
                  document.form.txtCodigo.value = "";
                  document.form.txtCodigo.value = codigoEntero;
                }
                if(nivel == 1){                    
                     if(convertir == 0){
                        nivel1(nivel, clasificacion);
                    }else{
                    numentero = parseInt(convertir);
                    sum = numentero;
                    document.form.txtCodigo.value = "";
                    document.form.txtCodigo.value = sum;
                    }                    
                }
                if(nivel == 2){
                    if(convertir == 0){
                        nivel3(nivel, clasificacion);
                    }else{                   
                    numentero = parseInt(convertir);
                    sum = numentero +1;
                    document.form.txtCodigo.value = "";
                    document.form.txtCodigo.value = sum;
                    }
                }
                if(nivel == 3){
                    if(convertir == 0){
                        nivel3(nivel, clasificacion);                        
                    }else{                        
                        numentero = parseInt(convertir);
                        sum = numentero +1;
                        document.form.txtCodigo.value = "";
                        document.form.txtCodigo.value = sum;
                    }                    
                }
                if(nivel == 4){
                    if(convertir == 0){
                        nivel3(nivel, clasificacion);
                    }else{
                        numentero = parseInt(convertir);
                        sum = numentero +1;
                        document.form.txtCodigo.value = "";
                        document.form.txtCodigo.value = sum;
                    }
                }
                if(nivel == 5){
                    if(convertir == 0){
                        nivel3(nivel, clasificacion);
                    }else{
                        numentero = parseInt(convertir);
                        sum = numentero +1;
                        document.form.txtCodigo.value = "";
                        document.form.txtCodigo.value = sum;
                    }
                }
        }
    }
    ajax10.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax10.send("accion="+4+"&clasificacion="+clasificacion+"&nivel="+nivel);
 }

// nivel 1
function nivel1(niv, clasi){

    var clasificacion= clasi;
    var nivel=niv-1;
    ajax11=objetoAjax();
    ajax11.open("POST", "sql/plan_cuentas.php",true);
    ajax11.onreadystatechange=function() {
        if (ajax11.readyState==4) {
            var respuesta11=ajax11.responseText;
            var cadena11=respuesta11.split("?");// elimina el ?
            convertir = cadena11[1].replace(/\D/g,'');
            document.form.txtCodigo.value = convertir+"1";
        }
    }
    ajax11.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax11.send("accion="+5+"&clasificacion="+clasificacion+"&nivel="+nivel);
}

// nivel 3
function nivel3(niv, clasi){
    
    var clasificacion= clasi;
    var nivel=niv-1;
    ajax11=objetoAjax();
    ajax11.open("POST", "sql/plan_cuentas.php",true);
    ajax11.onreadystatechange=function() {
        if (ajax11.readyState==4) {            
            var respuesta11=ajax11.responseText;            
            var cadena11=respuesta11.split("?");// elimina el ?
            convertir = cadena11[1].replace(/\D/g,'');
            document.form.txtCodigo.value = convertir+"1";
        }
    }
    ajax11.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax11.send("accion="+5+"&clasificacion="+clasificacion+"&nivel="+nivel);
}
*/

function opcionPeriodoContable(){

    for(i=0;i<document.form.checkbox1.length;i++){
        if(document.form.checkbox1[i].checked) {
            marcado=i;
        }
    }

    if(document.form.checkbox1[marcado].value == "Anual"){
        var fechaAnual = new Date();
        dia = fechaAnual.getDate();
        mes = fechaAnual.getMonth() + 1;
        anio = fechaAnual.getFullYear();
        //tiempo = prompt("Ingrese la cantidad de d\u00edas a a\u00f1adir");
        tiempo = 365;// 365 = 1 año
        addTime = tiempo * 86400; //Tiempo en segundos
        fechaAnual.setSeconds(addTime); //Añado el tiempo

        dia2 = (dia < 10) ? '0' + dia : dia;//aumenta cero
        mes2 = (mes < 10) ? '0' + mes : mes;//aumenta cero

        document.form.txtFechaIngreso.value = anio+"-"+mes2+"-"+dia2;
        //document.getElementById("mensaje1").innerHTML = "Tiempo a\u00f1adido: " + tiempo + " d\u00edas";
        dia3 = fechaAnual.getDate();
        dia4 = (dia3 < 10) ? '0' + dia3 : dia3;//aumenta cero
        mes3 = fechaAnual.getMonth()+1;
        mes4 = (mes3 < 10) ? '0' + mes3 : mes3;//aumenta cero
        ano3 = fechaAnual.getFullYear();

        document.form.txtFechaSalida.value = ano3+"-"+mes4+"-"+dia4;
    }

    if(document.form.checkbox1[marcado].value == "Semestral"){

        var fechaSemestral = new Date();
        dia = fechaSemestral.getDate();
        mes = fechaSemestral.getMonth() + 1;
        anio = fechaSemestral.getFullYear();
        //tiempo = prompt("Ingrese la cantidad de d\u00edas a a\u00f1adir");
        tiempo = 182;// 182 = 6 meses
        addTime = tiempo * 86400; //Tiempo en segundos
        fechaSemestral.setSeconds(addTime); //Añado el tiempo

        dia2 = (dia < 10) ? '0' + dia : dia;//aumenta cero
        mes2 = (mes < 10) ? '0' + mes : mes;//aumenta cero

        document.form.txtFechaIngreso.value = anio+"-"+mes2+"-"+dia2;
        //document.getElementById("mensaje1").innerHTML = "Tiempo a\u00f1adido: " + tiempo + " d\u00edas";
        dia3 = fechaSemestral.getDate();
        dia4 = (dia3 < 10) ? '0' + dia3 : dia3;//aumenta cero
        mes3 = fechaSemestral.getMonth()+1;
        mes4 = (mes3 < 10) ? '0' + mes3 : mes3;//aumenta cero
        ano3 = fechaSemestral.getFullYear();
        
        document.form.txtFechaSalida.value = ano3+"-"+mes4+"-"+dia4;

    }

    if(document.form.checkbox1[marcado].value == "Personalizado"){         
     document.getElementById("calendario2").style.visibility="visible";
     document.getElementById("calendario1").style.visibility="visible";
 }else{
     document.getElementById("calendario2").style.visibility="hidden";
     document.getElementById("calendario1").style.visibility="hidden";
 }	
}



//****************** RETENCIONES ****************//
function comboretencion(aux, codigo){
    //saca el porcentaje del combobox seleccionado para la retencion de la factura pagina ajax/nuevaRetencionC.php
    id = String(codigo.value);
    ajax13=objetoAjax();
    ajax13.open("POST", "sql/retenciones.php",true);
    ajax13.onreadystatechange=function(){
        if (ajax13.readyState==4){
            var respuesta13=ajax13.responseText;
            calcularRetencion(respuesta13);
        }
    }
    ajax13.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax13.send("accion="+aux+"&codigo="+id)
}

function calcularRetencion(cadena){
    porsentaje = cadena;
    base = document.frmRetencion.txtBase.value;
    respuest = parseFloat(base*porsentaje)/100;
    document.frmRetencion.txtRetenido.value = respuest;
    document.frmRetencion.txtTotal.value = respuest;
}


function comboPorcentajeRetencion(aux, form){
    //saca los porcentaje de retencion y los muestra en el combobox para la retencion de los activos pagina ajax/nuevoActivo.php
    if(cmbRetenciones.value == "Si"){
        ajax131=objetoAjax();
        ajax131.open("POST", "sql/retenciones.php",true);
        ajax131.onreadystatechange=function(){
            if (ajax131.readyState==4){
                var respuesta131=ajax131.responseText;
                muestraPorcentajeRetencion(respuesta131, form);
            }
        }
        ajax131.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
        ajax131.send("accion="+aux)
    }else{
        limpiaPorcentajeRetencion(form);
    }    
}

function muestraPorcentajeRetencion(respuesta, form){
    cadena = respuesta.split("-");
    limite=cadena.length;
    cont=1;
    cont2=1;
    limpiaPorcentajeRetencion(form);
    form.elements['cmbPorcentajeRetencion'].options[0] = new Option("Seleccione Porcentaje","0");
    form.elements['cmbPorcentajeRetencion2'].options[0] = new Option("Seleccione Porcentaje","0");
    //document.frmNuevoActivo.cmbPorcentajeRetencion.options[0] = new Option("Seleccione Porcentaje","0");
    //document.frmNuevoActivo.cmbPorcentajeRetencion2.options[0] = new Option("Seleccione Porcentaje","0");
    for(i=0;i<limite-1;i=i+2){
        //document.frmNuevoActivo.cmbPorcentajeRetencion.options[cont] = new Option(cadena[i+1]+"%", cadena[i]);
        form.elements['cmbPorcentajeRetencion'].options[cont] = new Option(cadena[i+1]+"%", cadena[i]);
        cont++;
    }
    for(i=0;i<limite-1;i=i+2){
        //document.frmNuevoActivo.cmbPorcentajeRetencion2.options[cont2] = new Option(cadena[i+1]+"%", cadena[i]);
        form.elements['cmbPorcentajeRetencion2'].options[cont2] = new Option(cadena[i+1]+"%", cadena[i]);
        cont2++;
    }
}
function limpiaPorcentajeRetencion(form)
{
    for (m=form.elements['cmbPorcentajeRetencion'].options.length-1;m>=0;m--){
        //document.frmNuevoActivo.cmbPorcentajeRetencion.options[m]=null;
        form.elements['cmbPorcentajeRetencion'].options[m]=null;
    }
    for (m=form.elements['cmbPorcentajeRetencion2'].options.length-1;m>=0;m--){
        //document.frmNuevoActivo.cmbPorcentajeRetencion2.options[m]=null;
        form.elements['cmbPorcentajeRetencion2'].options[m]=null;
    }
}

function calculaRetencionRenta(aux, codigo, form){
    //saca el porcentaje del combobox seleccionado para la retencion a la renta del activo pagina ajax/nuevoActivo.php
    id = String(codigo.value);
    ajax133=objetoAjax();
    ajax133.open("POST", "sql/retenciones.php",true);
    ajax133.onreadystatechange=function(){
        if (ajax133.readyState==4){
            var respuesta133=ajax133.responseText;
            retencionValorCompra(respuesta133, form);
        }
    }
    ajax133.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax133.send("accion="+aux+"&codigo="+id)
}

function retencionValorCompra(cadena, form){
    porsentaje = cadena;
    base = form.elements['txtPrecio'].value;
    respuest = parseFloat(base*porsentaje)/100;
    form.elements['txtRetencionRenta'].value = respuest.toFixed(2);
    //document.frmNuevoActivo.txtRetencionRenta.value = respuest;
}

function calculaRetencionIva(aux, codigo, form){
    //saca el porcentaje del combobox seleccionado para la retencion del IVA del activo pagina ajax/nuevoActivo.php
    id = String(codigo.value);
    ajax134=objetoAjax();
    ajax134.open("POST", "sql/retenciones.php",true);
    ajax134.onreadystatechange=function(){
        if (ajax134.readyState==4){
            var respuesta134=ajax134.responseText;
            retencionIva(respuesta134, form);
        }
    }
    ajax134.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax134.send("accion="+aux+"&codigo="+id)
}
function retencionIva(cadena, form){
    porsentaje = cadena;
    //base = document.frmNuevoActivo.txtIva.value;
    base = form.elements['txtIva'].value;
    respues = parseFloat((base*porsentaje)/100);
    //document.frmNuevoActivo.txtRetencionIva.value = respuest;    
    form.elements['txtRetencionIva'].value = respues.toFixed(2);
}

//****************** PRESTAMOS ****************//
function calcular(txtPrestamo, txtFechaInicio, txtTazaInteres, cmbPlazo){
    //Funcion para calcular un nuevo prestamos Pagina: ajax/nuevoPrestamo;
    fecha_inicio = txtFechaInicio.value;
    plazo_meses = parseInt(cmbPlazo.value);
    taza_intereses = parseFloat(txtTazaInteres.value);
    prestamo = parseFloat(txtPrestamo.value);
    importeFinal = (prestamo + ((prestamo * taza_intereses)/100)).toFixed(2);
    document.formnp.txtImFinal.value = importeFinal;    
    valor_cuotas = (parseFloat(importeFinal/(plazo_meses+1))).toFixed(2);
    document.formnp.txtCuotas.value = valor_cuotas;
    intereses = ((prestamo * taza_intereses)/100).toFixed(2);
    document.formnp.txtIntereses.value = intereses; 
    fecha = sumaFecha(plazo_meses,fecha_inicio);
    document.formnp.txtFechaFin.value = fecha;
}

sumaFecha = function(m, fecha)
{
   var Fecha = new Date();
   var sFecha = fecha || (Fecha.getDate() + "/" + (Fecha.getMonth() +1) + "/" + Fecha.getFullYear());    
   var sep = sFecha.indexOf('/') != -1 ? '/' : '-'; 
   var aFecha = sFecha.split(sep);
   var meses = m || 0;
   var mes = parseInt(aFecha[1]); 
   aFecha[1] = mes + meses;

 var fFecha = Date.UTC(aFecha[0],aFecha[1],aFecha[2])+(86400000); // 86400000 son los milisegundos que tiene un día
 var fechaFinal = new Date(fFecha);

 var anno = fechaFinal.getFullYear();
 var mes = fechaFinal.getMonth();
 mes = parseInt(mes)+1;
 var dia = fechaFinal.getDate();
 var mes = (mes < 10) ? ("0" + mes) : mes;
 var dia = (dia < 10) ? ("0" + dia) : dia;
 var fechaFinal = anno+sep+mes+sep+dia;

 return (fechaFinal);

}

function listar_cuotas(){

    cadena = "<table id='grilla' class='lista' width='600px'>";
    cadena = cadena+"<thead>";
    cadena = cadena+"<tr>";
    cadena = cadena+"<th><strong>Ide</strong></th>";
    cadena = cadena+"<th><strong>C&eacute;dula / Nombre</strong></th>";
    cadena = cadena+"<th><strong>Fecha Pago</strong></th>";
    cadena = cadena+"<th><strong>Saldo</strong></th>";
    cadena = cadena+"<th><strong>Cuota</strong></th>";
    cadena = cadena+"<th><strong>Estado</strong></th>";
    cadena = cadena+"<th><a onclick='javascript: listar_prestamos();' title='Actualizar'><img alt='' width='16px' height='16px' src='images/actualizar16.png'></a></th>";
    cadena = cadena+"<th><a href='javascript: nuevoPrestamo();' title='Agregar nuevo Prestamo'><img alt='' src='images/add.png'></a></th>";
    cadena = cadena+"</tr>";
    cadena = cadena+"</thead>";
    cadena = cadena+"<tbody>";
    
    plazo = document.formnp.cmbPlazo.value;
    empleado = document.formnp.txtCliente1.value;
    fecha_inicio = document.formnp.txtFechaInicio.value;
    imp_final = parseFloat(document.formnp.txtImFinal.value);
    valor_cuotas = parseFloat(document.formnp.txtCuotas.value).toFixed(2);
    contador = 0;
    valor_restante = imp_final;
    for(i=0; i<=plazo; i++){
        contador++;
        valor_restante = (valor_restante - valor_cuotas).toFixed(2);
        fecha = sumaFecha(i,fecha_inicio);
        if(contador%2==0){
            cadena = cadena+"<tr class='odd' id='tr1'>";
            cadena = cadena+"<td>"+contador+"</td>";
            cadena = cadena+"<td>"+empleado+"</td>";
            cadena = cadena+"<td>"+fecha+"<input type='hidden' id='txtFecha"+contador+"' value='"+fecha+"' name='txtFecha"+contador+"' /></td>";
            cadena = cadena+"<td>"+valor_restante+"</td>";
            cadena = cadena+"<td>"+valor_cuotas+"</td>";
            cadena = cadena+"<td>Adeudar</td>";
            cadena = cadena+"<td></td>";
            cadena = cadena+"<td></td>";
            cadena = cadena+"</tr>";
        }
        if(contador%2==1){
            cadena = cadena+"<tr class='even' id='tr2'>";
            cadena = cadena+"<td>"+contador+"</td>";
            cadena = cadena+"<td>"+empleado+"</td>";
            cadena = cadena+"<td>"+fecha+"<input type='hidden' id='txtFecha"+contador+"' value='"+fecha+"' name='txtFecha"+contador+"' /></td>";
            cadena = cadena+"<td>"+valor_restante+"</td>";
            cadena = cadena+"<td>"+valor_cuotas+"</td>";
            cadena = cadena+"<td>Adeudar</td>";
            cadena = cadena+"<td></td>";
            cadena = cadena+"<td></td>";
            cadena = cadena+"</tr>";
        }
    }  

    cadena = cadena+"</tbody>";
    cadena = cadena+"</table>";
    //$("#tblTransaccion tbody").append(cadena);
    $("#div_listar_cp").html(cadena);
}

function saca_datos(id, accion){
    codigo = String(id.value);
    cadena = codigo.split("?");
    ajax14=objetoAjax();
    ajax14.open("POST", "sql/prestamos.php",true);
    ajax14.onreadystatechange=function(){
        if (ajax14.readyState==4){
            var respuesta14=ajax14.responseText;
            mostrarDatos(respuesta14);
        }
    }
    ajax14.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax14.send("txtAccion="+accion+"&id_prestamo="+cadena[0]+"&fecha_inicio")
}

//****************** CALCULO DE LA DEPRECIACION ****************//
function calcula_depreciacion(txtPrecio, txtPorcentajeDepre, txtVidaUtil, txtValorResidual, frm){
    //Funcion para calcular LA DEPRECION Pagina: ajax/nuevoActivo;    
    valor_compra = parseFloat(txtPrecio.value);
    porcentaje_depre = parseInt(txtPorcentajeDepre.value);
    vida_util = parseInt(txtVidaUtil.value);
    valorResidual = parseInt(txtValorResidual.value);
    var depreciacion = 0;
    if(valorResidual > 0){        
        depreciacion = ((valor_compra - valorResidual) * porcentaje_depre/100).toFixed(2);//ANUAL
        frm.elements['txtDepreciacionAnual'].value = depreciacion;
        frm.elements['txtDepreciacionMensual'].value = (depreciacion/12).toFixed(2);//MENSUAL
    }else{
        depreciacion = ((valor_compra*porcentaje_depre) / 100).toFixed(2);//ANUAL
        frm.elements['txtDepreciacionAnual'].value = depreciacion;
        frm.elements['txtDepreciacionMensual'].value = (depreciacion/12).toFixed(2);//MENSUAL
    }
    //valor_residual = valor_compra / 10;//saca el diez porciento del valor de compra
    //depreciacion = ((valor_compra - valor_residual) / vida_util).toFixed(2);   

}

//***************** PLAN CUENTAS **********************//







//**************** USUARIO ********************//

function comboEmpleados(aux){
    ajax19=objetoAjax();
    ajax19.open("POST", "sql/usuarios.php",true);
    ajax19.onreadystatechange=function(){
        if (ajax19.readyState==4){
            var respuesta=ajax19.responseText;
            asignaEmpleados(respuesta);
        }
    }
    ajax19.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax19.send("txtAccion="+aux)
}

function asignaEmpleados(cadena){
    array = cadena.split( "?" );
    limite=array.length;
    cont=1;
    limpiaEmpleados();
    document.form.cmbEmpleados.options[0] = new Option("Seleccione Empleados","0");
    for(i=1;i<limite;i=i+2){
        document.form.cmbEmpleados.options[cont] = new Option(array[i+1], array[i]);
        cont++;
    }
}

function limpiaEmpleados()
{
    for (m=document.form.cmbEmpleados.options.length-1;m>=0;m--){
        document.form.cmbEmpleados.options[m]=null;
    }
}

//********************* IMPUESTOS/IVA ****************

function impuestoValorAgregado(txtPrecio, aux, form){
    if(cmbImpuesto.value == "Si"){
        ajax20=objetoAjax();
        ajax20.open("POST", "sql/impuestos.php",true);
        ajax20.onreadystatechange=function(){
            if (ajax20.readyState==4){
                var respuesta=ajax20.responseText;
                asignaIva(respuesta, txtPrecio.value, form);
            }
        }
        ajax20.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
        ajax20.send("txtAccion="+aux);
    }else{
        document.frmNuevoActivo.txtIva.value=0;
    }    
}

function asignaIva(porcentaje, txtPrecio, form){
    iva = ((txtPrecio * porcentaje) /100);
    //document.frmNuevoActivo.txtIva.value=iva;
    form.elements['txtIva'].value=iva.toFixed(2);
}

//************************* PROVEEDORES *******************
function comboProveedores(txtAccion, codigo, form){
    ajax21=objetoAjax();
    ajax21.open("POST", "sql/proveedores.php",true);
    ajax21.onreadystatechange=function(){
        if (ajax21.readyState==4){            
            var respuesta=ajax21.responseText;
            asignaProveedores(respuesta, form);
        }
    }
    ajax21.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax21.send("txtAccion="+txtAccion+"&codigo="+codigo)
}

function asignaProveedores(cadena, form){    
    array = cadena.split( "-" );
    limite=array.length;    
    cont=1;
    limpiaProveedores(form);
    //document.frmNuevoActivo.cmbProveedor.options[0] = new Option("Seleccione Proveedor","0");
    form.elements['cmbProveedor'].options[0] = new Option("Seleccione Proveedor","0");
    for(i=0;i<limite-1;i=i+2){
        //document.frmNuevoActivo.cmbProveedor.options[cont] = new Option(array[i+1], array[i]);
        form.elements['cmbProveedor'].options[cont] = new Option(array[i+1], array[i]);
        cont++;
    }
}

function limpiaProveedores(form)
{
    for (m=form.elements['cmbProveedor'].options.length-1;m>=0;m--){
        form.elements['cmbProveedor'].options[m]=null;
        //document.frmNuevoActivo.cmbProveedor.options[m]=null;
    }
}

//*********************** VER LIBRO DIARIO ***********************************

function mostrarIdPeriodo(form, accion){
    //retorna en verLibroDiario.php
    var valorcombo2=form.elements['cmbperiodo1'].value;
    form.elements['txtIdPeriodoContable'].value=valorcombo2;

    ajax22=objetoAjax();
    ajax22.open("POST", "sql/periodo_contable.php",true);
    ajax22.onreadystatechange=function(){
        if (ajax22.readyState==4){
            var respuesta=ajax22.responseText;            
            asignaFechas(respuesta, form);
        }
    }
    ajax22.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax22.send("accion="+accion+"&id_periodo_contable="+valorcombo2);
    
}

function asignaFechas(cadena, form){

    array = cadena.split("?");
    //alert("cad: "+array[0]); frm_libro
    form.elements['txtFechaDesde'].value="";
    form.elements['txtFechaHasta'].value="";
    form.elements['txtFechaDesde'].value=array[0]+" 00:00";
    form.elements['txtFechaHasta'].value=array[1]+" 23:59";
    
}


function eliminar_plan_cuentas(accion, eliminar){
    //PAGINA: planCuentas.php

    if(eliminar == "No"){
        alert("Usted No tiene permisos. \nConsulte con el Administrador.");
    }else{
        var respuesta63 = confirm("Seguro desea eliminar el Plan de Cuentas?");
        if (respuesta63){
            $.ajax({
                url: 'sql/plan_cuentas.php',
                data: 'accion='+accion,
                type: 'post',
                beforeSend: function(){
                        //imagen de carga
                        $("#mensaje1").html("<p align='center'><img src='images/loading.gif' /></p>");
                    },
                    success: function(data){
                        if(data!="")
                            fn_buscar();
                        //alert(data);
                        document.getElementById("mensaje1").innerHTML=""+data;

                    }
                });
        }
    }


}


function importar_plan_cuentas(accion, guardar){
    //PAGINA: planCuentas.php

if(guardar == "No"){
    alert("Usted No tiene permisos. \nConsulte con el Administrador.");
}else{
    var respuesta63 = confirm("Seguro desea importar el Plan de Cuentas modelo?");
    if (respuesta63){
  //           alert('kkkk');

  $.ajax({
    url: 'sql/plan_cuentas.php',
    data: 'accion='+accion,
    type: 'post',
    beforeSend: function(){
                        //imagen de carga
                        //$("#mensaje1").html("<p align='center'> <span class='glyphicon glyphicon-time' aria-hidden='true'></span></p>");
                    },
                    success: function(data){
                        
                        if(data!="")

                            console.log (data);
                        //alert(data);
                       // document.getElementById("mensaje1").innerHTML=""+data;
                       alertify.success("Plan cuentas importado con exito");
                       fn_buscar();
                   }
               });
}
}


}

function importar_plan_cuentas2(accion, guardar){
    //PAGINA: planCuentas.php
//    alert('accion='+accion);
if(guardar == "No"){
    alert("Usted No tiene permisos. \nConsulte con el Administrador.");
}else{
    var respuesta63 = confirm("Procesar configuracion?");
    if (respuesta63){
  //           alert('kkkk');

  $.ajax({
    url: 'sql/plan_cuentas.php',
    data: 'accion='+accion,
    type: 'post',
    beforeSend: function(){
                        //imagen de carga
                        $("#mensaje1").html("<p align='center'> <span class='glyphicon glyphicon-time' aria-hidden='true'></span></p>");
                    },
                    success: function(data){
                        if(data!="")
                            fn_buscar();
                        //alert(data);
                        document.getElementById("mensaje1").innerHTML=""+data;

                    }
                });
}
}


}

function pdfInfoEmpleado(id_empleado){
    // pagina empleados.php
    miUrl = "reportes/rptInfoEmpleado.php?id_empleado="+id_empleado;
    window.open(miUrl,'noimporta','width=600, height=600, scrollbars=NO, titlebar=no');
}

function eliminaEmpresa(id_empresa, accion){
    // elimina la empresa y todo sus datos
    var respuesta3 = confirm("Seguro desea eliminar esta Empresa? \nEsta acci\u00f3n eliminara de forma permanente todos los datos associados con esta empresa");
    if (respuesta3){
        $.ajax({
            url: 'sql/empresa.php',
            data: 'id_empresa='+id_empresa+'&accion='+accion,
            type: 'post',
            success: function(data){
                if(data!="")
                    alert(data);
                            //listar_libro_diario();
                        }
                    });
    }
}

function modificaEmpresa(id_empresa, accion){
    //modifica los datos de la empresa
    $("#div_oculto").load("ajax/modificaEmpresa.php", {id_empresa: id_empresa, accion: accion}, function(){
      $.blockUI({
         message: $('#div_oculto'),

         overlayCSS: {backgroundColor: '#111'},
         css:{

            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',

            background: '', /* #f9f9f9*/
            top: '10px',
            left: '',
            position: 'absolute'
            /* width: '350px' */


        }
    });
  });
    /*
    var respuesta3 = confirm("Seguro desea eliminar esta Empresa? \nEsta acci\u00f3n eliminara de forma permanente todos los datos associados con esta empresa");
    if (respuesta3){
            $.ajax({
                    url: 'sql/empresa.php',
                    data: 'id_empresa='+id_empresa+'&accion='+accion,
                    type: 'post',
                    success: function(data){
                            if(data!="")
                            alert(data);
                            //listar_libro_diario();
                    }
            });
    }
    */
}

function guardar_usuario_empresa(acccion){
    // guarda el usuario creado en modulo administrador pag: administrarEmpresa.php

    var login=document.form['txtLogin'].value;
  //      document.getElementById("btnGuardar").style.visibility="hidden";
  var str = $("#form").serialize();
  
  $.ajax({

    url: 'sql/usuarios.php',
    data: str+"&txtAccion="+acccion+"&login="+login,
    type: 'POST',
    success: function(data){
        console.log(data);
        if(data==0){
         alertify.success("Usuario agregado con exito :)");
         listar_usuarios_empresa();
         fn_cerrar()
     }else{
         alertify.success("Usuario ya existe :)");
         listar_usuarios_empresa();
         fn_cerrar()
     }
     

 }

});


}


// function cambioSecuencialCheque(tipoDocumento,accccion){
//     console.log("accccion",accccion)
//     console.log("tipoDocumento",tipoDocumento)
//     var str = $("#frmAsientosContables").serialize();
//     console.log("str",str)
//     ajax23=objetoAjax();
   
   
  
//         ajax23.open("POST", "sql/libroDiario.php",true);
//         ajax23.onreadystatechange=function(){
//             if (ajax23.readyState==4){
//                 var respuesta23=ajax23.responseText;
//                 console.log(respuesta23);
//                 document.frmAsientosContables.txtNumeroDocumento.value=respuesta23;
//         }
//     }

//     ajax23.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
//     ajax23.send("txtAccion="+accccion+"&tipoDocumento="+tipoDocumento+"&str="+str)

// }

function cambioSecuencialCheque(tipoDocumento, accccion){
        var planCuenta = $("#planCuentaUnicoBanco").val();
        // console.log("str",str)
        ajax23=objetoAjax();
        ajax23.open("POST", "sql/libroDiario.php",true);
        ajax23.onreadystatechange=function(){
            if (ajax23.readyState==4){
              
                var respuesta23=ajax23.responseText;
                  console.log(respuesta23);
            document.frmAsientosContables.txtNumeroDocumento.value=respuesta23;
            //document.frmAsientosContables['txtNumeroComprobante'].value = respuesta23;
            //document.getElementById("txtNumeroComprobante").value = respuesta23;
            // document.frmAsientosContables.txtNumeroComprobante.value=respuesta23;
        }
    }
    ajax23.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax23.send("txtAccion="+accccion+"&tipoDocumento="+tipoDocumento+"&planCuenta="+planCuenta)

}

function numeroComprobante(acccion, tipoComprobante){

        ajax23=objetoAjax();
        ajax23.open("POST", "sql/libroDiario.php",true);
        ajax23.onreadystatechange=function(){
            if (ajax23.readyState==4){
                var respuesta23=ajax23.responseText;

            //document.frmAsientosContables['txtNumeroComprobante'].value = respuesta23;
            //document.getElementById("txtNumeroComprobante").value = respuesta23;
            document.frmAsientosContables.txtNumeroComprobante.value=respuesta23;
        }
    }
    ajax23.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax23.send("txtAccion="+acccion+"&tipoComprobante="+tipoComprobante)

}

function guardar_usuario(){    

    var login = "";
    login = no_repetir_login(txtLogin,7);//retorna 1 o 0
    var contrasena = "";    
    contrasena = validarContrasena(form);// retorna true o false    
    //var ciudad = document.form.opcion2.value//validamos si es > que cero
        
            if(login == 0){
                if(contrasena == true){
                    //if(ciudad >= 1){
                        var str = $("#form").serialize();
                        $.ajax({
                                url: 'sql/usuarios.php',
                                data: str,
                                type: 'POST',
                                success: function(data){
                                document.getElementById('mensaje11').innerHTML=""+data;
                                document.getElementById("form").reset();
                                listar_usuarios_empresa(1);
                                }
                        });
//                    }else{
//                        alert ('No se puede guardar porque no ha seleccionado su ciudad');
//                        document.form.cbciudad.focus();
//                    }

                }
                else{
                    alert ('No se puede guardar porque las contrase\u00f1as no son iguales');
                    document.form.txtPassword.focus();
                    document.form.txtPassword.value="";
                    document.form.txtRpPassword.value="";
                    document.getElementById("errorPassword").innerHTML="" ;
                }

            }else {
                alert ('No se puede guardar porque el empleado "'+document.form['txtLogin'].value+'" ya se encuentra registrado.');
                document.getElementById("noRepetirLogin").innerHTML="";
                document.form.txtLogin.focus();
//                document.form.txtLogin.value="";
            }
        
}

function listar_usuarios_empresa(page=1){
 //PAGINA: productos.php
    var str = $("#formUsuarios").serialize();
    console.log("LISTADO");
    $.ajax({
            url: 'ajax/listarUsuariosEmpresa.php',
            type: 'get',
            data: str+"&page="+page,
            success: function(data){
                console.log("data",data);
                    $("#div_listar_usuarios_empresa").html(data);
            }
    });
}

function cargaTipoEmpresa(aux){
    ajax24=objetoAjax();
    ajax24.open("POST", "sql/empresa.php",true);
    ajax24.onreadystatechange=function(){
        if (ajax24.readyState==4){
            var respuesta=ajax24.responseText;
            asignaTipoEmpresa(respuesta);
        }
    }
    ajax24.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax24.send("accion="+aux)
}

function asignaTipoEmpresa(cadena){
    array = cadena.split( "?" );
    limite=array.length;
    cont=1;
    limpiaTipoEmpresa();
    document.form.cmbTipoEmpresa.options[0] = new Option("Seleccione Tipo de Empresa","0");
    for(i=1;i<limite;i=i+2){
        document.form.cmbTipoEmpresa.options[cont] = new Option(array[i+1], array[i]);
        cont++;
    }
    
}

function limpiaTipoEmpresa()
{
    for (m=document.form.cmbTipoEmpresa.options.length-1;m>=0;m--){
        document.form.cmbTipoEmpresa.options[m]=null;
    }
}

function calcularCheque() {
    // retorna en bancos.php;    
    txtTotalCheque = $('#txtTotalCheque').val();    
    $('#txtChequesCB').val(parseFloat(txtTotalCheque).toFixed(2));
} 

function calcularDeposito() {
    // retorna en bancos.php;
    txtTotalDeposito = $('#txtTotalDeposito').val();
    $('#txtDepositosCB').val(parseFloat(txtTotalDeposito).toFixed(2));
}

function calcularNotaCredito() {
    // retorna en bancos.php;
    txtTotalNotaDebito = $('#txtTotalNotaDebito').val();
    txtTotalNotaDebito = parseFloat(txtTotalNotaDebito).toFixed(2);
    $('#txtNotaCreditoCB').val(txtTotalNotaDebito);
}

function calcularNotaDebito() {
    // retorna en bancos.php;
    txtTotalNotaCredito = $('#txtTotalNotaCredito').val();
    $('#txtNotaDebitoCB').val(parseFloat(txtTotalNotaCredito).toFixed(2));
}

function calcularTransferencia() {
    // retorna en bancos.php;
    txtTotalTransferencia = ($('#txtTotalTransferencia').val()=='')?0:$('#txtTotalTransferencia').val();
    $('#txtTransferenciasVentasCB').val(parseFloat(txtTotalTransferencia).toFixed(2));
}

function calcularTransferenciaC() {
    // retorna en bancos.php;
    txtTotalTransferenciaC = ($('#txtTotalTransferenciaC').val()=='')?0:$('#txtTotalTransferenciaC').val();
    
    $('#txtTransferenciasComprasCB').val(parseFloat(txtTotalTransferenciaC).toFixed(2));
}


function saldoTotalConciliado(accion) { //accion: 3
    // retorna en bancos.php;
    
    var cmbIdBancos = $('#cmbNombreCuentaCB').val();

    var str = $("#frmConciliacionBancaria").serialize();
    $.ajax({
        url: 'sql/bancos.php',
        type: 'post',
        data: str+"&accion="+accion+"&cmbIdBancos="+cmbIdBancos,
        success: function(data){
     console.log(data);
    codigo_sadoTotal = data.split("ô");
    
    $('#txtSaldoConsolidacionCB').val(parseFloat(codigo_sadoTotal[0]).toFixed(2));
    $('#txtCodigoCB').val(codigo_sadoTotal[1]);
}
});
    
    
}

function conciliarCuenta(frmConciliacionBancaria){ //

    console.log("frmConciliacionBancaria",frmConciliacionBancaria);
    var txtPermisosBancosGuardar = frmConciliacionBancaria.elements['txtPermisosBancosGuardar'].value;

    if(txtPermisosBancosGuardar == "No"){
        alert ("Usted No tiene permisos. \nConsulte con el Administrador.");
    }else{
        
        var numeroDeCuentasdeBancos = $('#txtNumeroFilas').val();

        var str = $("#frmConciliacionBancaria").serialize();
        $("input:checkbox").each(
            function() {
                if( $(this).is(':checked') ) {
                    //alert("El checkbox con valor " + $(this).val() + " está seleccionado");
                    var id_detalle_bancos1 = $(this).val();
                    console.log("id_detalle_bancos1",id_detalle_bancos1);
                    $.ajax({
                        url: 'sql/bancos.php',
                        type: 'post',
                        data: str+"&id_detalle_bancos="+id_detalle_bancos1+"&accion=4&numeroDeCuentasdeBancos="+numeroDeCuentasdeBancos,
                        // para mostrar el loadian antes de cargar los datos
                        beforeSend: function(){
                            //imagen de carga
                            $("#mensaje2").html("<p align='center'><img src='images/loading.gif' /></p>");
                        },
                        success: function(data){
                            console.log(data);
                            document.getElementById("mensaje2").innerHTML=data;
                            saldoTotalConciliado(3);
                            cargarDetallesCuentaBancos(2, cmbNombreCuentaCB.value);
                        }
                    });
                }else{
                    //alert("El checkbox con valor " + $(this).val() + " está de-seleccionado");
                    var id_detalle_bancos2 = $(this).val();
                    $.ajax({
                        url: 'sql/bancos.php',
                        type: 'post',
                        data: str+"&id_detalle_bancos="+id_detalle_bancos2+"&accion=5&numeroDeCuentasdeBancos="+numeroDeCuentasdeBancos,
                        // para mostrar el loadian antes de cargar los datos
                        beforeSend: function(){
                            //imagen de carga
                            $("#mensaje2").html("<p align='center'><img src='images/loading.gif' /></p>");
                        },
                        success: function(data){
                            document.getElementById("mensaje2").innerHTML=data;
                            saldoTotalConciliado(3);
                            cargarDetallesCuentaBancos(2, cmbNombreCuentaCB.value);
                        }
                    });
                }

            }
            );
        
    }

}









function cargarVendedores(aux){

    ajax29=objetoAjax();
    ajax29.open("POST", "sql/clientes.php",true);
    ajax29.onreadystatechange=function(){
        if (ajax29.readyState==4){
            var respuesta29=ajax29.responseText;
            asignaVendedores(respuesta29);
        }
    }
    ajax29.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    ajax29.send("accion="+aux)
}

function asignaVendedores(cadena){
    array = cadena.split( "?" );
    limite=array.length;
    cont=1;

    limpiaVendedores();
    document.frmClientes.cmbVendedor.options[0] = new Option("Ninguno","0");
    for(i=1;i<limite;i=i+2){
        document.frmClientes.cmbVendedor.options[cont] = new Option(array[i+1], array[i]);
        cont++;
    }
    
}

function limpiaVendedores()
{
    for (m=document.frmClientes.cmbVendedor.options.length-1;m>=0;m--){
        document.frmClientes.cmbVendedor.options[m]=null;
    }
}


function comboCuentasContables(aux){
    //alert("Va mostrar plan de cuenta");
	//alert(aux);
    ajax1=objetoAjax();
//    ajax1.open("POST", "sql/busquedas.php",true);
ajax1.open("POST", "sql/plan_cuentas.php",true);
ajax1.onreadystatechange=function(){         
    if (ajax1.readyState==4){
        var respuesta=ajax1.responseText;            
        asignarComboCuentaContable(respuesta);
    }
}
ajax1.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
ajax1.send("accion="+aux)
}

function asignarComboCuentaContable(cadena ){
    array = cadena.split( "?" );
//	alert(array);
limite=array.length;
cont=1;
    //var etiqueta = document.getElementById(tag) txtIceEnlace
    document.form_enlaces.txtIvaCreditoEnlace.options[0] = new Option("Seleccione Plan de Cuentas","0");
    document.form_enlaces.txtInventarioProductoEnlace.options[0] = new Option("Seleccione Plan de Cuentas","0");
    document.form_enlaces.txtIvaEnlace.options[0] = new Option("Seleccione Plan de Cuentas","0");
    document.form_enlaces.txtOtrosGastosEnlace.options[0] = new Option("Seleccione Plan de Cuentas","0");
    document.form_enlaces.txtCuentaContable.options[0] = new Option("Seleccione Plan de Cuentas","0");
    document.form_enlaces.txtIceEnlace.options[0] = new Option("Seleccione Plan de Cuentas","0");
    for(i=1;i<limite;i=i+2){
        document.form_enlaces.txtIvaCreditoEnlace.options[cont] = new Option(array[i+1], array[i]);
        document.form_enlaces.txtInventarioProductoEnlace.options[cont] = new Option(array[i+1], array[i]);
        document.form_enlaces.txtIvaEnlace.options[cont] = new Option(array[i+1], array[i]);
        document.form_enlaces.txtOtrosGastosEnlace.options[cont] = new Option(array[i+1], array[i]);
        document.form_enlaces.txtCuentaContable.options[cont] = new Option(array[i+1], array[i]);
        document.form_enlaces.txtIceEnlace.options[cont] = new Option(array[i+1], array[i]);
        cont++;
    }
    //document.getElementById("cbpais").selectedIndex = "3";
    //document.form.txtRetencionIva.selectedIndex = '0'; // seleccion para ecuador    
}

function reiniciar_cuenta(accion){
    var respuesta63 = confirm("Seguro Inicializar su cuenta de Empresa ?");
    if (respuesta63){
        $.ajax({
            url: 'sql/plan_cuentas.php',
            data: 'accion='+accion,
            type: 'post',
            beforeSend: function(){
                    //imagen de carga
                   // $("#mensaje1").html("<p align='center'><span class='glyphicon glyphicon-time' aria-hidden='true'></span></p>");
               },
               success: function(data){
                if(data!=""){
                    alertify.error("Cuenta reiniciada con exito");
                    fn_buscar();
                }
            }
        });
    }
}
function listar_modulo_dominio(page=1){
 //PAGINA: productos.php
    var str = $("#formDomiios").serialize();

    $.ajax({
            url: 'ajax/listar_dominios.php',
            type: 'get',
            data: str+"&page="+page,
            success: function(data){
                console.log("data",data);
                    $("#div_listar_dominios").html(data);
            }
    });
}
function modificarDominiosModulos(id_dominio){
// funcion modifica usuarios funciona en la pagina: ajax/modificarUsuario.php
	$("#div_oculto").load("ajax/modificarDominiosModulos.php", {id_dominio: id_dominio}, function(){
		$.blockUI({
			message: $('#div_oculto'),
		overlayCSS: {backgroundColor: '#111'},
			css:{

			 '-webkit-border-radius': '3px',
                '-moz-border-radius': '3px',
                'box-shadow': '6px 6px 20px gray',
				top: '5%',
				left: '15%',
				width: '75%',
                position: 'absolute'


			}
		});
	});
}