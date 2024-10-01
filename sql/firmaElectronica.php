<?php 
	//require_once('../ver_sesion.php');
	//Start session
	session_start();
	//Include database connection details
	require_once('../conexion.php');
	
$id_empresa = $_POST['txtIdEmpresa'];
$clave = $_POST['txtClave'];
$autorizacion = $_POST['switch-proveedor'];
$nombre=$_FILES['archivo']['name'];
$guardado=$_FILES['archivo']['tmp_name'];

if(!file_exists('archivos')){
	mkdir('archivos',0777,true);
	
	if(file_exists('archivos')){
		if(move_uploaded_file($guardado,$nombre)){
		    $sqla="update empresa set       	FElectronica='$nombre' , clave ='$clave'  ,
		      autorizacion_sri='".$autorizacion."'
		    WHERE id_empresa='".$id_empresa."'; ";
		  //  echo $sqla;
            $resultaa=mysql_query($sqla);
            if($resultaa){ 
            
                echo "</br>Archivo guardado con exito";
            }
            
            else{ echo "</br>Archivo no se pudo guardar";      }
			
		}else{
			echo "</br>Archivo no se pudo guardar";
		}
	}
	
}else{
	if(move_uploaded_file($guardado, $nombre)){
	        $sqla="update empresa set       	FElectronica='$nombre' , clave ='$clave' , 
	         autorizacion_sri='".$autorizacion."'
	        WHERE id_empresa='".$id_empresa."'; ";
	           // echo $sqla;
            $resultaa=mysql_query($sqla);
            if($resultaa){ 	echo "</br>Archivo guardado con exito";      }else{ echo "</br>Archivo no se pudo guardar";     }
	
	}else{
		echo "</br>Archivo no se pudo guardar";
	}
}

        

?>