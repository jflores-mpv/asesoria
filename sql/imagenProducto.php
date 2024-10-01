<?php

	//require_once('../ver_sesion.php');
	//Start session
	session_start();
	//Include database connection details
	require_once('../conexion.php');
	$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
$idProducto = $_POST['idProducto'];

$nombre=$_FILES['file']['name'];
$guardado=$_FILES['file']['tmp_name'];


if(!file_exists('../images/empresas/'.$sesion_id_empresa.'/'.$idProducto)){
	mkdir('../images/empresas/'.$sesion_id_empresa.'/'.$idProducto,0777,true);
	if(file_exists('../images/empresas/'.$sesion_id_empresa.'/'.$idProducto)){
		if(move_uploaded_file($guardado, '../images/empresas/'.$sesion_id_empresa.'/'.$idProducto.'/'.$nombre)){
		        $sqla="update productos set       img='".$nombre."'       WHERE codigo='".$idProducto."' AND id_empresa=$sesion_id_empresa; ";
		        $resultaa=mysql_query($sqla);
                if ($resultaa){    echo "Imagen del producto  Actualizada.";     }
                else{         echo "Producto no se puede Actualizar";     }

		}else{
		    	echo "3";
		}
	}
}else{
	if(move_uploaded_file($guardado, '../images/empresas/'.$sesion_id_empresa.'/'.$idProducto.'/'.$nombre)){
	    
	           // $sqla="update productos set       img='".$nombre."'       WHERE id_producto='".$idProducto."'; ";
	             $sqla="update productos set       img='".$nombre."'       WHERE codigo='".$idProducto."' AND id_empresa=$sesion_id_empresa; ";
	            $resultaa=mysql_query($sqla);
                if ($resultaa){    echo "Producto Actualizado";     
                    
                }else{         echo "Producto no se puede Actualizar";     }
	}else{
		        echo "7";
	}
} ?>