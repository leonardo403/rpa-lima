<?php

namespace App\database;

use PDOException;

class InsertData
{
    public $conn;

    public function insert($datas)
  {    
    $sql = "INSERT INTO table_rpa (name, amount) VALUES (:name, :amount)";
    
    try {
        $stmt = $this->conn->prepare($sql);    
        $stmt = $conn->prepare($sql);
        $this->conn->beginTransaction();
        foreach($datas as $row) {          
          $stmt->execute($row);
        }
        echo 'Salvo com sucesso!';          
    } catch (PDOException $pdo_ex) {
        echo "Falha: " . $pdo_ex->getMessage();
    }     
  }
}