<?php
header("Cache-Control: no-cache, no-store, must-revalidate"); // Evita el almacenamiento en caché en navegadores modernos
header("Pragma: no-cache"); // Evita el almacenamiento en caché en navegadores antiguos
header("Expires: 0"); // Establece la fecha de expiración en el pasado
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asesoria Empresarial y Contabilidad</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="librerias/jquery-3.2.1.min.js"></script>
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">

    <script language="javascript" type="text/javascript" src="js/index.js"></script>
    <script>
        // Cuando la página esté completamente cargada
        $(document).ready(function() {
            // Mostrar el popup
            $('#popupModal').modal('show');

            // Enfocar el campo txtPassword al cerrar el modal
            $('#popupModal').on('hidden.bs.modal', function () {
                $('#txtPassword').focus();
            });
        });
    </script>
    <style>
        body {
            background: url('images/back.png') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .login-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            margin: auto;
        }
        .login-title {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        .form-control {
            border-radius: 20px;
            border: 2px solid #007bff;
            animation: blink 1s step-end infinite alternate;
        }
        .btn-custom {
            width: 100%;
            border-radius: 20px;
            margin-top: 10px;
        }
        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            padding: 10px;
            background: rgba(0, 0, 0, 0.7);
            color: #fff;
            font-size: 14px;
        }
        @keyframes blink {
            50% {
                border-color: #f00; /* Cambia el color a rojo para hacerlo más llamativo */
            }
        }
        /* Estilos personalizados para el popup */
        .modal-content {
            background: #fff;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.3);
            text-align: center;
        }
        .modal-header {
            border-bottom: none;
            background-color: #007bff;
            color: white;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            text-align: center;
        }
        .modal-title {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .modal-body {
            font-size: 1.1rem;
            color: #333;
        }
        .modal-footer {
            justify-content: center;
            border-top: none;
        }
        .close {
            color: white;
            opacity: 1;
            font-size: 1.2rem;
        }
        .close:hover {
            color: #ddd;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
    <script>
    // Forzar la recarga de la página al volver atrás
    window.onload = function() {
        if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
            location.reload(); // Recargar la página
        }
    };
    </script>
</head>
<body>
    <!-- Modal (Popup) -->
    <div class="modal fade" id="popupModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">¡Bienvenido!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Digite directamente su número de RUC o usuario en el campo de texto y presione "Iniciar Sesión".
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Entendido</button>
                </div>
            </div>
        </div>
    </div>

    <div class="login-container">
        <div class="login-title">Validación de RUC</div>
        <form name="form" id="form" method="post" action="javascript:ingreso_empresa();"> 
            <div class="form-group">
                <input type="password" id="txtPassword" class="form-control" name="txtPassword" placeholder="Ingrese su RUC o su Usuario" required="required" />
                <span id="mensaje" class="text-danger"></span> 
            </div>
            <button class="btn btn-primary btn-custom" id="button-addon2">Iniciar Sesión</button>
            <button class="btn btn-secondary btn-custom" type="button" onclick="location.href='crearCuenta.php'">Registrarse</button>
        </form> 
    </div>
    <footer>
     &copy; desde 2024 AS CONTA & Support Tech Ec - Todos los derechos reservados
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>