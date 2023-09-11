<?php
namespace App\Controllers\Dashboard;

use App\Models\Permission;
use App\Models\Product;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\User;
use Core\Application;
use Core\Controller;
use Core\Database;
use Core\Request;
use Core\Response;
use Core\View;
use Exception;
use Utils\FileUploader;
use Utils\Validation;

class RoleController extends Controller
{
  public function index(Request $request, Response $response)
  {
    $filter = [
      "keywords" => $request->getQuery("keywords"),
    ];
    $auth = Application::getInstance()->getAuthentication();
    if (!$auth->hasPermission("role.access")) {
      return $response->redirect(BASE_URI . "/dashboard", 200, [
        "toast" => [
          "type" => "error",
          "message" => "You do not have permission to access this page.",
        ],
      ]);
    }
    if (isset($filter)) {
      $role = Role::filterAdvanced($filter);
    } else {
      $role = Role::all();

    }

    return $response->setBody(
      View::renderWithDashboardLayout(new View("pages/dashboard/role/index"), [
        "title" => "Roles",
        "roles" => $role,
        "filter" => $filter
      ])
    );
  }

  public function show(Request $request, Response $response)
  {
    $auth = Application::getInstance()->getAuthentication();
    if (!$auth->hasPermission("role.show")) {
      return $response->redirect(BASE_URI . "/dashboard", 200, [
        "toast" => [
          "type" => "error",
          "message" => "You do not have permission to access this page.",
        ],
      ]);
    }

    $role = Role::find($request->getQuery("id"));
    if (!$role || !$role instanceof Role) {
      return $response->redirect("/dashboard/roles");
    }

    $rolePermissions = (function () use ($role) {
      $permissions = [];
      foreach ($role->permissions() as $rolePermission) {
        if ($rolePermission->status == "1") {
          $permissions[] = $rolePermission->permission();
        }
      }
      return $permissions;
    })();

    return $response->setBody(
      View::renderWithDashboardLayout(new View("pages/dashboard/role/show"), [
        "title" => "Role",
        "role" => $role,
        "rolePermissions" => $rolePermissions,
      ])
    );
  }

  public function create(Request $request, Response $response)
  {
    $auth = Application::getInstance()->getAuthentication();
    if (!$auth->hasPermission("role.create")) {
      return $response->redirect(BASE_URI . "/dashboard", 200, [
        "toast" => [
          "type" => "error",
          "message" => "You do not have permission to access this page.",
        ],
      ]);
    }

    switch ($request->getMethod()) {
      case "POST":
        Database::getInstance()->beginTransaction();

        $role = new Role();
        try {
          $role->name = Validation::validateName($request->getParam("name"));
        } catch (Exception $e) {
          Database::getInstance()->rollbackTransaction();
          return $response->redirect("/dashboard/role/create", 200, [
            "toast" => [
              "type" => "error",
              "message" => $e->getMessage(),
            ],
          ]);
        }
        $role->save();

        $permissions = $request->getParam("permissions") ?? [];
        if (is_array($permissions)) {
          foreach ($permissions as $permission) {
            $permission = Permission::find($permission);
            $role->addPermission($permission);
          }
        }

        Database::getInstance()->commitTransaction();
        return $response->redirect(BASE_URI . "/dashboard/role", 200, [
          "toast" => [
            "type" => "success",
            "message" => "Add role successfull!!"
          ]
        ]);
      case "GET":
        $permissions = Permission::all();
        return $response->setBody(
          View::renderWithDashboardLayout(
            new View("pages/dashboard/role/create"),
            [
              "title" => "Create Role",
              "permissions" => $permissions,
            ]
          )
        );
      default:
        break;
    }
  }

  public function update(Request $request, Response $response)
  {
    $auth = Application::getInstance()->getAuthentication();
    if (!$auth->hasPermission("role.update")) {
      return $response->redirect(BASE_URI . "/dashboard", 200, [
        "toast" => [
          "type" => "error",
          "message" => "You do not have permission to access this page.",
        ],
      ]);
    }
    switch ($request->getMethod()) {
      case "GET":
        $role = Role::find($request->getQuery("id"));
        if (!$role || !$role instanceof Role) {
          return $response->redirect("/dashboard/roles");
        }

        $rolePermissions = (function () use ($role) {
          $permissions = [];
          foreach ($role->permissions() as $rolePermission) {
            // if ($rolePermission->status == "1") {
            $permissions[] = $rolePermission->permission();
            // }
          }
          return $permissions;
        })();

        $permissions = Permission::all();
        return $response->setBody(
          View::renderWithDashboardLayout(
            new View("pages/dashboard/role/update"),
            [
              "title" => "Update Role",
              "role" => $role,
              "permissions" => $permissions,
              "rolePermissions" => $rolePermissions,
            ]
          )
        );
      case "POST":
        Database::getInstance()->beginTransaction();
        $role = Role::find($request->getParam("id"));
        $permissions = $request->getParam("permissions") ?? [];

        foreach ($role->permissions() as $rolePermission) {
          $rolePermission->delete();
        }

        foreach ($permissions as $permission) {
          $permission = Permission::findOne(["id" => $permission]);
          if ($permission)
            $role->addPermission($permission);
        }
        Database::getInstance()->commitTransaction();

        return $response->redirect(BASE_URI . "/dashboard/role", 200, [
          "toast" => [
            "type" => "success",
            "message" => "Update role successfull",
          ],
        ]);
    }
  }

  public function delete(Request $request, Response $response)
  {
    $auth = Application::getInstance()->getAuthentication();
    if (!$auth->hasPermission("role.delete")) {
      return $response->redirect(BASE_URI . "/dashboard", 200, [
        "toast" => [
          "type" => "error",
          "message" => "You do not have permission to access this page.",
        ],
      ]);
    }

    switch ($request->getMethod()) {
      case "GET":
        $role = Role::find($request->getQuery("id"));
        if (!$role || !$role instanceof Role) {
          return $response->redirect("/dashboard/role");
        }

        $rolePermissions = (function () use ($role) {
          $permissions = [];
          foreach ($role->permissions() as $rolePermission) {
            if ($rolePermission->status == "1") {
              $permissions[] = $rolePermission->permission();
            }
          }
          return $permissions;
        })();

        $permissions = Permission::all();
        return $response->setBody(
          View::renderWithDashboardLayout(
            new View("pages/dashboard/role/delete"),
            [
              "title" => "Delete Role",
              "role" => $role,
              "permissions" => $permissions,
              "rolePermissions" => $rolePermissions,
            ]
          )
        );
      case "POST":

        Database::getInstance()->beginTransaction();
        $role = Role::find($request->getParam("id"));
        dd($role);
        $newRole = Role::find($request->getParam("new-id"));

        $users = User::findAll(["role_id" => $role->id]);
        foreach ($users as $user) {
          $user->role_id = $newRole->id;
          $user->save();
        }

        $rolePermissions = RolePermission::findAll(["role_id" => $role->id]);
        foreach ($rolePermissions as $rolePermission) {
          $rolePermission->delete();
        }

        $role->delete();

        // check if role is deleted
        $role = Role::find($request->getParam("id"));
        if ($role) {
          Database::getInstance()->rollbackTransaction();
          return $response->redirect(BASE_URI . "/dashboard/role", 200, [
            "toast" => [
              "type" => "error",
              "message" => "Delete role failed",
            ],
          ]);
        }

        Database::getInstance()->commitTransaction();
        return $response->redirect(BASE_URI . "/dashboard/role", 200, [
          "toast" => [
            "type" => "success",
            "message" => "Delete role ",
          ],
        ]);
    }
  }
}
