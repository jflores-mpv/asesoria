<?php 
// include "../conexion.php";

// error_reporting(E_ALL);
// ini_set('display_errors', 1);
    session_start();
include('../conexion2.php');
require_once('../sql/encriptar.php');

    define('NUM_ITEMS', 10);
    $criterio_mostrar = $_GET['criterio_mostrar'];
    // echo "REPORTE EMPRESAS";
    $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
// echo "LISTADO";
    $txtNombre= $_GET['txtNombre'];
  $dominio = $_SERVER['SERVER_NAME'];
    $fechaInicio = $_GET['fechaDesde'];
    $fechaFin = $_GET['fechaHasta'];
    $txtValor= $_GET['txtValor'];
    $txtRUC = $_GET['txtRUC'];
    $txtNombreComercial = $_GET['txtNombreComercial'];
    $txtCodigoIngreso=$_GET['txtCodigoIngreso'];
    $txtLimite=$_GET['txtLimite'];


    $sql2 ="SELECT
                         empresa.`id_empresa` AS empresa_id_empresa,
                         empresa.`nombre` AS empresa_nombre,
                         empresa.`codigo_empresa` AS empresa_codigo_empresa,
                         empresa.`razonSocial` AS razonSocial,
                         empresa.`ruc` AS empresa_ruc,
                         empresa.`telefono1` AS empresa_telefono1,
                         empresa.`telefono2` AS empresa_telefono2,
                         empresa.`direccion` AS empresa_direccion,
                         empresa.`autorizacion_sri` AS empresa_autorizacion_sri,
                         empresa.`imagen` AS empresa_imagen,
                         empresa.`fecha_inicio` AS empresa_fecha_inicio,
                         empresa.`estado` AS empresa_estado,
                         empresa.`id_tipo_empresa` AS empresa_id_tipo_empresa,
                         empresa.`limiteFacturas` AS empresa_limite,
                         empresa.`URL` AS empresa_url,
                         periodo_contable.`id_periodo_contable` AS periodo_contable_id_periodo_contable,
                         periodo_contable.`estado` AS periodo_contable_estado,
                         periodo_contable.`id_empresa` AS periodo_contable_id_empresa,
                         id_renovacion AS id_renovacion,
                     
                         renovaciones.fecha_renovacion as renovaciones_fecha_renovacion    
                         
                    FROM
                        `empresa` empresa 
                        
                        INNER JOIN `periodo_contable` periodo_contable ON empresa.`id_empresa` = periodo_contable.`id_empresa`
                        LEFT JOIN `renovaciones` renovaciones ON renovaciones.`id_empresa` = empresa.`id_empresa`
                        where  fecha_inicio >= '".$fechaInicio."' AND fecha_inicio <= '".$fechaFin."' ";
                        
                        // $sql2 .= "AND empresa.`url` LIKE '%" . str_replace("www.", "", $dominio) . "%'" ;
                        
                        if($txtNombre!=''||$txtRUC!=''||$txtCodigoIngreso!=''|| $txtNombreComercial!='' || $txtLimite!=''){
                            $sql2.= ' and  ';
                        }
            

                    	if($txtNombre!=''){
                    		$sql2.= " `razonSocial` like '%".$txtNombre."%' "; 
                    	}
                    	
                    	if($txtNombreComercial!=''){
                    		$sql2.= " `nombre` like '%".$txtNombreComercial."%' "; 
                    	}
                    	
                    	if($txtRUC!=''){
                           		$sql2.= " `ruc` like '%".$txtRUC."%' "; 
                    	}
                    	
                    	if($txtLimite!=''){
                    		$sql2.= " `limiteFacturas`='".$txtLimite."' "; 
                    	}
                    	
                    	if($txtCodigoIngreso!=''){
                    		$sql2.= " `codigo_empresa` like '%".$txtCodigoIngreso."%' "; 
                    	}
                    	
                        $sql2.= "ORDER BY empresa.id_empresa DESC LIMIT $criterio_mostrar" ;
     
// echo $sql2;
   
    $result=mysqli_query($conexion,$sql2);
    
?>
<link rel="stylesheet" type="text/css" href="librerias/font-awesome-4.7.0/css/font-awesome.css">
    <div class="table-responsive">
  <table class="table table-striped table-hover ">
    <thead>

      <tr class="bg-light">
      
        <th class="border p-3">Estado</th>
        <th class="border p-3">L&iacute;mite</th>
       
        <th class="border p-3">OK</th>
        <th class="border p-3 text-left">Cont</th>
    <?php        
            $dominio = $_SERVER['SERVER_NAME'];
            
            if( $dominio=='econtweb.com' or $dominio=='www.econtweb.ec' or $dominio=='contricapsas.com' or $dominio=='www.contricapsas.ec'  ){
            
    ?>        
   <th class="border p-3 text-left">Tipo Empresa</th>
            
        <?php    }   ?>   
     
        
        <th class="border p-3 text-left">ID Empresa</th>
        <th class="border p-3 text-left">RUC</th>
        <th colspan="3" class="border p-3 text-left">Empresa Nombre</th>
        <th colspan="3" class="border p-3 text-left">Raz&oacute;n Social</th>
        <th class="border p-3">Fecha de inicio</th>
        <th class="border p-3">C&oacute;digo de empresa</th>
        <th class="border p-3">Eliminar</th>
        <th class="border p-3">Ingresos</th>
        <th class="border p-3">Ventas</th>
        <th class="border p-3">Renovar</th>
        <th class="border p-3">Fecha de renovaci&oacute;n</th>
        
        <!--<th class="border p-3">URL</th>-->
        
      </tr>
    </thead>
    <tbody>

<?php        
    $cont=0;
    $totales=0;
    $saldo = 0;
    
    $totalVentas = '0';
    while ($row = $result->fetch_assoc()) {
        $estado = $row['empresa_estado'];
        $cont++;
            ?>

                        <tr class="bg-warning">
                            <td>
                                <?php    
                                   
                                    if($estado == 'Activo'){
                                        ?><a href="javascript: suspenderEmpresa(<?=$row['empresa_id_empresa']?>, '12');" title="Inactivar Usuario" class="text-decoration-none p-auto"><i class="fa fa-thumbs-up " aria-hidden="true"></i></a><?php
                                    }
                                    if($estado == 'Inactivo'){
                                        ?><a href="javascript: suspenderEmpresa(<?=$row['empresa_id_empresa']?>, '13');" title="Activar Usuario" class="text-decoration-none" ><i class="fa fa-thumbs-down text-danger" aria-hidden="true"></i></a><?php
                                    }
                                ?>    
                            </td>
                            <td>
                                <input class="form-control" id="limite<?php echo $row['empresa_id_empresa'] ?>" name="limite<?php echo $row['empresa_id_empresa'] ?>" value="<?php echo $ruc=$row['empresa_limite'] ?>">
                            </td>
            

                            <td>
                                <a href="javascript: modificaLimite1(16,'<?php echo $row['empresa_id_empresa']; ?>')">                    
                                    <span class="fa fa-check" aria-hidden="true"></span></a>
                            </td>
                        
                            <td class="border p-3 text-left"><?php echo $cont ?></td>
                            
                            <?php        
                            
                            $dominio = $_SERVER['SERVER_NAME'];
                                    
                            if( $dominio=='econtweb.com' or $dominio=='www.econtweb.com' ){
                                    
                            ?>        
                                    <td>
                                        
                                        <div style="display: flex; align-items: center;">
                                            
                                                <select class="js-example-basic-single js-states form-control" style="width:150px" id="tipo<?php echo $row['empresa_id_empresa'] ?>"  
                                                name="tipo<?php echo $row['empresa_id_empresa'] ?>" >
                                                  
                                                <option data-subtext="todos" value="0">Todos</option>
                                                
                                                <?php
                                                
                                                $consultaTipo = "Select * From tipo_empresas WHERE id_tipo_empresa IN (104, 20); ";
                                               
                                                $resultadoTipo = mysqli_query($conexion, $consultaTipo);
                        
                                                while ($misdatosTipo = mysqli_fetch_assoc($resultadoTipo)) {
                                                ?>
                                                    <option data-subtext="<?php echo $misdatosTipo["id_tipo_empresa"]; ?>" value="<?php echo $misdatosTipo["id_tipo_empresa"]; ?>"  
                                                    <?php if($misdatosTipo["id_tipo_empresa"]==$row['empresa_id_tipo_empresa'] ){?> selected <?php } ?>>
                                                        <?php echo $misdatosTipo["tipo_empresa"]?>
                                                    </option>
                                                <?php } ?>
                                                
                                                </select>  
                                            
                                            <a style="margin-left:5px" href="javascript: modificatipo('<?php echo $row['empresa_id_empresa']; ?>')">                    
                                            <span class="fa fa-check" aria-hidden="true"></span></a>
                                            
                                        </div>
                                        
                                    </td>
                                                                
                            <?php   }   ?>    
                            
                            <?php        
                            
                            $dominio = $_SERVER['SERVER_NAME'];
                                    
                            if( $dominio=='contricapsas.com' or $dominio=='www.contricapsas.com' ){
                                    
                            ?>        
                                    <td>
                                        <div style="display: flex; align-items: center;">
                                              <select class="js-example-basic-single js-states form-control" style="width:150px" id="tipo<?php echo $row['empresa_id_empresa'] ?>"  
                                              name="tipo<?php echo $row['empresa_id_empresa'] ?>" >
                                                <option data-subtext="todos" value="0">Todos</option>
                                                
                                                <?php
                                                $consultaTipo = "Select * From tipo_empresas WHERE id_tipo_empresa IN (103, 20,105); ";
                                                // echo $consulta;
                                                $resultadoTipo = mysqli_query($conexion, $consultaTipo);
                        
                                                while ($misdatosTipo = mysqli_fetch_assoc($resultadoTipo)) {
                                                ?>
                                                
                                                    <option data-subtext="<?php echo $misdatosTipo["id_tipo_empresa"]; ?>" value="<?php echo $misdatosTipo["id_tipo_empresa"]; ?>"  <?php if($misdatosTipo["id_tipo_empresa"]==$row['empresa_id_tipo_empresa'] ){?> selected <?php } ?>>
                                                        <?php echo $misdatosTipo["tipo_empresa"]?>
                                                    </option>
                                                    
                                                <?php } ?>
                                                
                                            </select>  
                                            
                                            <a style="margin-left:5px" href="javascript: modificatipo('<?php echo $row['empresa_id_empresa']; ?>')">                    
                                            <span class="fa fa-check" aria-hidden="true"></span></a>
                                            
                                        </div>
                                    </td>
                                                                
                            <?php   }   ?>
                            
                            
                            
                            <td class="border p-3 text-left"><?php echo utf8_decode($row['empresa_id_empresa']) ?></td>
                            <td class="border p-3 text-left"><?php echo utf8_decode($row['empresa_ruc']) ?></td>
                            <td colspan="3" class="border p-3 text-left"><?php echo utf8_encode($nombre=$row['empresa_nombre']) ?></td>
                            <td colspan="3" class="border p-3 text-left"><?php echo utf8_encode($nombre=$row['razonSocial']) ?></td>
                            <td class="border p-3" ><input class="form-control" style="width:150px" id="fecha<?php echo $row['empresa_id_empresa'] ?>" name="fecha<?php echo $row['empresa_id_empresa'] ?>" value="<?php echo $ruc=$row['empresa_fecha_inicio'] ?>"></td>
                            <td class="border p-3"><?php echo $ruc=$row['empresa_codigo_empresa'] ?></td>

                            <!--<td>-->
                            <!--    <a class="btn" onclick="preguntarSiNo('<?php echo $row['empresa_id_empresa'] ?>','<?php echo $row['periodo_contable_id_periodo_contable'] ?>')">-->
                            <!--    <span class="fa fa-trash" aria-hidden="true"></span></a>-->
                            <!--</td>-->
                            <td></td>
                              <td></td>
                            <td>   <?php
                            
                                        $consultaE = "Select COUNT(*) as ventasE From ventas where id_empresa='".$row['empresa_id_empresa']."';";
                                        
                                        $resultadoE = mysqli_query($conexion, $consultaE);

                                        while ($misdatosE = mysqli_fetch_assoc($resultadoE)) {
                                            
                                            $total22= $misdatosE["ventasE"];
                                            
                                        } ?>
                                        
                                        <?php echo$total22 ?>
                                
                            </td>
                            
                            <td class="border p-3">
                            <?php
                             $hoy = date('Y-m-d');
                            $fecha_renovacion = trim($row['renovaciones_fecha_renovacion']);
                             $fecha_objeto = new DateTime($fecha_renovacion);
                            $fecha_objeto->modify('+1 year');
                             $fecha_calculada = $fecha_objeto->format('Y-m-d'); 
                            
                            if($fecha_renovacion!='' && $fecha_renovacion<$hoy ){
                                ?>
                                  <input type="date" tabindex="1" id="txtFechaRenovacion<?php echo $row['id_renovacion']?>" value="<?php echo $fecha_calculada?>" class="form-control " required="required" name="txtFechaRenovacion<?php echo $row['id_renovacion']?>"  placeholder="Fecha renovacion" autocomplete="off">
                             <button type="button" class="btn btn-danger" onclick="actualizar_renovacion(<?php  echo $row['id_renovacion'] ?>)">Renovar</button>
                                <?php
                            }else{
                                echo 'Activo';
                            }
                            ?>

                           
                               
                            </td>
                           <td class="border p-3"><?php echo $row['renovaciones_fecha_renovacion'] ?></td>
                                    <!--<td class="border p-3 text-left"><?php echo $row['empresa_url']?></td>-->
                        </tr>   
                       
                        
                
                                                                              <?php
   $consultaUsuarios = "SELECT usuarios.login, establecimientos.codigo AS estCodi, establecimientos.id AS estId, emision.codigo AS emicod, password
                    FROM usuarios
                    LEFT JOIN establecimientos ON establecimientos.id_empresa = usuarios.id_empresa
                    LEFT JOIN emision ON emision.id_est = establecimientos.id
                    WHERE usuarios.id_empresa = '".$row['empresa_id_empresa']."'";

$resultadoUsuarios = mysqli_query($conexion, $consultaUsuarios);

if (!$resultadoUsuarios) {
    die("Error en la consulta: " . mysqli_error($conexion));
}

while ($misdatosUsuarios = mysqli_fetch_assoc($resultadoUsuarios)) { ?>


                                   <tr>
                                               <td colspan="2">
                                                    <label>Usuarios</label>
                                                    <input class="form-control" id="login" name="login"  readonly value="<?php echo $misdatosUsuarios['login'] ?>">
                                                </td>
                                                <td colspan="3">
                                                    <label>Contrase&ntilde;a</label>
                                                    <?php     $user_hashed_password =  $desencriptar($misdatosUsuarios['password']);
                                                    // echo $encriptar($misdatosUsuarios['password']);
                                                    ?>
                                                  
                                                    <input class="form-control" id="password" name="password" readonly value="<?php echo $user_hashed_password ?>">
                                                </td>
                                                
                                                <td>
               
                                                    <label>Establecimiento</label>
                                                    <input class="form-control" id="password" name="password" readonly value="<?php echo $misdatosUsuarios['estId'] ?>">
                                                    <input class="form-control" id="password" name="password" readonly value="<?php echo $misdatosUsuarios['estCodi'] ?>">
                                                 
                                                 </td>
                                                
                                                 <td >
                                                    <label>Emisi&oacute;n</label>
                                                    <input class="form-control" id="password" name="password" readonly value="<?php echo $misdatosUsuarios['emicod'] ?>">
                                                 
                                                </td>
                                                

                                            </tr>
                                           <?php } ?>
                       
                                
                            
            <?php   }  ?>
            
            
                                    


            
            
                    </tbody>
                </table>
            </div>
            
            
        <?php      ?>

<script>



</script>
    
  
  
  
  