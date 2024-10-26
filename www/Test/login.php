<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexión a la base de datos
$server = 'mysql';
$dbname = 'db_api';
$username = 'usr_api';
$password = '4pI_p45s';
$dsn = "mysql:host=$server;dbname=$dbname";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}

// Procesar el formulario de inicio de sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Cifrado con MD5

    $stmt = $pdo->prepare("SELECT * FROM usuario WHERE usuario = :username AND password = :password");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $_SESSION['username'] = $username;
        header("Location: show_data.php");
        exit;
    } else {
        $error_message = "Usuario o contraseña incorrectos.";
    }
}
?>

<!-- Diseño HTML del formulario de inicio de sesión -->
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Iniciar Sesión</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body class="login-body">
        <div class="login-container">
            <h2 class="login-title">Iniciar Sesión</h2>
            <?php if (isset($error_message)) : ?>
                <p class="error-message"><?= htmlspecialchars($error_message) ?></p>
            <?php endif; ?>
            <form action="login.php" method="POST" class="login-form">
                <div class="input-group">
                    <label for="username">Usuario</label>
                    <input type="text" id="username" name="username" class="input-field" required>
                </div>
                <div class="input-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" class="input-field" required>
                </div>
                <button type="submit" class="btn-login">Iniciar Sesión</button>
            </form>
        </div>
    </body>
</html>
