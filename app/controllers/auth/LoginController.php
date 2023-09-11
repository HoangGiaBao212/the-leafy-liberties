<?php
namespace App\Controllers\Auth;

use App\Models\User;
use Core\Application;
use Core\Controller;
use Core\Request;
use Core\Response;
use Core\View;
use Exception;
use Utils\Validation;

class LoginController extends Controller
{
  public function index(Request $request, Response $response)
  {
    $response->setStatusCode(200);
    $response->setBody(
      View::renderWithLayout(new View("pages/auth/login"), [
        "header" => "",
        "footer" => "",
      ])
    );
  }

  public function login(Request $request, Response $response)
  {
    try {
      $email = Validation::validateEmail($request->getParam("email"));
      $password = Validation::validatePassword($request->getParam("password"));
    } catch (Exception $e) {
      return $response->redirect(BASE_URI . "/login", 200, [
        "toast" => [
          "type" => "error",
          "message" => $e->getMessage(),
        ],
      ]);
    }

    $user = User::findOne(["email" => $email]);

    if (!$user) {
      return $response->redirect(BASE_URI . "/login", 200, [
        "toast" => [
          "type" => "error",
          "message" => "Email not found",
        ],
      ]);
    }

    if ($user->status == 0) {
      return $response->redirect(BASE_URI . "/login", 200, [
        "toast" => [
          "type" => "error",
          "message" => "You have been banned",
        ],
      ]);
    } else {
      if (password_verify($password, $user->password)) {
        Application::getInstance()
          ->getAuthentication()
          ->setUser($user);
        return $response->redirect(BASE_URI . "/", 200, [
          "toast" => [
            "type" => "success",
            "message" => "Login success",
          ],
        ]);
      } else {
        return $response->redirect(BASE_URI . "/login", 200, [
          "toast" => [
            "type" => "error",
            "message" => "Password incorrect",
          ],
        ]);
      }
    }
  }

  public function logout(Request $request, Response $response)
  {
    Application::getInstance()
      ->getSession()
      ->destroy();
    $response->redirect(BASE_URI . "/", 200, [
      "toast" => [
        "type" => "success",
        "message" => "Logout success",
      ],
    ]);
  }
}