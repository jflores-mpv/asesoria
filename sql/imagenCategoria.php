<?php

	//require_once('../ver_sesion.php');
	//Start session
	session_start();
	//Include database connection details
	require_once('../conexion.php');
	
$idProducto = $_POST['idProducto'];
$nombre=$_FILES['file']['name'];
$guardado=$_FILES['file']['tmp_name'];

echo "</br>".$idProducto;
echo "</br>".$nombre;
echo "</br>".$guardado;

if(!file_exists('../images')){
	mkdir('../images',0777,true);
	if(file_exists('../images')){
	    
		if(move_uploaded_file($guardado, '../images/'.$nombre)){
		        $sqla="update centro_costo set       img='".$nombre."'       WHERE id_centro_costo='".$idProducto."'; ";
		        $resultaa=mysql_query($sqla);
                if ($resultaa){    echo "Imagen ya existe, producto  Actualizado";     }
                else{         echo "Producto no se puede Actualizar";     }

		}else{
		    	echo "3";
		}
	}
}else{
	if(move_uploaded_file($guardado, '../images/'.$nombre)){
	    
	            $sqla="update centro_costo set       img='".$nombre."'       WHERE id_centro_costo='".$idProducto."'; ";
	            
	            $resultaa=mysql_query($sqla);
                if ($resultaa){    echo "Producto Actualizado";     
                    
                }else{         echo "Producto no se puede Actualizar";     }
	}else{
		        echo "7";
	}
} 

?>