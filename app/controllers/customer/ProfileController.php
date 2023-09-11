<?php

namespace App\Controllers\Customer;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\User;
use Core\Application;
use Core\Controller;
use Core\Request;
use Core\Response;
use Core\View;
use DateTime;
use Exception;
use Utils\FileUploader;
use Utils\Validation;

class ProfileController extends Controller
{
  public function index(Request $request, Response $response)
  {
    $auth = Application::getInstance()->getAuthentication();

    if (!$auth->isAuthenticated()) {
      $response->redirect(BASE_URI . "/login");
    }
    $user = Application::getInstance()
      ->getAuthentication()
      ->getUser();
    $response->setStatusCode(200);
    $response->setBody(
      View::renderWithLayout(new View("pages/profile/index"), [
        "user" => $user,
      ])
    );
  }

  public function update(Request $request, Response $response)
  {
    $auth = Application::getInstance()->getAuthentication();
    if (!$auth->isAuthenticated()) {
      $response->redirect("/login");
    }
    $user = $auth->getUser();
    $uploader = new FileUploader([
      "allowedExtensions" => ["jpeg", "jpg", "png"],
      "maxFileSize" => 2097152,
      "uploadPath" => "resources/images/user/",
    ]);
    $result = $uploader->upload($_FILES["avatar"]);
    if ($result) {
      $request->setParam("avatar", $result);
    } else {
      return $response->setBody(
        View::renderWithDashboardLayout(new View("pages/profile"), [
          "title" => "Profile",
          "toast" => [
            "type" => "error",
            "message" => "Edit profile failed!",
          ],
        ])
      );
    }
    $user->image = $request->getParam("avatar");
    $user->address = $request->getParam("address");
    if ($request->getParam("gender") == "male") {
      $user->gender = 1;
    } else if ($request->getParam("gender") == "female") {
      $user->gender = 2;
    } else {
      $user->gender = 0;
    }
    try {
      $user->phone = Validation::validatePhone($request->getParam("phone"));
      $user->name = Validation::validateName($request->getParam("name"));
      $user->email = Validation::validateEmail($request->getParam('email'));
      $password = Validation::validatePassword($request->getparam('current-password'));
      if (!password_verify($password, $user->password)) {
        $response->setStatusCode(200);
        return $response->redirect(BASE_URI . "/profile", 200, [
          "toast" => [
            "type" => "error",
            "message" => "Current password is wrong",
          ],
        ]);
      }
    } catch (\Exception $e) {
      return $response->redirect(BASE_URI . "/profile", 200, [
        "toast" => [
          "type" => "error",
          "message" => $e->getMessage(),
        ],
      ]);
    }

    $user->save();
    $response->redirect(BASE_URI . "/profile", 200, [
      "toast" => [
        "type" => "success",
        "message" => "Update profile successfully",
      ],
    ]);
  }

  public function settings(Request $request, Response $response)
  {
    $auth = Application::getInstance()->getAuthentication();

    if (!$auth->isAuthenticated()) {
      $response->redirect(BASE_URI . "/login");
    }
    $user = Application::getInstance()
      ->getAuthentication()
      ->getUser();
    $response->setStatusCode(200);
    $response->setBody(
      View::renderWithLayout(new View("pages/profile/settings"), [
        "user" => $user,
      ])
    );
  }

  public function changePassword(Request $request, Response $response)
  {
    $auth = Application::getInstance()->getAuthentication();

    if (!$auth->isAuthenticated()) {
      $response->redirect(BASE_URI . "/login");
    }
    $user = Application::getInstance()
      ->getAuthentication()
      ->getUser();

    try {
      $password = Validation::validatePassword($request->getparam('current-password'));
      if (!password_verify($password, $user->password)) {
        $response->setStatusCode(200);
        return $response->redirect(BASE_URI . "/profile", 200, [
          "toast" => [
            "type" => "error",
            "message" => "Current password is wrong",
          ],
        ]);
      } else {
        $newPassword = Validation::validatePassword($request->getparam('new-password'));
        $confirmPassword = Validation::validatePassword($request->getparam('confirm-new-password'));
        if ($newPassword != $confirmPassword) {
          $response->setStatusCode(200);
          return $response->redirect(BASE_URI . "/profile", 200, [
            "toast" => [
              "type" => "error",
              "message" => "New password and confirm password do not match",
            ],
          ]);
        }
        $user->password = password_hash(Validation::validatePassword($request->getparam('new-password')), PASSWORD_DEFAULT);
        $user->save();
        $response->setStatusCode(200);
        return $response->redirect(BASE_URI . "/profile", 200, [
          "toast" => [
            "type" => "success",
            "message" => "Update password successfully",
          ],
        ]);
      }
    } catch (\Exception $e) {
      return $response->redirect(BASE_URI . "/profile", 200, [
        "toast" => [
          "type" => "error",
          "message" => $e->getMessage(),
        ],
      ]);
    }
    $password = $request->getParam("password");
    $user->password = password_hash($password, PASSWORD_DEFAULT);
    $user->save();
    $response->redirect(BASE_URI . "/profile", 200, [
      "toast" => [
        "type" => "success",
        "message" => "Update successfully",
      ],
    ]);
  }
  public function orders(Request $request, Response $response)
  {
    $auth = Application::getInstance()->getAuthentication();

    if (!$auth->isAuthenticated()) {
      $response->redirect(BASE_URI . "/login");
    }
    $user = Application::getInstance()
      ->getAuthentication()
      ->getUser();

    $orders = $user->orders();

    $response->setStatusCode(200);
    $response->setBody(
      View::renderWithLayout(new View("pages/profile/orders"), [
        "orders" => $orders,
        "user" => $user,
      ])
    );
  }

  public function orderDetail(Request $request, Response $response)
  {
    switch ($request->getMethod()) {
      case "GET":
        $user = Application::getInstance()
          ->getAuthentication()
          ->getUser();

        $order = Order::findOne(["id" => $request->getQuery("id")]);
        $response->setStatusCode(200);
        return $response->setBody(
          View::renderWithLayout(
            new View("pages/profile/orderDetail"),
            [
              "title" => "Order Detail Update",
              "user" => $user,
              "order" => $order,
            ]
          )
        );
      case "POST":
        dd($request->getParam("id"));
        $order = Order::find($request->getParam("id"));
        if (!$order) {
          return $response->setBody(
            View::renderWithDashboardLayout(
              new View("pages/profile/orders"),
              [
                "title" => "Users",
                "toast" => [
                  "type" => "error",
                  "message" => "Edit account fail!",
                ],
              ]
            )
          );
        }
      default:
        break;
    }
  }
  public function delete(Request $request, Response $response)
  {
    $order = Order::find($request->getQuery("id"));
    if (!$order) {
      return $response->redirect(BASE_URI . "/profile");
    }

    switch ($request->getMethod()) {
      case "GET":
        $response->setStatusCode(200);
        $order_products = OrderProduct::all();
        foreach ($order_products as $order_product) {
          if ($order_product->order_id == $order->id) {
            $order_product->delete();
          }
        }
        $order->delete();
        return $response->redirect(BASE_URI . "/profile/orders", 200, [
          "toast" => [
            "type" => "success",
            "message" => "Successfully removed from order ",
          ],
        ]);
    }
  }
}