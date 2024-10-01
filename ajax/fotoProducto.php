<?php
	require_once('../ver_sesion.php');

	//Start session
	session_start();
		
	//Include database connection details
	        require_once('../conexion.php');
	        $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
            $sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];
        
        $emision_ambiente= $_SESSION['emision_ambiente'] ;
        $emision_tipoFacturacion=$_SESSION['emision_tipoFacturacion'];
        $emision_tipoEmision=$_SESSION['emision_tipoEmision'] ;
        $idProducto = $_POST['id'];


?>

<html lang="es-ES">
    
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Nuevo Producto</title>
</head>
<body> 
    
    <div class="modal-header">
        <h3>Imagen</h3>
    </div>
        
<div class="modal-body">
     <?php
        $sql="SELECT img as imagen
    FROM
          `productos` productos WHERE codigo='".$idProducto."' AND id_empresa=$sesion_id_empresa;";


        $result=mysql_query($sql);
        while($row=mysql_fetch_array($result)) {     ?>    
    
    
 <form method="post" action="#" enctype="multipart/form-data">
    <div class="card" style="width: 18rem;">
        <img class="card-img-top" src="images/empresas/<?php echo $sesion_id_empresa."/".$idProducto."/".$row['imagen']; ?>">
        <div class="card-body">
            <div class="form-group">
                <label for="image">Nueva imagen</label>
                <input type="file" class="form-control-file m-1" name="image" id="image">
            </div>
            <input type="button" class="btn btn-primary upload m-2" value="Subir" onClick="imagen('<?php echo $idProducto ?>')">
        </div>
    </div>
</form>  

<?php } ?>


</div>
        

    <div class="modal-footer mt-3">
            <button type="button" class="btn btn-default" onClick="fn_cerrar()">Cerrar</button>
    </div>
   
</body>	
         
       
            
</html>