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

    $successfulOrder = Order::findAll(["status" => "5"]);
    $pendingOrders = Order::findAll(["status" => "0"]);
    $customer = count(User::findAll(["deleted_at" => "null"]));
    $orders = count(Order::all());
    $db = Database::getInstance();
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
    if ($request->getParam("filter-type") === "all") {
      $response->redirect(BASE_URI . "/dashboard");
    } else {

      $successfulOrders = Order::findAll(["status" => "5"]);

      $pendingOrderList = Order::findAll(["status" => "0"]);
      // $orders = count(Order::all());

      $successfulOrder = [];
      $newOrders = [];
      $pendingOrders = [];
      $customerList = [];
      $filterDate   =  $request->getParam("filter-date");
      $filterMonth = $request->getParam("filter-month");
      $filterYear = $request->getParam("filter-year");
      $dateToMatch = $filterYear . '-' . $filterMonth . '-' . $filterDate;

      $db = Database::getInstance();
      $top5prdSold = $db->select("SELECT p.id, p.name, SUM(op.quantity) AS total_quantity
      FROM products p
      JOIN orders_products op ON op.product_id = p.id
      JOIN orders o ON o.id = op.order_id
      WHERE o.status = 5
      AND YEAR(o.create_at) = ?
      AND MONTH(o.create_at) = ?
      AND DAY(o.create_at) = ?
      GROUP BY p.id, p.name
      ORDER BY total_quantity DESC
      LIMIT 5;", [
        $filterYear,
        $filterMonth,
        $filterDate,
      ]);

      $categorySold = $db->select("SELECT c.id AS category_id, COUNT(DISTINCT o.id) AS num_orders
      FROM categories c
      JOIN products_categories pc ON pc.category_id = c.id
      JOIN products p ON p.id = pc.product_id
      JOIN orders_products op ON op.product_id = p.id
      JOIN orders o ON o.id = op.order_id
      WHERE o.status = 5
      AND YEAR(o.create_at) = ?
      AND MONTH(o.create_at) = ?
      AND DAY(o.create_at) = ?
      GROUP BY c.id
      ORDER BY num_orders DESC
      LIMIT 6;", [
        $filterYear,
        $filterMonth,
        $filterDate,
      ]);

      $test = json_encode($top5prdSold);

      foreach ($successfulOrders as $order) {
        $orderDate = $order->create_at;

        if (substr($orderDate, 0, 10) === $dateToMatch) {
          $successfulOrder[] = $order;
        }
      }
      foreach ($pendingOrderList as $pendingOrder) {
        $pendingOrderDate = $pendingOrder->create_at;
        if (substr($pendingOrderDate, 0, 10) === $dateToMatch) {
          $pendingOrders[] = $pendingOrder;
        }
      }
      foreach (User::findAll(["deleted_at" => "null"]) as $customer) {
        if (substr($customer->created_at, 0, 10) === $dateToMatch) {
          $customerList[] = $customer;
        }
      }
      foreach (Order::findAll(["deleted_at" => "null"]) as $order) {
        if (substr($order->create_at, 0, 10) === $dateToMatch) {
          $newOrders[] = $order;
        }
      }

      $customer = count($customerList);
      $orders = count($newOrders);
      $response->setStatusCode(200);
      $response->setBody(
        View::renderWithLayout(
          new View("pages/dashboard/index"),
          [
            "title" => "Dashboard",
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
  }
}
