<?php 
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
require "reportes/code128.php";
 
$conexion = mysqli_connect('localhost', 'root', 'new_password', 'asesoria_asesoria');

$code = $_GET["code"];
$modulo = $_GET["modulo"];
$cantidad = isset($_GET["cantidad"]) && $_GET["cantidad"] !== '' ? $_GET["cantidad"] : 0;


if($modulo=='detalle_compras'){
    $tabla='id_compra';

$sqlAltura = "SELECT SUM($modulo.cantidad) AS suma_cantidad 
FROM $modulo
WHERE $tabla='$code' ";

$resultAltura = mysqli_query($conexion, $sqlAltura);
while ($filAltura= mysqli_fetch_assoc($resultAltura)) { 
    
    $filAlturaResultado= intval($filAltura['suma_cantidad']);
    if($filAlturaResultado > 3){
        $codigos_por_fila = 3; // Número de códigos por fila
        $altura_por_codigo = 32; // Altura de cada código en la fila
        $num_filas = ceil(($filAlturaResultado / $codigos_por_fila) * $altura_por_codigo);
        $orientacion = 'P';
    } else {
        $filAlturaResultado = $filAlturaResultado;
        $orientacion = 'L';
    }
}

    
}else if($modulo=='productos'){
        $tabla='productos';
        $filAlturaResultado=$cantidad;
        $codigos_por_fila = 3; // Número de códigos por fila
        $altura_por_codigo = 32; // Altura de cada código en la fila
        $num_filas = ceil(($filAlturaResultado / $codigos_por_fila) * $altura_por_codigo);
    
}else{
     $tabla='id_ingreso';
$sqlAltura = "SELECT SUM($modulo.cantidad) AS suma_cantidad 
FROM $modulo
WHERE $tabla='$code' ";

$resultAltura = mysqli_query($conexion, $sqlAltura);
while ($filAltura= mysqli_fetch_assoc($resultAltura)) { 
    
    $filAlturaResultado= intval($filAltura['suma_cantidad']);
    $codigos_por_fila = 3; // Número de códigos por fila
    $altura_por_codigo = 35; // Altura de cada código en la fila
    
    if($filAlturaResultado > 3){
        $num_filas = ceil(($filAlturaResultado / $codigos_por_fila) * $altura_por_codigo);
        $orientacion = 'P';
    } else {
        $num_filas = $altura_por_codigo;
        $orientacion = 'L';
    }
}

}





// Crear el objeto PDF fuera del bucle
$pdf = new PDF_Code128('P', 'mm', array(105, $num_filas));

$pdf->SetAutoPageBreak(true, -$filAlturaResultado); 
$pdf->SetMargins(0, 0, 0); 
$pdf->SetFont('Helvetica', '', 8);
$pdf->AddPage('P');

  $sql = "SELECT productos.codigo as codigo, $modulo.cantidad as cantidadCodigos,productos.producto as nombre
        FROM $modulo, productos
        WHERE $tabla='$code' and productos.id_producto=$modulo.id_producto";

$resultSumaCantidad = mysqli_query($conexion, $sql);

if (!$resultSumaCantidad) {
    die("Error al ejecutar la consulta: " . mysqli_error($conexion));
}



$altura_celda = 15; // Ajusta el alto de la celda según sea necesario
$ancho_celda = 25;
$contador = 0;
$x = 6;
$y = 3;

while ($fila = mysqli_fetch_assoc($resultSumaCantidad)) {   
    $cantidadCodigos = intval($fila['cantidadCodigos']);
    
    for ($i = 0; $i < $cantidadCodigos; $i++) {
        $codigo = $fila['codigo']; // Obtener el código actual
        
        $pdf->Code128($x, $y, $codigo, $ancho_celda, $altura_celda);
        $pdf->Cell(2, 26, '', 0, 0, 'C');
        $pdf->Cell(32.33, 40, $codigo, 0, 0, 'C');
        $pdf->Cell(2, 26, '', 0, 0, 'C');
        
        $contador++;
        $x += 34.33;
        
        if ($contador == 3 ) {
            $pdf->Ln();
            $pdf->Cell(((32.33 * 3) + 8), 3, '', 0, 0, 'C');
            $pdf->Ln();
            $x = 6;
            $y += 29;
            $contador = 0;
        }
    }
}

$pdf->Output();
$pdf->Close(); 
?>
