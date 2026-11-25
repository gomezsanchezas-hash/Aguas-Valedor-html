
<?php
header('Content-Type: application/json; charset=utf-8');

require_once 'api/conexion.php';

$response = [
    'exito' => false,
    'mensaje' => 'Ocurrió un error inesperado.'
];

// Comprobar que la petición sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    $response['mensaje'] = 'Método no permitido.';
    echo json_encode($response);
    exit;
}

// Obtener y limpiar los datos
$nombre = trim($_POST['nombre'] ?? '');
$correo = trim($_POST['correo'] ?? '');
$pedido = trim($_POST['pedido'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');

// Comprobar campos vacíos
if (
    $nombre === '' ||
    $correo === '' ||
    $pedido === '' ||
    $direccion === '' ||
    $telefono === ''
) {
    http_response_code(400);
    $response['mensaje'] = 'Todos los campos son obligatorios.';
    echo json_encode($response);
    exit;
}

// Validar correo electrónico
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    $response['mensaje'] = 'El correo electrónico no es válido.';
    echo json_encode($response);
    exit;
}

// Validar teléfono (10 dígitos)
if (!preg_match('/^[0-9]{10}$/', $telefono)) {
    http_response_code(400);
    $response['mensaje'] = 'El teléfono debe contener exactamente 10 dígitos.';
    echo json_encode($response);
    exit;
}

// Sanitizar los datos de texto
$nombre = htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');
$pedido = htmlspecialchars($pedido, ENT_QUOTES, 'UTF-8');
$direccion = htmlspecialchars($direccion, ENT_QUOTES, 'UTF-8');

try {

    // Preparar consulta SQL
    $sql = "INSERT INTO pedidos
            (nombre, correo, pedido, direccion, telefono)
            VALUES
            (:nombre, :correo, :pedido, :direccion, :telefono)";

    $stmt = $pdo->prepare($sql);

    // Vincular parámetros
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
    $stmt->bindParam(':pedido', $pedido, PDO::PARAM_STR);
    $stmt->bindParam(':direccion', $direccion, PDO::PARAM_STR);
    $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);

    // Ejecutar consulta
    $stmt->execute();

    http_response_code(200);

    $response['exito'] = true;
    $response['mensaje'] = '¡Pedido registrado correctamente!';

} catch (PDOException $e) {

    error_log($e->getMessage());

    http_response_code(500);
    $response['mensaje'] = 'Error al guardar el pedido.';
}

// Enviar respuesta JSON
echo json_encode($response);

?>
