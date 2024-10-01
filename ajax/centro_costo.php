<?php
	require_once('../ver_sesion.php');

	//Start session
	session_start();
		
	//Include database connection details
	require_once('../conexion.php');
	   $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
    $idCosto= $_GET['id'];
    $accion = (trim($idCosto)!='0')?'2':'1';
    $titulo = ($accion=='2')?'Modificar':'Nuevo';
    $detalle='';
    if( $idCosto!='0'){
        $sql="SELECT `id_centro_costo`, `detalle`, `id_empresa` FROM `centro_costo_empresa` WHERE id_empresa=$sesion_id_empresa AND id_centro_costo=$idCosto" ; 
        $result=mysql_query($sql);
        while($ver=mysql_fetch_array($result)){ 
            $detalle = $ver['detalle'];
        }
    }
   
?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    
    <title>Centro de Costos</title>
</head>

<body> 

        <form name="formulario_centro_costos" id="formulario_centro_costos" method="post" action="javascript:crud_centro_costo('<?php echo $accion ?>',1);" >
        <input type="hidden"  id="idCentroCosto"  name="idCentroCosto" value="<?php echo $idCosto ?>" /> 

       <div class="modal-body">
        <div class="row">
            <div class="col-lg-12 text-center">
                <H1><?php echo $titulo ?> Centro de Costos</H1>
            </div>
        </div>
        
        <div class="row">
       
            <div class="col-lg-6 my-3">
                <label for="txtDetalle" class="form-check-label">Detalle</label>
				<input type="text" tabindex="6" maxlength="100" required="required" id="txtDetalle" class="form-control fs-4 p-1 required" name="txtDetalle"  value="<?php echo $detalle ?>"   /> 
            </div>


        </div>
        

          
            <div class="modal-footer">
                <button type="button" class="btn " onClick="fn_cerrar()">Cerrar</button>
        	    <input  type="submit" value="Guardar" id="submit" tabindex="18" class="btn btn-success " name="btnIngresar"  />
            
            </div>

   
 </form>

</body>	
    
</html>