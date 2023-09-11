<?php

namespace App\Controllers\Customer;

use App\Models\Author;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Review;
use App\Models\ReviewStatus;
use Core\Application;
use Core\Controller;
use Core\Database;
use Core\Request;
use Core\Response;
use Core\View;
use Utils\Pagination;

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

    $pagination = [
      "page" => $request->getQuery("page") ?? 1,
      "limit" => $request->getQuery("limit") ?? 24,
    ];
    $products = Product::filterAdvanced($filter);

    $pagination["total"] = count($products);

    $pagination["totalPages"] = ceil($pagination["total"] / $pagination["limit"]);

    $pagination["products"] = Pagination::paginate(
      $products,
      $pagination["page"],
      $pagination["limit"]
    );

    $response->setStatusCode(200);
    $response->setBody(
      View::renderWithLayout(new View("pages/products"), [
        "title" => "Shop",
        "products" => $products,
        "filter" => $filter,
        "pagination" => $pagination
      ])
    );
  }

  public function show(Request $request, Response $response)
  {
    try {
      $product = Product::find($request->getQuery("id"));
      $author = Author::find($product->author_id);
      $product_category = ProductCategory::find($product->id);
      $reviews = Review::findAll([
        "product_id" => $product->id,
      ]);
      $review_status = ReviewStatus::find($product->id);

    } catch (\Exception $e) {
      dd($e->getMessage());
    }
    $response->setStatusCode(200);
    $response->setBody(
      View::renderWithLayout(new View("pages/product"), [
        "product" => $product,
        "author" => $author,
        "reviews" => $reviews,
        "review_status" => $review_status,
      ])
    );
  }

  public function getProducts(Request $request, Response $response)
  {
    $response->setStatusCode(200);

    try {
      $products = [];
      foreach (Product::all() as $product) {
        $products[] = $product->toArray();
      }
      $response->jsonResponse($products);
    } catch (\Exception $e) {
      dd($e->getMessage());
    }
  }

  public function getCategories(Request $request, Response $response)
  {
    $response->setStatusCode(200);

    try {
      $categories = [];
      foreach (Category::all() as $category) {
        $categories[] = $category->toArray();
      }
      $response->jsonResponse($categories);
    } catch (\Exception $e) {
      dd($e->getMessage());
    }
  }

  public function getProductCategories(Request $request, Response $response)
  {
    $response->setStatusCode(200);

    try {
      $productCategories = [];
      foreach (ProductCategory::all() as $productCategory) {
        $productCategories[] = $productCategory->toArray();
      }
      $response->jsonResponse($productCategories);
    } catch (\Exception $e) {
      dd($e->getMessage());
    }
  }

  public function getAuthors(Request $request, Response $response)
  {
    $response->setStatusCode(200);

    try {
      $authors = [];
      foreach (Author::all() as $author) {
        $authors[] = $author->toArray();
      }
      $response->jsonResponse($authors);
    } catch (\Exception $e) {
      dd($e->getMessage());
    }
  }

  public function comment(Request $request, Response $response)
  {
    $user = Application::getInstance()
      ->getAuthentication()
      ->getUser();
    $product = Product::find($request->getQuery("id"));
    if (!$user)
      return $response->redirect(BASE_URI . "/login", 200, [
        "toast" => [
          "type" => "error",
          "message" => "You are not logged in",
        ],
      ]);
    date_default_timezone_set('Asia/Ho_Chi_Minh');

    $newComment = new Review();
    $newComment->user_id = $user->id;
    $newComment->product_id = $product->id;
    $newComment->content = trim($request->getParam("new-comment"));
    $newComment->rating = trim($request->getParam("rating"));
    if (!$newComment->content) {
      return $response->redirect(BASE_URI . "/product" . "?id=" . $product->id);
    }
    $newComment->save();
    return $response->redirect(BASE_URI . "/product" . "?id=" . $product->id, 200, [
      "toast" => [
        "type" => "success",
        "message" => "Comment successfully",
      ],
    ]);
  }

  public function commentUpdate(Request $request, Response $response)
  {
    $review = Review::find($request->getQuery("id"));
    $product = Product::find($review->product_id);
    $review->content = $request->getParam("update-comment");
    if ($request->getParam("update-rating" . $review->id)) {
      $review->rating = $request->getParam("update-rating" . $review->id);
    }
    $review->save();
    $response->redirect(BASE_URI . "/product?id=$product->id", 200, [
      "toast" => [
        "type" => "success",
        "message" => "Update successfully",
      ],
    ]);
  }

  public function commentStatus(Request $request, Response $response)
  {
    $product = Product::find($request->getQuery("id"));
    $reviewStatus = ReviewStatus::find($product->id);
    $reviewStatus->status == 0 ? $reviewStatus->status = 1 : $reviewStatus->status = 0;
    $reviewStatus->save();
    return $response->redirect(BASE_URI . "/product" . "?id=" . $product->id, 200, [
      "toast" => [
        "type" => "success",
        "message" => $reviewStatus->status == 0 ? "Turn off comment successfully" : "Turn on comment successfully",
      ],
    ]);
  }
}