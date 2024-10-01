<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar y Descargar Registros en Tablas</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Buscar Registros en Tablas</h1>
        <form method="POST" class="mb-4">
            <div class="form-row">
                <div class="col">
                    <input type="text" class="form-control" name="tabla" placeholder="Nombre de la tabla" required>
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="columna" placeholder="Nombre de la columna" required>
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="valores" placeholder="Valores separados por comas" required>
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </div>
            </div>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Obtener valores del formulario
            $tabla = $_POST['tabla'];
            $columna = $_POST['columna'];
            $valores = $_POST['valores'];

            // Datos de conexión a la base de datos
       $servername = "localhost";
            $username = "root";
            $password = 'new_password';
            $dbname = "asesoria_asesoria";

            // Crear conexión
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Verificar conexión
            if ($conn->connect_error) {
                die("Conexión fallida: " . $conn->connect_error);
            }

            // Escapar nombres de tabla y columna
            $tabla = $conn->real_escape_string($tabla);
            $columna = $conn->real_escape_string($columna);

            // Procesar valores para la cláusula IN
            $valoresArray = array_map('trim', explode(',', $valores));
            $valoresPlaceholders = implode(',', array_fill(0, count($valoresArray), '?'));

            // Verificar si la tabla existe
            $sql = "SHOW TABLES LIKE '$tabla'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Obtener registros de la tabla especificada que coincidan con los valores en la columna especificada
                $sqlSelect = "SELECT * FROM $tabla WHERE $columna IN ($valoresPlaceholders)";
                $stmt = $conn->prepare($sqlSelect);
                if ($stmt) {
                    $types = str_repeat('s', count($valoresArray));
                    $stmt->bind_param($types, ...$valoresArray);
                    $stmt->execute();
                    $resultSelect = $stmt->get_result();
                    $columns = $resultSelect->fetch_fields();

                    // Mostrar registros en una tabla con checkboxes
                    echo '<form method="POST" action="download.php">';
                    echo '<table class="table table-bordered">';
                    echo '<thead class="thead-dark"><tr>';
                    echo '<th>Seleccionar</th>';
                    foreach ($columns as $col) {
                        echo "<th>{$col->name}</th>";
                    }
                    echo '</tr></thead><tbody>';
                    while ($row = $resultSelect->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td><input type="checkbox" name="selected_rows[]" value="' . htmlspecialchars(json_encode($row)) . '"></td>';
                        foreach ($row as $value) {
                            echo "<td>{$value}</td>";
                        }
                        echo '</tr>';
                    }
                    echo '</tbody></table>';

                    // Botón para descargar los registros seleccionados
                    echo '<input type="hidden" name="tabla" value="' . $tabla . '">';
                    echo '<button type="submit" class="btn btn-success">Descargar Seleccionados</button>';
                    echo '</form>';

                    $stmt->close();
                } else {
                    echo "<div class='alert alert-danger'>Error en la consulta: " . $conn->error . "</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>La tabla especificada no existe</div>";
            }

            // Cerrar conexión
            $conn->close();
        }
        ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
