<?php
namespace App\Controllers\Auth;

use App\Models\Role;
use App\Models\User;
use Core\Application;
use Core\Controller;
use Core\Database;
use Core\Request;
use Core\Response;
use Core\View;
use Exception;
use Utils\Validation;

class RegisterController extends Controller
{
  public function index(Request $request, Response $response)
  {
    $response->setBody(
      View::renderWithLayout(new View("pages/auth/register"), [
        "header" => "",
        "footer" => "",
      ])
    );
  }

  public function register(Request $request, Response $response)
  {
    try {
      $email = Validation::validateEmail($request->getParam("email"));
      $name = Validation::validateName($request->getParam("name"));
      $phone = Validation::validatePhone($request->getParam("phone"));
      $password = Validation::validatePassword($request->getParam("password"));
    } catch (Exception $e) {
      return $response->redirect(BASE_URI . "/register", 200, [
        "toast" => [
          "type" => "error",
          "message" => $e->getMessage(),
        ],
      ]);
    }

    if (User::findOne(["email" => $email])) {
      $response->setStatusCode(200);
      return $response->redirect(BASE_URI . "/register", 200, [
        "toast" => [
          "type" => "error",
          "message" => "Email already exists",
        ],
      ]);
    }
    if (User::findOne(["phone" => $phone])) {
      $response->setStatusCode(200);
      return $response->redirect(BASE_URI . "/register", 200, [
        "toast" => [
          "type" => "error",
          "message" => "Phone already exists",
        ],
      ]);
    }
    Database::getInstance()->beginTransaction();

    $user = new User();
    $user->email = $email;
    $user->name = $name;
    $user->phone = $phone;
    $user->password = password_hash($password, PASSWORD_DEFAULT);
    $role = Role::findAll(["name" => "customer"])[0];
    if ($role == null) {
      Database::getInstance()->rollbackTransaction();
      return $response->redirect(BASE_URI . "/register", 200, [
        "toast" => [
          "type" => "error",
          "message" => "Something went wrong!",
        ],
      ]);
    }
    $user->setRole($role);
    $user->save();

    if (!$user->id) {
      Database::getInstance()->rollbackTransaction();
      $response->setStatusCode(200);
      return $response->redirect(BASE_URI . "/register", 200, [
        "toast" => [
          "type" => "error",
          "message" => "Registration failed",
        ],
      ]);
    }

    Application::getInstance()
      ->getAuthentication()
      ->setUser($user);

    $response->redirect(BASE_URI . "/", 200, [
      "toast" => [
        "type" => "success",
        "message" => "Registration success",
      ],
    ]);

    Database::getInstance()->commitTransaction();
  }
}