<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../conexion2.php'; // Conexión a la base de datos
session_start();

$id_empresa = isset($_SESSION["sesion_id_empresa"]) ? $_SESSION["sesion_id_empresa"] : null;

// Si no se pasa ningún rango de fechas, se toma el año en curso
if (!isset($_GET['desde']) && !isset($_GET['hasta'])) {
    $fechaDesde = date('Y-01-01');  // Primer día del año en curso
    $fechaHasta = date('Y-12-31');  // Último día del año en curso
} else {
    $fechaDesde = isset($_GET['desde']) ? $_GET['desde'] : date('Y-01-01');
    $fechaHasta = isset($_GET['hasta']) ? $_GET['hasta'] : date('Y-12-31');
}

// Depuración de las fechas recibidas (opcional)
echo "Desde: " . $fechaDesde . " Hasta: " . $fechaHasta . "<br>";

// Consulta SQL para obtener ventas
$query = "
    SELECT MONTH(fecha_venta) AS mes, SUM(total) AS total_ventas
    FROM ventas
    WHERE fecha_venta BETWEEN ? AND ?
    AND id_empresa = ?
    GROUP BY MONTH(fecha_venta)
    ORDER BY MONTH(fecha_venta) ASC";

$stmt = $conexion->prepare($query);
$stmt->bind_param("ssi", $fechaDesde, $fechaHasta, $id_empresa);
$stmt->execute();
$result = $stmt->get_result();

// Inicializar un array con 12 posiciones (una por cada mes)
$ventas = array_fill(0, 12, 0);

// Procesar los resultados
while ($row = $result->fetch_assoc()) {
    $ventas[$row['mes'] - 1] = (float)$row['total_ventas'];
}

// Devolver los datos en formato JSON
echo json_encode(['ventas' => $ventas]);

// Cerrar la conexión
$stmt->close();
$conexion->close();
?>
