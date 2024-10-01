<?php
        require('ver_sesion.php');
        require_once('conexion.php');
        $sesion_tipo_empresa = $_SESSION["sesion_tipo_empresa"];
        $sesion_id_nivel_institucion = $_SESSION["sesion_id_nivel_institucion"];
        $sesion_id_institucion_educativa= $_SESSION["sesion_id_institucion_educativa"];
        $sesion_id_empresa = $_SESSION["sesion_id_empresa"];
        $emision_ambiente= $_SESSION['emision_ambiente'] ;
        $emision_tipoFacturacion=$_SESSION['emision_tipoFacturacion'];
        $emision_tipoEmision=$_SESSION['emision_tipoEmision'] ;
        
        $sesion_id_usuario = $_SESSION["sesion_id_usuario"];
        
        
        $protocolo = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
        $host = $_SERVER['HTTP_HOST'];
        $uri = $_SERVER['REQUEST_URI'];
        
        $url_actual = $protocolo . $host . $uri;
                
        
        // Parsea la URL para obtener el path
        $path = parse_url($url_actual, PHP_URL_PATH);
        
        // Usa basename para obtener lo que está después del último "/"
        $pagina_actual = basename($path);
        
        $pagina_actual; 
        

       $login= $_SESSION['usuarios_login']

?>
<style>
.activeModulo{
    
 border-radius: 5px;
  box-shadow: 0px 0px 0px #070913, 0px 0px 0px #629554, inset 4px 4px 8px #31771e, inset -4px -4px 8px #99ca8b00;
}
</style>

<script src="js/permisosModulos.js"></script>
<link rel="stylesheet" type="text/css" href="librerias/font-awesome-4.7.0/css/font-awesome.css">
<ul class="listadoScroll menu" >
    <li>
        <a ><p><?php echo $login ?></p></a>
    </li>


    <li><a href="menuPrincipalCondominios.php" ><i class="fa fa-home" aria-hidden="true"></i><p>Inicio</p></a></li>
  
<?php
        $sql = "SELECT
        modulos.`id` AS id,
		modulos.`modulo` AS modulo,
		modulos.`url` AS url,
		modulos.`icono` AS icono,
		
		permisos_usuarios.`mostrar` AS mostrar,
		permisos_usuarios.`id_modulo` AS mostrar,
		permisos_usuarios.`id_usuario` AS id_usuario
		
	FROM
     `modulos` modulos
    
    INNER JOIN  `permisos_usuarios` permisos_usuarios ON permisos_usuarios.id_modulo  = modulos.id  
     
     where permisos_usuarios.id_usuario='".$sesion_id_usuario."' and 	permisos_usuarios.`mostrar`= 'SI' ;";

        $result = mysql_query($sql) or die(mysql_error());
        $numero_filas = mysql_num_rows($result);
        
        while ($row = mysql_fetch_assoc($result)){
            // echo $row['url'];
            // echo $pagina_actual;
?>

  <li <?php echo ($row['url']== $pagina_actual) ? 'class="activeModulo"' : ''; ?>>
    <a href="<?php echo $row['url']; ?>">
        <i class="<?php echo $row['icono']; ?>" aria-hidden="true"></i>
        <p><?php echo $row['modulo']; ?></p>
    </a>
</li>
        
<?php   }  



?>        





</ul>

  