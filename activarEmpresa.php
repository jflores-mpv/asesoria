<?php   
    
    require_once('clases/configuraciones.php');
    $configuraciones = new configuraciones();

    //Include database connection details
    require_once('conexion.php');

    // Aqui valido si existe sesion, de haberla envio al usuario a la pagina que le corresponda
    //Start session
    session_start();
    
    $cookie_tipo = $_COOKIE['tipo_cookie'];
    $sesion_tipo = $_SESSION["sesion_tipo"];

    $v1 = $_GET['id'];
    $nombre=$_POST['nom'];
    
        
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Activar Empresa</title>

<!-- START ESTILOS Y CLASES PARA AJAX -->
    <!--<script language="javascript" type="text/javascript" src="js/jquery-1.3.2.min.js"></script>-->
    <script language="javascript" type="text/javascript" src="js/jquery.blockUI.js"></script>
    <script language="javascript" type="text/javascript" src="js/jquery.validate.1.5.2.js"></script>
    <script language="javascript" type="text/javascript" src="js/index.js"></script>
    <!-- END ESTILOS Y CLASES PARA AJAX -->

    <!-- STAR estilo para la plantilla  -->

    <link rel="stylesheet" type="text/css" media="screen" href="css/960.css" /><!--panel derecho-->
    <link rel="stylesheet" type="text/css" media="screen" href="css/screen.css" /><!--cuerpo de la pagina-->
    <link rel="stylesheet" type="text/css" href="css/style.css" media="screen" /><!--fondo y color--> 

    <!-- estilo para validar los campos vacios -->
    <script type="text/javascript" src="js/jquery-1.4.1.min.js"></script>
    <script src="js/jquery.validate.js" type="text/javascript"></script>
   

    <!--validaciones de los campos -->
    <script type="text/javascript" src="js/validaciones.js"></script>

    <!--END estilo para la plantilla -->
    <link rel="shortcut icon" href="images/<?php $configuraciones->logoSistema(); ?>">
 
</head>

<body>

<?php //include("menu.php"); ?>


<div class="transbox"><!-- transparencia -->

    <div id="contentTop" style="width: 400px" class="" >        
        
                <div class="path" style="width: 350px"><a href="index.php" title="Pagina de Inicio">Inicio </a><img src="images/arrowPath.png" alt="" /> </div>
                   <!--
                   <div class="header" style="width: 350px">
                        <h1><?php $configuraciones->nombreSistema(); ?></h1>
                        <h2><?php $configuraciones->descripcionEmpresa(); ?></h2>
                    </div> 
                  <!---->
                  
                    <center>
                        <h2>Activar Empresa</h2>
                   <img src="images/<?php $configuraciones->logoSistema(); ?>" alt="" width="206" height="161" />
                    </center>
                   <center>
                        <form name="form" id="form" method="post" action="index.php" >
                            <div style="width: 340px" id="mensaje"></div>
                            <?php

                            try
                             {
                                if($_GET['id'] > 0){
                                    $sqlp="update empresa set estado='Activo' where id_empresa='".$v1."'; ";
                                    $result=mysql_query($sqlp);
                                    if($result){
                                        ?> <div class='transparent_ajax_correcto'><p>Empresa <?php echo $nombre; ?> Activa.</p></div> <?php
                                    }
                                    else{
                                        ?> <div class='transparent_ajax_error'><p>Error al Activar Empresa: problemas con la consulta <?php echo "\n".mysql_error(); ?></p></div> <?php
                                    }
                                }else {
                                    ?> <div class='transparent_ajax_error'><p>No hay variables <?php echo "\n".mysql_error(); ?></p></div> <?php
                                }
                                
                               
                            }catch (Exception $e) {
                            // Error en algun momento.
                               ?> <div class="transparent_ajax_error"><p>Error: <?php echo "".$e; ?></p></div> <?php
                            }

                            ?>
               
                       


                             <input type="submit" value="Regresar"  id="btnActivar" class="submit" name="btnActivar"/>

                        <br><br />
                        <!--<div >Para crear una cuenta en Alexsys<br /> <input type="button" value="Registrarse" style="width: 100px" id="btnEnviar" class="submit" name="btnEnviar" onclick = "location='registro.php'" /><br></div><br/> -->

                        <div >
                        <?php $configuraciones->telefonoEmpresa(); ?> <br><?php $configuraciones->emailEmpresa(); ?> <br> <?php $configuraciones->direccionEmpresa(); ?> <br/>
                        </div>
                        
                        </form>

                       </center>

                   <div class="path" style="width: 380px"></div>
    </div>
</div>
</body>

</html>
