<?php

namespace App\Controllers\Dashboard;

use App\Models\Product;
use App\Models\Review;
use Core\Application;
use Core\Controller;
use Core\Request;
use Core\Response;
use Core\View;

class ReviewController extends Controller
{
  public function index(Request $request, Response $response)
  {
    // $filter = [
    //   "keywords" => $request->getQuery("keywords"),
    // ];
    // if (isset($filter)) {
    //   $reviews = Review::filterAdvanced($filter);
    // } else {
    //   $reviews = Review::all();

    // }
    $products = Product::all();
    return $response->setBody(
      View::renderWithDashboardLayout(new View("pages/dashboard/review/index"), [
        "title" => "Review",
        "products" => $products,
      ])
    );
  }
  public function reviewDetail(Request $request, Response $response)
  {

    $product = Product::find($request->getQuery("id"));
    $reviews = Review::findAll(["product_id" => $product->id]);
    $reviewsValid = [];
    foreach ($reviews as $review) {
      if ($review->deleted_at == null) {
        $reviewsValid[] = $review;
      }
    }
    return $response->setBody(
      View::renderWithDashboardLayout(new View("pages/dashboard/review/reviewDetail"), [
        "title" => "Review",
        "product" => $product,
        "reviewsValid" => $reviewsValid,
      ])
    );
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
    $review = Review::find($request->getBody()["id"]);
    if (!$review) {
      $response->jsonResponse([
        "type" => "success",
        "message" => "Failed to remove review removed from table",
      ]);
    }

    $review->delete();
    $response->jsonResponse([
      "type" => "success",
      "message" => "Review removed from table",
    ]);
  }
}