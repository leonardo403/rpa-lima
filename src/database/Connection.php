<?php

namespace App\database;

use PDO;
use PDOException;

class Connection 
{
  
  protected $servername;
  protected $dbname;
  protected $username;
  protected $password;
  protected $dsn;
  
  protected $conn;

  public function __construct()
  {    
  }  
  
  public  function  ConnInsert($datas)
  {
    $servername='localhost';
    $dbname='rpa_lima';
    $username='root';
    $password='';  
    $dsn = "mysql:dbname=$dbname;host=$servername;charset=UTF8";
    
    try {
      $conn = new PDO($dsn, $username, $password,[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
      
      echo "Connection successfully !!";            
      
      $sql = "INSERT INTO table_rpa (name, amount) VALUES (:name, :amount)";     
      $stmt = $conn->prepare($sql);      

      foreach($datas as $row) {          
        $stmt->execute($row);
      }
        

      echo 'Salvo com sucesso!';   
    } catch(PDOException $e) {
      echo "Falha: " . $e->getMessage();
    }

    
  }  

  public function closeConn(): void
  {
    $this->conn = null;
  }
}
