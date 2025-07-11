<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $nombre = isset($data['nombre']) ? trim($data['nombre']) : '';
    
    if (empty($nombre)) {
        $response['message'] = 'El correo electrónico es requerido.';
        echo json_encode($response);
        exit;
    }
    
    try {
        $host = 'localhost';
        $dbname = 'utc_chafa';
        $username = 'root'; 
        $password = ''; 
        
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $pdo->prepare("SELECT contra FROM usuarios WHERE nombre = :nombre");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $password = $user['contra'];
            
            // Enviar correo electrónico
            $to = $nombre;
            $subject = 'Recuperación de contraseña';
            $message = "Tu contraseña es: $password";
            $headers = 'From: no-reply@blogs.edu.mx' . "\r\n" .
                       'Reply-To: no-reply@blogs.edu.mx' . "\r\n" .
                       'X-Mailer: PHP/' . phpversion();
            
            if (mail($to, $subject, $message, $headers)) {
                $response['success'] = true;
                $response['message'] = 'Se ha enviado tu contraseña a tu correo electrónico.';
            } else {
                $response['message'] = 'Error al enviar el correo electrónico.';
            }
        } else {
            $response['message'] = 'No se encontró una cuenta con ese correo electrónico.';
        }
    } catch (PDOException $e) {
        $response['message'] = 'Error de base de datos: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Método no permitido';
}

echo json_encode($response);
?>