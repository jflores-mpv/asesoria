<?php
error_reporting(0);
require_once('../ver_sesion.php');

    //Start session
session_start();

    //Include database connection details
require_once('../conexion.php');

$sesion_id_empresa = $_SESSION["sesion_id_empresa"];
$sesion_id_usuario = $_SESSION["sesion_id_usuario"];
$accion = $_POST['txtAccion'];


$fechaRealizado = date('Y-m-d h:i:s').".000000";
$fechaRegistro = date('Y-m-d h:i:s');


if($accion == '1'){

    $hoy= date('Y-m-d h:i:s');
    $newDate = date("Y/m/d", strtotime($hoy));
    
    $consulta5="Select Mensaje,name,estado,notificaciones.id,leads.id as idLead From notificaciones,leads where 
    notificaciones.id_usuario='".$sesion_id_usuario."' and leads.id=notificaciones.id_lead LIMIT 10; " ;
    $result=mysql_query($consulta5);
     //   echo $consulta5
    $cont=0;
             while($row=mysql_fetch_array($result))//permite ir de fila en fila de la tabla
             {
                $estado = $row["estado"];
                if ($estado == '0'){
                    $bg='bg-warning';
                }else{
                    $bg='';
                }
                echo "<a  onClick='estadoNotificacion({$row["id"]},1,2,{$row["idLead"]})' class='{$bg}' style='cursor:pointer'>{$row["Mensaje"]}{$row["name"]}</a>
                
                <input id='a$cont' type='hidden' value='{$row["Mensaje"]}{$row["name"]}'>
                ";
                $cont++;
            }

        }




        if($accion == "2"){

            $txtid=$_POST['id'];
            $interaccion= $_POST['num'];

            $sqlp="update notificaciones set estado= '".$interaccion."'  where id='".$txtid."'; ";

            $result = mysql_query($sqlp) or die(mysql_error());
            if($result){
                echo "1";
            }else{
                echo "2";
            }

        }

        if($accion == "3"){

            $txtid=$_POST['id'];
            $hoy= date('Y-m-d h:i:s');
            $newDate = date("Y/m/d", strtotime($hoy));

            $consulta5="SELECT id_usuario FROM leads where id='".$txtid."' ";
            $result=mysql_query($consulta5);
            while($row=mysql_fetch_array($result))
            {
                $sesion_id_usuario = $row['id_usuario'];
            }


            $sql3 = "insert into notificaciones( Mensaje,      estado,    autor , fecha,              id_usuario ,id_lead ) 
            values ('Presupuesto aceptado por:',   '0', '$sesion_id_usuario', '".$newDate."','".$sesion_id_usuario."','".$txtid."' ); ";
            $result3 = mysql_query($sql3) or die(mysql_error());

            if ($result3){
               echo "1";
           }else{
               echo "3";
           }  

       }

       if($accion == "4"){


        $consulta5="SELECT count(id) as notificaciones FROM notificaciones where id_usuario='".$sesion_id_usuario."' and estado='0' ";
        $result=mysql_query($consulta5);
        while($row=mysql_fetch_array($result))
        {
            $notificaciones= $row['notificaciones'];
            echo $notificaciones;
        }


    }

    ?>