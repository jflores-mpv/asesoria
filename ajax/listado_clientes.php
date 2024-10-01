<?php

require_once('../conexion.php');
require_once('../conexion2.php');
session_start();
$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
 $sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];
 
$nombreCliente= trim($_GET['nombreCliente']);
$id_ciudad = $_GET['cbciudad'];
$cedula = trim($_GET['cedula']);
$numeroRegistros=$_GET['cantidad_clientes'];
$facturados = $_GET['facturados'];



$idClientes= substr($_GET['idCLientes'], 2); 
if($idClientes!=''){
    $idClientes= explode(",", $idClientes);
}else{
    $idClientes= array(0);
}

if ($sesion_tipo_empresa=='COLEGIO'){
    $sql="SELECT
    estudiante.nombres_estudiante,
    estudiante.apellidos_estudiante,
    estudiante.cedula as cedula_estudiante,
    clientes.*,
    paralelo_colegio.paralelo,
    curso_colegio.descripcion,
    seccion_colegio.nombre_seccion
FROM
    `estudiante`
INNER JOIN clientes ON estudiante.factura_cliente = clientes.id_cliente
LEFT JOIN paralelo_colegio ON estudiante.paralelo = paralelo_colegio.id_paralelo
LEFT JOIN curso_colegio ON curso_colegio.id_curso = paralelo_colegio.id_curso
LEFT JOIN seccion_colegio ON seccion_colegio.id_seccion = curso_colegio.id_seccion ";
}else{
  $sql="SELECT `id_cliente`, `nombre`, `apellido`, `direccion`, `cedula`,id_ciudad FROM `clientes`";  
}

$sql.=" where clientes.id_empresa='".$sesion_id_empresa."' AND clientes.estado = 'Activo'";

if ($sesion_tipo_empresa=='COLEGIO'){
    $cmbSeccion = $_GET['cmbSeccion'];
    $cmbCurso = $_GET['cmbCurso'];
    $cmbParalelo = $_GET['cmbParalelo'];
    
    if($cmbSeccion!=0){
         $sql.=" and seccion_colegio.id_seccion ='".$cmbSeccion."' ";
    }
    if($cmbCurso!=0){
         $sql.=" and curso_colegio.id_curso ='".$cmbCurso."' ";
    }
    if($cmbParalelo!=0){
         $sql.=" and estudiante.paralelo ='".$cmbParalelo."' ";
    }
}
if($nombreCliente != ''){
    $sql.="  and clientes.nombre like '%".$nombreCliente."%' ";
}
if($cedula!= ''){
    $sql.=" and  clientes.cedula like '%".$cedula."%' ";
}
if($id_ciudad != '0'){
    $sql.=" and clientes.id_ciudad ='".$id_ciudad."' ";
}

if($facturados != 'Todos'){
    if($facturados=='SI'){
         $sql.=" AND clientes.id_cliente  IN (SELECT ventas.id_cliente from ventas WHERE YEAR(ventas.fecha_venta) = YEAR(CURRENT_DATE) AND MONTH(ventas.fecha_venta) = MONTH(CURRENT_DATE) AND ventas.id_empresa=$sesion_id_empresa) ";
    }else{
         $sql.=" AND clientes.id_cliente   NOT IN (SELECT ventas.id_cliente from ventas WHERE YEAR(ventas.fecha_venta) = YEAR(CURRENT_DATE) AND MONTH(ventas.fecha_venta) = MONTH(CURRENT_DATE) AND ventas.id_empresa=$sesion_id_empresa) ";
    }
   
}
if ($sesion_tipo_empresa=='COLEGIO'){
// $sql.=" GROUP BY clientes.id_cliente ";

}
                if($sesion_id_empresa==41){
    // echo $sql;
}

// echo $sql;

// $sql.=" and id_ciudad ='".$id_ciudad."' ";
                $resultClientes=mysqli_query($conexion,$sql);

                $num_total_rows = mysqli_num_rows($resultClientes);

                if ($num_total_rows > 0) {
                    $page = false;
                    if (isset($_GET["page"])) {
                      $page = $_GET["page"];
                    }
                 
                    if (!$page) {
                        $start = 0;
                        $page = 1;
                    } else {
                        $start = ($page - 1) * $numeroRegistros;
                    }
                
                $total_pages = ceil($num_total_rows / $numeroRegistros);
                $sql.=" ORDER BY clientes.id_cliente DESC LIMIT $start, ".$numeroRegistros;
                $resultClientes2=mysql_query($sql);
                
                if($sesion_tipo_empresa=='COLEGIO'){
    // echo $sql;
}
                // echo $sql;
                echo '<ul>';
                               
                echo '<li><input  class="form-check-input" type="checkbox" onclick="marcar(this);" /> Marcar todos</li>';
                $contador=0;
                while($rowClientes=mysql_fetch_array($resultClientes2))
                {
                ?>
                  <li class="list-group-item">
                      <?php echo $contador ?>
                    <input  class="form-check-input idClienteListado2" type="checkbox"  name="idClienteListado"   value="<?php echo $rowClientes['id_cliente'] ?>" <?php if(in_array( $rowClientes['id_cliente'] , $idClientes)){ ?> checked <?php } ?> >
                    <?php
                    echo $rowClientes['nombre'].' '.$rowClientes['apellido'];
                    if ($sesion_tipo_empresa=='COLEGIO'){
                    echo ' _____  '.$rowClientes['nombres_estudiante'].' '.$rowClientes['apellidos_estudiante'].' '.$rowClientes['cedula_estudiante'];
                    }
                    
                    ?>
                  </li>
                  
                     
                <?php 
                $contador++;
                }   
                
                
                

echo '</ul>';

?>
<script>
function marcar(source) 
{
    console.log(source);
    
  checkboxes=document.getElementsByClassName('idClienteListado2'); //obtenemos todos los controles con clase idClienteListado
  for(i=0;i<checkboxes.length;i++) //recoremos todos los controles
  {
    if(checkboxes[i].type == "checkbox") //solo si es un checkbox entramos
    {
      checkboxes[i].checked=source.checked; //si es un checkbox le damos el valor del checkbox que lo llamó (Marcar/Desmarcar Todos)
    }
  }
}
</script>

<?php

echo '<ul class="pagination">';
// echo 'total=>'.$total_pages;
if ($total_pages > 1) {
  if ($page != 1) {
      echo '<li class="page-item"><a class="page-link" onClick="listarClientes('.($page-1).')" ><span aria-hidden="true">&laquo;</span></a></li>';
  }

  for ($i=1;$i<=$total_pages;$i++) {
      if ($page == $i) {
          echo '<li class="page-item active"><a class="page-link" href="#">'.$page.'</a></li>';
      } else {
          echo '<li class="page-item"><a class="page-link" onClick="listarClientes('.$i.')" >'.$i.'</a></li>';
      }
  }

  if ($page != $total_pages) {
      echo '<li class="page-item"><a class="page-link" onClick="listarClientes('.($page+1).')" ><span aria-hidden="true">&raquo;</span></a></li>';
  }
}
echo '</ul>';
echo '</nav>';
}
else{
?>

<h5>No se encontraron resultados.</h5>
<?php

}
?>
