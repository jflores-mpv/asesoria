<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consultar y Guardar RUC</title>
    <!-- Incluye Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Estilo personalizado para el GIF de carga */
        #loading {
            display: none;
            width: 30px;
            height: 30px;
            margin-left: 10px; /* Espacio entre el botón y el GIF */
        }
    </style>
    <script>
        function consultarRUC() {
            var txt_ruc = document.getElementById("numeroRuc").value;

            // Validar que el RUC tenga 13 caracteres
            if (txt_ruc.length != 13) {
                alert("El número de RUC debe tener exactamente 13 caracteres.");
                return false;
            }

            // Mostrar el GIF de carga
            document.getElementById("loading").style.display = "inline";

            var xhr = new XMLHttpRequest();
            xhr.open("GET", "consultar_ruc.php?ruc=" + txt_ruc, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById("loading").style.display = "none"; // Ocultar GIF de carga

                    var data = JSON.parse(xhr.responseText);
                    if (data.result === 1) {
                        document.getElementById("razonSocial").value = data.razonSocial;
                        document.getElementById("nombreComercial").value = data.nombreComercial;
                        document.getElementById("direccionCompleta").value = data.direccionCompleta;
                    } else {
                        var modal = new bootstrap.Modal(document.getElementById('errorModal'));
                        document.getElementById("errorModalMessage").textContent = "RUC no encontrado.";
                        modal.show();
                    }
                }
            };
            xhr.send();
            return false; 
        }

        function limpiarFormulario() {
            document.getElementById("numeroRuc").value = "";
            document.getElementById("razonSocial").value = "";
            document.getElementById("nombreComercial").value = "";
            document.getElementById("direccionCompleta").value = "";
        }
    </script>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">Consulta de RUC</h2>

    <!-- Mostrar alertas si hay errores o éxito -->
    <?php if (isset($_GET['error']) && $_GET['error'] == 'duplicado'): ?>
        <div class="alert alert-danger" role="alert">
            El RUC ingresado ya existe en la base de datos.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="alert alert-success" role="alert">
            El registro se ha guardado con éxito.
        </div>
    <?php endif; ?>

    <form onsubmit="return consultarRUC();" class="form-group">
        <div class="mb-3">
            <label for="numeroRuc" class="form-label">Número de RUC:</label>
            <div class="input-group">
                <input type="text" id="numeroRuc" name="numeroRuc" class="form-control" required>
                <button type="submit" class="btn btn-primary">Validar</button>
            </div>
        </div>
    </form>

    <hr>

    <h2 class="text-center">Datos del RUC</h2>

    <form method="POST" action="proveedores.php" class="form-group">
        <div class="mb-3">
            <label for="razonSocial" class="form-label">Razón Social:</label>
            <input type="text" id="razonSocial" name="razonSocial" class="form-control" readonly>
        </div>

        <div class="mb-3">
            <label for="nombreComercial" class="form-label">Nombre Comercial:</label>
            <input type="text" id="nombreComercial" name="nombreComercial" class="form-control" readonly>
        </div>

        <div class="mb-3">
            <label for="direccionCompleta" class="form-label">Dirección Completa:</label>
            <input type="text" id="direccionCompleta" name="direccionCompleta" class="form-control" readonly>
        </div>

        <button type="submit" class="btn btn-success">Guardar</button>
    </form>
</div>

<!-- Modal para avisos de error (RUC no encontrado) -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="errorModalLabel">Aviso</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="errorModalMessage">
        <!-- Mensaje de error dinámico -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Incluye Bootstrap JS y dependencias -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
