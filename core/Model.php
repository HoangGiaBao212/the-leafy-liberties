<?php

namespace Core;

use Core\Database;

class Model
{
  protected $table;
  protected $primaryKey = "id";
  protected $fillable = [];
  public $attributes = [];
  protected static $db;

  public function __construct($attributes = [])
  {
    $this->attributes = $attributes;
    self::$db = Database::getInstance();
  }

  public static function find($id)
  {
    $table = (new static())->table;
    $primaryKey = (new static())->primaryKey;
    $query = "SELECT * FROM $table WHERE $primaryKey = ?";
    $result = self::$db->select($query, [$id]);
    if (!empty($result)) {
      return new static($result[0]);
    }
    return null;
  }

  public static function findOne($params)
  {
    $table = (new static())->table;
    $query = "SELECT * FROM $table WHERE ";
    $values = [];
    foreach ($params as $key => $value) {
      if ($value === 'null') {
        $query .= "$key IS NULL AND ";
      } else {
        $query .= "$key = ? AND ";
        $values[] = $value;
      }
    }
    $query = rtrim($query, " AND ");
    $result = self::$db->select($query, $values);
    if (!empty($result)) {
      return new static($result[0]);
    }
    return null;
  }

  public static function findAll($params)
  {
    $table = (new static())->table;
    $query = "SELECT * FROM $table WHERE ";
    $values = [];
    foreach ($params as $key => $value) {
      if ($value === 'null') {
        $query .= "$key IS NULL AND ";
      } else {
        $query .= "$key = ? AND ";
        $values[] = $value;
      }
    }
    $query = rtrim($query, " AND ");
    $result = self::$db->select($query, $values);
    $models = [];
    foreach ($result as $row) {
      $models[] = new static($row);
    }
    return $models;
  }


  public static function where($conditions = [])
  {
    $table = (new static())->table;
    $query = "SELECT * FROM $table WHERE ";
    if (isset($conditions["sql"])) {
      $query .= $conditions["sql"];
      $result = self::$db->select($query, $conditions["params"] ?? []);
      $models = [];
      foreach ($result as $row) {
        $models[] = new static($row);
      }
      return $models;
    }
    foreach ($conditions as $condition) {
      $query .=
        $condition["column"] .
        " " .
        $condition["operator"] .
        " " .
        $condition["value"] .
        " AND ";
    }
    $query = rtrim($query, " AND ");
    $result = self::$db->select($query);
    $models = [];
    foreach ($result as $row) {
      $models[] = new static($row);
    }
    return $models;
  }

  public static function create($attributes)
  {
    $model = new static($attributes);
    $model->save();
    return $model;
  }

  public static function all()
  {
    $table = (new static())->table;
    $query = "SELECT * FROM $table";
    $result = self::$db->select($query);
    $models = [];
    foreach ($result as $row) {
      $models[] = new static($row);
    }
    return $models;
  }
  public function save(): void
  {
    if (isset($this->attributes[$this->primaryKey])) {
      $this->update();
    } else {
      $this->insert();
    }
  }

  private function insert(): void
  {
    $table = $this->table;
    $fillable = $this->fillable;
    $attributes = array_intersect_key($this->attributes, array_flip($fillable));
    $columns = implode(", ", array_keys($attributes));
    $placeholders = implode(", ", array_fill(0, count($attributes), "?"));
    $values = array_values($attributes);

    $query = "INSERT INTO $table ($columns) VALUES ($placeholders)";
    self::$db->insert($query, $values);
    $this->attributes[$this->primaryKey] = self::$db->getLastInsertedId();
  }

  private function update(): void
  {
    $table = $this->table;
    $primaryKey = $this->primaryKey;
    $fillable = $this->fillable;
    $attributes = array_intersect_key($this->attributes, array_flip($fillable));
    $setClause = implode(" = ?, ", array_keys($attributes)) . " = ?";
    $values = array_values($attributes);
    $values[] = $this->attributes[$primaryKey];

    $query = "UPDATE $table SET $setClause WHERE $primaryKey = ?";
    self::$db->update($query, $values);
  }

  public function delete(): void
  {
    $table = $this->table;
    $primaryKey = $this->primaryKey;
    $id = $this->attributes[$primaryKey];
    $query = "DELETE FROM $table WHERE $primaryKey = ?";
    self::$db->delete($query, [$id]);
  }
  public function toJson(): string
  {
    return json_encode($this->attributes);
  }

  public function toArray(): array
  {
    return $this->attributes;
  }

  public function __get($key): ?string
  {
    return $this->attributes[$key] ?? null;
  }

  public function __set($key, $value): void
  {
    $this->attributes[$key] = $value;
  }

}