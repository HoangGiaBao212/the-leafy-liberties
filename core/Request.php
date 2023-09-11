<?php
namespace Core;

use Utils\DotEnv;

class Request
{
  private $method;
  private $url;
  private $params;
  private $query;
  private $body;
  private $headers;
  private $files;

  public function __construct()
  {
    $this->method = $_SERVER["REQUEST_METHOD"];
    $this->url = substr(
      $_SERVER["REQUEST_URI"],
      strlen(DotEnv::get("BASE_URI")),
      strlen($_SERVER["REQUEST_URI"])
    );
    $this->query = $this->sanitizeArray($_GET);
    $this->params = $this->sanitizeArray($_POST);
    $this->body = $this->parseRequestBody();
    $this->headers = $this->parseRequestHeaders();
    $this->files = $_FILES;
  }

  public function getMethod()
  {
    return $this->method;
  }

  public function getUrl()
  {
    return $this->url;
  }

  public function getPath()
  {
    $path = $this->url;
    $position = strpos($path, "?");
    if ($position === false) {
      return $path;
    }
    return substr($path, 0, $position);
  }

  public function getParam($key)
  {
    if (isset($this->params[$key])) {
      return $this->params[$key];
    } else {
      return null;
    }
  }

  public function getQuery($key)
  {
    if (isset($this->query[$key])) {
      return $this->query[$key];
    } else {
      return null;
    }
  }

  public function getBody()
  {
    return $this->body;
  }

  public function getHeader($key)
  {
    if (isset($this->headers[$key])) {
      return $this->headers[$key];
    } else {
      return null;
    }
  }

  public function getFile($key)
  {
    if (isset($this->files[$key])) {
      return $this->files[$key];
    } else {
      return null;
    }
  }

  public function getParams()
  {
    return $this->params;
  }

  public function getQueries()
  {
    return $this->query;
  }

  public function setParam($key, $value)
  {
    $this->params[$key] = $this->sanitizeInput($value);
  }

  public function setQuery($key, $value)
  {
    $this->query[$key] = $this->sanitizeInput($value);
  }

  public function setBody($body)
  {
    $this->body = $body;
  }

  private function parseRequestBody()
  {
    $contentType = isset($_SERVER["CONTENT_TYPE"])
      ? $_SERVER["CONTENT_TYPE"]
      : "";
    switch ($contentType) {
      case "application/json":
        return json_decode(file_get_contents("php://input"), true);
      case "application/x-www-form-urlencoded":
        return $this->sanitizeArray($_POST);
      default:
        return null;
    }
  }

  private function parseRequestHeaders()
  {
    $headers = [];
    foreach ($_SERVER as $key => $value) {
      if (substr($key, 0, 5) === "HTTP_") {
        $headers[
          str_replace(
            " ",
            "-",
            ucwords(str_replace("_", " ", strtolower(substr($key, 5))))
          )
        ] = $value;
      }
    }
    return $headers;
  }

  private function sanitizeArray($array)
  {
    $sanitized = [];
    foreach ($array as $key => $value) {
      $sanitized[$key] = $this->sanitizeInput($value);
    }
    return $sanitized;
  }

  private function sanitizeInput($input)
  {
    if (is_array($input)) {
      return $this->sanitizeArray($input);
    } else {
      return htmlspecialchars($input);
    }
  }
}