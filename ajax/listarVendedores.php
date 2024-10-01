<?php

session_start();

include('../conexion.php');
include "../clases/paginado_basico.php";
$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
$criterio_usu_per= trim($_GET['criterio_usu_per']);
$criterio_usu_tipo= $_GET['criterio_tipo'];
$criterio_mostrar= $_GET['criterio_mostrar'];


    // $paging = new PHPPaging;
$sql = "SELECT `id_vendedor`, `codigo`, `nombre`, `apellidos`, `direccion`, `telefono`, `comision`, `id_empresa`, `caracter_identificacion`, `cedula`, `estado`,email FROM `vendedores` WHERE `id_empresa`='".$sesion_id_empresa."' ";

if($criterio_usu_per!=''){
    $sql .= " AND CONCAT(nombre, ' ',apellidos) like '%".$criterio_usu_per."%'"; 
}
//  echo $sql;
$result = mysql_query($sql) ;
$num_total_rows = mysql_num_rows($result);



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
		$start = ($page - 1) * $criterio_mostrar;
	}

	$total_pages = ceil($num_total_rows / $criterio_mostrar);

	$sql.= "order by vendedores.`id_vendedor` ASC  LIMIT ".$start." , ".$criterio_mostrar ;
   
	
	$result2=mysql_query($sql);

	echo '<nav>';
	echo '<ul class="pagination">';
	
	if ($total_pages > 1) {
		if ($page != 1) {
			echo '<li class="page-item"><a class="page-link" onClick="listarVendedores('.($page-1).')" ><span aria-hidden="true">&laquo;</span></a></li>';
		}
		
		for ($i=1;$i<=$total_pages;$i++) {
			if ($page == $i) {
				echo '<li class="page-item active"><a class="page-link" href="#">'.$page.'</a></li>';
			} else {
				echo '<li class="page-item"><a class="page-link" onClick="listarVendedores('.$i.')" >'.$i.'</a></li>';
			}
		}
		
		if ($page != $total_pages) {
			echo '<li class="page-item"><a class="page-link" onClick="listarVendedores('.($page+1).')" ><span aria-hidden="true">&raquo;</span></a></li>';
		}
	}
	echo '</ul>';
	echo '</nav>';
	
	?>

	<table id="grilla" class="table table-hover table-bordered table-striped bg-white"  >
		<thead >
			<tr>
				<th><strong>Nombres</strong></th>
				<th><strong>Identificaci&oacute;n</strong></th>
				<th><strong>Email</strong></th>
				<th><strong>Tel&eacute;fono</strong></th>
				<th><strong>Direcci&oacute;n</strong></th>
				<th><strong>Estado</strong></th>
				<th><strong>Modificar</strong></th>
				<th><strong>Eliminar</strong></th>
			</tr>
		</thead>


		<tbody>
			
			<?php    
			if ($num_total_rows > 0) {
				
				while ($row = mysql_fetch_array($result2) ) {
					
					
					?>
					<tr id="tr_<?=$row['id_vendedor']?>"  class="bg-white">
					    	<td  ><?=$row['nombre'].' '.$row['apellidos']?></td>
						<td  ><?=$row['cedula']?></td>
						<td><?=$row['email']?></td>
						<td  ><?=$row['telefono']?></td>
						<td><?=$row['direccion']?></td>
					<?php

                    $estado = $row['estado'];
                    if($estado == 'Activo'){
                        ?><td><a href="javascript: suspenderVendedor(<?=$row['id_vendedor']?>,0);" title="Inactivar Vendedor" class="btn"><ion-icon name="thumbs-up-outline"   ></ion-icon></a></td><?php
                    }else{
                        ?><td><a href="javascript: suspenderVendedor(<?=$row['id_vendedor']?>, 1);" title="Activar Vendedor" class="btn"><ion-icon name="thumbs-down-outline"   ></ion-icon></a></td><?php
                    }

                 ?>
						<td><a href="javascript: nuevo_vendedor(<?=$row['id_vendedor']?>);" title="Modificar"><span type="button" class="btn  fa fa-edit"></a></td>
							<td><a href="javascript: preguntarSiNoVendedor(<?=$row['id_vendedor']?>);" title="Eliminar Producto"><span type="button" class="btn  fa fa-trash"></a></td> 
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
							echo '<li class="page-item"><a class="page-link" onClick="listarVendedores('.($page-1).')" ><span aria-hidden="true">&laquo;</span></a></li>';
						}
						
						for ($i=1;$i<=$total_pages;$i++) {
							if ($page == $i) {
								echo '<li class="page-item active"><a class="page-link" href="#">'.$page.'</a></li>';
							} else {
								echo '<li class="page-item"><a class="page-link" onClick="listarVendedores('.$i.')" >'.$i.'</a></li>';
							}
						}
						
						if ($page != $total_pages) {
							echo '<li class="page-item"><a class="page-link" onClick="listarVendedores('.($page+1).')" ><span aria-hidden="true">&raquo;</span></a></li>';
						}
					}
					echo '</ul>';
					echo '</nav>';
				}

				?>
