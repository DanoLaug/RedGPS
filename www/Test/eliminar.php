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

    // Verificar si se ha recibido el ID para eliminar
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Eliminar el registro de la tabla usuario donde id coincide
        $stmtUsuario = $pdo->prepare("DELETE FROM usuario WHERE id = :id");
        $stmtUsuario->bindParam(':id', $id, PDO::PARAM_INT);
        $stmtUsuario->execute();

        // Eliminar el registro de la tabla persona donde id coincide
        $stmtPersona = $pdo->prepare("DELETE FROM persona WHERE id = :id");
        $stmtPersona->bindParam(':id', $id, PDO::PARAM_INT);
        $stmtPersona->execute();

        // Redirigir de vuelta a la página principal después de eliminar
        header("Location: show_data.php");
        exit;
    } 
    
    else {
        echo "ID de persona no especificado.";
    }
?>
