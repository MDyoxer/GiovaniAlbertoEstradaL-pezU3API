<?php

class Database {
    private $host = 'localhost';
    private $db = 'utc_blog';
    private $user = 'root';
    private $pass = '';
    private $pdo;

    public function getConnection(){
        if($this->pdo === null){
          $dsn = "mysql:host={$this->host};dbname={$this->db};charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try{
                $this->pdo = new PDO($dsn, $this->user, $this ->pass, $options);
                
            } catch (PDOExeption $e){
                throw new PDOExeption($e->getMessage(), (int)$e->getCode());
            }
        }
        return $this->pdo;
    }

}

?>