<?php
require_once __DIR__ . '/../cors-headers.php';
require_once __DIR__ . '/../include/api-tareas.php';

    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        Tarea::all_tareas();
    }
?>