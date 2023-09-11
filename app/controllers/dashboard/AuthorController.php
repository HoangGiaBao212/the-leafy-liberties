<?php
namespace App\Controllers\Dashboard;

use App\Models\Author;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductTag;
use Core\Application;
use Core\Controller;
use Core\Request;
use Core\Response;
use Core\View;
use Utils\FileUploader;

class AuthorController extends Controller
{
  public function index(Request $request, Response $response)
  {
    $filter = [
      "keywords" => $request->getQuery("keywords"),
    ];
    $auth = Application::getInstance()->getAuthentication();
    if (!$auth->hasPermission("author.access")) {
      return $response->redirect(BASE_URI . "/dashboard", 200, [
        "toast" => [
          "type" => "error",
          "message" => "You do not have permission to access this page.",
        ],
      ]);
    }
    if (isset($filter)) {
      $authors = Author::filterAdvanced($filter);
    } else {
      $authors = Author::all();

    }
    return $response->setBody(
      View::renderWithDashboardLayout(
        new View("pages/dashboard/author/index"),
        [
          "title" => "Authors",
          "authors" => $authors,
          "filter" => $filter,
        ]
      )
    );
  }

  public function create(Request $request, Response $response)
  {
    $auth = Application::getInstance()->getAuthentication();
    if (!$auth->hasPermission("product.create")) {
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
            new View("pages/dashboard/author/create"),
            [
              "title" => "Add Author",
            ]
          )
        );
      case 'POST':
        $uploader = new FileUploader([
          "allowedExtensions" => ["jpeg", "jpg", "png"],
          "maxFileSize" => 2097152,
          "uploadPath" => "resources/images/authors/",
        ]);

        $result = $uploader->upload($_FILES["image"]);

        if ($result) {
          $request->setParam("image", $result);
        } else {
          return $response->redirect(BASE_URI . "/dashboard/author", 200, [
            "toast" => [
              "type" => "error",
              "message" => "Add author failed",
            ],
          ]);
        }
        if(Author::findOne(["name" => strtolower($request->getParam("name"))])){
          return $response->redirect(BASE_URI . '/dashboard/author/create',200,[
            "toast" => [
              "type" => "error",
              "message" => "This author is already exist!"
            ]
          ]);
        }
        $author = new Author();
        $author->name = $request->getParam("name");
        $author->image = $request->getParam("image");
        // echo $request->getParam("author-name");
        $author->save();
        return $response->redirect(BASE_URI . "/dashboard/author", 200, [
          "toast" => [
            "type" => "success",
            "message" => "Add author successful",
          ],
        ]);

    }
  }

  public function store(Request $request, Response $response)
  {
    $auth = Application::getInstance()->getAuthentication();
    if (!$auth->hasPermission("author.create")) {
      return $response->redirect(BASE_URI . "/dashboard", 200, [
        "toast" => [
          "type" => "error",
          "message" => "You do not have permission to access this page.",
        ],
      ]);
    }
    $author = new Author();
    // get from request
    $author->save();
    return $response->redirect(BASE_URI . "/dashboard/author");
  }

  public function show(Request $request, Response $response)
  {
    $auth = Application::getInstance()->getAuthentication();
    if (!$auth->hasPermission("author.access")) {
      return $response->redirect(BASE_URI . "/dashboard", 200, [
        "toast" => [
          "type" => "error",
          "message" => "You do not have permission to access this page.",
        ],
      ]);
    }
    $author = Author::find($request->getQuery("id"));
    if (!$author) {
      return $response->redirect(BASE_URI . "/dashboard/author");
    }
    return $response->setBody(
      View::renderWithDashboardLayout(
        new View("pages/dashboard/author/show"),
        [
          "title" => "Show author",
          "author" => $author,
        ]
      )
    );
  }

  public function update(Request $request, Response $response)
  {
    $auth = Application::getInstance()->getAuthentication();
    if (!$auth->hasPermission('author.update')) {
      return $response->redirect(BASE_URI . '/dashboard', 200, [
        'toast' => [
          'type' => 'error',
          'message' => 'You do not have permission to access this page.'
        ]
      ]);
    }
    switch ($request->getMethod()) {
      case "GET":
        $author = Author::findOne(["id" => $request->getQuery("id")]);
        $response->setStatusCode(200);
        return $response->setBody(
          View::renderWithDashboardLayout(
            new View("pages/dashboard/author/update"),
            [
              "title" => "authors",
              "author" => $author,
            ]
          )
        );
      case "POST":
        $author = Author::find($request->getParam("id"));
        if (!$author) {
          return $response->redirect(BASE_URI . "/dashboard/author", 200, [
            "toast" => [
              "type" => "error",
              "message" => "Edit author fail",
            ],
          ]);
        } else {
          $uploader = new FileUploader([
            "allowedExtensions" => ["jpeg", "jpg", "png"],
            "maxFileSize" => 2097152,
            "uploadPath" => "resources/images/authors/",
          ]);

          $result = $uploader->upload($_FILES["image"]);
          if ($result) {
            $request->setParam("image", $result);
          } else {
            return $response->setBody(
              View::renderWithDashboardLayout(new View("pages/dashboard/author"), [
                "title" => "authors",
                "toast" => [
                  "type" => "error",
                  "message" => "Edit author failed!",
                ],
              ])
            );
          }
          $author->name = $request->getParam("name");
          if (($request->getParam("image") == "Extension not allowed, please choose a jpeg, jpg, png file.") == false) {
            $author->image = $request->getParam("old_img");
          } else {
            $author->image = $request->getParam("image");
          }
          $author->save();
          return $response->redirect(BASE_URI . "/dashboard/author", 200, [
            "toast" => [
              "type" => "success",
              "message" => "Edit author successful",
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
    if (!$auth->hasPermission("author.delete")) {
      return $response->redirect(BASE_URI . "/dashboard", 200, [
        "toast" => [
          "type" => "error",
          "message" => "You do not have permission to access this page.",
        ],
      ]);
    }
    $author = Author::find($request->getBody()["id"]);
    if (!$author) {
      $response->jsonResponse([
        "type" => "success",
        "message" => "Failed to remove author removed from table",
      ]);
    }
    $products = Product::findAll(["author_id" => $author->id]);
    foreach ($products as $product) {
      $tags = ProductTag::findAll(["product_id" => $product->id]);
      $categories = ProductCategory::findAll(["product_id" => $product->id]);
      foreach ($categories as $category) {
        $category->delete();
      }
      foreach ($tags as $tag) {
        $tag->delete();
      }
      $product->delete();
    }
    $author->delete();
    $response->jsonResponse([
      "type" => "success",
      "message" => "Author removed from table",
    ]);
  }
}
