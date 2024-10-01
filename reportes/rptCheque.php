<?php
error_reporting(0);


//Include database connection details

   require_once('../conexion.php');

function imprimirCheque($idLibroDiario, &$pdf){
    // echo "libro diario==>".$idLibroDiario;
    // exit();
    session_start();
  
     $sesion_ciudades_ciudad = $_SESSION['sesion_empresa_id_ciudad'];
     $sqlCiudad="SELECT `id_ciudad`, `ciudad` FROM `ciudades` WHERE id_ciudad=$sesion_ciudades_ciudad";
    
    $resultCiudad= mysql_query($sqlCiudad);
    $ciudad='';
    while($roC= mysql_fetch_array($resultCiudad)){
        
        $ciudad = $roC['ciudad'];
    }
           
    
   $sqlLibroDiario="SELECT * FROM `detalle_bancos` WHERE id_libro_diario = $idLibroDiario";
    $resultLibroDiario =mysql_query($sqlLibroDiario);
    $existe = mysql_num_rows($resultLibroDiario);
    if($existe>0){
         $datosBancarios=array();
        while($rowLb = mysql_fetch_array($resultLibroDiario)){
           $acreedor = $rowLb['detalle'];
            $valor= $rowLb['valor'];
            $fecha = $rowLb['fecha_cobro'];
        }


function basico($numero) {
    $valor = array ('uno','dos','tres','cuatro','cinco','seis','siete','ocho',
    'nueve','diez','once','doce','trece','catorce','quince','dieciseis','diecisiete','dieciocho','diecinueve','veinte','veintiuno ','vientidos ','veintitrés ', 'veinticuatro','veinticinco',
    'veintiséis','veintisiete','veintiocho','veintinueve');
    return $valor[$numero - 1];
    }
    
    function decenas($n) {
    $decenas = array (30=>'treinta',40=>'cuarenta',50=>'cincuenta',60=>'sesenta',
    70=>'setenta',80=>'ochenta',90=>'noventa');
    if( $n <= 29) return basico($n);
    $x = $n % 10;
    if ( $x == 0 ) {
    return $decenas[$n];
    } else return $decenas[$n - $x].' y '. basico($x);
    }
    
    function centenas($n) {
    $cientos = array (100 =>'cien',200 =>'doscientos',300=>'trecientos',
    400=>'cuatrocientos', 500=>'quinientos',600=>'seiscientos',
    700=>'setecientos',800=>'ochocientos', 900 =>'novecientos');
    if( $n >= 100) {
    if ( $n % 100 == 0 ) {
    return $cientos[$n];
    } else {
    $u = (int) substr($n,0,1);
    $d = (int) substr($n,1,2);
    return (($u == 1)?'ciento':$cientos[$u*100]).' '.decenas($d);
    }
    } else return decenas($n);
    }
    
    function miles($n) {
    if($n > 999) {
    if( $n == 1000) {return 'mil';}
    else {
    $l = strlen($n);
    $c = (int)substr($n,0,$l-3);
    $x = (int)substr($n,-3);
    if($c == 1) {$cadena = 'mil '.centenas($x);}
    else if($x != 0) {$cadena = centenas($c).' mil '.centenas($x);}
    else $cadena = centenas($c). ' mil';
    return $cadena;
    }
    } else return centenas($n);
    }
    
    function millones($n) {
    if($n == 1000000) {return 'un millón';}
    else {
    $l = strlen($n);
    $c = (int)substr($n,0,$l-6);
    $x = (int)substr($n,-6);
    if($c == 1) {
    $cadena = ' millón ';
    } else {
    $cadena = ' millones ';
    }
    return miles($c).$cadena.(($x > 0)?miles($x):'');
    }
    }
    function convertir($n) {
    switch (true) {
    case ( $n >= 1 && $n <= 29) : return basico($n); break;
    case ( $n >= 30 && $n < 100) : return decenas($n); break;
    case ( $n >= 100 && $n < 1000) : return centenas($n); break;
    case ($n >= 1000 && $n <= 999999): return miles($n); break;
    case ($n >= 1000000): return millones($n);
    }
    }

  
// $acreedor='Juan López';
// $valor = '75,322';
// $cantidadLetra = 'Setenta y cinco 32/100 o setentea y cinco dolares con treinta y dos centavos Setenta y cinco 32/100 o setentea y cinco dolares con treinta y dos centavos';
// $ciudad = 'Quito';
// $fecha = '2014/09/26';


$numero = str_replace(',', ".",$valor);
    $entero = floor($numero);
   
    list($entero, $decimal) = explode('.', $numero);
    $decimalConvertido = convertir($decimal);
  
    if(is_null($decimal)){
           $cantidadLetra= ''.ucfirst(convertir($entero)).utf8_decode(' dólares ');
    }else{
           $cantidadLetra= ''.ucfirst(convertir($entero)).utf8_decode(' dólares con '.$decimalConvertido.' centavos');
    }
   
 

//version 1 es completo
// version 2 es solo letra
$versiones='2';
//  echo "cheque 2";
 
//  exit();
// $pdf->AddPage('L',array(76,156));
$pdf->AddPage('P','A4');
$pdf->SetFont('times', '', 12);
if($versiones=='1'){

    $primeraLinea=18;
    $pdf->SetY($primeraLinea);

    
    $pdf->MultiCell(23,3,utf8_decode('PÁGUESE A LA ORDEN DE '),0,'L',0); //comentar
    
    
    $pdf->SetY($primeraLinea);
    $pdf->SetX(35);
    $pdf->Cell(70, 9, $acreedor , 0, 1, 'L', 0, '', 0);
    $pdf->SetY($primeraLinea);
    $pdf->SetX(110);
    $pdf->Cell($primeraLinea, 10, 'US. $' , 0, 1, 'L', 0, '', 0);
    $pdf->SetY($primeraLinea);
    $pdf->SetX(160);
    $pdf->Cell($primeraLinea, 10, $valor , 0, 1, 'L', 0, '', 0);
    
    
    $pdf->SetY($primeraLinea+8);
    $pdf->MultiCell(140,9,'LA SUMA DE '.$cantidadLetra, 0,'L',0);
    

    $pdf->SetY($primeraLinea+14);
    $pdf->SetX(120);
    $pdf->Cell(25,5,"US.DOLARES", 0,'L',0);

    // nombre
    $pdf->Line(25, $primeraLinea+7, 96, $primeraLinea+7 );//comentar
    // saldo
    $pdf->Line(105, $primeraLinea+7, 145, $primeraLinea+7 );//comentar

    //suma
    $pdf->Line(2, 37, 120, 37 );//comentar
    $pdf->Line(22, 32, 145, 32 );//comentar

    //ciudad

    $pdf->Line(5, 42, 30, 42 );//comentar

    //ano mes dia
    $pdf->Line(35, 42, 70, 42 );//comentar
    
    $pdf->Line(110, 52, 140, 52 );//comentar
    $pdf->SetY(52);
    $pdf->SetX(110);
    $pdf->Cell(30, 6, 'FIRMA' , 0, 1, 'C', 0, '', 0);
    
    $pdf->SetY(35);
    $pdf->SetX(5);
    $pdf->Cell(30, 10, $ciudad , 0, 1, 'C', 0, '', 0);
    
    $pdf->SetY(41);
    $pdf->SetX(5);
    $pdf->Cell(30, 6, 'CIUDAD' , 0, 1, 'C', 0, '', 0);
    
    $pdf->SetY(35);
    $pdf->SetX(41);
    $pdf->Cell(70, 10, $fecha , 0, 1, 'L', 0, '', 0);
    
    $pdf->SetY(41);
    $pdf->SetX(35);
    $pdf->Cell(30, 6, 'AÑO MES DIA ' , 0, 1, 'C', 0, '', 0);
}else{

    $primeraLinea=18;
    $pdf->SetY($primeraLinea);
   
    
    $pdf->SetY($primeraLinea);
    $pdf->SetX(35);
    $pdf->Cell(70, 9, $acreedor , 0, 1, 'L', 0, '', 0);

    $pdf->SetY($primeraLinea);
    $pdf->SetX(120);
    $pdf->Cell($primeraLinea, 10, $valor , 0, 1, 'L', 0, '', 0);
    
    
    $pdf->SetY($primeraLinea+8);
    $pdf->MultiCell(140,9,'                           '.$cantidadLetra, 0,'L',0);
  
    
    $pdf->SetY(35);
    $pdf->SetX(70);
    $pdf->Cell(30, 10, $ciudad.",".$fecha , 0, 1, 'C', 0, '', 0);



}


$pdf->Output('cheque.pdf', 'I');
    }
   
}
function basico($numero) {
    $valor = array ('uno','dos','tres','cuatro','cinco','seis','siete','ocho',
    'nueve','diez','once','doce','trece','catorce','quince','dieciseis','diecisiete','dieciocho','diecinueve','veinte','veintiuno ','vientidos ','veintitrés ', 'veinticuatro','veinticinco',
    'veintiséis','veintisiete','veintiocho','veintinueve');
    return $valor[$numero - 1];
    }
    
    function decenas($n) {
    $decenas = array (30=>'treinta',40=>'cuarenta',50=>'cincuenta',60=>'sesenta',
    70=>'setenta',80=>'ochenta',90=>'noventa');
    if( $n <= 29) return basico($n);
    $x = $n % 10;
    if ( $x == 0 ) {
    return $decenas[$n];
    } else return $decenas[$n - $x].' y '. basico($x);
    }
    
    function centenas($n) {
    $cientos = array (100 =>'cien',200 =>'doscientos',300=>'trecientos',
    400=>'cuatrocientos', 500=>'quinientos',600=>'seiscientos',
    700=>'setecientos',800=>'ochocientos', 900 =>'novecientos');
    if( $n >= 100) {
    if ( $n % 100 == 0 ) {
    return $cientos[$n];
    } else {
    $u = (int) substr($n,0,1);
    $d = (int) substr($n,1,2);
    return (($u == 1)?'ciento':$cientos[$u*100]).' '.decenas($d);
    }
    } else return decenas($n);
    }
    
    function miles($n) {
    if($n > 999) {
    if( $n == 1000) {return 'mil';}
    else {
    $l = strlen($n);
    $c = (int)substr($n,0,$l-3);
    $x = (int)substr($n,-3);
    if($c == 1) {$cadena = 'mil '.centenas($x);}
    else if($x != 0) {$cadena = centenas($c).' mil '.centenas($x);}
    else $cadena = centenas($c). ' mil';
    return $cadena;
    }
    } else return centenas($n);
    }
    
    function millones($n) {
    if($n == 1000000) {return 'un millón';}
    else {
    $l = strlen($n);
    $c = (int)substr($n,0,$l-6);
    $x = (int)substr($n,-6);
    if($c == 1) {
    $cadena = ' millón ';
    } else {
    $cadena = ' millones ';
    }
    return miles($c).$cadena.(($x > 0)?miles($x):'');
    }
    }
    function convertir($n) {
    switch (true) {
    case ( $n >= 1 && $n <= 29) : return basico($n); break;
    case ( $n >= 30 && $n < 100) : return decenas($n); break;
    case ( $n >= 100 && $n < 1000) : return centenas($n); break;
    case ($n >= 1000 && $n <= 999999): return miles($n); break;
    case ($n >= 1000000): return millones($n);
    }
    }

function imprimirCheque2($idLibroDiario, &$pdf,$nombreCliente){
   
    session_start();
  
     $sesion_ciudades_ciudad = $_SESSION['sesion_empresa_id_ciudad'];
     $sqlCiudad="SELECT `id_ciudad`, `ciudad` FROM `ciudades` WHERE id_ciudad=$sesion_ciudades_ciudad";
    
    $resultCiudad= mysql_query($sqlCiudad);
    $ciudad='';
    while($roC= mysql_fetch_array($resultCiudad)){
        
        $ciudad = $roC['ciudad'];
    }
   
    
     $sqlLibroDiario="SELECT * FROM `detalle_bancos` WHERE id_libro_diario = $idLibroDiario";
    $resultLibroDiario =mysql_query($sqlLibroDiario);
    $existe = mysql_num_rows($resultLibroDiario);
    if($existe>0){
         $datosBancarios=array();
        while($rowLb = mysql_fetch_array($resultLibroDiario)){
           $acreedor = $rowLb['detalle'];
            $valor= $rowLb['valor'];
            $fecha = $rowLb['fecha_cobro'];
        }
 if($nombreCliente!=''){
           $acreedor = $nombreCliente; 
        }
// $acreedor='Juan López';
// $valor = '75,322';
// $cantidadLetra = 'Setenta y cinco 32/100 o setentea y cinco dolares con treinta y dos centavos Setenta y cinco 32/100 o setentea y cinco dolares con treinta y dos centavos';
// $ciudad = 'Quito';
// $fecha = '2014/09/26';


$numero = str_replace(',', ".",$valor);
    $entero = floor($numero);
   
    list($entero, $decimal) = explode('.', $numero);
    $decimalConvertido = convertir($decimal);
  
    if(is_null($decimal)){
           $cantidadLetra= ''.ucfirst(convertir($entero)).utf8_decode(' dólares ');
    }else{
           $cantidadLetra= ''.ucfirst(convertir($entero)).utf8_decode(' dólares con '.$decimalConvertido.' centavos');
    }
   
 

//version 1 es completo
// version 2 es solo letra
$versiones='2';
//  echo "cheque 2";
 
//  exit();
// $pdf->AddPage('L',array(76,156));
$pdf->AddPage('P','A4',0,0);
$pdf->SetFont('times', '', 12);

if($versiones=='1'){

    $primeraLinea=18;
    $pdf->SetY($primeraLinea);

    
    $pdf->MultiCell(23,3,utf8_decode('PÁGUESE A LA ORDEN DE '),0,'L',0); //comentar
    
    
    $pdf->SetY($primeraLinea);
    $pdf->SetX(35);
    $pdf->Cell(70, 9, $acreedor , 0, 1, 'L', 0, '', 0);
    $pdf->SetY($primeraLinea);
    $pdf->SetX(110);
    $pdf->Cell($primeraLinea, 10, 'US. $' , 0, 1, 'L', 0, '', 0);
    $pdf->SetY($primeraLinea);
    $pdf->SetX(160);
    $pdf->Cell($primeraLinea, 10, $valor , 0, 1, 'L', 0, '', 0);
    
    
    $pdf->SetY($primeraLinea+8);
    $pdf->MultiCell(140,9,'LA SUMA DE '.$cantidadLetra, 0,'L',0);
    

    $pdf->SetY($primeraLinea+14);
    $pdf->SetX(120);
    $pdf->Cell(25,5,"US.DOLARES", 0,'L',0);

    // nombre
    $pdf->Line(25, $primeraLinea+7, 96, $primeraLinea+7 );//comentar
    // saldo
    $pdf->Line(105, $primeraLinea+7, 145, $primeraLinea+7 );//comentar

    //suma
    $pdf->Line(2, 37, 120, 37 );//comentar
    $pdf->Line(22, 32, 145, 32 );//comentar

    //ciudad

    $pdf->Line(5, 42, 30, 42 );//comentar

    //ano mes dia
    $pdf->Line(35, 42, 70, 42 );//comentar
    
    $pdf->Line(110, 52, 140, 52 );//comentar
    $pdf->SetY(52);
    $pdf->SetX(110);
    $pdf->Cell(30, 6, 'FIRMA' , 0, 1, 'C', 0, '', 0);
    
    $pdf->SetY(35);
    $pdf->SetX(5);
    $pdf->Cell(30, 10, $ciudad , 0, 1, 'C', 0, '', 0);
    
    $pdf->SetY(41);
    $pdf->SetX(5);
    $pdf->Cell(30, 6, 'CIUDAD' , 0, 1, 'C', 0, '', 0);
    
    $pdf->SetY(35);
    $pdf->SetX(41);
    $pdf->Cell(70, 10, $fecha , 0, 1, 'L', 0, '', 0);
    
    $pdf->SetY(41);
    $pdf->SetX(35);
    $pdf->Cell(30, 6, 'AÑO MES DIA ' , 0, 1, 'C', 0, '', 0);
}else{

    $primeraLinea=18;
    $pdf->SetY($primeraLinea);
   
    
    $pdf->SetY($primeraLinea);
    $pdf->SetX(35);
    $pdf->Cell(70, 9, $acreedor , 0, 1, 'L', 0, '', 0);

    $pdf->SetY($primeraLinea);
    $pdf->SetX(120);
    $pdf->Cell($primeraLinea, 10, $valor , 0, 1, 'L', 0, '', 0);
    
    
    $pdf->SetY($primeraLinea+8);
    $pdf->MultiCell(140,9,'                           '.$cantidadLetra, 0,'L',0);
  
    
    $pdf->SetY(35);
    $pdf->SetX(70);
    $pdf->Cell(30, 10, $ciudad.",".$fecha , 0, 1, 'C', 0, '', 0);



}

// $pdf->Output('cheque.pdf', 'I');

    }

  return $pdf;
}

function imprimirChequeProdubanco($idLibroDiario, &$pdf,$nombreCliente){
   
    session_start();
  $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
     $sesion_ciudades_ciudad = $_SESSION['sesion_empresa_id_ciudad'];
     $sqlCiudad="SELECT `id_ciudad`, `ciudad` FROM `ciudades` WHERE id_ciudad=$sesion_ciudades_ciudad";
    
    $resultCiudad= mysql_query($sqlCiudad);
    $ciudad='';
    while($roC= mysql_fetch_array($resultCiudad)){
        
        $ciudad = $roC['ciudad'];
    }
   
    
     $sqlLibroDiario="SELECT * FROM `detalle_bancos` WHERE id_libro_diario = $idLibroDiario";
    $resultLibroDiario =mysql_query($sqlLibroDiario);
    $existe = mysql_num_rows($resultLibroDiario);
    if($existe>0){
         $datosBancarios=array();
        while($rowLb = mysql_fetch_array($resultLibroDiario)){
           $acreedor = $rowLb['detalle'];
            $valor= $rowLb['valor'];
            $fecha = $rowLb['fecha_cobro'];
        }
        if($nombreCliente!=''){
           $acreedor = $nombreCliente; 
        }

// $acreedor='Juan López';
// $valor = '75,322';
// $cantidadLetra = 'Setenta y cinco 32/100 o setentea y cinco dolares con treinta y dos centavos Setenta y cinco 32/100 o setentea y cinco dolares con treinta y dos centavos';
// $ciudad = 'Quito';
// $fecha = '2014/09/26';


$numero = str_replace(',', ".",$valor);
    $entero = floor($numero);
   
    list($entero, $decimal) = explode('.', $numero);
    $decimalConvertido = convertir($decimal);
  
    if(is_null($decimal)){
           $cantidadLetra= ''.ucfirst(convertir($entero)).utf8_decode(' dólares ');
    }else{
           $cantidadLetra= ''.ucfirst(convertir($entero)).utf8_decode(' dólares con '.$decimalConvertido.' centavos');
    }
   
 

//version 1 es completo
// version 2 es solo letra
$versiones='2';
//  echo "cheque 2";
 
//  exit();
// $pdf->AddPage('L',array(76,156));
$pdf->AddPage('P','A4',0,0);
$pdf->SetFont('times', '', 12);

if($versiones=='1'){

    $primeraLinea=10;
    $espaciado= 63;
    $pdf->SetY($primeraLinea);
    $pdf->SetX($espaciado);
    $pdf->SetFont('times', '', 5);
    $pdf->MultiCell(15,3,utf8_decode('PÁGUESE A LA ORDEN DE :'),0,'L',0); //comentar
    $pdf->SetFont('times', '', 12);

    
    $pdf->SetY($primeraLinea);
    $pdf->SetX(15+$espaciado);
    $pdf->Cell(90, 9, $acreedor , 0, 1, 'L', 0, '', 0);
    $pdf->SetY($primeraLinea);
    $pdf->SetX(105+$espaciado);
    $pdf->SetFont('times', '', 5);
    $pdf->Cell($primeraLinea, 10, 'US. $' , 0, 1, 'L', 0, '', 0);
    $pdf->SetFont('times', '', 12);
    $pdf->SetY($primeraLinea);
    $pdf->SetX(150+$espaciado);
    $pdf->Cell($primeraLinea, 10, $valor , 0, 1, 'L', 0, '', 0);
    
    // $cantidadLetra = "Lorem, ipsum dolor sit amet consectetur adipisicing elit. Molestias ex consequuntur distinctio vitae modi accusamus";
    
    $pdf->SetY($primeraLinea+8);
    $pdf->SetX(62);
    $pdf->SetFont('times', '', 10);
    $pdf->MultiCell(140,5,'LA SUMA DE : '.$cantidadLetra, 0,'L',0);
    $pdf->SetFont('times', '', 10);

    $pdf->SetY($primeraLinea+12);
    $pdf->SetX(112+$espaciado);
    $pdf->SetFont('times', '', 6);
    $pdf->Cell(25,5,"DOLARES", 0,'L',0);
    $pdf->SetFont('times', '', 12);

    // nombre
    $pdf->Line($espaciado, $primeraLinea+7, 200, $primeraLinea+7 );//comentar
    // saldo
    // $pdf->Line(105, $primeraLinea+7, 200, $primeraLinea+7 );//comentar

  
    $pdf->Line(30+$espaciado,  $primeraLinea+12, 200,  $primeraLinea+12 );//detalle
    $pdf->Line($espaciado, $primeraLinea+17, 200, $primeraLinea+17 );//detalle2
    


    $pdf->Line($espaciado, 32, 60+$espaciado, 32 );//ciudad    
    $pdf->Line(70+$espaciado, 40, 200, 40 );//firma
    
    $pdf->SetY(40);
    $pdf->SetX(85+$espaciado);
    $pdf->Cell(30, 6, 'FIRMA' , 0, 1, 'C', 0, '', 0);
    
    $pdf->SetY(26);
    $pdf->SetX($espaciado);
    $pdf->SetFont('times', '', 12);
    $pdf->Cell(55, 10, $ciudad.", ".$fecha , 0, 1, 'L', 0, '', 0);
    $pdf->SetFont('times', '', 12);
    $pdf->SetY(32);
    $pdf->SetX($espaciado);
    $pdf->Cell(30, 6, 'CIUDAD' , 0, 1, 'C', 0, '', 0);
    


}else{

   
    $primeraLinea=6+10;
    $espaciado= 63;

    // $acreedor = "Lorem ipsum dolor, sit amet consectetur adipisicing elit. Asperiores accusamus voluptatum possimus atque, quidem ex labore temporibus voluptatem eius nulla excepturi vel perspiciatis enim perferendis ducimus, provident, iusto saepe tenetur.";
    $anchoCelda = 70;

// Truncar el texto si es más largo que el ancho de la celda
if (strlen($acreedor) > $anchoCelda) {
    $acreedor = substr($acreedor, 0, $anchoCelda);
}

// Agregar el texto a la celda

     $pdf->SetFont('times', '', 10);
    $pdf->SetY($primeraLinea);
    $pdf->SetX(15+$espaciado);
    $pdf->Cell(105, 9, utf8_decode($acreedor), 0, 1, 'L', 0, '', 0);
    // $pdf->Cell(105, 9, utf8_decode($acreedor), 0, 1, 'L', 0, '', 0, true);


    $pdf->SetFont('times', '', 10);
    $pdf->SetY($primeraLinea-1);
    $pdf->SetX(125+$espaciado);
    $pdf->Cell($primeraLinea, 10, $valor , 0, 2, 'L', 0, '', 0);
    
    //  $cantidadLetra = "Lorem, ipsum dolor sit amet consectetur adipisicing elit. Molestias ex consequuntur distinctio vitae modi accusamus";
    // $cantidadLetra = "Lorem ipsum dolor, sit amet consectetur adipisicing elit. Asperiores accusamus voluptatum possimus atque, quidem ex labore temporibus voluptatem eius nulla excepturi vel perspiciatis enim perferendis ducimus, provident, iusto saepe tenetur.";
        $anchoCelda2 = 165;

// Truncar el texto si es más largo que el ancho de la celda
if (strlen($cantidadLetra) > $anchoCelda2) {
    $cantidadLetra = substr($cantidadLetra, 0, $anchoCelda2);
}

    $pdf->SetY($primeraLinea+8);
    $pdf->SetX(62);
    $pdf->SetFont('times', '', 10);
    
    $pdf->MultiCell(140,5,utf8_decode('                          ').($cantidadLetra), 0,'L',0);

    $pdf->SetY(16+$primeraLinea);
    $pdf->SetX($espaciado);
    $pdf->SetFont('times', '', 10);
    $pdf->Cell(55, 10, $ciudad.", ".$fecha , 0, 1, 'L', 0, '', 0);
    $pdf->SetFont('times', '', 12);
    }

  return $pdf;
}
}
function imprimirChequeProdubanco_pruebas($idLibroDiario, &$pdf,$nombreCliente){
   
    session_start();
  $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
     $sesion_ciudades_ciudad = $_SESSION['sesion_empresa_id_ciudad'];
     $sqlCiudad="SELECT `id_ciudad`, `ciudad` FROM `ciudades` WHERE id_ciudad=$sesion_ciudades_ciudad";
    
    $resultCiudad= mysql_query($sqlCiudad);
    $ciudad='';
    while($roC= mysql_fetch_array($resultCiudad)){
        
        $ciudad = $roC['ciudad'];
    }
   
    
     $sqlLibroDiario="SELECT * FROM `detalle_bancos` WHERE id_libro_diario = $idLibroDiario";
    $resultLibroDiario =mysql_query($sqlLibroDiario);
    $existe = mysql_num_rows($resultLibroDiario);
    if($existe>0){
         $datosBancarios=array();
        while($rowLb = mysql_fetch_array($resultLibroDiario)){
           $acreedor = $rowLb['detalle'];
            $valor= $rowLb['valor'];
            $fecha = $rowLb['fecha_cobro'];
        }
        if($nombreCliente!=''){
           $acreedor = $nombreCliente; 
        }

// $acreedor='Juan López';
// $valor = '75,322';
// $cantidadLetra = 'Setenta y cinco 32/100 o setentea y cinco dolares con treinta y dos centavos Setenta y cinco 32/100 o setentea y cinco dolares con treinta y dos centavos';
// $ciudad = 'Quito';
// $fecha = '2014/09/26';


$numero = str_replace(',', ".",$valor);
    $entero = floor($numero);
   
    list($entero, $decimal) = explode('.', $numero);
    $decimalConvertido = convertir($decimal);
  
    if(is_null($decimal)){
           $cantidadLetra= ''.ucfirst(convertir($entero)).utf8_decode(' dólares ');
    }else{
           $cantidadLetra= ''.ucfirst(convertir($entero)).utf8_decode(' dólares con '.$decimalConvertido.' centavos');
    }
   
 

//version 1 es completo
// version 2 es solo letra
$versiones='2';
//  echo "cheque 2";
 
//  exit();
// $pdf->AddPage('L',array(76,156));
$pdf->AddPage('P','A4',0,0);
$pdf->SetFont('times', '', 12);

if($versiones=='1'){

    $primeraLinea=10;
    $espaciado= 63;
    $pdf->SetY($primeraLinea);
    $pdf->SetX($espaciado);
    $pdf->SetFont('times', '', 5);
    $pdf->MultiCell(15,3,utf8_decode('PÁGUESE A LA ORDEN DE :'),0,'L',0); //comentar
    $pdf->SetFont('times', '', 12);

    
    $pdf->SetY($primeraLinea);
    $pdf->SetX(15+$espaciado);
    $pdf->Cell(90, 9, $acreedor , 0, 1, 'L', 0, '', 0);
    $pdf->SetY($primeraLinea);
    $pdf->SetX(105+$espaciado);
    $pdf->SetFont('times', '', 5);
    $pdf->Cell($primeraLinea, 10, 'US. $' , 0, 1, 'L', 0, '', 0);
    $pdf->SetFont('times', '', 12);
    $pdf->SetY($primeraLinea);
    $pdf->SetX(150+$espaciado);
    $pdf->Cell($primeraLinea, 10, $valor , 0, 1, 'L', 0, '', 0);
    
    // $cantidadLetra = "Lorem, ipsum dolor sit amet consectetur adipisicing elit. Molestias ex consequuntur distinctio vitae modi accusamus";
    
    $pdf->SetY($primeraLinea+8);
    $pdf->SetX(62);
    $pdf->SetFont('times', '', 10);
    $pdf->MultiCell(140,5,'LA SUMA DE : '.$cantidadLetra, 0,'L',0);
    $pdf->SetFont('times', '', 10);

    $pdf->SetY($primeraLinea+12);
    $pdf->SetX(112+$espaciado);
    $pdf->SetFont('times', '', 6);
    $pdf->Cell(25,5,"DOLARES", 0,'L',0);
    $pdf->SetFont('times', '', 12);

    // nombre
    $pdf->Line($espaciado, $primeraLinea+7, 200, $primeraLinea+7 );//comentar
    // saldo
    // $pdf->Line(105, $primeraLinea+7, 200, $primeraLinea+7 );//comentar

  
    $pdf->Line(30+$espaciado,  $primeraLinea+12, 200,  $primeraLinea+12 );//detalle
    $pdf->Line($espaciado, $primeraLinea+17, 200, $primeraLinea+17 );//detalle2
    


    $pdf->Line($espaciado, 32, 60+$espaciado, 32 );//ciudad    
    $pdf->Line(70+$espaciado, 40, 200, 40 );//firma
    
    $pdf->SetY(40);
    $pdf->SetX(85+$espaciado);
    $pdf->Cell(30, 6, 'FIRMA' , 0, 1, 'C', 0, '', 0);
    
    $pdf->SetY(26);
    $pdf->SetX($espaciado);
    $pdf->SetFont('times', '', 12);
    $pdf->Cell(55, 10, $ciudad.", ".$fecha , 0, 1, 'L', 0, '', 0);
    $pdf->SetFont('times', '', 12);
    $pdf->SetY(32);
    $pdf->SetX($espaciado);
    $pdf->Cell(30, 6, 'CIUDAD' , 0, 1, 'C', 0, '', 0);
    


}else{

   
    $primeraLinea=6+10;
    $espaciado= 63;

    // $acreedor = "Lorem ipsum dolor, sit amet consectetur adipisicing elit. Asperiores accusamus voluptatum possimus atque, quidem ex labore temporibus voluptatem eius nulla excepturi vel perspiciatis enim perferendis ducimus, provident, iusto saepe tenetur.";
    $anchoCelda = 70;

// Truncar el texto si es más largo que el ancho de la celda
if (strlen($acreedor) > $anchoCelda) {
    $acreedor = substr($acreedor, 0, $anchoCelda);
}

// Agregar el texto a la celda

     $pdf->SetFont('times', '', 10);
    $pdf->SetY($primeraLinea);
    $pdf->SetX(15+$espaciado);
    $pdf->Cell(105, 9, utf8_decode($acreedor), 0, 1, 'L', 0, '', 0);
    // $pdf->Cell(105, 9, utf8_decode($acreedor), 0, 1, 'L', 0, '', 0, true);


    $pdf->SetFont('times', '', 10);
    $pdf->SetY($primeraLinea-1);
    $pdf->SetX(125+$espaciado);
    $pdf->Cell($primeraLinea, 10, $valor , 0, 2, 'L', 0, '', 0);
    
    
    //   $cantidadLetra = "Lorem, ipsum dolor sit amet consectetur adipisicing elit. Molestias ex.";
    // $cantidadLetra = "Lorem ipsum dolor, sit amet consectetur adipisicing elit. Asperiores accusamus voluptatum possimus atque, quidem ex labore temporibus voluptatem eius nulla excepturi vel perspiciatis enim perferendis ducimus, provident, iusto saepe tenetur.";
        $anchoCelda2 = 125;

// Truncar el texto si es más largo que el ancho de la celda
if (strlen($cantidadLetra) > $anchoCelda2) {
    $cantidadLetra = substr($cantidadLetra, 0, $anchoCelda2);
}

$texto =$cantidadLetra;
$longitudMaxima = 125; // Longitud máxima de la celda



// Rellenar el texto con guiones hasta alcanzar la longitud máxima de la celda
$textoRelleno = str_pad($texto, $longitudMaxima, "- ");

// Recortar el texto si es más largo que la longitud máxima de la celda
$textoFinal = substr($textoRelleno, 0, $longitudMaxima);

// Agregar el texto a la celda



    $pdf->SetY($primeraLinea+8);
    $pdf->SetX(62);
    $pdf->SetFont('times', '', 10);
    // Agregar el texto y los guiones a la celda
    // $pdf->Cell($longitudMaxima, 5, utf8_decode($textoFinal), 0, 1, 'L');
$pdf->MultiCell($longitudMaxima, 5, $textoFinal, 0, 'L');
//   $pdf->MultiCell($longitudMaxima, 5, $texto . $guiones, 1, 'L');
    // $pdf->MultiCell(140,5,utf8_decode('                          ').($cantidadLetra), 0,'L',0);

    $pdf->SetY(16+$primeraLinea);
    $pdf->SetX($espaciado);
    $pdf->SetFont('times', '', 10);
    $pdf->Cell(55, 10, $ciudad.", ".$fecha , 0, 1, 'L', 0, '', 0);
    $pdf->SetFont('times', '', 12);
    }

  return $pdf;
}
}
?>