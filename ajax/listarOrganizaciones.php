<?php 
	session_start();
	require_once "../conexion2.php";

   $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
 $sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
 ?>
<div class="row pt-3">
                        <div class="col-lg-4 border ">
			                <h6 >Nombre</h6>
			            </div>
			            <div class="col-lg-2 border">
			                 <h6 >Ciudad</h6>
			            </div>
			            <div class="col-lg-2 border">
			                 <h6 >Estado</h6>
			            </div>
			            <div class="col-lg-2 border">
                                <h6>Editar</h6>
                        </div>
			            <div class="col-lg-2 border">
                                <h6>Eliminar</h6>	
                        </div>
    
			<?php 

			{ $sql="SELECT id,nombre,estado,ciudad from organizacion where id_empresa=$sesion_id_empresa" ; }

				$result=mysqli_query($conexion,$sql);

				
				while($ver=mysqli_fetch_row($result)){ 

					$datos=$ver[0]."||".
						   $ver[1]."||".
						   $ver[2]."||".
						   $ver[3];
			 ?>
			             <div class="col-lg-4 border ">
			                <h6 ><?php echo $ver[1] ?></h6>
			            </div>
			            <div class="col-lg-4 border">
			                 <h6 ><?php echo $ver[3] ?></h6>
			            </div>
			            <div class="col-lg-2 border">
			                <button class="botonListado glyphicon glyphicon-pencil m-auto" data-bs-toggle="modal" data-bs-target="#modalEdicion" 
			                onclick="agregaform('<?php echo $datos ?>')" >
			                </button>
			            </div>
			            <div class="col-lg-2 border">
			                <button  class="botonListado glyphicon glyphicon-erase" onclick="preguntarSiNo('<?php echo $ver[0] ?>',2)"><i class="fas fa-trash-alt" ></i></button>
			            </div>
			        
               
			<?php 	}			 ?>
</div>
