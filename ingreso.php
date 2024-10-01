<?php

    require_once('ver_sesion_empresa.php');
	   require_once('clases/configuraciones.php');
    $configuraciones = new configuraciones();

	
    session_start();
    
    // $cookie_tipo = $_COOKIE['tipo_cookie'];
    $sesion_tipo = $_SESSION["sesion_tipo"];
    $sesion_id_empresa_prof=$_SESSION['sesion_id_empresa'] ;
	$sesion_cod_activacion_prof=$_SESSION['sesion_cod_activacion'] ;
	
	$sesion_empresa_nombre = $_SESSION['sesion_empresa_nombre'];
	
	$sesion_id_institucion_educativa= $_SESSION['sesion_id_institucion_educativa'];

?>

<html lang="es-ES">
    
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="INICIAR SESION">
    <meta name="generator" content="INICIAR SESION">
    <title>Iniciar Sesi&oacute;n</title>
<meta name="google-adsense-account" content="ca-pub-1139165833980568">

    <script src="https://kit.fontawesome.com/7816c50242.js" crossorigin="anonymous"></script>
    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/sign-in/">

    
    <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="librerias/jquery-3.2.1.min.js"></script>
    <script language="javascript" type="text/javascript" src="js/index.js"></script>
      
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1139165833980568"
     crossorigin="anonymous"></script>  

  </head>


    <body class="text-center">
      <div class="container">
        <div class="row justify-content-center ">
          <div class="col-md-6 col-lg-4 ">
            <main class="form-signin border shadow p-5 mt-5">
                <h1 class="h3 mb-3 fw-normal"><? echo $sesion_empresa_nombre ?></h1>
              <form name="form" id="form" method="post" action="javascript: ingreso_cursoProf();">
                <h1 class="h3 mb-3 fw-normal">Iniciar Sesi&oacute;n</h1>

                <div class="form-floating">
                    
                    <div class="input-group">
                      <input type="text" autofocus id="txtLogin" class="form-control" name="txtLogin" placeholder="Usuario" required="required" />
                        <!--<label for="floatingInput">Usuario</label>-->
                    </div>
                    
                    <div class="input-group mt-2">
                      <input type="password" id="txtPassword" class="form-control" name="txtPassword" placeholder="Contrase&ntilde;a" required="required" />
                      <button class="btn btn-outline-secondary" type="button" id="show-password"><i class="fa fa-eye" aria-hidden="true"></i></button>
                      <!--<label for="floatingInput">Contraseña</label>-->
                    </div>
                </div>
                

                <div id="mensaje"></div>
                <button class="w-100 btn btn-lg btn-success mt-3" type="submit">Ingresar</button>
              </form>
            </main>
          </div>
        </div>
      </div>
    </body>

    <script>
    
          const passwordInput = document.getElementById('txtPassword');
          const showPasswordButton = document.getElementById('show-password');
        
          showPasswordButton.addEventListener('click', () => {
            if (passwordInput.type === 'password') {
              passwordInput.type = 'text';
              showPasswordButton.innerHTML = '<i class="fa fa-eye-slash"></i>';
            } else {
              passwordInput.type = 'password';
              showPasswordButton.innerHTML = '<i class="fa fa-eye"></i>';
            }
          });
          
    </script>
    
</html>

