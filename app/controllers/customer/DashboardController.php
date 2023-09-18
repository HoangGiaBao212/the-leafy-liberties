<?php

namespace App\Controllers\Customer;

use App\Models\Order;
use App\Models\User;
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
    $successfulOrder = Order::findAll(["status" => "5"]);
    $pendingOrders = Order::findAll(["status" => "0"]);
    $customer = count(User::findAll(["deleted_at" => "null"]));
    $orders = count(Order::all());
    
    $response->setStatusCode(200);
    $response->setBody(
      View::renderWithLayout(
        new View("pages/dashboard/index"),
        [
          "title" => "Dashboard",
          "user" => $user,
          "successfulOrder" => $successfulOrder,
          "pendingOrders" => $pendingOrders,
          "customer" => $customer,
          "orders" => $orders
        ],
        "layouts/dashboard"
      )
    );
  }
  public function filter(Request $request, Response $response)
  {
  }
}
