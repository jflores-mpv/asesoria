<?php

//Start session
session_start();
$criterio_mostrar = $_GET['criterio_mostrar'];
define('NUM_ITEMS_BY_PAGE', 20);

include('../conexion2.php');

include "../clases/paginado_basico.php";
$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
$criterio_usu_per= $_GET['criterio_usu_per'];
$criterio_usu_tipo= $_GET['criterio_tipo'];
$criterio_ordenar_por = $_GET['criterio_ordenar_por'];
$criterio_orden = $_GET['criterio_orden'];

$result = $conexion->query("SELECT
COUNT(plan_cuentas.`id_plan_cuenta`) AS plan_cuentas_id_plan_cuenta
FROM
`plan_cuentas` plan_cuentas   where plan_cuentas.`id_empresa`='".$sesion_id_empresa."'");

$row = $result->fetch_assoc();
$num_total_rows = $row['plan_cuentas_id_plan_cuenta'];

        //PERMISOS AL MODULO PLAN CUENTAS
        $sesion_plan_cuentas_guardar = $_SESSION["sesion_plan_cuentas_guardar"];
        $sesion_plan_cuentas_modificar = $_SESSION["sesion_plan_cuentas_modificar"];
        $sesion_plan_cuentas_eliminar = $_SESSION["sesion_plan_cuentas_eliminar"];


        //funcion para dar tabulaciones a las cuentas
        function tab($no)
        {
            $tab='';
            for($x=1; $x<$no; $x++)
            $tab.="&nbsp;&nbsp;&nbsp;&nbsp;";
            return $tab;
        }
        
	



  if ($num_total_rows > 0) {
      
    $page = false;
//  echo "PAGE==>".$_GET["page"];
    //examino la pagina a mostrar y el inicio del registro a mostrar
    if (isset($_GET["page"])) {
        $page = $_GET["page"];
    }
 
    if (!$page) {
        $start = 0;
        $page = 1;
    } else {
        $start = ($page - 1) * NUM_ITEMS_BY_PAGE;
    }
    
    // echo "start===>".$start;
    //calculo el total de paginas
    $total_pages = ceil($num_total_rows / NUM_ITEMS_BY_PAGE);
 

    $sql =" SELECT
    plan_cuentas.`id_plan_cuenta` AS plan_cuentas_id_plan_cuenta,
    plan_cuentas.`codigo` AS plan_cuentas_codigo,
    plan_cuentas.`nombre` AS plan_cuentas_nombre,
    plan_cuentas.`clasificacion` AS plan_cuentas_clasificacion,
    plan_cuentas.`tipo` AS plan_cuentas_tipo,
    plan_cuentas.`categoria` AS plan_cuentas_categoria,
    plan_cuentas.`nivel` AS plan_cuentas_nivel,
    plan_cuentas.`total` AS plan_cuentas_total,
    plan_cuentas.`id_empresa` AS plan_cuentas_id_empresa,
    plan_cuentas.`cuenta_banco` AS plan_cuentas_cuenta_banco,
    plan_cuentas.`borrar` AS plan_cuentas_cuenta_borrar,     
    empresa.`id_empresa` AS empresa_id_empresa,
    empresa.`ruc` AS empresa_ruc,
    empresa.`nombre` AS empresa_nombre
FROM
    `empresa` empresa 
    
    INNER JOIN `plan_cuentas` plan_cuentas ON empresa.`id_empresa` = plan_cuentas.`id_empresa`  
    where empresa.`id_empresa`='".$sesion_id_empresa."' ";


   	if ($criterio_usu_per!=''){
        $sql .= " AND CONCAT(plan_cuentas.`nombre` like '%".$criterio_usu_per."%', plan_cuentas.`codigo` like '".$criterio_usu_per."%')  "; 
    }
     
    // if ($criterio_ordenar_por!=''){
    //     $sql .= sprintf(" order by  %s %s ", fn_filtro($criterio_ordenar_por), fn_filtro($criterio_orden));
    // }
    
    	
	if ($nivel!=0){
		$sql.= "and plan_cuentas.`nivel` <= ".$nivel." "; 
	}
	
   

     $sql.= " ORDER BY  plan_cuentas.`codigo`  LIMIT ".$start." , ".NUM_ITEMS_BY_PAGE ;
     
    // echo $sql;
    
    $result=mysqli_query($conexion,$sql);

    
?>

    <table id="grilla" class="table table-hover table-bordered table-striped bg-white"  >
    <thead >
    <tr>
            <th><strong>Ide</strong></th>
            <th><strong>C&oacute;digo</strong></th>
            <th><strong>Nombre de la cuenta</strong></th>            
            <th><strong>Cuenta de banco</strong></th>
           <th><a href="javascript: nuevoPlanCuentas('<?php echo $sesion_plan_cuentas_guardar; ?>');" title="Agregar nueva cuenta"><img alt="" src="images/add.png"></a></th>
        </tr>
    </thead>


    <tbody>
    
<?php    
    if ($result->num_rows > 0) {
        
        while ($row = $result->fetch_assoc()) {
            ?>
             <tr class="odd" id="tr_<?=$row['plan_cuentas_id_plan_cuenta']?>">
            <td><?=$contador?></td>
            <td><?=$row['plan_cuentas_codigo']?></td>
            <td>
            <?php
            $nivel = $row['plan_cuentas_nivel'];
            echo tab($nivel).(utf8_encode($row['plan_cuentas_nombre']));
            ?>
            </td>
            <td><?=$row['plan_cuentas_cuenta_banco']?></td>
            
            <td><a href="javascript: modificarPlanCuentas(<?=$row['plan_cuentas_id_plan_cuenta']?>, '<?php echo $sesion_plan_cuentas_modificar; ?>');" title="Editar cuenta">
                <i class="fa fa-pencil-square-o fa-1x"></i></a></td>
            <?php 
              if ($row['plan_cuentas_cuenta_borrar']==1){
                ?>
                <td><a href="javascript: eliminarPlanCuentas(<?=$row['plan_cuentas_id_plan_cuenta']?>,5, '<?php echo $sesion_plan_cuentas_eliminar; ?>');" title="Eliminar cuenta">
                    <i class="fa fa-trash-o fa-1x"></i></a></td>
              <?php }
            else{ ?>
                <td></td>
                <?php
            } ?>
        </tr>
            <?php
        }
        echo '</tbody>';
         echo '</table>';
    }
 
 
     echo '<nav>';
echo '<ul class="pagination">';
 
if ($total_pages > 1) {
    if ($page != 1) {
        echo '<li class="page-item" data-paginaActual="'.($page-1).'"><a class="page-link" onClick="fn_buscar('.($page-1).')" ><span aria-hidden="true">&laquo;</span></a></li>';
    }
 
    $half = floor(15 / 2); // Mitad de los botones que deseas mostrar
    $start = max(1, $page - $half);
    $end = min($total_pages, $start + 15 - 1);

    if ($end - $start < 15) {
        $start = max(1, $end - 15 + 1);
    }

    for ($i = $start; $i <= $end; $i++) {
        if ($page == $i) {
            echo '<li class="page-item active" data-paginaActual="'.$i.'"><a class="page-link" href="#">'.$i.'</a></li>';
        } else {
            echo '<li class="page-item" data-paginaActual="'.$i.'"><a class="page-link" onClick="fn_buscar('.$i.')" >'.$i.'</a></li>';
        }
    }

    if ($page != $total_pages) {
        echo '<li class="page-item" data-paginaActual="'.($page+1).'"><a class="page-link" onClick="fn_buscar('.($page+1).')" ><span aria-hidden="true">&raquo;</span></a></li>';
    }
}
echo '</ul>';
echo '</nav>';

   
}
     
?>


