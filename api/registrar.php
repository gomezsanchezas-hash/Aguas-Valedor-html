
<?php
header('Content-Type: application/json; charset=utf-8');

require_once 'conexion.php';

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
$titular = trim($_POST['titular'] ?? '');
$numtarjeta = $_POST['numtarjeta'] ?? '';
$fechaexpiracion = $_POST['fechaexpiracion'] ?? '';
$cvc = $_POST['CVC'] ?? '';

if (
    empty($titular) ||
    !preg_match('/^[0-9]{16}$/', $numtarjeta) ||
    !preg_match('/^(0[1-9]|1[0-2])\/[0-9]{2}$/', $fechaexpiracion) ||
    !preg_match('/^[0-9]{3,4}$/', $cvc)
) {
    throw new Exception('Datos de demostración incorrectos.');
}

$ultimosDigitos = substr($numtarjeta, -4);
$tipoTarjeta = 'Demostración';

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
            (nombre, correo, pedido, direccion, telefono, titular, ultimos_digitos, tipo_tarjeta)
            VALUES
            (:nombre, :correo, :pedido, :direccion, :telefono, :titular, :ultimos_digitos, :tipo_tarjeta)";

    $stmt = $pdo->prepare($sql);

    // Vincular parámetros
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
    $stmt->bindParam(':pedido', $pedido, PDO::PARAM_STR);
    $stmt->bindParam(':direccion', $direccion, PDO::PARAM_STR);
    $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);
    $stmt->bindParam(':titular', $titular, PDO::PARAM_STR);
    $stmt->bindParam(':ultimos_digitos', $ultimosDigitos, PDO::PARAM_STR);
    $stmt->bindParam(':tipo_tarjeta', $tipoTarjeta, PDO::PARAM_STR);

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