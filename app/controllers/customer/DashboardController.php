<?php

namespace App\Controllers\Customer;

use App\Models\Order;
use App\Models\User;
use Core\Application;
use Core\Controller;
use Core\Database;
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
    $db = Database::getInstance();
    //top 5 product sold in one month
    $top5prdSold = $db->select("SELECT p.id, p.name, SUM(op.quantity) AS total_quantity
      FROM products p
      JOIN orders_products op ON op.product_id = p.id
      JOIN orders o ON o.id = op.order_id
      WHERE o.status = 5
      AND YEAR(o.create_at) = YEAR(CURRENT_DATE)
      AND MONTH(o.create_at) = MONTH(CURRENT_DATE)
      GROUP BY p.id, p.name
      ORDER BY total_quantity DESC
      LIMIT 5;", []);

    $test = json_encode($top5prdSold);

    // //most sold by category query
    $categorySold = $db->select("SELECT c.id AS category_id, COUNT(DISTINCT o.id) AS num_orders
      FROM categories c
      JOIN products_categories pc ON pc.category_id = c.id
      JOIN products p ON p.id = pc.product_id
      JOIN orders_products op ON op.product_id = p.id
      JOIN orders o ON o.id = op.order_id
      WHERE o.status = 5
      GROUP BY c.id
      ORDER BY num_orders DESC
      LIMIT 6;", []);
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
          "orders" => $orders,
          "top5prdSold" => $top5prdSold,
          "categorySold" => $categorySold,
          "test" => $test,
        ],
        "layouts/dashboard"
      )
    );
  }
  public function filter(Request $request, Response $response)
  {
  }
}
