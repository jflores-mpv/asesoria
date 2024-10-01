<?php 
	session_start();
	require_once "../conexion2.php";

   $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
 $sesion_empresa_nombre = $_SESSION["sesion_empresa_nombre"];
 ?>
                <div class="row pt-3 border-bottom bg-light mt-3 border-top">
                        <div class="col-lg-12 border-bottom ">
			                <p>Listado Puntos de emisión:</p>
			            </div>
			            <!--<div class="col-lg-2 ">-->
			            <!--     <p >Emisi&oacute;n</p>-->
			            <!--</div>-->
			            <!--<div class="col-lg-1 ">-->
			            <!--     <p >Tipo</p>-->
			            <!--</div>-->
			            <!--<div class="col-lg-1 ">-->
			            <!--     <p >Emision</p>-->
			            <!--</div>-->
			            <!-- <div class="col-lg-2 ">-->
			            <!--     <p >Ambiente</p>-->
			            <!--</div>-->
			            <!--<div class="col-lg-1 ">-->
               <!--                 <p >Editar</p>			            -->
               <!--         </div>-->
			            <!--<div class="col-lg-1 ">-->
               <!--                 <p >Eliminar</p>			            -->
               <!--         </div>-->
                </div>
			<?php 

			{
						$sql="SELECT 
						establecimientos.id_empresa,
						id_est,
						emision.codigo,
						establecimientos.codigo,
						emision.id,
						emision.tipoFacturacion,
						emision.tipoEmision,
						
						emision.ambiente ,
						emision.numFac
						from emision,establecimientos where 
						establecimientos.id_empresa=$sesion_id_empresa and emision.id_est=establecimientos.id" ;
				}

				$result=mysqli_query($conexion,$sql);

				
				while($ver=mysqli_fetch_row($result)){ 

					$datos=$ver[3]."||".
						   $ver[2]."||".
						   $ver[4]."||".
						   $ver[5]."||".
						   $ver[6]."||".
						   $ver[7]."||".
						   $ver[8];
if ($ver[6] == 'E') {
     $variable="Electrónica";
} elseif ($ver[6]  == 'F') {
     $variable="Física";
} 

if ($ver[7] == 2) {
     $variable2="Producción";
} elseif ($ver[7] == 1) {
     $variable2="Pruebas";
} 
			 ?>
			 
		    <div class="row border-bottom py-2">
              <div class="d-flex w-100 justify-content-between">
                  <div class="fw-bold">Establecimiento: <?php echo $ver[3] ?> - Punto de Emisión: <?php echo $ver[2] ?></div>
                  <small> <a onclick="modificarEmision('<?php echo $ver[4] ?>')" ><div class="my-icon3 "><i class="fa fa-edit mr-3 "></i></div></a></small>
                  <small> <a onclick="eliminarEstablecimiento(9,'<?php echo $ver[4] ?>')" ><div class="my-icon3 "><i class="fa fa-trash mr-3 "></i></div></a></small>
                </div>
                <p class="mb-1">Emisión: <strong><?php echo $variable ?></strong> Ambiente : <strong><?php echo $variable2 ?></strong>  Secuencial:  <strong><?php echo $ver[8]  ?></strong></p>
            </div>
			 

			<?php	}	 ?>

