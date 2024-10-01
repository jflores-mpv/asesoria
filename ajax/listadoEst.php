<?php 
	session_start();
	require_once "../conexion2.php";

   $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
 $sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
 ?>
<div class="row pt-3 border-bottom bg-light mt-3 border-top">
                        <div class="col-lg-3 ">
			                <h6 >Direcci&oacute;n</h6>
			            </div>
			            <div class="col-lg-2">
			                 <h6 >Est</h6>
			            </div>
		 
			            <div class="col-lg-2 ">
                                <h6>Editar</h6>
                        </div>
			            <div class="col-lg-2 ">
                                <h6>Eliminar</h6>	
                        </div>
    </div>
			<?php 

			{ $sql="SELECT id,direccion,codigo,if(establecimientos.centro_costo is null or establecimientos.centro_costo =0,'No tiene centro de costo selecionado',centro_costo_empresa.detalle ) as detalle from establecimientos LEFT JOIN centro_costo_empresa ON centro_costo_empresa.id_centro_costo = establecimientos.centro_costo where establecimientos.id_empresa=$sesion_id_empresa" ; }
// echo $sql;
				$result=mysqli_query($conexion,$sql);

				
				while($ver=mysqli_fetch_row($result)){ 

					$datos=$ver[0]."||".
						   $ver[1]."||".
						   $ver[2];
			 ?>
			            
			       <div class="row pt-3 border-bottom">     
			             <div class="col-lg-3 ">
			                <h6 ><?php echo utf8_encode($ver[1]) ?></h6>
			            </div>
			            <div class="col-lg-2">
			                 <h6 ><?php echo $ver[2] ?></h6>
			            </div>
			            
	  
			            <div class="col-lg-2 ">
			                <a onclick="modificarEstablecimiento('<?php echo $ver[0] ?>')" class="btn btn-success">Editar </a>
			            </div>
			            <div class="col-lg-2">
			                 <a onclick="eliminarEstablecimiento(8,'<?php echo $ver[0] ?>')" ><div class="my-icon3 "><i class="fa fa-trash mr-3 "></i></div></a>
			            </div>
			        </div>
               
			<?php 	}			 ?>

