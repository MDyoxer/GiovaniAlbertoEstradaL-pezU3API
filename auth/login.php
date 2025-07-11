<?php
header("Content-Type: application/json");
require_once '../conn/db_conn.php';
require_once __DIR__ . '/../cors-headers.php';

// Headers de seguridad básicos
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");

session_start(); // Iniciar sesión PHP

$database = new Database();
$pdo = $database->getConnection();

$data = json_decode(file_get_contents('php://input'), true);

// Validar JSON
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Datos inválidos']);
    exit;
}

$nombre = $data['nombre'] ?? '';
$contra = $data['contra'] ?? '';

// Validaciones
if (empty($nombre)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Nombre es requerido']);
    exit;
}

if (empty($contra)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Contraseña es requerida']);
    exit;
}

try {
    // Verificar usuario
    $stmt = $pdo->prepare("SELECT * FROM users WHERE nombre = ?");
    $stmt->execute([$nombre]);
    $user = $stmt->fetch();

    if ($user) {
        if ($contra === $user['contra']) {
            // Guardar datos importantes en sesión PHP
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nombre'] = $user['nombre'];
            $_SESSION['logged_in'] = true;
            
            // Respuesta exitosa
            echo json_encode([
                'success' => true,
                'user' => [
                    'id' => $user['id'],
                    'nombre' => $user['nombre'],
                
                ]
            ]);
        } else {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'error' => 'Credenciales incorrectas'
            ]);
        }
    } else {
        http_response_code(401);
        echo json_encode(['success' => false, 'error' => 'Credenciales incorrectas']);
    }
} catch (PDOException $e) {
    error_log('Database error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error en el servidor']);
}
?>