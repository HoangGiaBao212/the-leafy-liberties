<?php
namespace App\Controllers\Dashboard;

use App\Models\Author;
use App\Models\Category;
use App\Models\Import;
use App\Models\ImportProduct;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductTag;
use App\Models\User;
use Core\Application;
use Core\Controller;
use Core\Database;
use Core\Request;
use Core\Response;
use Core\View;
use Utils\FileUploader;

class ImportController extends Controller
{
  public function index(Request $request, Response $response)
  {
    $filter = [
      "keywords" => $request->getQuery("keywords"),
    ];

    $auth = Application::getInstance()->getAuthentication();
    if (!$auth->hasPermission("import.access")) {
      return $response->redirect(BASE_URI . "/dashboard", 200, [
        "toast" => [
          "type" => "error",
          "message" => "You do not have permission to access this page.",
        ],
      ]);
    }
    if (isset($filter)) {
      $imports = Import::filterAdvanced($filter);
    } else {
      $imports = Import::all();

    }
    return $response->setBody(
      View::renderWithDashboardLayout(
        new View("pages/dashboard/import/index"),
        [
          "title" => "Import",
          "imports" => $imports,
          "filter" => $filter,
        ]
      )
    );
  }

  public function create(Request $request, Response $response)
  {
    $auth = Application::getInstance()->getAuthentication();
    if (!$auth->hasPermission("import.create")) {
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
            new View("pages/dashboard/import/create"),
            [
              "title" => "Import Product",
            ]
          )
        );
      case 'POST':
        if ($request->getParam('product-id') == null) {
          return $response->redirect(BASE_URI . '/dashboard/import/create', 200, [
            "toast" => [
              "type" => "error",
              "message" => "Please insert products!!"
            ]
          ]);
        }
        Database::getInstance()->beginTransaction();
        $import = new Import();
        $import->user_id = $auth->getUser()->id;
        $import->total_price = 0;
        $import->save();
        $products = [];
        for ($i = 0; $i < count($request->getParam('product-id')); $i++) {
          $products[] = [
            'product-id' => $request->getParam('product-id')[$i],
            'quantity' => $request->getParam('quantity')[$i],
            'price' => $request->getParam('price')[$i],
          ];
        }

        foreach ($products as $product) {
          $import->addProduct(
            Product::find($product['product-id']),
            $product['quantity'],
            $product['price'],
          );
        }
        Database::getInstance()->commitTransaction();
        return $response->redirect(BASE_URI . "/dashboard/import", 200, [
          "toast" => [
            "type" => "success",
            "message" => "Import successfully.",
          ],
        ]);
    }
  }

  public function store(Request $request, Response $response)
  {
    $auth = Application::getInstance()->getAuthentication();
    if (!$auth->hasPermission("import.create")) {
      return $response->redirect(BASE_URI . "/dashboard", 200, [
        "toast" => [
          "type" => "error",
          "message" => "You do not have permission to access this page.",
        ],
      ]);
    }
    return $response->redirect(BASE_URI . "/dashboard/import");
  }

  public function show(Request $request, Response $response)
  {
    $auth = Application::getInstance()->getAuthentication();
    if (!$auth->hasPermission("import.access")) {
      return $response->redirect(BASE_URI . "/dashboard", 200, [
        "toast" => [
          "type" => "error",
          "message" => "You do not have permission to access this page.",
        ],
      ]);
    }
    $product = Product::find($request->getQuery("id"));
    if (!$product) {
      return $response->redirect(BASE_URI . "/dashboard/import");
    }
    return $response->setBody(
      View::renderWithDashboardLayout(
        new View("pages/dashboard/import/show"),
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
    if (!$auth->hasPermission('import.update')) {
      return $response->redirect(BASE_URI . '/dashboard', 200, [
        'toast' => [
          'type' => 'error',
          'message' => 'You do not have permission to access this page.'
        ]
      ]);
    }
    switch ($request->getMethod()) {
      case "GET":
        $import = Import::findOne(["id" => $request->getQuery("id")]);
        $response->setStatusCode(200);
        return $response->setBody(
          View::renderWithDashboardLayout(
            new View("pages/dashboard/import/update"),
            [
              "title" => "Import Update",
              "import" => $import,
            ]
          )
        );
      case "POST":
        $import = Import::find($request->getParam("import-id"));
        if (!$import) {
          return $response->redirect(BASE_URI . "/dashboard/import", 200, [
            "toast" => [
              "type" => "error",
              "message" => "Edit import bill fail",
            ],
          ]);
        } else {
          //Viết hàm import trong này
          $products = [];
          for ($i = 0; $i < count($request->getParam('product-id')); $i++) {
            $products[] = [
              'product-id' => $request->getParam('product-id')[$i],
              'quantity' => $request->getParam('quantity')[$i],
              'price' => $request->getParam('price')[$i],
            ];
          }
          $total = 0;
          foreach ($products as $product) {
            $importProduct = ImportProduct::findOne(["import_id" => $import->id, "product_id" => $product["product-id"]]);
            $old_quantity = $importProduct->quantity;
            $importProduct->quantity = $product["quantity"];
            $importProduct->price = $product["price"];
            $total += $product["quantity"] * $product["price"];
            $importProduct->save();
            $updateProduct = Product::findOne(["id" => $product["product-id"]]);
            $updateProduct->price = $importProduct->price;
            $updateProduct->quantity = ($updateProduct->quantity + $importProduct->quantity - $old_quantity);
            $updateProduct->save();
          }
          $import->total_price = $total;
          $import->save();
          return $response->redirect(BASE_URI . "/dashboard/import", 200, [
            "toast" => [
              "type" => "success",
              "message" => "Edit import bill successful",
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

    if (!$auth->hasPermission('import.delete')) {
      return $response->redirect(BASE_URI . '/dashboard', 200, [
        'toast' => [
          'type' => 'error',
          'message' => 'You do not have permission to access this page.'
        ]
      ]);
    }


    $import = Import::find($request->getQueries()["id"]);
    if (!$import) {
      return $response->jsonResponse([
        "type" => "error",
        "message" => "Import bill not found",
      ]);
    }
    $importProducts = ImportProduct::findAll(["import_id" => $import->id]);
    foreach ($importProducts as $importProduct) {
      $updateProduct = Product::findOne(["id" => $importProduct->product_id]);
      $updateProduct->quantity -= $importProduct->quantity;
      $updateProduct->save();
      $importProduct->delete();
    }
    $import->delete();
    $response->jsonResponse([
      "type" => "success",
      "message" => "Import bill removed from table",
    ]);
  }
}
