<?php
//esta pagina retorna en la pagina: ajax/modificar_plan_cuentas
try {

    require_once('../conexion.php');
	
    // Is there a posted query string?
    if(isset($_POST['queryString'])) {

            $queryString = $_POST['queryString'];

            // Is the string length greater than 0?
            if(strlen($queryString) >0) {
                    $query = "SELECT * FROM plan_cuentas WHERE plan_cuentas.nombre LIKE '$queryString%' OR plan_cuentas.codigo LIKE '$queryString%' order by plan_cuentas.codigo asc";
                    $result = mysql_query($query);
                    $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
                    if($result) {
                        if($numero_filas == 0){
                                echo "<div class='alert alert-danger'><p><label> No hay resultados con el parámetro ingresado.</label></p></div>";
                         }else{
                            // While there are results loop through them - fetching an Object (i like PHP5 btw!).
                             echo "<table class='table table-bordered table-hover' border='0' >";
                             echo "<tr style='border-bottom: 1px solid #CCC;'>
                             <th >Modificar</th><th style='padding-left: 5px; padding-right: 5px;'>C&oacute;digo</th><th style='padding-left: 5px; padding-right: 5px;'>Nombre</th><th style='padding-left: 5px; padding-right: 5px;'>Clasificaci&oacute;n</th><th style='padding-left: 5px; padding-right: 5px;'>Tipo</th><th style='padding-left: 5px; padding-right: 5px;'>Categor&iacute;a</th></tr>";
                             while ($row = mysql_fetch_assoc($result)) {
                                    echo "<tr><td><li onClick='javascript: modificarPlanCuentas(".$row['id_plan_cuenta'].");'><a ><img src='images/edit.png' width='16' height='16' /></a></li></td>";
                                    echo "<td>".$row["codigo"]."</td>";
                                    echo "<td>".$row["nombre"]."</td>";
                                    echo "<td>".$row["clasificacion"]."</td>";
                                    echo "<td>".$row["tipo"]."</td>";
                                    echo "<td>".$row["categoria"]."</td>";
                                    echo "</tr>";
                                    }
                               echo"</table>";
                           }
                    } else {
                            echo "<div class='transparent_ajax_error'><p><label>Error: Hay un problema con la consulta.</label></p></div>";
                    }
            } else {
                echo "<div class='transparent_ajax_error'><p><label>Error: La longitud no es la permitida.</label></p></div>";
                    // Dont do anything.
            } // There is a queryString.
    } else {
            echo "<div class='transparent_ajax_error'><p><label>Error: No hay ningún acceso directo a este script!</label></p></div>";
    }
}catch (Exception $e){ // Error en algun momento.
       echo "<div class='transparent_ajax_error'><p><label>Error: ".$e."</label></p></div>";
       }
?>
