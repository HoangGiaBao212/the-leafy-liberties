<?php

use Core\Application;

$auth = Application::getInstance()->getAuthentication();

$menu = [
  "dashboard.access" => [
    "name" => "Dashboard",
    "icon" => "fa-bars",
    "url" => BASE_URI . "/dashboard",
  ],
  "user.access" => [
    "name" => "Users",
    "icon" => "fa-users",
    "url" => BASE_URI . "/dashboard/user",
  ],
  "product.access" => [
    "name" => "Products",
    "icon" => "fa-bag-shopping",
    "url" => BASE_URI . "/dashboard/product",
  ],
  "author.access" => [
    "name" => "Authors",
    "icon" => "fa-user-pen",
    "url" => BASE_URI . "/dashboard/author",
  ],
  "review.access" => [
    "name" => "Reviews",
    "icon" => "fa-message",
    "url" => BASE_URI . "/dashboard/review",
  ],
  "order.access" => [
    "name" => "Orders",
    "icon" => "fa-shopping-cart",
    "url" => BASE_URI . "/dashboard/order",
  ],
  "permission.access" => [
    "name" => "Permissions",
    "icon" => "fa-user-lock",
    "url" => BASE_URI . "/dashboard/permission",
  ],
  "role.access" => [
    "name" => "Roles",
    "icon" => "fa-user-plus",
    "url" => BASE_URI . "/dashboard/role",
  ],
  "import.access" => [
    "name" => "Import",
    "icon" => "fa-file-import",
    "url" => BASE_URI . "/dashboard/import",
  ],
  "category.access" => [
    "name" => "Categories",
    "icon" => "fa-list",
    "url" => BASE_URI . "/dashboard/category",
  ],
  "coupon.access" => [
    "name" => "Coupons",
    "icon" => "fa-ticket",
    "url" => BASE_URI . "/dashboard/coupon",
  ],
  // "slide.access" => [
  //   "name" => "Slides",
  //   "icon" => "fa-sliders",
  //   "url" => BASE_URI . "/dashboard/slide",
  // ],
  // "setting.access" => [
  //   "name" => "Settings",
  //   "icon" => "fa-cog",
  //   "url" => BASE_URI . "/dashboard/setting",
  // ]
];

foreach ($menu as $key => $menuItem) {
  if (!$auth->hasPermission($key)) {
    unset($menu[$key]);
  }
}
?>
<div
    class="menu-left flex flex-col w-16 lg:w-72 bg-white h-auto text-[#315854] transition-all duration-300 border-none z-[200] hover:shadow-lg">
    <div class="flex flex-col justify-between flex-grow  z-[888]">
        <ul id='menu-list' class="sticky top-0 flex flex-col py-4 space-y-1 list">
            <li class="block px-5">
                <a href="<?php echo BASE_URI .
          "/"; ?>" class="flex items-center justify-center py-4">
                    <img src="<?php echo BASE_URI .
            "/resources/images/logo.png"; ?>" alt="logo" />
                </a>
            </li>
            <?php foreach ($menu as $menuItem): ?>
            <li
                class="focus:outline-none hover:bg-[#315854] text-[#40736d] hover:text-white border-l-4 hover:border-[#add1ce] pr-6 transition-all">
                <a href="<?php echo $menuItem["url"]; ?>" class="relative flex flex-row items-center h-11">
                    <span class="inline-flex items-center justify-center ml-4">
                        <i class="fa-solid <?php echo $menuItem["icon"]; ?>"></i>
                    </span>
                    <span class="ml-2 text-sm tracking-wide truncate menu-text">
                        <?php echo $menuItem["name"]; ?>
                    </span>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<script>
let menu = document.querySelector(".menu-left");

function toggleMenu() {
    if (menu.classList.contains('lg:w-72')) {
        menu.classList.remove('lg:w-72');
    } else {
        menu.classList.add('lg:w-72');
    }
}
var currentPath = window.location.pathname;
var links = document.getElementsByTagName("a");
for (var i = 1; i < links.length; i++) {
    if (links[i].getAttribute("href") === currentPath) {
        links[i].parentElement.classList.add("bg-primary", "text-white", "border-[#add1ce]");
        console.log(links[i].classList)
    } else {
        links[i].parentElement.classList.remove("bg-primary", "text-white", "border-[#add1ce]");
    }
}
</script>
