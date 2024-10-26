<?php

    phpinfo();

?>

<?php
$server = 'mysql';
$dbname = 'db_api';
$username = 'usr_api';
$password = '4pI_p45s';

try {
    $dsn = "mysql:host=$server;dbname=$dbname";
    $pdo = new PDO($dsn, $username, $password);
    // Configurar PDO para manejar errores
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexión exitosa a la base de datos.";
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>
