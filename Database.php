<?php

namespace Neuro;

/*
* Mysql database class - only one connection allowed.
*/
use PDO;

class Database {
  private static $instance;
  private $host = "localhost";
  private $username = "root";
  private $password = "1965";
  private $database = "neuro";
  private $connection;

  public static function getInstance(){
    if(!self::$instance) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  // Constructor
  private function __construct() {
    $this->connection = new PDO("mysql:host={$this->host};dbname={$this->database}", $this->username, $this->password);
    $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }

  // Get the connection
  public function getConnection() {
    return $this->connection;
  }

}
