<?php

session_start();
define('NUM_ITEMS_BY_PAGE', 10);
include('../conexion2.php');
include "../clases/paginado_basico.php";
$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
$criterio_usu_per= $_GET['criterio_usu_per'];
$criterio_usu_tipo= $_GET['criterio_tipo'];



    // $paging = new PHPPaging;
$result = $conexion->query('
	SELECT COUNT(Id) as ventas_id_transportista    
	FROM
	`transportista`
	
	WHERE transportista.`id_empresa`='.$sesion_id_empresa.'  ');
$row = $result->fetch_assoc();
$num_total_rows = $row['ventas_id_transportista'];


    // $sql = "SELECT `Id`, `Nombres`, `Direccion`, `Telefono`, `Cedula`, `Tipo`, `Placa`, `id_empresa` FROM `transportista` WHERE transportista.`id_empresa`='".$sesion_id_empresa."'  ";

    // if ($_GET['criterio_usu_per']!=''){
    //  $sql .= " and ( Nombres like '".substr($_GET['criterio_usu_per'], 0, 16)."%'  ||  Cedula like '".substr($_GET['criterio_usu_per'], 0, 16)."%'  ||  Direccion like '".substr($_GET['criterio_usu_per'], 0, 16)."%' ||  Placa like '".substr($_GET['criterio_usu_per'], 0, 16)."%'  )"; 
    // }

    // if (isset($_GET['criterio_ordenar_por'])){
    //      $sql .= sprintf(" order by %s %s", fn_filtro($_GET['criterio_ordenar_por']), fn_filtro($_GET['criterio_orden'])); }
    //     else{
    //      $sql .= " order by Id asc "; }

    // if ($_GET['criterio_mostrar']!=''){
    //  $sql .= " LIMIT ".substr($_GET['criterio_mostrar'], 0, 16)." "; 
    // }
    // echo $sql;

    // $sql.='  5';

    // if (isset($_GET['criterio_tipo'])){
    //  $sql .= " and productos.`tipos_compras` like '%".fn_filtro(substr($_GET['criterio_tipo'], 0, 16))."%' "; }  
    // if (isset($_GET['criterio_ordenar_por'])){
    //  $sql .= sprintf(" order by %s %s", fn_filtro($_GET['criterio_ordenar_por']), fn_filtro($_GET['criterio_orden'])); }
    // else{
    //  $sql .= " order by productos.`producto` asc "; }

    // $paging->agregarConsulta($sql); 
    // $paging->div('listarTransportistas');
    // $paging->modo('desarrollo'); 
    // if (isset($_GET['criterio_mostrar']))
    //  $paging->porPagina(fn_filtro((int)$_GET['criterio_mostrar']));
    // $paging->verPost(true);
    // $paging->mantenerVar("criterio_usu_per", "criterio_ordenar_por", "criterio_orden", "criterio_mostrar");
    // $paging->ejecutar();

    // ________________________________________________________


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
	

	$sql ="SELECT `Id`, `Nombres`, `Direccion`, `Telefono`, `Cedula`, `Tipo`, `Placa`, `id_empresa` FROM `transportista` WHERE transportista.`id_empresa`='".$sesion_id_empresa."' ";
	
	
	if ($criterio_usu_per!=''){
		$sql .= " and ( Nombres like '".substr($criterio_usu_per, 0, 16)."%'  ||  Cedula like '".substr($criterio_usu_per, 0, 16)."%'  ||  Direccion like '".substr($criterio_usu_per, 0, 16)."%' ||  Placa like '".substr($criterio_usu_per, 0, 16)."%'  ) "; 
	}
	
	
	
	$sql.= "order by transportista.`Id` ASC  LIMIT ".$start." , ".NUM_ITEMS_BY_PAGE ;
    // echo $sql;
	
	$result=mysqli_query($conexion,$sql);
    // $row_cnt = mysqli_num_rows($conexion,$sql);
    // echo "row_cnt==>".$row_cnt;
	
	echo '<nav>';
	echo '<ul class="pagination">';
	
	if ($total_pages > 1) {
		if ($page != 1) {
			echo '<li class="page-item"><a class="page-link" onClick="listarTransportistas('.($page-1).')" ><span aria-hidden="true">&laquo;</span></a></li>';
		}
		
		for ($i=1;$i<=$total_pages;$i++) {
			if ($page == $i) {
				echo '<li class="page-item active"><a class="page-link" href="#">'.$page.'</a></li>';
			} else {
				echo '<li class="page-item"><a class="page-link" onClick="listarTransportistas('.$i.')" >'.$i.'</a></li>';
			}
		}
		
		if ($page != $total_pages) {
			echo '<li class="page-item"><a class="page-link" onClick="listarTransportistas('.($page+1).')" ><span aria-hidden="true">&raquo;</span></a></li>';
		}
	}
	echo '</ul>';
	echo '</nav>';
	
	?>

	<table id="grilla" class="table table-hover table-bordered table-striped bg-white"  >
		<thead >
			<tr>
				<th><strong>#</strong></th>
				<th><strong>Nombres</strong></th>
				<th><strong>Direcci&oacute;n</strong></th>
				<th><strong>Tel&eacute;fono</strong></th>
				<th><strong>C&eacute;dula</strong></th>
				<th><strong>Tipo</strong></th>
				<th><strong>Placa</strong></th>
				<th><strong>Modificar</strong></th>
				<th><strong>Eliminar</strong></th>
			</tr>
		</thead>


		<tbody>
			
			<?php    
			if ($result->num_rows > 0) {
				
				while ($row = $result->fetch_assoc()) {
					
					
					?>
					<tr id="tr_<?=$row['Id']?>"  class="bg-white">
						<td  ><?php echo $contador;?></td>
						<td  ><?=$row['Nombres']?></td>
						<td><?=$row['Direccion']?></td>
						<td  ><?=$row['Telefono']?></td>
						<td><?=$row['Cedula']?></td>
						<td><?=$row['Tipo']?></td>
						<td><?=$row['Placa']?></td>

						<td><a href="javascript: nuevochofer(<?=$row['Id']?>);" title="Modificar"><span type="button" class="btn  fa fa-edit"></a></td>
							<td><a href="javascript: preguntarSiNoTransportista(<?=$row['Id']?>);" title="Eliminar Producto"><span type="button" class="btn  fa fa-trash"></a></td> 
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
							echo '<li class="page-item"><a class="page-link" onClick="listarTransportistas('.($page-1).')" ><span aria-hidden="true">&laquo;</span></a></li>';
						}
						
						for ($i=1;$i<=$total_pages;$i++) {
							if ($page == $i) {
								echo '<li class="page-item active"><a class="page-link" href="#">'.$page.'</a></li>';
							} else {
								echo '<li class="page-item"><a class="page-link" onClick="listarTransportistas('.$i.')" >'.$i.'</a></li>';
							}
						}
						
						if ($page != $total_pages) {
							echo '<li class="page-item"><a class="page-link" onClick="listarTransportistas('.($page+1).')" ><span aria-hidden="true">&raquo;</span></a></li>';
						}
					}
					echo '</ul>';
					echo '</nav>';
				}

				?>
