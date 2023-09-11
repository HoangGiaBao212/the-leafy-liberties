<?php
namespace Core;

use Core\Application;
use Core\View;

class Controller
{
  protected array $middlewares = [];

  public function render(View $view, $params = [])
  {
    return Application::getInstance()
      ->getView()
      ->render($view, $params);
  }

  public function renderWithLayout(
    View $view,
    $params = [],
    $layout = "layouts/default"
  ) {
    return Application::getInstance()
      ->getView()
      ->renderWithLayout($view, $params, $layout);
  }

  public function addMiddleware($middleware)
  {
    $this->middlewares[] = $middleware;
  }

  public function getMiddlewares()
  {
    return $this->middlewares;
  }
}