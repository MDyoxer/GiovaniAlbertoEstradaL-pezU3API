<?php
require_once __DIR__ . '/../conn/db_conn.php';

class Tarea {
    public static function new_tarea($nombre, $materia, $fecha_limite) {
        $database = new DataBase();
        $pdo = $database->getConnection();

        try {
            $fecha_inicio = date('Y-m-d');

            if (strtotime($fecha_limite) < strtotime($fecha_inicio)) {
                throw new Exception("La fecha límite no puede ser anterior a la fecha de inicio");
            }

            $stmt = $pdo->prepare('INSERT INTO tareas (nombre, materia, fecha_inicio, fecha_limite) VALUES (:nombre, :materia, :fecha_inicio, :fecha_limite)');
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':materia', $materia);
            $stmt->bindParam(':fecha_inicio', $fecha_inicio);
            $stmt->bindParam(':fecha_limite', $fecha_limite);

            if ($stmt->execute()) {
                header('HTTP/1.1 201 Created');
                echo json_encode(['message' => 'Tarea registrada correctamente']);
            } else {
                header('HTTP/1.1 400 Bad Request');
                echo json_encode(['message' => 'No se pudo registrar la tarea']);
            }


        } catch (PDOException $e) {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['error' => $e->getMessage()]);
        } catch (Exception $ex) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['error' => $ex->getMessage()]);
        }
    }

    //mostrar tareas
    public static function all_tareas(){
        $database = new Database();
        $pdo = $database->getConnection();

        try{
            $stmt=$pdo->prepare('SELECT * FROM tareas');
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
         header('Content-Type: application/json');
            header('HTTP/1.1 200 OK');
            echo json_encode($result);
        } catch (PDOException $e) {
            header('HTTP/1.1 500 Error interno del servidor');
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    //eliminar tarea
 public static function EliminarTarea($id){
        $database = new Database();
        $pdo = $database->getConnection();

        try{
            $stmt = $pdo->prepare('DELETE FROM tareas WHERE id = :id');
            $stmt->bindParam(':id',$id,PDO::PARAM_INT);

       if ($stmt->execute()) {
                header('HTTP/1.1 200 Tarea eliminado correctamente');
                echo json_encode(['message' => 'Tarea borrado correctamente']);
            } else {
                header('HTTP/1.1 400 Tarea no se ha borrado correctamente');
                echo json_encode(['message' => 'Tarea no se ha borrado correctamente']);
            }
        } catch (PDOException $e) {
            header('HTTP/1.1 500 Error interno del servidor');
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
?>
