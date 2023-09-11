<?php
namespace App\Controllers\Dashboard;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\User;
use Core\Application;
use Core\Controller;
use Core\Request;
use Core\Response;
use Core\View;
use Utils\FileUploader;
use Utils\Validation;

class UserController extends Controller
{
  public function index(Request $request, Response $response)
  {
    $filter = [
      "keywords" => $request->getQuery("keywords"),
    ];
    $auth = Application::getInstance()->getAuthentication();
    if (!$auth->hasPermission('user.access')) {
      return $response->redirect(BASE_URI . '/dashboard');
    }
    if (isset($filter)) {
      $users = User::filterAdvanced($filter);
    } else {
      $users = User::all();

    }
    return $response->setBody(
      View::renderWithDashboardLayout(new View("pages/dashboard/user/index"), [
        "title" => "Users",
        "users" => $users,
        "filter" => $filter
      ])
    );
  }

  public function create(Request $request, Response $response)
  {
    $auth = Application::getInstance()->getAuthentication();
    if (!$auth->hasPermission('user.create')) {
      return $response->redirect(BASE_URI . "/dashboard", 200, [
        "toast" => [
          "type" => "error",
          "message" => "You do not have permission to access this page.",
        ],
      ]);
    }
    switch ($request->getMethod()) {
      case 'GET':
        $response->setStatusCode(200);
        return $response->setBody(
          View::renderWithDashboardLayout(
            new View("pages/dashboard/user/create"),
            [
              "title" => "Add User",
            ]
          )
        );
      case 'POST':
        $uploader = new FileUploader([
          "allowedExtensions" => ["jpeg", "jpg", "png"],
          "maxFileSize" => 2097152,
          "uploadPath" => "resources/images/user/",
        ]);

        $result = $uploader->upload($_FILES["image"]);

        if ($result) {
          $request->setParam("image", $result);
        } else {
          return $response->setBody(
            View::renderWithDashboardLayout(new View("pages/dashboard/user/index"), [
              "title" => "Users",
              "toast" => [
                "type" => "error",
                "message" => "Add user failed!",
              ],
            ])
          );
        }

        $user = new User();
        $users = User::all();
        if (User::findOne(["email" => $request->getParam("email")])) {
          $response->setStatusCode(200);
          return $response->redirect(BASE_URI . "/register", 200, [
            "toast" => [
              "type" => "error",
              "message" => "Email already exists",
            ],
          ]);
        }
        $user->email = $request->getParam("email");
        $user->name = $request->getParam("name");
        if (($request->getParam("image") == "Extension not allowed, please choose a jpeg, jpg, png file.") == false) {
          $user->image = NULL;
        } else {
          $user->image = $request->getParam("image");
        }
        $user->phone = $request->getParam("phone");
        $user->password = password_hash($request->getParam("password"), PASSWORD_DEFAULT);
        $user->role_id = $request->getParam("role");
        $user->status = 1;
        $user->save();
        return $response->redirect(BASE_URI . '/dashboard/user', 200, [
          "toast" => [
            "type" => "success",
            "message" => "Add user successful!"
          ]
        ]);
    }

  }

  public function store(Request $request, Response $response)
  {
    $auth = Application::getInstance()->getAuthentication();
    if (!$auth->hasPermission("user.create")) {
      return $response->redirect(BASE_URI . "/dashboard/user", 200, [
        "toast" => [
          "type" => "error",
          "message" => "You do not have permission to access this page.",
        ],
      ]);
    }
    $user = new User();
    // get from request
    $user->save();
    return $response->redirect(BASE_URI . "/dashboard/user");
  }

  public function show(Request $request, Response $response)
  {
    $auth = Application::getInstance()->getAuthentication();
    if (!$auth->hasPermission("user.access")) {
      return $response->redirect(BASE_URI . "/dashboard/user", 200, [
        "toast" => [
          "type" => "error",
          "message" => "You do not have permission to access this page.",
        ],
      ]);
    }
    $user = User::find($request->getQuery("id"));
    if (!$user) {
      return $response->redirect(BASE_URI . "/dashboard/user");
    }
    return $response->setBody(
      View::renderWithDashboardLayout(new View("pages/dashboard/user/show"), [
        "title" => "Show User",
        "user" => $user,
      ])
    );
  }

  public function update(Request $request, Response $response)
  {
    $auth = Application::getInstance()->getAuthentication();
    if (!$auth->hasPermission('user.update')) {
      return $response->redirect(BASE_URI . "/dashboard/user", 200, [
        "toast" => [
          "type" => "error",
          "message" => "You do not have permission to access this page.",
        ],
      ]);
    }
    switch ($request->getMethod()) {
      case "GET":
        $user = User::findOne(["id" => $request->getQuery("id")]);
        // dd($user->id);
        $response->setStatusCode(200);
        return $response->setBody(
          View::renderWithDashboardLayout(
            new View("pages/dashboard/user/update"),
            [
              "title" => "User Update",
              "user" => $user,
            ]
          )
        );
      case "POST":
        // dd($request->getParam("id"));
        $user = User::find($request->getParam("id"));
        if (!$user) {
          return $response->setBody(
            View::renderWithDashboardLayout(
              new View("pages/dashboard/user/index"),
              [
                "title" => "Users",
                "toast" => [
                  "type" => "error",
                  "message" => "Edit user fail!",
                ],
              ]
            )
          );
        } else {
          $uploader = new FileUploader([
            "allowedExtensions" => ["jpeg", "jpg", "png"],
            "maxFileSize" => 2097152,
            "uploadPath" => "resources/images/user/",
          ]);
          $result = $uploader->upload($_FILES["image"]);
          if ($result) {
            $request->setParam("image", $result);
          } else {
            return $response->setBody(
              View::renderWithDashboardLayout(new View("pages/dashboard/user"), [
                "title" => "Users",
                "toast" => [
                  "type" => "error",
                  "message" => "Edit user failed!",
                ],
              ])
            );
          }
          if (($request->getParam("image") == "Extension not allowed, please choose a jpeg, jpg, png file.") == false) {
            $user->image = $request->getParam("old_img");
          } else {
            $user->image = $request->getParam("image");
          }
          $user->email = ($request->getParam("email") != null && $request->getParam("email") != "") ? Validation::validateEmail($request->getParam("email")) : $user->email;
          $user->name = Validation::validateName($request->getParam("name"));
          $user->phone = Validation::validatePhone($request->getParam("phone"));
          $user->status = $request->getParam("status");
          if ($request->getParam("password") != null && $request->getParam("password") != "") {
            $user->password = password_hash(Validation::validatePassword($request->getParam("password")), PASSWORD_DEFAULT);
          }
          $user->role_id = $request->getParam("role");
          $user->save();
          return $response->redirect(BASE_URI . "/dashboard/user", 200, [
            "toast" => [
              "type" => "success",
              "message" => "Edit user successful.",
            ],
          ]);
        }
      default:
        break;
    }
  }

  public function delete(Request $request, Response $response)
  {
    $auth = Application::getInstance()->getAuthentication();
    if (!$auth->hasPermission("coupon.delete")) {
      return $response->redirect(BASE_URI . "/dashboard", 200, [
        "toast" => [
          "type" => "error",
          "message" => "You do not have permission to access this page.",
        ],
      ]);
    }
    $user = User::find($request->getBody()["id"]);

    if (!$user) {
      $response->jsonResponse([
        "type" => "success",
        "message" => "Failed to remove user removed from table",
      ]);
    }
    if ($user->email == $auth->getUser()->email) {
      $response->redirect(BASE_URI . '/dashboard/user', 200, [
        "toast" => [
          "type" => "error",
          "message" => "You can't delete the current login User!!"
        ]
      ]);
    } else {
      $orders = Order::findAll(["user_id" => $user->id]);
      foreach ($orders as $order) {
        $ordProducts = OrderProduct::findAll(["order_id" => $order->id]);
        foreach ($ordProducts as $ordProduct) {
          $ordProduct->delete();
        }
        $order->delete();
      }
      $user->delete();
    }
    // dd($user);
  }
}