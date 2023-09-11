<?php

namespace App\Controllers\Dashboard;

use App\Models\Setting;
use Core\Application;
use Core\Controller;
use Core\Request;
use Core\Response;
use Core\View;

class SettingController extends Controller
{
  public function index(Request $request, Response $response)
  {
    $auth = Application::getInstance()->getAuthentication();
    if (!$auth->hasPermission("setting.access")) {
      return $response->redirect(BASE_URI . "/dashboard", 200, [
        "toast" => [
          "type" => "error",
          "message" => "You do not have permission to access this page.",
        ],
      ]);
    }
    $settings = Setting::all();
    return $response->setBody(
      View::renderWithDashboardLayout(new View("pages/dashboard/setting/index"), [
        "title" => "Settings",
        "settings" => $settings,
      ])
    );
  }

  public function show(Request $request, Response $response)
  {
  }

  public function create(Request $request, Response $response)
  {
  }

  public function store(Request $request, Response $response)
  {
  }

  public function update(Request $request, Response $response)
  {
    switch ($request->getMethod()) {
      case "GET":
        $setting = setting::findOne(["id" => $request->getQuery("id")]);
        // dd($request->getQueries());
        $response->setStatusCode(200);
        return $response->setBody(
          View::renderWithDashboardLayout(
            new View("pages/dashboard/setting/update"),
            [
              "title" => "setting Update",
              "setting" => $setting,
            ]
          )
        );
      case "POST":
        $setting = Setting::find($request->getParam("id"));
        if (!$setting) {
          return $response->setBody(
            View::renderWithDashboardLayout(
              new View("pages/dashboard/setting/index"),
              [
                "title" => "settings",
                "toast" => [
                  "type" => "error",
                  "message" => "Edit account fail!",
                ],
              ]
            )
          );
        } else {
          $setting->name = $request->getParam("name");
          $setting->value = $request->getParam("value");
          $setting->save();
          return $response->setBody(
            View::renderWithDashboardLayout(
              new View("pages/dashboard/setting/index"),
              [
                "title" => "settings",
                "toast" => [
                  "type" => "success",
                  "message" => "Edit account successful!",
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
    $auth = Application::getInstance()->getAuthentication();
    if (!$auth->hasPermission("coupon.delete")) {
      return $response->redirect(BASE_URI . "/dashboard", 200, [
        "toast" => [
          "type" => "error",
          "message" => "You do not have permission to access this page.",
        ],
      ]);
    }
    $setting = Setting::find($request->getBody()["id"]);
    if (!$setting) {
      $response->jsonResponse([
        "type" => "success",
        "message" => "Failed to remove setting removed from table",
      ]);
    }
    // dd($setting);
    $setting->delete();
    $response->jsonResponse([
      "type" => "success",
      "message" => "Setting has been removed from table",
    ]);
  }
}