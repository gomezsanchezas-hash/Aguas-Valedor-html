<?php
$host = 'localhost';
$db   = 'bd_aguas';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';
 
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Activar excepciones en errores
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Modo de recuperación por defecto
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Usar sentencias preparadas reales
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    // La conexión fue exitosa
} catch (PDOException $e) {
    // Captura el error de conexión o de PDO
    throw new PDOException($e->getMessage(), (int)$e->getCode());
    // En producción, es recomendable registrar el error ($e->getMessage()) y mostrar un mensaje genérico al usuario.
}