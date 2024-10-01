<?php 

	//require_once('../ver_sesion.php');
	//Start session
	session_start();
	//Include database connection details
	require_once('../conexion.php');

$id_empresa = $_POST['txtIdEmpresa'];
$nombre=$_FILES['archivo']['name'];
$guardado=$_FILES['archivo']['tmp_name'];

if(!file_exists('archivos')){
	mkdir('archivos',0777,true);
	
	if(file_exists('archivos')){
		if(move_uploaded_file($guardado, 'archivos/'.$nombre)){
		        $sqla="update empresa set       imagen='$nombre'       WHERE id_empresa='".$id_empresa."'; ";
                $resultaa=mysql_query($sqla);
                if ($resultaa){    echo "1";     }else{         echo "2";     }

		}else{
		    	echo "Archivo no se pudo guardar 2";
		}
	}
	
}else{
	if(move_uploaded_file($guardado, 'archivos/'.$nombre)){
	    
	            $sqla="update empresa set  imagen='$nombre'  WHERE id_empresa='".$id_empresa."'; ";
	            $resultaa=mysql_query($sqla);
                if ($resultaa){     echo "<div style='color:green'>Archivo guardado con exito</div>";    }else{          echo "<div style='color:red'>Archivo no se pudo guardar</div>";   }
                
		      
	}else{
		        echo "<div style='color:red'>Archivo no se pudo guardar</div>";
	}
}

       
   

?>