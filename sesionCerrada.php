<?php
    require_once('ver_sesion_empresa.php');
	   require_once('clases/configuraciones.php');
    $configuraciones = new configuraciones();

	
    session_start();
    
    $cookie_tipo = $_COOKIE['tipo_cookie'];
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

    
    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/sign-in/">

    
    <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>

    
    <style>
        html,
        body {
          height: 100%;
        }
        
        body {
          display: flex;
          align-items: center;
          padding-top: 20px;
          padding-bottom: 40px;
          background-color: #f5f5f5;
        }
        
        .form-signin {
          width: 100%;
          max-width: 30%;
          padding: 5%;
          margin-top:5%;
          margin: auto;
        }
        
        .form-signin .checkbox {
          font-weight: 400;
        }
        
        .form-signin .form-floating:focus-within {
          z-index: 2;
        }
        
        .form-signin input[type="email"] {
          margin-bottom: -1px;
          border-bottom-right-radius: 0;
          border-bottom-left-radius: 0;
        }
        
        .form-signin input[type="password"] {
          margin-bottom: 10px;
          border-top-left-radius: 0;
          border-top-right-radius: 0;
        }

    </style>
        
        <script src="librerias/jquery-3.2.1.min.js"></script>
        <script language="javascript" type="text/javascript" src="js/index.js"></script>
  </head>


 <body class="text-center">
     <main class="form-signin border shadow  bg-success text-white rounded">
         
         <h1 class="h3 mb-3 fw-normal">Sesion Cerrada</h1>
         

    </main>
</body>

</html>
