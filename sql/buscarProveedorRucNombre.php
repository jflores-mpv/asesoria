<?php
	session_start();
	$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
try {

require_once('../conexion.php');

    if(isset($_POST['queryString'])) {
    $queryString = $_POST['queryString'];

        // Is the string length greater than 0?
        if(strlen($queryString) >0) {
        $query = "SELECT
         proveedores.id_proveedor AS proveedores_id_proveedor,
         proveedores.nombre_comercial AS proveedores_nombre_comercial,
         proveedores.nombre AS proveedores_nombre,
         proveedores.direccion AS proveedores_direccion,
         proveedores.ruc AS proveedores_ruc,
         proveedores.telefono AS proveedores_telefono,
         proveedores.movil AS proveedores_movil,
         proveedores.fax AS proveedores_fax,
         proveedores.email AS proveedores_email,
         proveedores.web AS proveedores_web,
         proveedores.observaciones AS proveedores_observaciones,
         proveedores.id_ciudad AS proveedores_id_ciudad
    FROM
         proveedores

    WHERE proveedores.`id_empresa`='".$sesion_id_empresa."' and  
	(proveedores.ruc LIKE '%$queryString%' or proveedores.nombre_comercial LIKE '%$queryString%') LIMIT 10; ";
// echo $query;
        $result = mysql_query($query) or die(mysql_error());
        $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
        if($result) {
            if($numero_filas == 0){
                        echo "<div class=''> No hay resultados con el parámetro ingresado. </div>";
                 }else{
                    echo "<table class='table table-condensed table-hover'>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "
                          <th style='padding-right: 5px;'>RUC</th> 
                          <th style='padding-right: 5px;'>Nombre</th> 
					      <th style='padding-right: 5px;'>Dirección</th>  
					      <th style='padding-right: 5px;'><a href='javascript: fn_cerrar_div();'>	<button type='button' class='btn btn-default' aria-label='Left Align'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span></button></a></th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                    while ($row = mysql_fetch_array($result)) {
                        echo '<tr style="cursor: pointer" title="Clic para seleccionar" onClick="fill6(\''.$row["proveedores_nombre_comercial"].'\',\''.$row["proveedores_ruc"].'\',\''.$row["proveedores_telefono"].'\',\''.$row["proveedores_direccion"].'\',\''.$row["proveedores_id_proveedor"].'\');" >';
                        echo "<td>".$row["proveedores_ruc"]."</td>";
                        echo "<td>".$row["proveedores_nombre_comercial"]."</td>";
                        echo "<td>".$row["proveedores_direccion"]."</td>";
                        echo "</tr>";
                    }
                    echo"</table> ";
                 }
                
            } else {
                    echo 'ERROR: Hay un problema con la consulta.';
            }
        } else {
            echo 'La longitud no es la permitida.';
                // Dont do anything.
        } // There is a queryString.
    } else {
            echo 'No hay ningún acceso directo a este script!';
    }
}catch (Exception $e) { echo "".$e; }
?>