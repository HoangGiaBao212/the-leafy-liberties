<?php

namespace App\Controllers\Customer;

use App\Models\Product;
use App\Models\Slide;
use App\Models\User;
use Core\Controller;
use Core\Database;
use Core\Request;
use Core\Response;
use Core\View;
use Exception;

class HomeController extends Controller
{
  public function index(Request $request, Response $response)
  {
    $slides = Slide::findAll(["status" => 1, "deleted_at" => "null"]);

    $response->setStatusCode(200);
    $response->setBody(
      View::renderWithLayout(new View("pages/index"), [
        "title" => "Home",
        "slides" => $slides,
      ])
    );
  }

  public function about(Request $request, Response $response)
  {
    $response->setStatusCode(200);
    $response->setBody(
      View::renderWithLayout(new View("pages/about"), [
        "title" => "About",
      ])
    );
  }

  public function cart(Request $request, Response $response)
  {
    $response->setStatusCode(200);
    $response->setBody(
      View::renderWithLayout(new View("pages/cart"), [
        "title" => "Cart",
      ])
    );
  }
  public function wishlist(Request $request, Response $response)
  {
    $response->setStatusCode(200);
    $response->setBody(
      View::renderWithLayout(new View("pages/wishlist"), [
        "title" => "Wishlist",
      ])
    );
  }
  public function product(Request $request, Response $response)
  {
    $response->setStatusCode(200);
    $response->setBody(
      View::renderWithLayout(new View("pages/product"), [
        "title" => "Product",
      ])
    );
  }
  public function profile(Request $request, Response $response)
  {
    $response->setStatusCode(200);
    $response->setBody(
      View::renderWithLayout(new View("pages/profile"), [
        "title" => "Profile",
      ])
    );
  }

  public function checkout(Request $request, Response $response)
  {
    $response->setStatusCode(200);
    $response->setBody(
      View::renderWithLayout(new View("pages/checkout"), [
        "title" => "Checkout",
      ])
    );
  }
}
