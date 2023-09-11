<?php

namespace Core;

use Exception;

class View
{
  private string $name;

  public function __construct(string $name)
  {
    $this->name = $name;
  }

  public static function render(View $view, $params = []): string
  {
    $viewPath = self::getViewPath($view);
    if (!file_exists($viewPath)) {
      throw new Exception("View file $viewPath not found");
    }
    extract($params);
    ob_start();
    require_once $viewPath;
    $content = ob_get_clean();
    return $content;
  }

  public static function renderWithLayout(
    View $view,
    $params = [],
    $layout = "layouts/default"
  ): string {
    $layoutContent = self::render(new View($layout), $params);
    $content = self::render($view, $params);
    return str_replace("{{content}}", $content, $layoutContent);
  }

  public static function renderWithDashboardLayout(
    View $view,
    $params = [],
    $layout = "layouts/dashboard"
  ): string {
    $layoutContent = self::render(new View($layout), $params);
    $content = self::render($view, $params);
    return str_replace("{{content}}", $content, $layoutContent);
  }

  private static function getViewPath(View $view): string
  {
    $viewRoot = __DIR__ . "/../app/views/";
    $viewPath = str_replace("/", DIRECTORY_SEPARATOR, $view->name);
    return $viewRoot . $viewPath . ".php";
  }
}