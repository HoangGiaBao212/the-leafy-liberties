<?php
use App\Controllers\Customer\CartController;
use App\Controllers\Customer\WishlistController;
use App\Controllers\Dashboard\ProductController;
use Core\Application;

$router = Application::getInstance()->getRouter();

$router->post("/api/wishlist/add", [WishlistController::class, "add"]);
$router->post("/api/wishlist/remove", [WishlistController::class, "remove"]);
$router->post("/api/wishlist/empty", [WishlistController::class, "empty"]);
$router->post("/api/wishlist/add-to-cart", [WishlistController::class, "addToCart"]);
$router->post("/api/wishlist/move-all-to-cart", [WishlistController::class, "moveAllToCart"]);

$router->post("/api/cart/add", [CartController::class, "add"]);
$router->post("/api/cart/remove", [CartController::class, "remove"]);
$router->post("/api/cart/empty", [CartController::class, "empty"]);
$router->post("/api/cart/update", [CartController::class, "update"]);


// get all products
$router->get("/api/products", [ProductController::class, "showAll"]);
$router->get("/api/products/show", [ProductController::class, "showOne"]);