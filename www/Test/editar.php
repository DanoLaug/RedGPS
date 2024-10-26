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

    // Obtener los datos de la persona a editar
    if (isset($_GET['id'])) {
        $personaId = $_GET['id'];

        // Consultar los datos actuales de la persona
        $stmt = $pdo->prepare("SELECT * FROM persona WHERE id = :id");
        $stmt->bindParam(':id', $personaId);
        $stmt->execute();
        $persona = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si no se encuentra la persona, redirigir
        if (!$persona) {
            header("Location: show_data.php");
            exit;
        }
    }

    // Actualizar los datos si se envió el formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = $_POST['nombre'];
        $apellido1 = $_POST['apellido1'];
        $apellido2 = $_POST['apellido2'];
        $fechaNacimiento = $_POST['fecha_nacimiento'];
        $genero = $_POST['genero'];

        // Actualizar los datos de la persona en la base de datos
        $stmtUpdate = $pdo->prepare("UPDATE persona SET nombre = :nombre, apellido1 = :apellido1, apellido2 = :apellido2, fecha_nacimiento = :fecha_nacimiento, genero = :genero WHERE id = :id");
        $stmtUpdate->bindParam(':nombre', $nombre);
        $stmtUpdate->bindParam(':apellido1', $apellido1);
        $stmtUpdate->bindParam(':apellido2', $apellido2);
        $stmtUpdate->bindParam(':fecha_nacimiento', $fechaNacimiento);
        $stmtUpdate->bindParam(':genero', $genero);
        $stmtUpdate->bindParam(':id', $personaId);
        $stmtUpdate->execute();

        // Redirigir de vuelta al listado de personas
        header("Location: show_data.php");
        exit;
    }
?>

<!-- ---------------------------------------------------HTML Formulario para editar una persona -->
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Editar Persona</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <div class="form-container">
            <h2 class="data-title">Editar Persona</h2>

            <form action="editar.php?id=<?= $personaId ?>" method="POST">
                <div class="input-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" value="<?= $persona['nombre'] ?>" required>
                </div>
                <div class="input-group">
                    <label for="apellido1">Apellido 1:</label>
                    <input type="text" id="apellido1" name="apellido1" value="<?= $persona['apellido1'] ?>" required>
                </div>
                <div class="input-group">
                    <label for="apellido2">Apellido 2:</label>
                    <input type="text" id="apellido2" name="apellido2" value="<?= $persona['apellido2'] ?>" required>
                </div>
                <div class="input-group">
                    <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?= $persona['fecha_nacimiento'] ?>" required>
                </div>
                <div class="input-group">
                    <label for="genero">Género:</label>
                    <select id="genero" name="genero" required>
                        <option value="M" <?= $persona['genero'] === 'M' ? 'selected' : '' ?>>Masculino</option>
                        <option value="F" <?= $persona['genero'] === 'F' ? 'selected' : '' ?>>Femenino</option>
                    </select>
                </div>
                <button type="submit" class="btn">Guardar Cambios</button>
            </form>
        </div>
    </body>
</html>
