<?php

namespace App\Controllers\Dashboard;

use App\Models\Permission;
use App\Models\User;
use App\Models\UserPermission;
use Core\Application;
use Core\Controller;
use Core\Database;
use Core\Request;
use Core\Response;
use Core\View;

class PermissionController extends Controller
{
  public function index(Request $request, Response $response)
  {
    $auth = Application::getInstance()->getAuthentication();
    if (!$auth->hasPermission('permission.access')) {
      return $response->redirect(BASE_URI . "/dashboard", 200, [
        "toast" => [
          "type" => "error",
          "message" => "You do not have permission to access this page.",
        ],
      ]);
    }

    $users = [];
    foreach (UserPermission::all() as $userPermission) {
      $user = User::findOne(["id" => $userPermission->user_id]);
      $users[] = $user;
    }

    $users = array_unique($users, SORT_REGULAR);

    $response->setBody(
      View::renderWithDashboardLayout(
        new View("pages/dashboard/permission/index"),
        [
          "title" => "Permissions",
          "users" => $users,
        ]
      )
    );
  }

  public function update(Request $request, Response $response)
  {
    $auth = Application::getInstance()->getAuthentication();
    if (!$auth->hasPermission('permission.update')) {
      return $response->redirect(BASE_URI . "/dashboard", 200, [
        "toast" => [
          "type" => "error",
          "message" => "You do not have permission to access this page.",
        ],
      ]);
    }
    switch ($request->getMethod()) {
      case 'GET':
        $user = User::findOne(["id" => $request->getQuery("id")]);

        if (!$user || !$user instanceof User) {
          return $response->redirect(BASE_URI . "/dashboard/permission", 200, [
            "toast" => [
              "type" => "error",
              "message" => "User not found.",
            ],
          ]);
        }

        $userPermissions = (function () use ($user) {
          $permissions = [];
          foreach ($user->permissions() as $userPermission) {
            $permissions[] = $userPermission->permission();
          }
          return $permissions;
        })();

        $permissions = Permission::all();
        $response->setStatusCode(200);
        return $response->setBody(
          View::renderWithDashboardLayout(
            new View("pages/dashboard/permission/update"),
            [
              "title" => "Update Permission",
              "user" => $user,
              "permissions" => $permissions,
              "userPermissions" => $userPermissions,
            ]
          )
        );
      case 'POST':
        Database::getInstance()->beginTransaction();
        $user = User::findOne(["id" => $request->getBody()["id"]]);

        if (!$user || !$user instanceof User) {
          return $response->redirect(BASE_URI . "/dashboard/permission", 200, [
            "toast" => [
              "type" => "error",
              "message" => "User not found.",
            ],
          ]);
        }

        foreach ($user->permissions() as $userPermission) {
          $userPermission->delete();
        }

        foreach ($request->getBody()["permissions"] as $permissionId) {
          $permission = Permission::findOne(["id" => $permissionId]);
          if ($permission && $permission instanceof Permission) {
            $user->addPermission($permission);
          }
        }
        Database::getInstance()->commitTransaction();

        return $response->redirect(BASE_URI . "/dashboard/permission", 200, [
          "toast" => [
            "type" => "success",
            "message" => "Permission updated.",
          ],
        ]);
      default:
        # code...
        break;
    }

  }
}