 
<?php   
    //Start session
    session_start();

    //Include database connection details
    require_once('../conexion.php');
    $permisosSession = json_decode($_SESSION["permisos"]);
    $permisos = $permisosSession->permisos;
    //var_dump(count($permisos));
    $id = $_POST['id'];
    
    $permisos_modulo = null;
    for($i = 0; $i < count($permisos); $i++){
        $permisos[$i] = json_decode($permisos[$i]);
      //  echo "///".$permisos[$i]->id_modulo. "///".$id;
        if($permisos[$i]->id_modulo == $id){
            $permisos_modulo = $permisos[$i];
            break;
        }
    }
    $data["permisos"] = ($permisos_modulo);
    echo json_encode($data);
?>                