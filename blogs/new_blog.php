<?php 
require_once __DIR__ . '/../include/api-tareas.php';
require_once __DIR__ . '/../cors-headers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['nombre'], $input['materia'], $input['fecha_limite'])) {
        $nombre = $input['nombre'];
        $materia = $input['materia'];
        $fecha_limite = $input['fecha_limite'];

        Tarea::new_tarea($nombre, $materia, $fecha_limite);
    } else {
        header('HTTP/1.1 400 Solicitud inválida');
        echo json_encode(['message' => 'Datos incompletos']);
        exit;
    }
} else {
    header('HTTP/1.1 405 Método no permitido');
    echo json_encode(['message' => 'Método no permitido']);
    exit;
}
?>
