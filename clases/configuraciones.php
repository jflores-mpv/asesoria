<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of libroDiario
 *
 * @author Usuario
 */

//Include database connection details
require_once('conexion.php');

class configuraciones {
    

     public function nombreSistema(){
        try {
           $sql1="select nombre_sistema from configuraciones;";
            $result1=mysql_query($sql1);
            $nombre1 = "";
            while($row1=mysql_fetch_array($result1))//permite ir de fila en fila de la tabla
                {
                    $nombre1=utf8_decode($row1['nombre_sistema']);
                }
           echo $nombre1;

        }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex; ?></p></div> <?php }

    }
     public function direccionEmpresa(){
        try {
           $sql1="select direccion_empresa from configuraciones;";
            $result1=mysql_query($sql1);
            $direccion = "";
            while($row1=mysql_fetch_array($result1))//permite ir de fila en fila de la tabla
                {
                    $direccion=$row1['direccion_empresa'];
                }
           echo $direccion;

        }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex; ?></p></div> <?php }

    }

    public function telefonoEmpresa(){
        try {
           $sql1="select telefono1_empresa, telefono2_empresa from configuraciones;";
            $result1=mysql_query($sql1);
            $telefono = "";
            while($row1=mysql_fetch_array($result1))//permite ir de fila en fila de la tabla
                {
                    $telefono=$row1['telefono1_empresa']." / ".$row1['telefono2_empresa'];
                }
           echo $telefono;

        }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex; ?></p></div> <?php }

    }

    public function emailEmpresa(){
        try {
           $sql1="select email_empresa from configuraciones;";
            $result1=mysql_query($sql1);
            $email = "";
            while($row1=mysql_fetch_array($result1))//permite ir de fila en fila de la tabla
                {
                    $email=$row1['email_empresa'];
                }
           echo $email;

        }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex; ?></p></div> <?php }

    }
    
    public function descripcionEmpresa(){
        try {
           $sql1="select descripcion_sistema from configuraciones;";
            $result1=mysql_query($sql1);
            $descripcion = "";
            while($row1=mysql_fetch_array($result1))//permite ir de fila en fila de la tabla
                {
                    $descripcion=$row1['descripcion_sistema'];
                }
           echo $descripcion;

        }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex; ?></p></div> <?php }

    }

    public function logoSistema(){
        try {
           $sql1="select logo_sistema from configuraciones;";
            $result1=mysql_query($sql1);
            $logo_sistema = "";
            while($row1=mysql_fetch_array($result1))//permite ir de fila en fila de la tabla
                {
                    $logo_sistema=$row1['logo_sistema'];
                }
           echo $logo_sistema;

        }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex; ?></p></div> <?php }

    }

    public function iconoSistema(){
        try {
           $sql1="select icono_sistema from configuraciones;";
            $result1=mysql_query($sql1);
            $icono_sistema = "";
            while($row1=mysql_fetch_array($result1))//permite ir de fila en fila de la tabla
                {
                    $icono_sistema=$row1['icono_sistema'];
                }
           echo $icono_sistema;

        }catch(Exception $ex) { ?> <div class="transparent_ajax_error"><p>Error en la consulta: <?php echo "".$ex; ?></p></div> <?php }

    }
   
    
   

}
?>
