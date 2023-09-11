<?php
namespace App\Controllers\Customer;

use Core\Application;
use Core\Controller;
use Core\Request;
use Core\Response;
use Core\View;

class DashboardController extends Controller
{
  public function index(Request $request, Response $response)
  {
    $auth = Application::getInstance()->getAuthentication();

    if (
      !$auth->isAuthenticated() ||
      !$auth->hasPermission("dashboard.access")
    ) {
      $response->redirect(BASE_URI . "/login");
      return;
    }

    $user = $auth->getUser();

    // todo: get list permission contain '.access'

    $response->setStatusCode(200);
    $response->setBody(
      View::renderWithLayout(
        new View("pages/dashboard/index"),
        [
          "title" => "Dashboard",
          "user" => $user,
        ],
        "layouts/dashboard"
      )
    );
  }
}