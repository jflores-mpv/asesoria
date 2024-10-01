<?php 
// include "../conexion.php";
    session_start();
include('../conexion2.php');
  include "../clases/paginado_basico.php";
   $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
 $criterio_usu_per= $_GET['criterio_usu_per'];
// echo "sesion id empresa==>".$sesion_id_empresa."<==>";
$result = $conexion->query('



SELECT COUNT(id_usuario) as ventas_id_venta
     
     
     
FROM
`usuarios` usuarios WHERE usuarios.`id_empresa`='.$sesion_id_empresa.'  ' );
     
   
$row = $result->fetch_assoc();
$num_total_rows = $row['ventas_id_venta'];




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
 

    $sql ="SELECT
     usuarios.`id_usuario` AS usuarios_id_usuario,
     usuarios.`id_empleado` AS usuarios_id_empleado,
     usuarios.`id_empresa` AS usuarios_id_empresa,
     usuarios.`login` AS usuarios_login,
     usuarios.`password` AS usuarios_password,
     usuarios.`tipo` AS usuarios_tipo,
     usuarios.`estado` AS usuarios_estado,
     usuarios.`fecha_registro` AS usuarios_fecha_registro,
     usuarios.`permisos` AS usuarios_permisos,
     usuarios.`id_permiso_asiento_contable` AS usuarios_id_permiso_asiento_contable,
     usuarios.`id_permiso_plan_cuenta` AS usuarios_id_permiso_plan_cuenta,
     usuarios.`reportes_contables` AS usuarios_reportes_contables,
     
     establecimientos.`codigo` AS establecimiento_codigo,
     emision.`codigo` AS emision_codigo
     
FROM
    

     `usuarios` usuarios  
     
     
     LEFT JOIN `establecimientos` establecimientos ON establecimientos.`id` = usuarios.`id_est` 
     
     LEFT JOIN `emision` emision ON emision.`id` = usuarios.`id_punto` 
     
     where usuarios.`id_empresa`='".$sesion_id_empresa."' ";
      
     


     
     $sql.= "ORDER BY usuarios_id_usuario DESC LIMIT ".$start." , ".NUM_ITEMS_BY_PAGE ;

    
    $result=mysqli_query($conexion,$sql);
    

?>

 <table id="grilla" class="table table-condensed " cellspacing="10" cellpadding="10">
    <thead >
        <tr>
            <th><strong>Ide</strong></th>
            <th><strong>Login</strong></th>
            <th><strong>EST</strong></th>
            <th><strong>EMI</strong></th>                   
            <th><strong>Tipo</strong></th>
            <th><strong>Permisos</strong></th>
            <th><strong>Estado</strong></th>
            <th></th>
            <th><a onclick="javascript: listar_usuarios_empresa(1);" title="Actualizar" class="btn"><ion-icon name="refresh-outline"></ion-icon></a></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    
<?php    
    if ($result->num_rows > 0) {
        
        while ($row = $result->fetch_assoc()) {
            
            
            ?>
    <tr class="odd" id="tr_<?=$row['usuarios_id_usuario']?>">
            <td><?= $contador?></td>
            <td><a href="javascript: pdfInfoUsuario(<?=$row['usuarios_id_usuario']?>);" title="Informacion del Usuario" style="color: blue; text-decoration: underline"><?=$row['usuarios_login']?></a></td>
            <!--<td>-->
            <?php
            //   $nombre = $row['empleados_nombre'];
            //   $apellido = $row['empleados_apellido'];
            //   echo $nombre." ".$apellido;
               ?>
            <!--</td>-->
            <td><?=$row['establecimiento_codigo']?></td>
            <td><?=$row['emision_codigo']?></td>
            <td><?=$row['usuarios_permisos']?></td>
            <td><?=$row['usuarios_estado']?></td>

           <?php
            $cookie_tipo = $_COOKIE['tipo_cookie'];
            $sesion_tipo = $_SESSION["sesion_tipo"];
             if($sesion_tipo=="Administrador Empresa" || $sesion_tipo=="Super Administrador" || $sesion_tipo=="Administrador")
                 {

                    $estado = $row['usuarios_estado'];
                    if($estado == 'Activo'){
                        ?><td><a href="javascript: suspenderUsuario(<?=$row['usuarios_id_usuario']?>, 3, '<?php echo $row['usuarios_login'];?>');" title="Inactivar Usuario" class="btn"><ion-icon name="thumbs-up-outline"   ></ion-icon></a></td><?php
                    }
                    if($estado == 'Inactivo'){
                        ?><td><a href="javascript: suspenderUsuario(<?=$row['usuarios_id_usuario']?>, 4, '<?php echo $row['usuarios_login'];?>');" title="Activar Usuario" class="btn"><ion-icon name="thumbs-down-outline"   ></ion-icon></a></td><?php
                    }

                 }
             if($cookie_tipo=="Empleado" || $sesion_tipo=="Empleado" )
                 {
                    ?><td></td><?php
                 }
              ?>
            <td>
            <?php
            $cookie_tipo = $_COOKIE['tipo_cookie'];
            $sesion_tipo = $_SESSION["sesion_tipo"];
             if($sesion_tipo=="Administrador Empresa" || $sesion_tipo=="Super Administrador" || $sesion_tipo=="Administrador")
                 {
                 ?><a href="javascript: modificarUsuarioEmpresa(<?=$row['usuarios_id_usuario']?>);" title="Editar Usuario" class="btn"><ion-icon name="create-outline"   ></ion-icon><?php
                 }
             if($cookie_tipo=="Empleado" || $sesion_tipo=="Empleado")
                 {
                    ?><?php
                 }
              ?>
            </td>
            <td>
            <?php
            $cookie_tipo = $_COOKIE['tipo_cookie'];
            $sesion_tipo = $_SESSION["sesion_tipo"];
             if($sesion_tipo=="Administrador Empresa" || $sesion_tipo=="Super Administrador" || $sesion_tipo=="Administrador")
                 {
                 ?><a href="javascript: eliminarEmpleado(<?=$row['empleados_id_empleado']?>, 6);" title="Eliminar Usuario" class="btn"><ion-icon name="trash-outline"  ></ion-icon></a><?php
                 }
             if($cookie_tipo=="Empleado" || $sesion_tipo=="Empleado")
                 {
                    ?><?php
                 }
              ?>
            </td>
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
        echo '<li class="page-item"><a class="page-link" onClick="listar_usuarios_empresa('.($page-1).')" ><span aria-hidden="true">&laquo;</span></a></li>';
    }
 
    $half = floor(15 / 2); // Mitad de los botones que deseas mostrar
    $start = max(1, $page - $half);
    $end = min($total_pages, $start + 15 - 1);

    if ($end - $start < 15) {
        $start = max(1, $end - 15 + 1);
    }

    for ($i = $start; $i <= $end; $i++) {
        if ($page == $i) {
            echo '<li class="page-item active"><a class="page-link" href="#">'.$i.'</a></li>';
        } else {
            echo '<li class="page-item"><a class="page-link" onClick="listar_usuarios_empresa('.$i.')" >'.$i.'</a></li>';
        }
    }

    if ($page != $total_pages) {
        echo '<li class="page-item"><a class="page-link" onClick="listar_usuarios_empresa('.($page+1).')" ><span aria-hidden="true">&raquo;</span></a></li>';
    }
}
echo '</ul>';
echo '</nav>';

    // echo '<nav>';
    // echo '<ul class="pagination">';
 
    // if ($total_pages > 1) {
    //     if ($page != 1) {
    //         echo '<li class="page-item"><a class="page-link" onClick="listar_usuarios_empresa('.($page-1).')" ><span aria-hidden="true">&laquo;</span></a></li>';
    //     }
 
    //     for ($i=1;$i<=$total_pages;$i++) {
    //         if ($page == $i) {
    //             echo '<li class="page-item active"><a class="page-link" href="#">'.$page.'</a></li>';
    //         } else {
    //             echo '<li class="page-item"><a class="page-link" onClick="listar_usuarios_empresa('.$i.')" >'.$i.'</a></li>';
    //         }
    //     }
 
    //     if ($page != $total_pages) {
    //         echo '<li class="page-item"><a class="page-link" onClick="listar_usuarios_empresa('.($page+1).')" ><span aria-hidden="true">&raquo;</span></a></li>';
    //     }
    // }
    // echo '</ul>';
    // echo '</nav>';
}



?>

