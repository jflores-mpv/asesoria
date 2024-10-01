////Empleados
/*function sumarEmpleados1(){
    var empleados = parseFloat(document.getElementById("empleados").value);
    var sueldoMensual = parseFloat(document.getElementById("sueldoMensual").value);
         
    valorAnual = empleados*sueldoMensual;
    document.getElementById("valorAnual").innerText = valorAnual;
            
    valorTotal = valorAnual * 12;
            
    document.getElementById("personalTotal").innerText = valorTotal;

} */


///porcentajes

function porcentajes1(){
    
        var ventas = parseFloat(document.getElementById("ventas").innerHTML);
        var costeCalculos = parseFloat(document.getElementById("costeCalculos").innerHTML);
        porCosteCalculos2 = costeCalculos / ventas;
        porCosteCalculos1= porCosteCalculos2*100;
        porCosteCalculos = porCosteCalculos1.toFixed(2);
        
        document.getElementById("porCosteCalculos").innerText = porCosteCalculos;

        
        var resultado = parseFloat(document.getElementById("resultado").innerHTML);
        porResultado2 = resultado / ventas;
        porResultado1= porResultado2*100;
        porResultado = porResultado1.toFixed(2);
        
        document.getElementById("porResultado").innerText = porResultado;

        
        var personal = parseFloat(document.getElementById("personalTotal").innerHTML);
        porPersonal2 = personal / ventas;
        porPersonal1= porPersonal2*100;
        porPersonal = porPersonal1.toFixed(2);
        
        document.getElementById("porPersonal1").innerText = porPersonal;

        var publicidad = parseFloat(document.getElementById("publicidad").innerHTML);
        porPublicidad2 = publicidad / ventas;
        porPublicidad1= porPublicidad2*100;
        porPublicidad = porPublicidad1.toFixed(2);
        
        document.getElementById("porpublicidad").innerText = porPublicidad;

        
        var energiaResultado = parseFloat(document.getElementById("energiaResultado").innerHTML);
        porenergiaResultado2 = energiaResultado / ventas;
        porenergiaResultado1= porenergiaResultado2*100;
        porenergiaResultado = porenergiaResultado1.toFixed(2);
        
        document.getElementById("porenergiaResultado").innerText = porenergiaResultado;

        
        var otrosGastos = parseFloat(document.getElementById("otrosGastos").innerHTML);
        porotrosGastos2 = otrosGastos / ventas;
        porotrosGastos1= porotrosGastos2*100;
        porotrosGastos = porotrosGastos1.toFixed(2);
        
        document.getElementById("porotrosGastos").innerText = porotrosGastos;

        
        var ebita = parseFloat(document.getElementById("ebita").innerHTML);
        porebita2 = ebita / ventas;
        porebita1= porebita2*100;
        porebita = porebita1.toFixed(2);
        
        document.getElementById("porebita").innerText = porebita;

        
        var ebitda = parseFloat(document.getElementById("ebitda").innerHTML);
        porebitda2 = ebitda / ventas;
        porebitda1= porebitda2*100;
        porebitda = porebitda1.toFixed(2);
        
        document.getElementById("porebitda").innerText = porebitda;

}

function porcentajes2(){
    
        var ventas2 = parseFloat(document.getElementById("ventas2").innerHTML);
        var costeCalculos = parseFloat(document.getElementById("costeCalculos2").innerHTML);
        porCosteCalculos2 = costeCalculos / ventas;
        porCosteCalculos1= porCosteCalculos2*100;
        porCosteCalculos = porCosteCalculos1.toFixed(2);
        
        document.getElementById("porCosteCalculos2").innerText = porCosteCalculos;

        
        var resultado = parseFloat(document.getElementById("resultado2").innerHTML);
        porResultado2 = resultado / ventas;
        porResultado1= porResultado2*100;
        porResultado = porResultado1.toFixed(2);
        
        document.getElementById("porResultado2").innerText = porResultado;

        var personal = parseFloat(document.getElementById("personalTotal2").innerHTML);
        porPersonal2 = personal / ventas;
        porPersonal1= porPersonal2*100;
        porPersonal = porPersonal1.toFixed(2);
        
        document.getElementById("porPersonal2").innerText = porPersonal;

        var publicidad = parseFloat(document.getElementById("publicidad2").innerHTML);
        porPublicidad2 = publicidad / ventas;
        porPublicidad1= porPublicidad2*100;
        porPublicidad = porPublicidad1.toFixed(2);
        
        document.getElementById("porpublicidad2").innerText = porPublicidad;

        var energiaResultado = parseFloat(document.getElementById("energiaResultado2").innerHTML);
        porenergiaResultado2 = energiaResultado / ventas;
        porenergiaResultado1= porenergiaResultado2*100;
        porenergiaResultado = porenergiaResultado1.toFixed(2);
        
        document.getElementById("porenergiaResultado2").innerText = porenergiaResultado;

        var otrosGastos = parseFloat(document.getElementById("otrosGastos2").innerHTML);
        porotrosGastos2 = otrosGastos / ventas;
        porotrosGastos1= porotrosGastos2*100;
        porotrosGastos = porotrosGastos1.toFixed(2);
        
        document.getElementById("porotrosGastos2").innerText = porotrosGastos;

        var ebita = parseFloat(document.getElementById("ebita2").innerHTML);
        porebita2 = ebita / ventas;
        porebita1= porebita2*100;
        porebita = porebita1.toFixed(2);
        
        document.getElementById("porebita2").innerText = porebita;

        var ebitda = parseFloat(document.getElementById("ebitda2").innerHTML);
        porebitda2 = ebitda / ventas;
        porebitda1= porebitda2*100;
        porebitda = porebitda1.toFixed(2);
        
        document.getElementById("porebitda2").innerText = porebitda;

}

function porcentajes3(){

               var ventas = parseFloat(document.getElementById("ventas3").innerHTML);
        var costeCalculos = parseFloat(document.getElementById("costeCalculos3").innerHTML);
        porCosteCalculos2 = costeCalculos / ventas;
        porCosteCalculos1= porCosteCalculos2*100;
        porCosteCalculos = porCosteCalculos1.toFixed(2);
        
        document.getElementById("porCosteCalculos3").innerText = porCosteCalculos;

        var resultado = parseFloat(document.getElementById("resultado3").innerHTML);
        porResultado2 = resultado / ventas;
        porResultado1= porResultado2*100;
        porResultado = porResultado1.toFixed(2);
        
        document.getElementById("porResultado3").innerText = porResultado;

        var personal = parseFloat(document.getElementById("personalTotal3").innerHTML);
        porPersonal2 = personal / ventas;
        porPersonal1= porPersonal2*100;
        porPersonal = porPersonal1.toFixed(2);
        document.getElementById("porPersonal3").innerText = porPersonal;

        var publicidad = parseFloat(document.getElementById("publicidad3").innerHTML);
        porPublicidad2 = publicidad / ventas;
        porPublicidad1= porPublicidad2*100;
        porPublicidad = porPublicidad1.toFixed(2);
        
        document.getElementById("porpublicidad3").innerText = porPublicidad;

        var energiaResultado = parseFloat(document.getElementById("energiaResultado3").innerHTML);
        porenergiaResultado2 = energiaResultado / ventas;
        porenergiaResultado1= porenergiaResultado2*100;
        porenergiaResultado = porenergiaResultado1.toFixed(2);
        
        document.getElementById("porenergiaResultado3").innerText = porenergiaResultado;

        var otrosGastos = parseFloat(document.getElementById("otrosGastos3").innerHTML);
        porotrosGastos2 = otrosGastos / ventas;
        porotrosGastos1= porotrosGastos2*100;
        porotrosGastos = porotrosGastos1.toFixed(2);
        
        document.getElementById("porotrosGastos3").innerText = porotrosGastos;

        var ebita = parseFloat(document.getElementById("ebita3").innerHTML);
        porebita2 = ebita / ventas;
        porebita1= porebita2*100;
        porebita = porebita1.toFixed(2);
        
        document.getElementById("porebita3").innerText = porebita;

        var ebitda = parseFloat(document.getElementById("ebitda3").innerHTML);
        porebitda2 = ebitda / ventas;
        porebitda1= porebitda2*100;
        porebitda = porebitda1.toFixed(2);
        
        document.getElementById("porebitda3").innerText = porebitda;
}





/// calculos 3------
function multiplicar3(){
    var num1 = parseFloat(document.getElementById("m23").value);
    var num2 = document.getElementById("alquiler3").value;
    suma = num1 * num2 * 12;
    document.getElementById("resultado3").innerText = (suma);
}


function sumarEnergia3(){
  var J24 = parseInt(document.getElementById("horasTrabajo").value);
    var I10 = parseFloat(document.getElementById("energia1").value);
    var H7 = parseInt (document.getElementById("energia2").value);
    var H8 = parseInt (document.getElementById("energia3").value);
    var I9 = parseInt (document.getElementById("energia4").value);
    var dias = parseInt(document.getElementById("dias").value);

    var E231 = parseFloat(document.getElementById("costoProd1").value);
    var E232 = parseFloat (document.getElementById("costoAlman1").value);
    var porComplemento1 = parseInt (document.getElementById("porComplemento3").value);
    var C9 = parseFloat (document.getElementById("costoComplementario").value);
    var tA = parseInt(document.getElementById("adicionales").value);
    var uA = parseFloat(document.getElementById("suministros").value);
    var vA = parseFloat(document.getElementById("municipio").value);
    var porMantenimiento = parseFloat(document.getElementById("porMantenimiento").value);
    var software = parseFloat(document.getElementById("software").value);
    var mobiliario = parseFloat(document.getElementById("mobiliario").value);
    var equipos = parseFloat(document.getElementById("equipos").value);
    var rA = parseFloat(document.getElementById("seguros").value);
    var sA = parseFloat(document.getElementById("asesorias").value);
    var qA = parseFloat(document.getElementById("porContingencias").value);
    var porVentas1 = parseInt (document.getElementById("porVentasDiarias3").value);
    var precioPromedio1 = parseInt (document.getElementById("precioPromedio3").value);
    var precioComplemento1 = parseInt (document.getElementById("precioComplemento3").value);
    var ventasDiarias = parseInt (document.getElementById("ventasDiarias3").value);
    var porPublicidad = parseInt (document.getElementById("porPublicidad").value);
    var porPromedio1 = parseInt (document.getElementById("porPromedio3").value);
    var C37 = parseFloat(document.getElementById("contMarginal").innerHTML);
   
    var C38 = parseFloat(document.getElementById("CM").innerHTML);
    var costeCalculos = parseFloat(document.getElementById("costeCalculos").innerHTML);
    var alquiler = parseFloat(document.getElementById("resultado3").innerHTML);
    var otrosGastos = parseFloat(document.getElementById("otrosGastos").innerHTML);
  
    var energia = parseFloat(document.getElementById("energiaResultado").innerHTML);
    var totalCostes = parseFloat(document.getElementById("totalCostes").innerHTML);
    var costesFijos = parseFloat(document.getElementById("costesFijos").innerHTML);
    var ebita = parseFloat(document.getElementById("ebita").innerHTML);
    var num1 = parseFloat(document.getElementById("m23").value);
    var decoracion = parseFloat(document.getElementById("decoracion").value);
    var softwareGestion = parseFloat(document.getElementById("softwareGestion").value);
    var nuevaApp = parseFloat(document.getElementById("nuevaApp").value);
    var amortizacion = parseInt(document.getElementById("amortizacion").value);
    var C22 = parseFloat(document.getElementById("publicidad").innerHTML);
    var ebitda = parseFloat(document.getElementById("ebitda").innerHTML);
    
    
    var empleados = parseFloat(document.getElementById("empleados").value);
    var sueldoMensual = parseFloat(document.getElementById("sueldoMensual").value);
         
    valorAnual = empleados*sueldoMensual;
    personal = valorAnual * 12;
    document.getElementById("personalTotal3").innerText = personal;
    
    
    
    
    porComplementoVal = porComplemento1/100;
    C7 = ventasDiarias*porComplementoVal;
    porVentasVal = porVentas1/100;
    E4  = 1-porVentasVal;
    D6  = ventasDiarias*porVentasVal; 
    E6 = ventasDiarias*E4;
    K23 = E6*porPromedio1;
    
    K22 = D6*precioPromedio1;
    K24 = K22+K23;
    
    C5 = K24/ventasDiarias;
    
    D11 = dias*ventasDiarias;
    D12 = dias*C7;
    
    C11 = D11*C5;
    C12 = D12*precioComplemento1;
    
    ventas=C11+C12
    
    document.getElementById("ventas3").innerText = ventas;
 
    
    
    I24 = dias/12;
    I8  = H8*24*I24;
    I7  = H7*I24*J24;
    K9 = I9*I10;
    K8 = I8*I10;
    K7 = I7*I10;
    energiaSuma = K7+K8+K9;
    energiaResultado1= (K7+K8+K9)*12;
    energiaResultado= energiaResultado1.toFixed(2);
    document.getElementById("energiaResultado3").innerText = energiaResultado;
    
    
    D7 = porComplemento1/100;
    C7  = ventasDiarias*D7;
    D12 = dias*C7;
    C16 = D12*C9;
    C6  = E231+E232;
    D11 = dias*ventasDiarias;
    C15 = D11*C6;
    costos1 = C15 + C16 ;
    costos=costos1.toFixed(2)
    document.getElementById("costeCalculos3").innerText = costos;
    
    
    
    suministros = tA + uA;
    porMantenimientoVal = porMantenimiento/100;
    porContingencias = qA/100;
    totalEquipos = equipos + mobiliario ;
    mantenimiento1 = totalEquipos*porMantenimientoVal;
    mantenimiento= mantenimiento1+software;
    varios1=dias*ventasDiarias;
    varios2=ventasDiarias*porVentas1;
    varios3=100%-porVentas1;
    varios4=ventasDiarias*porComplemento1;
    varios5=varios2*precioPromedio1;
    varios6=dias*varios4;
    varios7=ventasDiarias*varios3;
    varios8=varios6*precioComplemento1;
    varios9=varios7*precioPromedio1;
    varios10=varios5 +varios9;
    varios11=varios10/ventasDiarias;
    varios12=varios1*varios11;
    varios13=varios12 + varios8;
    varios14=varios13*porContingencias;
    varios= varios14 + sA + rA;
    otrosGastos1= varios + mantenimiento + vA +  suministros ;
    otrosGastos=otrosGastos1.toFixed(2)
    document.getElementById("otrosGastos3").innerText = otrosGastos  ;

    porVentasVal = porVentas1/100;
    E4  = 1-porVentasVal;
    E6  = ventasDiarias*E4;
    D6  = ventasDiarias*porVentasVal; 
    K23 = E6*porPromedio1;
    K22 = D6*precioPromedio1;
    K24 = K22+K23;
    C5 = K24/ventasDiarias;
    porComplementoVal = porComplemento1/100;
    C7 = ventasDiarias*porComplementoVal;
    D12 = dias*C7;       
    D11 = dias*ventasDiarias;
    C12 = D12*porComplemento1;
    C11 = D11*C5;
    ventasAnuales = C11+C12;
    porPublicidadVal = porPublicidad/100;
    publicidad1 = ventasAnuales * porPublicidadVal;
    publicidad=publicidad1.toFixed(2)
    document.getElementById("publicidad3").innerText = publicidad  ;

    
    

    // 7 //---------COSTES FIJOS
    
    F23 = energiaResultado1/2;
    F17 = costos1/12;
    costesFijos = F17+alquiler+personal+F23+otrosGastos1;
    
    document.getElementById("costesFijos3").innerText = costesFijos;
    
    
    var C14 = parseFloat(document.getElementById("ventas3").innerHTML);
    
    // 4 //COSTES VARIABLES
    costesVariables1 = totalCostes-costesFijos;
    costesVariables=costesVariables1.toFixed(2)
    document.getElementById("costesVariables3").innerText = costesVariables;
    
          // 3 ///EBITDA
    
    ebitda0 = costos1 + alquiler + personal + publicidad1 + otrosGastos1 + energiaResultado1;
    ebitda1 = C14-ebitda0;
    ebitda=ebitda1.toFixed(2)
    
    document.getElementById("ebitda3").innerText = ebitda;

    console.log("costos", costos1, )
    
    
            // 2 //EBITA
    O16 = decoracion * num1;
    O15 = mobiliario + equipos;
    O14 = softwareGestion + nuevaApp;  

    O17 = O14+O16+O15;
    
    C31 = C14*0.01;
    
    C30 = O17/amortizacion;
    ebi = C30 + C31;
    C29 = ebitda1;
    
    ebita20=C29-ebi;
    ebita = ebita20.toFixed(2);
    
    document.getElementById("ebita3").innerText = ebita;
    
    
        // 1 ////------------TOTAL COSTES
    
    porComplementoVal = porComplemento1/100;
    C7 = ventasDiarias*porComplementoVal;
    porVentasVal = porVentas1/100;
    E4  = 1-porVentasVal;
    D6  = ventasDiarias*porVentasVal; 
    E6 = ventasDiarias*E4;
    K23 = E6*porPromedio1;
    K22 = D6*precioPromedio1;
    C5 =  K24/ventasDiarias;
    D12 = dias*C7;
    D11 = dias*ventasDiarias;
    C12 = D12*precioComplemento1;
    C11 = D11*C5;
    C32 = ebita20;
    C14 = C11+C12;
    totalCostes = C14-C32;   
    
    document.getElementById("totalCostes3").innerText = totalCostes;
    
    // 5 // CASH 
    O16 = decoracion * num1;
    O15 = mobiliario + equipos;
    O14 = softwareGestion + nuevaApp;  

    C32 = ebita20;
    
    O18 = amortizacion;
    O17 = O14+O16+O15;
    C30 = O17/O18;
    cash1 = C32+C30;
    cash = cash1.toFixed(2);
    
    document.getElementById("cash3").innerText = cash;

         // 8 //---------contMarginal
    
    var totalCostes = parseFloat(document.getElementById("totalCostes").innerHTML);
    
    C36 = totalCostes-costesFijos;
    contMarginal1 = C14-C36;
    
    contMarginal=contMarginal1.toFixed(2)
    document.getElementById("contMarginal3").innerText = contMarginal;
    
    // 11 //---------CM
    C38 = contMarginal1/C14;
    CM=C38.toFixed(2)
    document.getElementById("CM3").innerText = CM;

     // 6 //------BREAK
    var costesFijos = parseFloat(document.getElementById("costesFijos").innerHTML);
    C39 = costesFijos/C38;
    D39 = C39/12;
    breakE1 = D39/30;   
    breakE=breakE1.toFixed(2)
    document.getElementById("breakE3").innerText = breakE;

        // 10 // NUMERO DE VENTAS
    D11 = dias*ventasDiarias;
    E13 = C14/D11
    C39 = costesFijos/C38;
    D39 = C39/12;
    D40 = D39/E13/30;
    numeroVentas1 = D40;
    numeroVentas=numeroVentas1.toFixed(2)
    document.getElementById("numeroVentas3").innerText = numeroVentas;
   
        // 9 //---------RIESGO
    
    C39 = costesFijos/C38;
    riesgo1 = C39/C14;
    riesgo2 = riesgo1*100;
    riesgo=riesgo2.toFixed(2);
    document.getElementById("riesgo3").innerText = riesgo;

    
    
}

   

/// calculos 2------
function multiplicar2(){
    var num1 = parseFloat(document.getElementById("m22").value);
    var num2 = document.getElementById("alquiler2").value;
    suma = num1 * num2 * 12;
    document.getElementById("resultado2").innerText = (suma);
}


function sumarEnergia2(){

    var J24 = parseInt(document.getElementById("horasTrabajo").value);
    var I10 = parseFloat(document.getElementById("energia1").value);
    var H7 = parseInt (document.getElementById("energia2").value);
    var H8 = parseInt (document.getElementById("energia3").value);
    var I9 = parseInt (document.getElementById("energia4").value);
    var dias = parseInt(document.getElementById("dias").value);

    var E231 = parseFloat(document.getElementById("costoProd1").value);
    var E232 = parseFloat (document.getElementById("costoAlman1").value);
    var porComplemento1 = parseInt (document.getElementById("porComplemento2").value);
    var C9 = parseFloat (document.getElementById("costoComplementario").value);
    var tA = parseInt(document.getElementById("adicionales").value);
    var uA = parseFloat(document.getElementById("suministros").value);
    var vA = parseFloat(document.getElementById("municipio").value);
    var porMantenimiento = parseFloat(document.getElementById("porMantenimiento").value);
    var software = parseFloat(document.getElementById("software").value);
    var mobiliario = parseFloat(document.getElementById("mobiliario").value);
    var equipos = parseFloat(document.getElementById("equipos").value);
    var rA = parseFloat(document.getElementById("seguros").value);
    var sA = parseFloat(document.getElementById("asesorias").value);
    var qA = parseFloat(document.getElementById("porContingencias").value);
    var porVentas1 = parseInt (document.getElementById("porVentasDiarias2").value);
    var precioPromedio1 = parseInt (document.getElementById("precioPromedio2").value);
    var precioComplemento1 = parseInt (document.getElementById("precioComplemento2").value);
    var ventasDiarias = parseInt (document.getElementById("ventasDiarias2").value);
    var porPublicidad = parseInt (document.getElementById("porPublicidad").value);
    var porPromedio1 = parseInt (document.getElementById("porPromedio2").value);
    var C37 = parseFloat(document.getElementById("contMarginal").innerHTML);
   
    var C38 = parseFloat(document.getElementById("CM").innerHTML);
    var costeCalculos = parseFloat(document.getElementById("costeCalculos").innerHTML);
    var alquiler = parseFloat(document.getElementById("resultado2").innerHTML);
    var otrosGastos = parseFloat(document.getElementById("otrosGastos").innerHTML);
  
    var energia = parseFloat(document.getElementById("energiaResultado").innerHTML);
    var totalCostes = parseFloat(document.getElementById("totalCostes").innerHTML);
    var costesFijos = parseFloat(document.getElementById("costesFijos").innerHTML);
    var ebita = parseFloat(document.getElementById("ebita").innerHTML);
    var num1 = parseFloat(document.getElementById("m22").value);
    var decoracion = parseFloat(document.getElementById("decoracion").value);
    var softwareGestion = parseFloat(document.getElementById("softwareGestion").value);
    var nuevaApp = parseFloat(document.getElementById("nuevaApp").value);
    var amortizacion = parseInt(document.getElementById("amortizacion").value);
    var C22 = parseFloat(document.getElementById("publicidad").innerHTML);
    var ebitda = parseFloat(document.getElementById("ebitda").innerHTML);
    
    
    var empleados = parseFloat(document.getElementById("empleados").value);
    var sueldoMensual = parseFloat(document.getElementById("sueldoMensual").value);
         
      valorAnual = empleados*sueldoMensual;
    personal = valorAnual * 12;
    document.getElementById("personalTotal2").innerText = personal;
    
    
    
    
    porComplementoVal = porComplemento1/100;
    C7 = ventasDiarias*porComplementoVal;
    porVentasVal = porVentas1/100;
    E4  = 1-porVentasVal;
    D6  = ventasDiarias*porVentasVal; 
    E6 = ventasDiarias*E4;
    K23 = E6*porPromedio1;
    
    K22 = D6*precioPromedio1;
    K24 = K22+K23;
    
    C5 = K24/ventasDiarias;
    
    D11 = dias*ventasDiarias;
    D12 = dias*C7;
    
    C11 = D11*C5;
    C12 = D12*precioComplemento1;
    
    ventas=C11+C12
    
    document.getElementById("ventas2").innerText = ventas;
 
    
    
    I24 = dias/12;
    I8  = H8*24*I24;
    I7  = H7*I24*J24;
    K9 = I9*I10;
    K8 = I8*I10;
    K7 = I7*I10;
    energiaSuma = K7+K8+K9;
    energiaResultado1= (K7+K8+K9)*12;
    energiaResultado= energiaResultado1.toFixed(2);
    document.getElementById("energiaResultado2").innerText = energiaResultado;
    
    
    D7 = porComplemento1/100;
    C7  = ventasDiarias*D7;
    D12 = dias*C7;
    C16 = D12*C9;
    C6  = E231+E232;
    D11 = dias*ventasDiarias;
    C15 = D11*C6;
    costos1 = C15 + C16 ;
    costos=costos1.toFixed(2)
    document.getElementById("costeCalculos2").innerText = costos;
    
    
    
    suministros = tA + uA;
    porMantenimientoVal = porMantenimiento/100;
    porContingencias = qA/100;
    totalEquipos = equipos + mobiliario ;
    mantenimiento1 = totalEquipos*porMantenimientoVal;
    mantenimiento= mantenimiento1+software;
    varios1=dias*ventasDiarias;
    varios2=ventasDiarias*porVentas1;
    varios3=100%-porVentas1;
    varios4=ventasDiarias*porComplemento1;
    varios5=varios2*precioPromedio1;
    varios6=dias*varios4;
    varios7=ventasDiarias*varios3;
    varios8=varios6*precioComplemento1;
    varios9=varios7*precioPromedio1;
    varios10=varios5 +varios9;
    varios11=varios10/ventasDiarias;
    varios12=varios1*varios11;
    varios13=varios12 + varios8;
    varios14=varios13*porContingencias;
    varios= varios14 + sA + rA;
    otrosGastos1= varios + mantenimiento + vA +  suministros ;
    otrosGastos=otrosGastos1.toFixed(2)
    document.getElementById("otrosGastos2").innerText = otrosGastos  ;

    porVentasVal = porVentas1/100;
    E4  = 1-porVentasVal;
    E6  = ventasDiarias*E4;
    D6  = ventasDiarias*porVentasVal; 
    K23 = E6*porPromedio1;
    K22 = D6*precioPromedio1;
    K24 = K22+K23;
    C5 = K24/ventasDiarias;
    porComplementoVal = porComplemento1/100;
    C7 = ventasDiarias*porComplementoVal;
    D12 = dias*C7;       
    D11 = dias*ventasDiarias;
    C12 = D12*porComplemento1;
    C11 = D11*C5;
    ventasAnuales = C11+C12;
    porPublicidadVal = porPublicidad/100;
    publicidad1 = ventasAnuales * porPublicidadVal;
    publicidad=publicidad1.toFixed(2)
    document.getElementById("publicidad2").innerText = publicidad  ;

    
    

    // 7 //---------COSTES FIJOS
    
    F23 = energiaResultado1/2;
    F17 = costos1/12;
    costesFijos = F17+alquiler+personal+F23+otrosGastos1;
    
    document.getElementById("costesFijos2").innerText = costesFijos;
    
    
    var C14 = parseFloat(document.getElementById("ventas2").innerHTML);
    
    // 4 //COSTES VARIABLES
    costesVariables1 = totalCostes-costesFijos;
    costesVariables=costesVariables1.toFixed(2)
    document.getElementById("costesVariables2").innerText = costesVariables;
    
          // 3 ///EBITDA
    
    ebitda0 = costos1 + alquiler + personal + publicidad1 + otrosGastos1 + energiaResultado1;
    ebitda1 = C14-ebitda0;
    ebitda=ebitda1.toFixed(2)
    
    document.getElementById("ebitda2").innerText = ebitda;


    
    
            // 2 //EBITA
    O16 = decoracion * num1;
    O15 = mobiliario + equipos;
    O14 = softwareGestion + nuevaApp;  

    O17 = O14+O16+O15;
    
    C31 = C14*0.01;
    
    C30 = O17/amortizacion;
    ebi = C30 + C31;
    C29 = ebitda1;
    
    ebita20=C29-ebi;
    ebita = ebita20.toFixed(2);
    
    document.getElementById("ebita2").innerText = ebita;
    
    
        // 1 ////------------TOTAL COSTES
    
    porComplementoVal = porComplemento1/100;
    C7 = ventasDiarias*porComplementoVal;
    porVentasVal = porVentas1/100;
    E4  = 1-porVentasVal;
    D6  = ventasDiarias*porVentasVal; 
    E6 = ventasDiarias*E4;
    K23 = E6*porPromedio1;
    K22 = D6*precioPromedio1;
    C5 = K24/ventasDiarias;
    D12 = dias*C7;
    D11 = dias*ventasDiarias;
    C12 = D12*precioComplemento1;
    C11 = D11*C5;
    C32 = ebita20;
    C14 = C11+C12;
    totalCostes = C14-C32;   
    
    document.getElementById("totalCostes2").innerText = totalCostes;
    
    // 5 // CASH 
    O16 = decoracion * num1;
    O15 = mobiliario + equipos;
    O14 = softwareGestion + nuevaApp;  

    C32 = ebita20;
    
    O18 = amortizacion;
    O17 = O14+O16+O15;
    C30 = O17/O18;
    cash1 = C32+C30;
    cash = cash1.toFixed(2);
    
    document.getElementById("cash2").innerText = cash;

         // 8 //---------contMarginal
    
    var totalCostes = parseFloat(document.getElementById("totalCostes").innerHTML);
    
    C36 = totalCostes-costesFijos;
    contMarginal1 = C14-C36;
    
    contMarginal=contMarginal1.toFixed(2)
    document.getElementById("contMarginal2").innerText = contMarginal;
    
    // 11 //---------CM
    C38 = contMarginal1/C14;
    CM=C38.toFixed(2)
    document.getElementById("CM2").innerText = CM;

     // 6 //------BREAK
    var costesFijos = parseFloat(document.getElementById("costesFijos").innerHTML);
    C39 = costesFijos/C38;
    D39 = C39/12;
    breakE1 = D39/30;   
    breakE=breakE1.toFixed(2)
    document.getElementById("breakE2").innerText = breakE;
    console.log("costesFijos",costesFijos,"C39",C39)

        // 10 // NUMERO DE VENTAS
    D11 = dias*ventasDiarias;
    E13 = C14/D11
    C39 = costesFijos/C38;
    D39 = C39/12;
    D40 = D39/E13/30;
    numeroVentas1 = D40;
    numeroVentas=numeroVentas1.toFixed(2)
    document.getElementById("numeroVentas2").innerText = numeroVentas;
   
        // 9 //---------RIESGO
    
    C39 = costesFijos/C38;
    riesgo1 = C39/C14;
    riesgo2 = riesgo1*100;
    riesgo=riesgo2.toFixed(2);
    document.getElementById("riesgo2").innerText = riesgo;

    
    
}








///// CALCULOS 1 -------------------------------///

function multiplicar(){
    var num1 = parseFloat(document.getElementById("m21").value);
    var num2 = document.getElementById("alquiler1").value;
    suma = num1 * num2 * 12;
    document.getElementById("resultado").innerText = (suma);
}

function arriendos() {
    var x = document.getElementById("alquiler1").value;
    document.getElementById("alquilerA").innerHTML = x;
    var y = document.getElementById("alquiler2").value;
    document.getElementById("alquilerB").innerHTML = y;
    var z = document.getElementById("alquiler3").value;
    document.getElementById("alquilerC").innerHTML = z;
}

function sumarEnergia(){
    
    var J24 = parseInt(document.getElementById("horasTrabajo").value);
    
    var I10 = parseFloat(document.getElementById("energia1").value);
    var H7 = parseInt (document.getElementById("energia2").value);
    var H8 = parseInt (document.getElementById("energia3").value);
    var I9 = parseInt (document.getElementById("energia4").value);
    
    var dias = parseInt(document.getElementById("dias").value);
    var E231 = parseFloat(document.getElementById("costoProd1").value);
    var E232 = parseFloat (document.getElementById("costoAlman1").value);
    
    var porComplemento1 = parseInt (document.getElementById("porComplemento1").value);
    var C9 = parseFloat (document.getElementById("costoComplementario").value);
    
    var tA = parseInt(document.getElementById("adicionales").value);
    var uA = parseFloat(document.getElementById("suministros").value);
    var vA = parseFloat(document.getElementById("municipio").value);
    var porMantenimiento = parseFloat(document.getElementById("porMantenimiento").value);
    var software = parseFloat(document.getElementById("software").value);
    var mobiliario = parseFloat(document.getElementById("mobiliario").value);
    var equipos = parseFloat(document.getElementById("equipos").value);
    var rA = parseFloat(document.getElementById("seguros").value);
    var sA = parseFloat(document.getElementById("asesorias").value);
    var qA = parseFloat(document.getElementById("porContingencias").value);
    var porVentas1 = parseInt (document.getElementById("porVentas1").value);
    var precioPromedio1 = parseInt (document.getElementById("precioPromedio1").value);
    var precioComplemento1 = parseInt (document.getElementById("precioComplemento1").value);
    var ventasDiarias = parseInt (document.getElementById("ventas1").value);
    var porPublicidad = parseInt (document.getElementById("porPublicidad").value);
    var porPromedio1 = parseInt (document.getElementById("porPromedio1").value);
    var C37 = parseFloat(document.getElementById("contMarginal").innerHTML);

    var C38 = parseFloat(document.getElementById("CM").innerHTML);
    var costeCalculos = parseFloat(document.getElementById("costeCalculos").innerHTML);
    var alquiler = parseFloat(document.getElementById("resultado").innerHTML);
    var otrosGastos = parseFloat(document.getElementById("otrosGastos").innerHTML);

    var energia = parseFloat(document.getElementById("energiaResultado").innerHTML);
    var totalCostes = parseFloat(document.getElementById("totalCostes").innerHTML);
    var costesFijos = parseFloat(document.getElementById("costesFijos").innerHTML);
    var ebita = parseFloat(document.getElementById("ebita").innerHTML);
    var num1 = parseFloat(document.getElementById("m21").value);
    var decoracion = parseFloat(document.getElementById("decoracion").value);
    var softwareGestion = parseFloat(document.getElementById("softwareGestion").value);
    var nuevaApp = parseFloat(document.getElementById("nuevaApp").value);
    var amortizacion = parseInt(document.getElementById("amortizacion").value);
    var C22 = parseFloat(document.getElementById("publicidad").innerHTML);
    var ebitda = parseFloat(document.getElementById("ebitda").innerHTML);
    
    var empleados = parseFloat(document.getElementById("empleados").value);
    var sueldoMensual = parseFloat(document.getElementById("sueldoMensual").value);
         
    valorAnual = empleados*sueldoMensual;
    personal = valorAnual * 12;
    document.getElementById("personalTotal").innerText = personal;
    

    porComplementoVal = porComplemento1/100;
    C7 = ventasDiarias*porComplementoVal;
    
    porVentasVal = porVentas1/100;
    E4  = 1-porVentasVal;
    
    D6  = ventasDiarias*porVentasVal; 
    E6 = ventasDiarias*E4;
    
    K23 = E6*porPromedio1;
    
    K22 = D6*precioPromedio1;
    K24 = K22+K23;
    
    C5 = K24/ventasDiarias;
    
    D11 = dias*ventasDiarias;
    D12 = dias*C7;
    
    C11 = D11*C5;
    C12 = D12*precioComplemento1;
    
    ventas=C11+C12
    
    document.getElementById("ventas").innerText = ventas;
    
    console.log("VENTAS","porComplemento1",porComplemento1,"C7",C7,"porVentasVal",porVentasVal,"E4",E4,"D6",D6,"ventasDiarias",ventasDiarias,"porVentasVal",porVentasVal,"porPromedio1",porPromedio1)
    
    
    I24 = dias/12;
    I8  = H8*24*I24;
    I7  = H7*I24*J24;
    K9 = I9*I10;
    K8 = I8*I10;
    K7 = I7*I10;
    
    energiaSuma = K7+K8+K9;
    energiaResultado1= (K7+K8+K9)*12;
    energiaResultado= energiaResultado1.toFixed(2);
    
    document.getElementById("energiaResultado").innerText = energiaResultado;
    
    
    D7 = porComplemento1/100;
    C7  = ventasDiarias*D7;
    D12 = dias*C7;
    C16 = D12*C9;
    C6  = E231+E232;
    D11 = dias*ventasDiarias;
    C15 = D11*C6;

    costos1 = C15 + C16 ;
    
    costos=costos1.toFixed(2)
    document.getElementById("costeCalculos").innerText = costos;
    
    console.log("D7",D7,"porComplemento1",porComplemento1,"C7",C7,"ventas",ventas)

    
    
     suministros = tA + uA;
    porMantenimientoVal = porMantenimiento/100;
    porContingencias = qA/100;
    totalEquipos = equipos + mobiliario ;
    mantenimiento1 = totalEquipos*porMantenimientoVal;
    mantenimiento= mantenimiento1+software;

    
    varios1=dias*ventasDiarias;
    varios2=ventasDiarias*porVentas1;
    varios3=100%-porVentas1;
    varios4=ventasDiarias*porComplemento1;
    varios5=varios2*precioPromedio1;
    varios6=dias*varios4;
    varios7=ventasDiarias*varios3;
    varios8=varios6*precioComplemento1;
    varios9=varios7*precioPromedio1;
    varios10=varios5 +varios9;
    varios11=varios10/ventasDiarias;
    varios12=varios1*varios11;
    varios13=varios12 + varios8;
    varios14=varios13*porContingencias;
    
    varios= varios14 + sA + rA;
    
    otrosGastos1= varios + mantenimiento + vA +  suministros ;
    
    otrosGastos=otrosGastos1.toFixed(2)
    
    document.getElementById("otrosGastos").innerText = otrosGastos  ;

    porVentasVal = porVentas1/100;
    E4  = 1-porVentasVal;
    E6  = ventasDiarias*E4;
    D6  = ventasDiarias*porVentasVal; 
        
    K23 = E6*porPromedio1;
    K22 = D6*precioPromedio1;
    K24 = K22+K23;
    C5 = K24/ventasDiarias;
        
    porComplementoVal = porComplemento1/100;
        
    C7 = ventasDiarias*porComplementoVal;
      
    D12 = dias*C7;       
        
    D11 = dias*ventasDiarias;
        
    C12 = D12*porComplemento1;
        
    C11 = D11*C5;
    ventasAnuales = C11+C12;
    porPublicidadVal = porPublicidad/100;
    publicidad1 = ventasAnuales * porPublicidadVal;
        
    publicidad=publicidad1.toFixed(2)

    document.getElementById("publicidad").innerText = publicidad  ;
        

    
    

    // 7 //---------COSTES FIJOS
    
    F23 = energiaResultado1/2;
    F17 = costos1/12;
    costesFijos = F17+alquiler+personal+F23+otrosGastos1;
    
    document.getElementById("costesFijos").innerText = costesFijos;
    
    
    var C14 = parseFloat(document.getElementById("ventas").innerHTML);
    
    // 4 //COSTES VARIABLES
    costesVariables1 = totalCostes-costesFijos;
    costesVariables=costesVariables1.toFixed(2)
    document.getElementById("costesVariables").innerText = costesVariables;
    
          // 3 ///EBITDA
    
    ebitda0 = costos1 + alquiler + personal + publicidad1 + otrosGastos1 + energiaResultado1;
    ebitda1 = C14-ebitda0;
    ebitda=ebitda1.toFixed(2)
    
    document.getElementById("ebitda").innerText = ebitda;
    

    
            // 2 //EBITA
    O16 = decoracion * num1;
    O15 = mobiliario + equipos;
    O14 = softwareGestion + nuevaApp;  

    O17 = O14+O16+O15;
    
    C31 = C14*0.01;
    
    C30 = O17/amortizacion;
    ebi = C30 + C31;
    C29 = ebitda1;
    
    ebita20=C29-ebi;
    ebita = ebita20.toFixed(2);
    
    document.getElementById("ebita").innerText = ebita;
    
    
        // 1 ////------------TOTAL COSTES
    
    porComplementoVal = porComplemento1/100;
    C7 = ventasDiarias*porComplementoVal;
    
    porVentasVal = porVentas1/100;
    E4  = 1-porVentasVal;
    
    D6  = ventasDiarias*porVentasVal; 
    E6 = ventasDiarias*E4;
    
    k23 = E6*porPromedio1;
    
    k22 = D6*precioPromedio1;
    
    C5 = K24/ventasDiarias;

    D12 = dias*C7;
    D11 = dias*ventasDiarias;
    C12 = D12*precioComplemento1;
    C11 = D11*C5;
    C32 = ebita20;
    C14 = C11+C12;
    totalCostes = C14-C32;   
    
    document.getElementById("totalCostes").innerText = totalCostes;
    

    // 5 // CASH 
    O16 = decoracion * num1;
    O15 = mobiliario + equipos;
    O14 = softwareGestion + nuevaApp;  

    C32 = ebita20;
    
    O18 = amortizacion;
    O17 = O14+O16+O15;
    C30 = O17/O18;
    cash1 = C32+C30;
    cash = cash1.toFixed(2);
    
    document.getElementById("cash").innerText = cash;
    

         
         // 8 //---------contMarginal
    
    var totalCostes = parseFloat(document.getElementById("totalCostes").innerHTML);
    
    C36 = totalCostes-costesFijos;
    contMarginal1 = C14-C36;
    
    contMarginal=contMarginal1.toFixed(2)
    document.getElementById("contMarginal").innerText = contMarginal;
    
    
    

    // 11 //---------CM
    C38 = contMarginal1/C14;
    CM=C38.toFixed(2)
    document.getElementById("CM").innerText = CM;
    

     

     // 6 //------BREAK
    var costesFijos = parseFloat(document.getElementById("costesFijos").innerHTML);
    C39 = costesFijos/C38;
    D39 = C39/12;
    breakE1 = D39/30;   
    breakE=breakE1.toFixed(2)
    document.getElementById("breakE").innerText = breakE;
    


    
        // 10 // NUMERO DE VENTAS
    D11 = dias*ventasDiarias;
    E13 = C14/D11
    C39 = costesFijos/C38;
    D39 = C39/12;
    D40 = D39/E13/30;
    numeroVentas1 = D40;
    numeroVentas=numeroVentas1.toFixed(2)
    document.getElementById("numeroVentas").innerText = numeroVentas;
   


    
        // 9 //---------RIESGO
    
    C39 = costesFijos/C38;
    riesgo1 = C39/C14;
    riesgo2 = riesgo1*100;
    riesgo=riesgo2.toFixed(2);
    document.getElementById("riesgo").innerText = riesgo;

    
    
}












