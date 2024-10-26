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

    // Procesar el formulario de inserción
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = $_POST['nombre'];
        $apellido1 = $_POST['apellido1'];
        $apellido2 = $_POST['apellido2'];
        $fecha_nacimiento = $_POST['fecha_nacimiento'];
        $genero = $_POST['genero'];
        $usuario = $_POST['usuario'];
        $correo = $_POST['correo'];
        $password = md5($_POST['password']); // Cifrar la contraseña con md5

        // Insertar datos en la tabla persona
        $stmtPersona = $pdo->prepare("INSERT INTO persona (nombre, apellido1, apellido2, fecha_nacimiento, genero) VALUES (?, ?, ?, ?, ?)");
        $stmtPersona->execute([$nombre, $apellido1, $apellido2, $fecha_nacimiento, $genero]);

        // Obtener el ID de la persona recién insertada
        $id_persona = $pdo->lastInsertId();

        // Insertar datos en la tabla usuario
        $stmtUsuario = $pdo->prepare("INSERT INTO usuario (id_persona, usuario, correo, password) VALUES (?, ?, ?, ?)");
        $stmtUsuario->execute([$id_persona, $usuario, $correo, $password]);

        // Redirigir a la lista de personas
        header("Location: show_data.php");
        exit;
    }
?>

<!-- --------------------------------------------------- HTML formulario Agregar persona  -->
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Agregar Nueva Persona</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    
    <body>
        <div class="form-container">
            <h2 class="data-title">Agregar Nueva Persona</h2>
            <form action="agregar.php" method="POST">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="apellido1">Primer Apellido:</label>
                    <input type="text" id="apellido1" name="apellido1" required>
                </div>
                <div class="form-group">
                    <label for="apellido2">Segundo Apellido:</label>
                    <input type="text" id="apellido2" name="apellido2" required>
                </div>
                <div class="form-group">
                    <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>
                </div>
                <div class="form-group">
                    <label for="genero">Género:</label>
                    <select id="genero" name="genero" required>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="usuario">Usuario:</label>
                    <input type="text" id="usuario" name="usuario" required>
                </div>
                <div class="form-group">
                    <label for="correo">Correo:</label>
                    <input type="email" id="correo" name="correo" required>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn">Agregar Persona</button>
            </form>
        </div>
    </body>
</html>
