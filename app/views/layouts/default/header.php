<?php

use App\Models\Cart;
use App\Models\Wishlist;
use Core\Application;
use App\Models\Category;

$categories = Category::all();

$auth = Application::getInstance()->getAuthentication();
$user = $auth->getUser() ?? null;

?>
<header
  class="flex justify-center bg-white z-50 sticky top-0 border-0 border-solid border-gray-200 border-b-[1px] shadow-lg">
  <div class="container flex items-center justify-between h-24 mt-5">
    <a class="w-48" href="<?php echo BASE_URI . "/"; ?>">
      <img src="<?php echo BASE_URI . "/resources/images/logo.png"; ?>" alt="HeaderLogo" />
    </a>
    <div class="hidden md:flex w-full max-w-[140px] group h-full mx-auto justify-center items-center relative">
      <button
        class="shadow-lg px-3 py-2 rounded-md text-primary font-semibold hover:text-white transition-all tracking-wide relative overflow-hidden group-hover:text-white">
        <i class="mr-1 fa-solid fa-bars"></i>
        Categories
        <span
          class="absolute bg-primary inset-0 rounded-md w-full h-0 overflow-hidden group-hover:h-full rotate-90 group-hover:rotate-0 duration-[400ms] text-white -z-10"></span>
      </button>
      <!-- show options -->
      <?php if (count($categories) > 0): ?>
        <div
          class="absolute h-0 overflow-hidden transition-all translate-y-7 bg-white border duration-300 border-gray-200 rounded-md shadow-lg outline-none opacity-0 w-52 top-24 mt-7 group-hover:h-fit group-hover:top-16 group-hover:opacity-100 group-hover:translate-y-0">
          <div class="px-1 py-1">
            <?php foreach ($categories as $category): ?>
              <span
                class="text-gray-700 group/category hover:bg-gray-300 hover:text-[#315854] px-4 py-2 transition-all block cursor-pointer">
                <a href="<?php echo BASE_URI .
                  "/products?categories[]=" .
                  $category->id; ?>"
                  class="block transition-all translate-x-0 text-md group-hover/category:translate-x-3">
                  <?php echo $category->name; ?>
                </a>
              </span>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>
    </div>
    <div class="box-border w-full px-10">
      <form action="<?php echo BASE_URI . '/products' ?>" method="GET"
        class="flex items-center justify-center w-full h-10 bg-gray-100 rounded-full">
        <input type="text" name="keywords" class="w-full h-full pl-5 bg-transparent rounded-tl-full rounded-bl-full"
          placeholder="Search.... "
          value="<?php echo isset($params['filter']['keywords']) ? $params['filter']['keywords'] : '' ?>" />
        <button class="flex items-center justify-center w-10 h-10">
          <i class="fa-solid fa-magnifying-glass" onclick="handleSearch()"></i>
        </button>
      </form>
    </div>
    <div class="relative flex-row justify-between gap-2 flex">
      <?php
      if ($user != null) {
        $wishlist = Wishlist::findAll(["user_id" => $user->id]);
        $cart = Cart::findAll(["user_id" => $user->id]);
      }
      if ($user != null && !$auth->hasPermission("dashboard.access")): ?>
        <a href="<?php echo BASE_URI . "/wishlist"; ?>"
          class="border-[1px] border-solid px-3 py-2 rounded-md hover:bg-[#315854] transition-all hover:text-white w-10 relative shadow-sm">
          <i class="fa-regular fa-heart"></i>
          <span
            class="absolute w-6 h-6 bg-primary-600 -top-2 -right-2 rounded-full text-center text-white <?php echo (count($wishlist) == 0 ? 'hidden' : 'block'); ?>">
            <?php echo count($wishlist); ?>
          </span>
        </a>

        <a href="<?php echo BASE_URI . "/cart"; ?>"
          class="border-[1px] border-solid px-2 py-2 rounded-md hover:bg-[#315854] transition-all hover:text-white w-10 relative shadow-sm">
          <i class="fa-brands fa-opencart"></i>
          <span
            class="absolute w-6 h-6 bg-primary-600 -top-2 -right-2 rounded-full text-center text-white <?php echo (count($cart) == 0 ? 'hidden' : 'block'); ?>">
            <?php echo count($cart); ?>
          </span>
        </a>
      <?php endif; ?>
      <button type="button"
        class="border-[1px] border-solid px-3 py-2 rounded-md hover:bg-[#315854] transition-all hover:text-white w-10 shadow-sm"
        data-dropdown-toggle="dropdownHover" data-dropdown-trigger="click" id="dropdownHoverButton">
        <i class="fa-regular fa-user"></i>
      </button>
      <div
        class="absolute hidden transition-all bg-white border border-gray-200 divide-y divide-gray-100 rounded-md shadow-lg outline-none w-28 right-16 mt-11"
        id="dropdownHover">
        <div class="px-1 py-1 " aria-labelledby="dropdownHoverButton">
          <?php if ($user != null): ?>
            <a href="<?php echo BASE_URI . "/profile"; ?>"
              class="block px-4 py-2 text-sm text-gray-700 hover:bg-[#315854] hover:text-white transition-all whitespace-nowrap">Your
              Profile</a>
            <?php if ($auth->hasPermission("dashboard.access")): ?>
              <a href="<?php echo BASE_URI . "/dashboard"; ?>"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-[#315854] hover:text-white transition-all">Dashboard</a>
            <?php endif; ?>
            <a href="<?php echo BASE_URI . "/logout"; ?>"
              class="block px-4 py-2 text-sm text-gray-700 hover:bg-[#315854] hover:text-white transition-all">Sign
              out</a>
          <?php else: ?>
            <a href="<?php echo BASE_URI . "/login"; ?>"
              class="block px-4 py-2 text-sm text-gray-700 hover:bg-[#315854] hover:text-white transition-all text-center">Sign
              in</a>
            <a href="<?php echo BASE_URI . "/register"; ?>"
              class="block px-4 py-2 text-sm text-gray-700 hover:bg-[#315854] hover:text-white transition-all text-center">Sign
              up</a>
          <?php endif; ?>
        </div>
      </div>

    </div>
  </div>
  </div>
</header>
