<?php

namespace Core;

use Exception;
use mysqli;
use Utils\DotEnv;

class Database
{
  private static Database $instance;
  private mysqli $mysqli;
  private int $transactionDepth;

  private function __construct()
  {
    $host = DotEnv::get("DB_HOST");
    $user = DotEnv::get("DB_USER");
    $password = DotEnv::get("DB_PASSWORD");
    $database = DotEnv::get("DB_NAME");

    $this->transactionDepth = 0;

    $this->mysqli = new mysqli($host, $user, $password, $database);
    if ($this->mysqli->connect_errno) {
      echo "Failed to connect to MySQL: " . $this->mysqli->connect_error;
      exit();
    }
  }

  public static function getInstance()
  {
    if (!isset(self::$instance)) {
      self::$instance = new Database();
    }
    return self::$instance;
  }
  private function executeQuery($query, $params)
  {
    $stmt = $this->mysqli->prepare($query);
    if ($stmt === false) {
      echo "Error preparing statement: " . $this->mysqli->error;
      exit();
    }

    if (!empty($params)) {
      $types = "";
      foreach ($params as $param) {
        if (is_int($param)) {
          $types .= "i";
        } elseif (is_float($param)) {
          $types .= "d";
        } elseif (is_string($param)) {
          $types .= "s";
        } else {
          $types .= "b";
        }
      }
      $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
  }

  public function select($query, $params = [])
  {
    $result = $this->executeQuery($query, $params);
    $rows = [];
    while ($row = $result->fetch_assoc()) {
      $rows[] = $row;
    }
    return $rows;
  }

  public function insert($query, $params = [])
  {
    $this->executeQuery($query, $params);
    return $this->mysqli->affected_rows;
  }

  public function update($query, $params = [])
  {
    $this->executeQuery($query, $params);
    return $this->mysqli->affected_rows;
  }

  public function delete($query, $params = [])
  {
    $this->executeQuery($query, $params);
    return $this->mysqli->affected_rows;
  }

  public function count($query, $params = [])
  {
    $result = $this->executeQuery($query, $params);
    $row = $result->fetch_row();
    return $row[0];
  }

  public function truncate($table)
  {
    $query = "TRUNCATE TABLE $table";
    $this->executeQuery($query, []);
    return $this->mysqli->affected_rows;
  }

  public function getLastInsertedId()
  {
    return $this->mysqli->insert_id;
  }

  public function beginTransaction()
  {
    if ($this->transactionDepth == 0) {
      $this->mysqli->begin_transaction();
    }
    $this->transactionDepth++;
  }
  public function rollbackTransaction()
  {
    $this->transactionDepth--;
    if ($this->transactionDepth == 0) {
      $this->mysqli->rollback();
    }
  }
  public function commitTransaction()
  {
    $this->transactionDepth--;
    if ($this->transactionDepth == 0) {
      $this->mysqli->commit();
    }
  }
}