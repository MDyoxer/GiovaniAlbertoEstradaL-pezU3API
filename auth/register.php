<?php 

require_once __DIR__ . '/../cors-headers.php';
require_once __DIR__ . '/../include/api-auth.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['nombre'], $input['contra'], $input['contra2'])) {
        $nombre = $input['nombre'];
        $contra = $input['contra'];
        $contra2 = $input['contra2'];

        if($contra !== $contra2) {
            header('HTTP/1.1 400 CONTRASEÑAS NO COINCIDEN');
            header('Content-Type: application/json');
            echo json_encode(['message' => 'LAS CONTRASEÑAS NO COINCIDEN']);
            exit;
        }

        Users::crear_usuario($nombre, $email, $contra, $contra2);
        
    } else {
        header('HTTP/1.1 400 SOLICITUD INVALIDA');
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Datos incompletos']);
        exit;
    }
} else {
    header('HTTP/1.1 405 METODO NO PERMITIDO');
    header('Content-Type: application/json');
    echo json_encode(['message' => 'METODO NO PERMITIDO']);
    exit;
}
?>