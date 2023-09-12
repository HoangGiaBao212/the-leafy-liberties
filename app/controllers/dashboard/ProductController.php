<?php
namespace App\Controllers\Dashboard;

use App\Models\Author;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductTag;
use App\Models\Tag;
use Core\Application;
use Core\Controller;
use Core\Database;
use Core\Request;
use Core\Response;
use Core\View;
use Utils\FileUploader;

class ProductController extends Controller
{
  public function index(Request $request, Response $response)
  {
    $filter = [
      "categories" => $request->getQuery("categories") ?? [],
      "tags" => $request->getQuery("tags") ?? [],
      "author" => $request->getQuery("author"),
      "price" => [
        "min" => $request->getQuery("min-price"),
        "max" => $request->getQuery("max-price"),
      ],
      "keywords" => $request->getQuery("keywords"),
      "order-by" => $request->getQuery("order-by") ?? "name",
      "order-direction" => $request->getQuery("order-direction") ?? "asc",
    ];

    $auth = Application::getInstance()->getAuthentication();
    if (!$auth->hasPermission("product.access")) {
      return $response->redirect(BASE_URI . "/dashboard", 200, [
        "toast" => [
          "type" => "error",
          "message" => "You do not have permission to access this page.",
        ],
      ]);
    }
    if (isset($filter)) {
      $products = Product::filterAdvanced($filter);
    } else {
      $products = Product::all();
    }
    return $response->setBody(
      View::renderWithDashboardLayout(
        new View("pages/dashboard/product/index"),
        [
          "title" => "Products",
          "products" => $products,
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
            new View("pages/dashboard/product/create"),
            [
              "title" => "Add Product",
            ]
          )
        );
      case 'POST':
        Database::getInstance()->beginTransaction();
        $uploader = new FileUploader([
          "allowedExtensions" => ["jpeg", "jpg", "png"],
          "maxFileSize" => 2097152,
          "uploadPath" => "resources/images/products/",
        ]);

        $result = $uploader->upload($_FILES["image"]);

        if ($result) {
          $request->setParam("image", $result);
        } else {
          return $response->redirect(BASE_URI . "/dashboard/product", 200, [
            "toast" => [
              "type" => "error",
              "message" => "Add product failed",
            ],
          ]);
        }
        $product = new Product();
        $products = Product::all();
        foreach ($products as $item) {
          if ($item->isbn == $product->isbn) {
            return $response->redirect(BASE_URI . "/dashboard/product/create", 200, [
              "toast" => [
                "type" => "error",
                "message" => "This ISBN number is already exist!",
              ],
            ]);
          } else {
            $product->isbn = $request->getParam("isbn");
            break;
          }
        }
        $product->name = $request->getParam("name");
        $product->image = $request->getParam("image");
        $product->price = $request->getParam("price");
        $product->description = $request->getParam("description");
        $product->quantity = $request->getParam("quantity");
        if ($request->getParam("author") != null) {
          $product->author_id = $request->getParam("author");
        }
        echo $request->getParam("author-name");
        if ($request->getParam("author-name") != null) {
          $author = new Author();
          $author->name = $request->getParam("author-name");
          $author->save();
          $product->author_id = $author->id;
        }
        $product->publisher_id = $request->getParam("publisher");
        $product->save();

        $categories = $request->getParam('categories');
        $product->removeAllCategories();
        foreach ($categories as $category_id) {
          $product->addCategory(Category::findOne(["id" => $category_id]));
        }

        $tags = $request->getParam('tags');
        $product->removeAllTags();
        foreach ($tags as $tag_id) {
          $product->addTag(Tag::findOne(["id" => $tag_id]));
        }

        Database::getInstance()->commitTransaction();
        return $response->redirect(BASE_URI . "/dashboard/product", 200, [
          "toast" => [
            "type" => "success",
            "message" => "Add product successful",
          ],
        ]);
    }
  }

  public function store(Request $request, Response $response)
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
    $product = new Product();
    // get from request
    $product->save();
    return $response->redirect(BASE_URI . "/dashboard/products");
  }

  public function show(Request $request, Response $response)
  {
    $auth = Application::getInstance()->getAuthentication();
    if (!$auth->hasPermission("product.access")) {
      return $response->redirect(BASE_URI . "/dashboard", 200, [
        "toast" => [
          "type" => "error",
          "message" => "You do not have permission to access this page.",
        ],
      ]);
    }
    $product = Product::find($request->getQuery("id"));
    if (!$product) {
      return $response->redirect(BASE_URI . "/dashboard/products");
    }
    return $response->setBody(
      View::renderWithDashboardLayout(
        new View("pages/dashboard/product/show"),
        [
          "title" => "Show Product",
          "product" => $product,
        ]
      )
    );
  }

  public function update(Request $request, Response $response)
  {
    $auth = Application::getInstance()->getAuthentication();
    if (!$auth->hasPermission('product.update')) {
      return $response->redirect(BASE_URI . '/dashboard', 200, [
        'toast' => [
          'type' => 'error',
          'message' => 'You do not have permission to access this page.'
        ]
      ]);
    }
    switch ($request->getMethod()) {
      case "GET":
        $product = Product::findOne(["id" => $request->getQuery("id")]);
        $response->setStatusCode(200);
        return $response->setBody(
          View::renderWithDashboardLayout(
            new View("pages/dashboard/product/update"),
            [
              "title" => "Products",
              "product" => $product,
            ]
          )
        );
      case "POST":
        $product = Product::find($request->getParam("id"));
        if (!$product || !($product instanceof Product)) {
          return $response->redirect(BASE_URI . "/dashboard/product", 200, [
            "toast" => [
              "type" => "error",
              "message" => "Edit product fail",
            ],
          ]);
        } else {
          $uploader = new FileUploader([
            "allowedExtensions" => ["jpeg", "jpg", "png"],
            "maxFileSize" => 2097152,
            "uploadPath" => "resources/images/products/",
          ]);

          $result = $uploader->upload($_FILES["image"]);
          if ($result) {
            $request->setParam("image", $result);
          } else {
            return $response->setBody(
              View::renderWithDashboardLayout(new View("pages/dashboard/product"), [
                "title" => "Products",
                "toast" => [
                  "type" => "error",
                  "message" => "Edit product failed!",
                ],
              ])
            );
          }
          $product->name = $request->getParam("name");
          if (($request->getParam("image") == "Extension not allowed, please choose a jpeg, jpg, png file.") == false) {
            $product->image = $request->getParam("old_img");
          } else {
            $product->image = $request->getParam("image");
          }
          if (Product::findOne(["isbn" => $request->getParam("isbn")]) != null && Product::findOne(["isbn" => $request->getParam("isbn")])->isbn != $product->isbn) {
            return $response->redirect(BASE_URI . "/dashboard/product/update?id=" . $product->id, 200, [
              "toast" => [
                "type" => "error",
                "message" => "ISBN number is already exist!!"
              ]
            ]);
          }
          $product->isbn = $request->getParam("isbn");
          $product->price = $request->getParam("price");
          $product->description = $request->getParam("description");
          $product->quantity = $request->getParam("quantity");
          $product->author_id = $request->getParam("author");
          $product->publisher_id = $request->getParam("publisher");

          $categories = $request->getParam('categories');
          $product->removeAllCategories();
          foreach ($categories as $category_id) {
            $product->addCategory(Category::findOne(["id" => $category_id]));
          }

          $tags = $request->getParam('tags');
          $product->removeAllTags();
          foreach ($tags as $tag_id) {
            $product->addTag(Tag::findOne(["id" => $tag_id]));
          }
          $product->save();
          return $response->redirect(BASE_URI . "/dashboard/product", 200, [
            "toast" => [
              "type" => "success",
              "message" => "Edit product successful",
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
    if (!$auth->hasPermission("product.delete")) {
      return $response->redirect(BASE_URI . "/dashboard", 200, [
        "toast" => [
          "type" => "error",
          "message" => "You do not have permission to access this page.",
        ],
      ]);
    }
    $product = Product::find($request->getBody()["id"]);
    $tags = ProductTag::findAll(["product_id" => $product->id]);
    $categories = ProductCategory::findAll(["product_id" => $product->id]);
    if (!$product) {
      $response->jsonResponse([
        "type" => "success",
        "message" => "Failed to remove Product removed from table",
      ]);
    }
    // dd($product);
    foreach ($categories as $category) {
      $category->delete();
    }
    foreach ($tags as $tag) {
      $tag->delete();
    }
    $product->delete();
    $response->jsonResponse([
      "type" => "success",
      "message" => "Product removed from table",
    ]);
  }

  public function showAll(Request $request, Response $response)
  {
    $auth = Application::getInstance()->getAuthentication();
    $products = Product::all();

    // each of them set value toJson()
    $productsJson = [];
    foreach ($products as $product) {
      $productsJson[] = json_encode($product);
    }

    return $response->jsonResponse([
      $productsJson
    ]);
  }

  public function showOne(Request $request, Response $response)
  {
    $auth = Application::getInstance()->getAuthentication();
    $product = Product::find($request->getQuery("id"));
    if (!$product) {
      return $response->jsonResponse([
        "type" => "error",
        "message" => "Product not found",
      ]);
    }
    return $response->jsonResponse(
      $product->attributes
    );
  }
}