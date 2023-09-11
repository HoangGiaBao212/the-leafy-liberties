<?php

use App\Controllers\Auth\LoginController;
use App\Controllers\Auth\RegisterController;
use App\Controllers\Customer\CheckoutController;
use Core\Application;

use App\Controllers\Customer\HomeController;
use App\Controllers\Customer\ProductController;
use App\Controllers\Customer\WishlistController;
use App\Controllers\Customer\CartController;
use App\Controllers\Customer\ProfileController;

$router = Application::getInstance()->getRouter();

$router->get("/", [HomeController::class, "index"]);


$router->get("/wishlist", [WishlistController::class, "index"]);

$router->get("/cart", [CartController::class, "index"]);

$router->get("/checkout", [CheckoutController::class, "index"]);
$router->post("/checkout/confirm", [CheckoutController::class, "confirm"]);

$router->get("/products", [ProductController::class, "index"]);
$router->get("/product", [ProductController::class, "show"]);
$router->get("/products/filter", [ProductController::class, "filter"]);

// profile
$router->get("/profile", [ProfileController::class, "index"]);
$router->post("/profile", [ProfileController::class, "update"]);
$router->get("/profile/settings", [ProfileController::class, "settings"]);
$router->post("/profile/settings/change_password", [ProfileController::class, "changePassword"]);
$router->get("/profile/orders", [ProfileController::class, "orders"]);
$router->get("/profile/orders/order_detail", [ProfileController::class, "orderDetail"]);
$router->post("/profile/order_detail", [ProfileController::class, "orderDetail"]);
$router->get("/profile/orders/order_detail/delete", [ProfileController::class, "delete"]);
$router->post("/profile/orders/order_detail/delete", [ProfileController::class, "delete",]);

// reviews
$router->post("/product/comment", [ProductController::class, "comment"]);
$router->post("/product/comment/update", [ProductController::class, "commentUpdate"]);
$router->post("/product/review_status", [ProductController::class, "commentStatus"]);


$router->get("/login", [LoginController::class, "index"]);
$router->post("/login", [LoginController::class, "login"]);

$router->get("/register", [RegisterController::class, "index"]);
$router->post("/register", [RegisterController::class, "register"]);

$router->get("/logout", [LoginController::class, "logout"]);

// get data
$router->get("/data/getProducts", [ProductController::class, "getProducts"]);
$router->get("/data/getCategories", [
  ProductController::class,
  "getCategories",
]);
$router->get("/data/getProductCategories", [
  ProductController::class,
  "getProductCategories",
]);
$router->get("/data/getAuthors", [ProductController::class, "getAuthors"]);