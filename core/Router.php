<?php

namespace Core;

use Exception;

class Router
{
  private Request $request;
  private Response $response;
  private array $routes = [];

  public function __construct(Request &$request, Response &$response)
  {
    $this->request = $request;
    $this->response = $response;
  }

  public function get($path, $callback)
  {
    $this->routes["GET"][$path] = $callback;
  }

  public function post($path, $callback)
  {
    $this->routes["POST"][$path] = $callback;
  }

  public function getRoutes($method)
  {
    return $this->routes[$method] ?? [];
  }

  public function resolve()
  {
    try {
      $method = $this->request->getMethod();
      $path = $this->request->getPath();
      $callback = $this->routes[$method][$path] ?? false;
      if ($callback === false) {
        $this->response->setStatusCode(404);
        return $this->response->redirect(BASE_URI . "/", 404, [
          "toast" => [
            "message" => "Page not found",
            "type" => "error",
          ],
        ]);
      }

      if (is_array($callback)) {
        if (class_exists($callback[0]) === false) {
          throw new Exception("Class {$callback[0]} not found");
        }

        if (method_exists($callback[0], $callback[1]) === false) {
          throw new Exception("Method {$callback[1]} not found");
        }

        if (is_subclass_of($callback[0], "Core\Controller") === false) {
          throw new Exception(
            "Class {$callback[0]} must be a subclass of Core\Controller"
          );
        }

        $controller = new($callback[0])();
        $callback[0] = $controller;
      }
      call_user_func($callback, [&$this->request][0], [&$this->response][0]);
    } catch (Exception $e) {
      $this->response->setStatusCode(500);
      echo $e->getMessage();
    }
  }
}