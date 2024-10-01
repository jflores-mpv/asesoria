<?php 
	session_start();
	require_once "../conexion2.php";

   $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
 $sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
 ?>
<div class="row pt-3 border-bottom bg-light mt-3 border-top">
                        <div class="col-lg-4 ">
			                <h6 >Detalle</h6>
			            </div>
			            <div class="col-lg-4">
			                 <h6 >Empresa</h6>
			            </div>
			            <div class="col-lg-2 ">
                                <h6>Editar</h6>
                        </div>
			            <div class="col-lg-2 ">
                                <h6>Eliminar</h6>	
                        </div>
    </div>
			<?php 

			{ $sql="SELECT `id_centro_costo`, `detalle`, `id_empresa` FROM `centro_costo_empresa` WHERE id_empresa=$sesion_id_empresa" ; }

				$result=mysqli_query($conexion,$sql);

				
				while($ver=mysqli_fetch_array($result)){ 
			 ?>
			            
			       <div class="row pt-3 border-bottom">     
			             <div class="col-lg-4 ">
			                <h6 ><?php echo $ver['detalle'] ?></h6>
			            </div>
			            <div class="col-lg-4 ">
			                 <h6 ><?php echo $ver['id_empresa'] ?></h6>
			            </div>
			            <div class="col-lg-2 ">
			                <a onclick="nuevo_centro_costo('<?php echo $ver['id_centro_costo'] ?>')" ><div class="my-icon3 "><i class="fa fa-edit mr-3 "></i></div></a>
			            </div>
			            <div class="col-lg-2">
			                 <a onclick="confirmar_eliminar_centroCosto('<?php echo $ver['id_centro_costo'] ?>')" ><div class="my-icon3 "><i class="fa fa-trash mr-3 "></i></div></a>
			            </div>
			        </div>
               
			<?php 	}			 ?>

