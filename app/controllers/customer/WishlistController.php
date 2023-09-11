<?php

namespace App\Controllers\Customer;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Wishlist;
use Core\Application;
use Core\Controller;
use Core\Database;
use Core\Request;
use Core\Response;
use Core\View;

class WishlistController extends Controller
{
  public function index(Request $request, Response $response)
  {
    $auth = Application::getInstance()->getAuthentication();
    if (!$auth->isAuthenticated()) {
      $response->redirect("/login");
      return;
    }

    $user = $auth->getUser();

    $wishlists = Wishlist::findAll([
      "user_id" => $user->id,
    ]);

    $products = [];

    foreach ($wishlists as $wishlist) {
      $products[] = $wishlist->product();
    }

    $response->setStatusCode(200);
    $response->setBody(
      View::renderWithLayout(new View("pages/wishlist"), [
        "title" => "Wishlist",
        "products" => $products,
      ])
    );
  }
  public function add(Request $request, Response $response)
  {
    $auth = Application::getInstance()->getAuthentication();
    if (!$auth->isAuthenticated()) {
      $response->jsonResponse([
        "type" => "error",
        "message" => "You are not authenticated",
      ]);
      return;
    }

    $user = $auth->getUser();
    $product = Product::find($request->getBody()["id"]);

    if (!$product) {
      $response->jsonResponse([
        "type" => "error",
        "message" => "Product not found",
      ]);
      return;
    }

    $wishlist = Wishlist::findOne([
      "product_id" => $product->id,
      "user_id" => $user->id,
    ]);

    if ($wishlist) {
      // $wishlist->delete();
      $response->jsonResponse([
        "type" => "info",
        "message" => "Product is already in wishlist",
      ]);
      return;
    }

    $wishlist = new Wishlist();
    $wishlist->product_id = $product->id;
    $wishlist->user_id = $user->id;
    $wishlist->save();
    // dd($wishlist);
    $response->jsonResponse([
      "type" => "success",
      "message" => "Product added to wishlist",
    ]);
  }

  public function remove(Request $request, Response $response)
  {
    $auth = Application::getInstance()->getAuthentication();
    if (!$auth->isAuthenticated()) {
      $response->jsonResponse([
        "type" => "error",
        "message" => "You are not authenticated",
      ]);
      return;
    }

    $user = $auth->getUser();

    $product = Product::find($request->getBody()["id"]);

    if (!$product) {
      $response->jsonResponse([
        "type" => "error",
        "message" => "Product not found",
      ]);
      return;
    }
    $wishlist = Wishlist::findOne([
      "product_id" => $product->id,
      "user_id" => $user->id,
    ]);

    if (!$wishlist) {
      $response->jsonResponse([
        "type" => "info",
        "message" => "Product not in wishlist",
      ]);
      return;
    }

    $wishlist->delete();

    $response->jsonResponse([
      "type" => "success",
      "message" => "Product removed from wishlist",
    ]);
  }

  public function empty(Request $request, Response $response)
  {
    $auth = Application::getInstance()->getAuthentication();
    if (!$auth->isAuthenticated()) {
      $response->jsonResponse([
        "type" => "error",
        "message" => "You are not authenticated",
      ]);
      return;
    }

    $user = $auth->getUser();

    $wishlists = Wishlist::findAll([
      "user_id" => $user->id,
    ]);

    foreach ($wishlists as $wishlist) {
      $wishlist->delete();
    }

    $response->jsonResponse([
      "type" => "success",
      "message" => "Wishlist emptied",
    ]);
  }

  public function addToCart(Request $request, Response $response)
  {
    $auth = Application::getInstance()->getAuthentication();
    if (!$auth->isAuthenticated()) {
      $response->jsonResponse([
        "type" => "error",
        "message" => "You are not authenticated",
      ]);
      return;
    }

    $user = $auth->getUser();
    $product = Product::find($request->getBody()["id"]);

    if (!$product) {
      $response->jsonResponse([
        "type" => "error",
        "message" => "Product not found",
      ]);
      return;
    }
    if ($product->quantity == 0) {
      $response->jsonResponse([
        "type" => "error",
        "message" => "Product is out of stock",
      ]);
      return;
    }
    $wishlist = Wishlist::findOne([
      "product_id" => $product->id,
      "user_id" => $user->id,
    ]);

    if (!$wishlist) {
      $response->jsonResponse([
        "type" => "info",
        "message" => "Product not in wishlist",
      ]);
      return;
    }

    $wishlist->delete();

    $cart = Cart::findOne([
      "product_id" => $product->id,
      "user_id" => $user->id,
    ]);

    if ($cart) {
      $response->jsonResponse([
        "type" => "info",
        "message" => "Product already in cart",
      ]);
      return;
    }

    $cart = new Cart();
    $cart->product_id = $product->id;
    $cart->user_id = $user->id;
    $cart->quantity = 1;
    $cart->save();

    $response->jsonResponse([
      "type" => "success",
      "message" => "Product added to cart",
    ]);
  }

  public function moveAllToCart(Request $request, Response $response)
  {
    $auth = Application::getInstance()->getAuthentication();
    if (!$auth->isAuthenticated()) {
      $response->jsonResponse([
        "type" => "error",
        "message" => "You are not authenticated",
      ]);
      return;
    }

    $user = $auth->getUser();

    $wishlists = Wishlist::findAll([
      "user_id" => $user->id,
    ]);

    foreach ($wishlists as $wishlist) {
      $cart = Cart::findOne([
        "product_id" => $wishlist->product_id,
        "user_id" => $user->id,
      ]);

      if ($cart) {
        $wishlist->delete();
        continue;
      }

      $cart = new Cart();
      $cart->product_id = $wishlist->product_id;
      $cart->user_id = $user->id;
      $cart->quantity = 1;
      $cart->save();

      $wishlist->delete();
    }

    $response->jsonResponse([
      "type" => "success",
      "message" => "All products moved to cart",
    ]);
  }
}
