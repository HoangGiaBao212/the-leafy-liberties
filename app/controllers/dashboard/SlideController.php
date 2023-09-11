<?php
namespace App\Controllers\Dashboard;

use App\Models\Slide;
use Core\Application;
use Core\Controller;
use Core\Request;
use Core\Response;
use Core\View;
use Utils\FileUploader;

class SlideController extends Controller
{
  public function index(Request $request, Response $response)
  {
    $filter = [
      "keywords" => $request->getQuery("keywords"),
    ];
    $auth = Application::getInstance()->getAuthentication();
    if (!$auth->hasPermission("slide.access")) {
      return $response->redirect(BASE_URI . "/dashboard", 200, [
        "toast" => [
          "type" => "error",
          "message" => "You do not have permission to access this page.",
        ],
      ]);
    }
    if (isset($filter)) {
      $slides = Slide::filterAdvanced($filter);
    } else {
      $slides = Slide::all();

    }

    $response->setBody(
      View::renderWithDashboardLayout(new View("pages/dashboard/slide/index"), [
        'title' => 'Slides',
        "slides" => $slides,
        "filter" => $filter,
      ])
    );
  }

  public function show(Request $request, Response $response)
  {
  }
  public function update(Request $request, Response $response)
  {
    $auth = Application::getInstance()->getAuthentication();
    if (!$auth->hasPermission('slide.update')) {
      return $response->redirect(BASE_URI . '/dashboard', 200, [
        'toast' => [
          'type' => 'error',
          'message' => 'You do not have permission to access this page.'
        ]
      ]);
    }
    switch ($request->getMethod()) {
      case "GET":
        $slide = Slide::findOne(["id" => $request->getQuery("id")]);
        $response->setStatusCode(200);
        return $response->setBody(
          View::renderWithDashboardLayout(
            new View("pages/dashboard/slide/update"),
            [
              "title" => "Slide Update",
              "slide" => $slide,
            ]
          )
        );
      case "POST":
        $slide = Slide::find($request->getParam("id"));
        if (!$slide) {
          return $response->redirect(BASE_URI . "/dashboard/slide", 200, [
            "toast" => [
              "type" => "error",
              "message" => "Edit slide fail",
            ],
          ]);
        } else {
          $uploader = new FileUploader([
            "allowedExtensions" => ["jpeg", "jpg", "png"],
            "maxFileSize" => 2097152,
            "uploadPath" => "resources/images/slides/",
          ]);

          $result = $uploader->upload($_FILES["image"]);

          if ($result) {
            $request->setParam("image", $result);
          } else {
            return $response->setBody(
              View::renderWithDashboardLayout(new View("pages/dashboard/slide"), [
                "title" => "Slides",
                "toast" => [
                  "type" => "error",
                  "message" => "Edit slide failed!",
                ],
              ])
            );
          }

          $slide->name = $request->getParam("title");
          if (($request->getParam("image") == "Extension not allowed, please choose a jpeg, jpg, png file.") == false) {
            $slide->image = $request->getParam("old_img");
          } else {
            $slide->image = $request->getParam("image");
          }
          $slide->status = $request->getParam("status");
          $slide->save();
          return $response->redirect(BASE_URI . "/dashboard/slide", 200, [
            "toast" => [
              "type" => "success",
              "message" => "Edit slide successful",
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
    $slide = Slide::find($request->getBody()["id"]);
    if (!$slide) {
      $response->jsonResponse([
        "type" => "success",
        "message" => "Failed to remove slide removed from table",
      ]);
    }
    // dd($slide);
    $slide->delete();
    $response->jsonResponse([
      "type" => "success",
      "message" => "Slide has been removed from table",
    ]);
  }
}
