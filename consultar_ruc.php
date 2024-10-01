<?php
// Archivo: consultar_ruc.php

if (isset($_GET['ruc'])) {
    $numeroRuc = $_GET['ruc'];
    $url = "https://apiconsulta.supporttechec.com/ConsultarRucPro.php?numeroRuc=" . $numeroRuc;
    
    $response = file_get_contents($url);

    if ($response === FALSE) {
        echo json_encode(array('result' => 0, 'message' => 'Error en la conexión con la API'));
        exit;
    }

    // Decodificar la respuesta JSON
    $data = json_decode($response, true);

    if ($data['result'] == 1) {
        echo json_encode($data);
    } else {
        echo json_encode(array('result' => 0, 'message' => 'RUC no encontrado'));
    }
}
?>
