<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nombres de Tablas y Cantidad de Registros</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Nombres de Tablas y Cantidad de Registros</h1>
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Nombre de la Tabla</th>
                    <th>Cantidad de Registros</th>
                </tr>
            </thead>
            <tbody>
                <?php
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

                // Obtener nombres de las tablas
                $sql = "SHOW TABLES";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Iterar sobre cada tabla
                    while($row = $result->fetch_array()) {
                        $tableName = $row[0];

                        // Contar registros en la tabla actual
                        $sqlCount = "SELECT COUNT(*) AS total FROM $tableName";
                        $resultCount = $conn->query($sqlCount);
                        $count = $resultCount->fetch_assoc()['total'];

                        echo "<tr>";
                        echo "<td>$tableName</td>";
                        echo "<td>$count</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>No se encontraron tablas</td></tr>";
                }

                // Cerrar conexión
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
