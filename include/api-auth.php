    <?php 
    //CREAR LOS USUARIOS
    require_once __DIR__ . '/../conn/db_conn.php';

    Class Users {
        public static function crear_usuario($nombre,$email,$contra,$contra2){
            $database = new Database();
            $pdo = $database->getConnection();

            try{
                $stmt = $pdo->prepare('INSERT INTO usuarios (nombre,email,contra) VALUES (:nombre,:email,:contra)');
                $stmt->bindParam(':nombre',$nombre);
                $stmt->bindParam(':email',$email);
                $stmt->bindParam(':contra',$contra);

                if($stmt->execute()){
                    header('HTTP/1.1 201 USUARIO CREADO CORRECTAMENTE');
                    echo json_encode(['message' => 'USUARIO CREADO CORRECTAMENTE']);
                }else{
                    header('HTTP/1.1 400 FALLO AL CREAR EL USUARIO');
                    echo json_encode(['message' => 'FALLO AL CREAR EL USUARIO']);
                }
            } catch(PDOException $e){
                header('HTTP/1.1 500 FALLO EN EL SERVIDOR');
                    echo json_encode(['message' => 'FALLO EN EL SERVIDOR']);
        }
        }
    }
    ?>