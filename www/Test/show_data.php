<?php
    session_start();

    // Verificar si el usuario ha iniciado sesión
    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit;
    }

    // Conexión a la base de datos
    $server = 'mysql';
    $dbname = 'db_api';
    $username = 'usr_api';
    $password = '4pI_p45s';
    $dsn = "mysql:host=$server;dbname=$dbname";

    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } 
    
    catch (PDOException $e) {
        die("Error en la conexión: " . $e->getMessage());
    }

    // Consultar los datos de la tabla persona
    $stmtPersona = $pdo->query("SELECT id, nombre, apellido1, apellido2, fecha_nacimiento, genero FROM persona");
    $personas = $stmtPersona->fetchAll(PDO::FETCH_ASSOC);

    // Función para calcular la edad
    function calcularEdad($fechaNacimiento) {
        $fechaActual = new DateTime();
        $fechaNac = new DateTime($fechaNacimiento);
        return $fechaActual->diff($fechaNac)->y;
    }
?>

<!-- Diseño HTML de show_data.php -->
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Listado de Personas</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body class="data-body">
        <div class="data-container">
            <h2 class="data-title">Listado de Personas</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Apellido 1</th>
                        <th>Apellido 2</th>
                        <th>Fecha de Nacimiento</th>
                        <th>Género</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($personas as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['nombre']) ?></td>
                        <td><?= htmlspecialchars($row['apellido1']) ?></td>
                        <td><?= htmlspecialchars($row['apellido2']) ?></td>
                        <td><?= htmlspecialchars($row['fecha_nacimiento']) ?></td>
                        <td><?= $row['genero'] == 'M' ? 'Masculino' : 'Femenino' ?></td>
                        <td class="actions">
                            <a href="editar.php?id=<?= $row['id'] ?>" class="btn-action edit"><img src="edit.svg" alt="Editar"></a>
                            <a href="eliminar.php?id=<?= $row['id'] ?>" class="btn-action delete" onclick="return confirm('¿Estás seguro de eliminar?');"><img src="trash.svg" alt="Eliminar"></a>
                            <a href="agregar.php" class="btn-action add"><img src="add_person.svg" alt="Agregar"></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <a href="logout.php" class="btn-logout">Cerrar Sesión</a>
        </div>
    </body>
</html>
