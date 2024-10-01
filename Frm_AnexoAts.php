<!DOCTYPE html>
<?php
error_reporting(0);
session_start();
include 'conexion2.php';
require('ver_sesion.php');

$sesion_id_institucion_educativa = $_SESSION["sesion_id_institucion_educativa"];
$sesion_aula = $_SESSION["sesion_cod_aula"];
$sesion_id_nivel_institucion = $_SESSION["sesion_id_nivel_institucion"];
$niveles = mysqli_query($conexion, "SELECT id_nivel, nombre FROM nivel_de_institucion WHERE id_institucion_educativa = '$sesion_id_institucion_educativa'");
$fcha = date("Y-m-d");
$fecha = date("Y-m-d H:i:s");
$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
$sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];
$sesion_cod_activacion_prof = $_SESSION["sesion_cod_activacion"];
$sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
?>

<html lang="es-ES">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Anexo transaccional</title>
    <script src="https://kit.fontawesome.com/7816c50242.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="librerias/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="librerias/alertifyjs/css/alertify.css">
    <link rel="stylesheet" type="text/css" href="librerias/alertifyjs/css/themes/default.css">
    <link rel="stylesheet" type="text/css" href="librerias/select2/css/select2.css">
    <link rel="stylesheet" href="librerias/bootstrap/css/style.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/iconos.css">
    <script src="librerias/jquery-3.2.1.min.js"></script>
    <script src="js/save.js"></script>
    <script src="librerias/bootstrap/js/bootstrap.js"></script>
    <script src="librerias/alertifyjs/alertify.js"></script>
    <script language="javascript" type="text/javascript" src="js/index.js"></script>
    <script language="javascript" type="text/javascript" src="js/ats.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.js"></script>
    <script language="javascript">
        $(document).ready(function() {
            $("#tipoDeclaracion").on('change', function() {
                $("#tipoDeclaracion option:selected").each(function() {
                    var elegido = $(this).val();
                    $.post("sql/periodo.php", { elegido: elegido }, function(data) {
                        $("#periodo").html(data);
                    });
                });
            });
        });
    </script>
</head>

<body onload="listar_reporte_ats(1)">
    <div class="wrapper d-flex align-items-stretch celeste">
        <nav id="sidebar"><?php { include("menusNiveles.php"); } ?></nav>
        <div id="content" class="pt-5">
            <header><?php { include("header.php"); } ?></header>
            <div id="content">
                <header><?php { include("header.php"); } ?></header>
                <div class="row">
                    <div class="col-lg-11 bg-white mx-5 p-4 rounded">
                        <form name="frmAnexoAts" id="frmAnexoAts" method="post">
                            <div id="mensaje"></div>
                            <div class="row">
                                <div class="col-lg-2 mb-3">
                                    <label for="tipoDeclaracion" class="form-label">Tipo de declaración</label>
                                    <select name="tipoDeclaracion" id="tipoDeclaracion" class="form-select">
                                        <option value="1">Mensual</option>
                                        <option value="2">Semestral</option>
                                    </select>
                                </div>
                                <div class="col-lg-2 mb-3">
                                    <label for="periodo" class="form-label">Periodo</label>
                                    <select name="periodo" id="periodo" class="form-select">
                                        <option value="01">Enero</option>
                                        <option value="02">Febrero</option>
                                        <option value="03">Marzo</option>
                                        <option value="04">Abril</option>
                                        <option value="05">Mayo</option>
                                        <option value="06">Junio</option>
                                        <option value="07">Julio</option>
                                        <option value="08">Agosto</option>
                                        <option value="09">Septiembre</option>
                                        <option value="10">Octubre</option>
                                        <option value="11">Noviembre</option>
                                        <option value="12">Diciembre</option>
                                    </select>
                                </div>
                                <div class="col-lg-2 mb-3">
                                    <label for="anio" class="form-label">Año de declaración</label>
                                    <input type="text" id="anio" name="anio" class="form-control">
                                </div>
                                <div class="col-lg-12">
                                    <button type="button" class="btn btn-primary" onclick="crear_ats();">Grabar</button>
                                    <button type="button" class="btn btn-secondary" onclick="javascript: fn_cerrar();">Cerrar</button>
                                </div>
                            </div>
                            <div class="row">
                                <form id="filtrosAts"></form>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-11 bg-white mx-5 p-4 mt-4 rounded">
                        <div id="listarAnexos"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script language="javascript" type="text/javascript" src="js/chart.js"></script>
    <script src="librerias/bootstrap/js/main.js"></script>
    <script language="javascript" type="text/javascript" src="js/main.js"></script>
</body>
</html>
