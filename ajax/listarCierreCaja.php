<?php
error_reporting(0);
        //Start session
session_start();
define('NUM_ITEMS_BY_PAGE', 10);
include('../conexion2.php');
include('../conexion.php');
include "../clases/paginado_basico.php";
$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
$criterio_usu_per= trim($_GET['criterio_usu_per']);
$criterio_usu_tipo= $_GET['criterio_tipo'];
  date_default_timezone_set('America/Guayaquil');
$sesion_id_usuario = $_SESSION["sesion_id_usuario"];

$fecha_desde=$_GET['fecha_desde'];
$fecha_hasta=$_GET['fecha_hasta'];
    // $paging = new PHPPaging;
$result = $conexion->query('
	SELECT COUNT(`id`) as id FROM `cierre_caja_encabezado` WHERE id_usuario='.$sesion_id_usuario.'  ');
$row = $result->fetch_assoc();
$num_total_rows = $row['id'];


?>
<div style="width:48%; float:left" >
    <div class="col-lg-3" style="display: inline-block;">
         Fecha Cierre:
        </div>
    <div class="col-lg-8" style="display: inline-block;">
                 
                  <input class="form-control" name="fecha_cierre" type="text" id="fecha_cierre" placeholder="fecha cierre" onclick="displayCalendar(fecha_cierre,'yyyy-mm-dd',this)" value="<?php echo date('Y-m-d') ?>">
                  </div>
	<table id="grilla" class="table table-hover table-bordered table-striped bg-white"  >
		<thead >
			<tr>
				<th><strong>Forma pago</strong></th>
				<th><strong>Detalle</strong></th>
				<th><strong>Valor</strong></th>
				<th><strong>Guardar</strong></th>
			</tr>
		</thead>


		<tbody>
			<?php
			$query = "SELECT `id_forma_pago`, `nombre`, `id_plan_cuenta`, `id_empresa`, `id_tipo_movimiento`, `diario`, `ingreso`, `egreso`, `pagar`, `tipo` FROM `formas_pago` WHERE `id_empresa`=$sesion_id_empresa and id_tipo_movimiento in(1,2,4,16) ";
			$resultq = mysql_query($query) or die(mysql_error());
			$numero_filas = mysql_num_rows($resultq); 
			if($resultq) 
			{
				if($numero_filas == 0)
				{
					echo "<div class='alert alert-danger'> No hay formas de pago registradas en el sistema </div>";
				}
				else
				{
					$a=0;
					while($rowPago = mysql_fetch_array($resultq)){
						?>
						<tr  class="bg-white">
							<td  ><input type="text" id="formaPago<?php echo $a ?>"  name="formaPago<?php echo $a ?>" class="form-control"  value="<?php echo $rowPago['nombre'] ?>" >
								<input type="hidden" id="idFormaPago<?php echo $a ?>"  name="idFormaPago<?php echo $a ?>" class="form-control"  value="<?php echo $rowPago['id_forma_pago'] ?>">
								
								
							</td>
							<td  ><input type="text" id="detalle<?php echo $a ?>"  name="detalle<?php echo $a ?>" class="form-control"  ></td>
							<td  ><input type="text" id="valor<?php echo $a ?>" name="valor<?php echo $a ?>" class="form-control"></td>
							

							<td><a href="javascript: guardarCierreCaja(<?php echo $a ?>,1);" title="Modificar"><span type="button" class="btn  fa fa-edit"></a></td>
								
							</tr>
							<?php
							$a++;
						}
						?>
					</tbody>
				</table>
			</div>
			<?php
			
		}
		
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
		

		$sql ="SELECT 
		cierre_caja_encabezado.`id`, 
		cierre_caja_encabezado.`fecha`, 
		cierre_caja_encabezado.`hora`, 
		cierre_caja_encabezado.`id_usuario`, 
		cierre_caja_encabezado.`total`, 
		cierre_caja_encabezado.`caja`, 
		cierre_caja_detalle.detalle,
		SUM(cierre_caja_detalle.valor) as total,
		usuarios.login
		
		FROM `cierre_caja_encabezado` 
		INNER JOIN cierre_caja_detalle ON cierre_caja_detalle.id_cierre =cierre_caja_encabezado.id  
		INNER JOIN usuarios ON cierre_caja_encabezado.`id_usuario`=usuarios.`id_usuario`
		WHERE cierre_caja_encabezado.id_usuario='".$sesion_id_usuario."' ";
		
		
		if($fecha_desde !='' && $fecha_hasta!=''){
			$sql .= " and DATE_FORMAT(fecha, '%Y %m %d')>='".$fecha_desde."' and DATE_FORMAT(fecha, '%Y %m %d')<='".$fecha_hasta."'  ";
		}
		
		
		if ($criterio_usu_per!=''){
			$sql .= " and ( detalle like '".substr($criterio_usu_per, 0, 16)."%' )"; 
		}
		
		
		
		$sql.= " GROUP BY  cierre_caja_encabezado.`id` order by cierre_caja_encabezado.`id` ASC  LIMIT ".$start." , ".NUM_ITEMS_BY_PAGE ;
    //  echo $sql;
		
		$result=mysqli_query($conexion,$sql);
    // $row_cnt = mysqli_num_rows($conexion,$sql);
    // echo "row_cnt==>".$row_cnt;
		
		echo '<nav>';
		echo '<ul class="pagination">';
		
		if ($total_pages > 1) {
			if ($page != 1) {
				echo '<li class="page-item"><a class="page-link" onClick="listarCierreCaja('.($page-1).')" ><span aria-hidden="true">&laquo;</span></a></li>';
			}
			
			for ($i=1;$i<=$total_pages;$i++) {
				if ($page == $i) {
					echo '<li class="page-item active"><a class="page-link" href="#">'.$page.'</a></li>';
				} else {
					echo '<li class="page-item"><a class="page-link" onClick="listarCierreCaja('.$i.')" >'.$i.'</a></li>';
				}
			}
			
			if ($page != $total_pages) {
				echo '<li class="page-item"><a class="page-link" onClick="listarCierreCaja('.($page+1).')" ><span aria-hidden="true">&raquo;</span></a></li>';
			}
		}
		echo '</ul>';
		echo '</nav>';
		
		?>
		<div style="width:45%; float :right" >
			<table id="grilla" class="table table-hover table-bordered table-striped bg-white" >
				<thead >
					<tr>
						<th><strong>Fecha</strong></th>
						<th><strong>Total</strong></th>
						<th><strong>Realizado por</strong></th>
						<th><strong>PDF</strong></th>
					</tr>
				</thead>


				<tbody>
					
					<?php    
					if ($result->num_rows > 0) {
						
						while ($row = $result->fetch_assoc()) {
							
							
							?>
							<tr id="tr_<?=$row['id']?>"  class="bg-white">
								
								<td  ><?=$row['fecha']?></td>
								
								<td  ><?=$row['total']?></td>
								<td><?=$row['login']?></td>
								<td><input type="button" class="btn btn-success" value="PDF" onclick="pdfCierreCaja('<?php echo $row['id']?>','<?php echo $row['fecha']?>' )"></td>
								
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
							echo '<li class="page-item"><a class="page-link" onClick="listarCierreCaja('.($page-1).')" ><span aria-hidden="true">&laquo;</span></a></li>';
						}
						
						for ($i=1;$i<=$total_pages;$i++) {
							if ($page == $i) {
								echo '<li class="page-item active"><a class="page-link" href="#">'.$page.'</a></li>';
							} else {
								echo '<li class="page-item"><a class="page-link" onClick="listarCierreCaja('.$i.')" >'.$i.'</a></li>';
							}
						}
						
						if ($page != $total_pages) {
							echo '<li class="page-item"><a class="page-link" onClick="listarCierreCaja('.($page+1).')" ><span aria-hidden="true">&raquo;</span></a></li>';
						}
					}
					echo '</ul>';
					echo '</nav>';
				}
				
				?>
			</div>