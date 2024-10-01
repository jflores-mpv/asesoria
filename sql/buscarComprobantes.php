<?php
// esta pagina retorna en la pagina: libroDiario.php
try {

require_once('../conexion.php');
	
    // Is there a posted query string?
    if(isset($_POST['queryString'])) {
            $queryString = $_POST['queryString'];
            // Is the string length greater than 0?
            $a=0;
            if(strlen($queryString) > 0) {

                    $query = "SELECT * FROM libro_diario WHERE numero_comprobante LIKE '$queryString%' OR numero_asiento LIKE '$queryString%' LIMIT 10";
                    $result = mysql_query($query) or die(mysql_error());
                    $numero_filas = mysql_num_rows($result); // obtenemos el número de filas
                    if($result) {
                         if($numero_filas == 0){
                                echo "<center><p><label> No hay resulados con el parámetro ingresado.</label></p></center>";
                         }else{
                             echo "<table width='350' border='0' id='grilla' class='lista'>";
                            while ($row = mysql_fetch_assoc($result)) {

                                if($a == 0){
                                    echo "<tr>";
                                    echo "<td><center><label style='color:#39c;'><strong># Compro</strong></label></center></td>";
                                    echo "<td width='80'><center><label style='color:#39c;'><strong>Fecha</strong></label></center></td>";
                                    echo "<td><center><label style='color:#39c;'><strong># Asiento</strong></label></center></td>";
                                    echo "<td><center><label style='color:#39c;'><strong>Total Debe</strong></label></center></td>";
                                    echo "<td><center><label style='color:#39c;'><strong>Total Haber</strong></label></center></td>";
                                   echo "</tr>";
                                }
                                echo '<tr title="Clic para seleccionar" onClick="fill1(\''.$row["numero_comprobante"]." - ".$row["fecha"]." - ".$row["descripcion"].'\','.$row["id_libro_diario"].');">';
                                echo '<td>' .$row["numero_comprobante"].'</td>';
                                echo '<td width="80">'.$row["fecha"].'</td>';
                                echo '<td>'.$row["numero_asiento"].'</td>';
                                 echo '<td>'.$row["total_debe"].'</td>';
                                 echo '<td>'.$row["total_haber"].'</td>';
                                 echo "</tr>";
                                 $a++;
                             }
                             echo "</table>";
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
}catch (Exception $e){ // Error en algun momento.
        echo "".$e;  }
?>