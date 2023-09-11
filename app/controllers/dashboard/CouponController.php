<?php
namespace App\Controllers\Dashboard;

use App\Models\Coupon;
use Core\Application;
use Core\Controller;
use Core\Request;
use Core\Response;
use Core\View;

class CouponController extends Controller
{
  public function index(Request $request, Response $response)
  {
    $filter = [
      "keywords" => $request->getQuery("keywords"),
    ];
    $auth = Application::getInstance()->getAuthentication();
    if (!$auth->hasPermission('coupon.access')) {
      return $response->redirect(BASE_URI . "/dashboard/coupon", 200, [
        "toast" => [
          "type" => "error",
          "message" => "You do not have permission to access this page.",
        ],
      ]);
    }
    if (isset($filter)) {
      $coupons = Coupon::filterAdvanced($filter);
    } else {
      $coupons = Coupon::all();

    }
    return $response->setBody(
      View::renderWithDashboardLayout(
        new View("pages/dashboard/coupon/index"),
        [
          "title" => "Coupons",
          "coupons" => $coupons,
          "filter" => $filter
        ]
      )
    );
  }

  public function create(Request $request, Response $response)
  {
    $auth = Application::getInstance()->getAuthentication();
    if (!$auth->hasPermission("coupon.create")) {
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
            new View("pages/dashboard/coupon/create"),
            [
              "title" => "Add Coupon",
            ]
          )
        );
      case 'POST':
        $coupon = new Coupon();
        if (Coupon::findOne(["code" => $request->getParam("code")])) {
          return $response->redirect(BASE_URI . '/dashboard/coupon/create', 200, [
            "toast" => [
              "type" => "error",
              "message" => "This code is already exist!!"
            ]
          ]);
        }
        $coupon->code = strtoupper($request->getParam("code"));
        $coupon->description = strtoupper($request->getParam("description"));
        $coupon->expired = $request->getParam("expired");
        $coupon->quantity = $request->getParam("quantity");
        $coupon->save();
        return $response->redirect(BASE_URI . "/dashboard/coupon", 200, [
          "toast" => [
            "type" => "success",
            "message" => "Add coupon successful",
          ],
        ]);

    }
  }

  public function store(Request $request, Response $response)
  {
    $auth = Application::getInstance()->getAuthentication();
    if (!$auth->hasPermission("coupon.create")) {
      return $response->redirect(BASE_URI . "/dashboard/coupon", 200, [
        "toast" => [
          "type" => "error",
          "message" => "You do not have permission to access this page.",
        ],
      ]);
    }
    $coupon = new Coupon();
    // get from request
    $coupon->save();
    return $response->redirect(BASE_URI . "/dashboard/coupon/index");
  }

  public function show(Request $request, Response $response)
  {
    $auth = Application::getInstance()->getAuthentication();
    if (!$auth->hasPermission("coupon.access")) {
      return $response->redirect(BASE_URI . "/dashboard", 200, [
        "toast" => [
          "type" => "error",
          "message" => "You do not have permission to access this page.",
        ],
      ]);
    }
    $coupon = Coupon::find($request->getQuery("id"));
    if (!$coupon) {
      return $response->redirect(BASE_URI . "/dashboard/coupon/index");
    }
    return $response->setBody(
      View::renderWithDashboardLayout(new View("pages/dashboard/coupon/show"), [
        "title" => "Show Coupon",
        "coupon" => $coupon,
      ])
    );
  }

  public function update(Request $request, Response $response)
  {
    $auth = Application::getInstance()->getAuthentication();
    if (!$auth->hasPermission("coupon.update")) {
      return $response->redirect(BASE_URI . "/dashboard", 200, [
        "toast" => [
          "type" => "error",
          "message" => "You do not have permission to access this page.",
        ],
      ]);
    }
    switch ($request->getMethod()) {
      case "GET":
        $coupon = Coupon::findOne(["id" => $request->getQuery("id")]);
        // dd($request->getQueries());
        $response->setStatusCode(200);
        return $response->setBody(
          View::renderWithDashboardLayout(
            new View("pages/dashboard/coupon/update"),
            [
              "title" => "Coupon Dashboard",
              "coupon" => $coupon,
            ]
          )
        );
      case "POST":
        $coupon = Coupon::find($request->getParam("id"));
        if (!$coupon) {
          return $response->redirect(BASE_URI . "/dashboard/coupon", 200, [
            "toast" => [
              "type" => "error",
              "message" => "Edit coupon fail",
            ],
          ]);
        } else {
          // dd($coupon);
          if (Coupon::findOne(["code" => strtoupper($request->getParam("code"))])) {
            return $response->redirect(BASE_URI . '/dashboard/coupon/update?id=' . $coupon->id, 200, [
              "toast" => [
                "type" => "error",
                "message" => "This code is already exist!!"
              ]
            ]);
          }
          $coupon->code = strtoupper($request->getParam("code"));
          $coupon->expired = $request->getParam("expired");
          $coupon->quantity = $request->getParam("quantity");
          $coupon->description = strtoupper($request->getParam("description"));
          $coupon->save();
          return $response->redirect(BASE_URI . "/dashboard/coupon", 200, [
            "toast" => [
              "type" => "success",
              "message" => "Edit coupon success",
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
    $coupon = Coupon::find($request->getBody()["id"]);
    if (!$coupon) {
      $response->jsonResponse([
        "type" => "success",
        "message" => "Failed to remove coupon removed from table",
      ]);
    }
    // dd($coupon);
    $coupon->delete();
    $response->jsonResponse([
      "type" => "success",
      "message" => "Coupon removed from table",
    ]);
  }
}
