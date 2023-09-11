<?php
use Core\Application;

$auth = Application::getInstance()->getAuthentication();
if (!$auth->isAuthenticated() || !$auth->hasPermission("dashboard.access")) {
  Application::getInstance()
    ->getResponse()
    ->redirect(BASE_URI . "/login");
}

$user = $auth->getUser();
?>
<div class="w-full bg-white h-16 sticky top-0 border-0 border-solid border-l-[1px] border-gray-300 shadow-md z-40">
  <div class="flex items-center justify-between h-full p-4 header">
    <div class="flex items-center justify-center left">
      <i class="mr-2 font-medium cursor-pointer fa-regular fa-bars-sort fa-lg" onclick="toggleMenu()"></i>
      <span class="text-2xl font-medium text-[#52938d]">
        <?php echo ucfirst(basename($params["title"] ?? "Dashboard")); ?>
      </span>
    </div>
    <div class="relative flex items-center right">
      <div class="w-10 mr-2 avatar">
        <img src="<?php echo $user->image
          ? $user->image
          : BASE_URI . "/resources/images/avatar.png"; ?>" alt=""
          class="object-cover w-full h-full rounded-full cursor-pointer" onmouseover="showMenu()"
          onmouseout="hideMenuDelayed()" />
        <ul id="menu"
          class="absolute right-0 flex-col hidden gap-2 p-2 transition-all bg-white rounded-lg shadow-lg top-full"
          onmouseover="showMenu()" onmouseout="hideMenuDelayed()">
          <li
            class=" whitespace-nowrap hover:bg-[#cee4e1] transition-all cursor-pointer p-2 text-left text-gray-600 w-full h-full">
            <a href="<?php echo BASE_URI .
              "/dashboard/user/update" . '?id=' . $user->id ?>" class="block w-full h-full">User Setting</a>
          </li>
          <li
            class="whitespace-nowrap cursor-pointer hover:bg-[#cee4e1] transition-all p-2 text-left text-gray-600 w-full h-full">
            <a href="<?php echo BASE_URI . "/logout"; ?> " class="block w-full h-full">Logout</a>
          </li>
        </ul>
      </div>
      <span class="user" onmouseover="showMenu()" onmouseout="hideMenuDelayed()">
        <?php echo $user->name; ?>
      </span>
    </div>
  </div>
</div>
<script>
  var timeoutId;

  function showMenu() {
    clearTimeout(timeoutId);
    document.getElementById("menu").style.display = "flex";
  }

  function hideMenu() {
    document.getElementById("menu").style.display = "none";
  }

  function hideMenuDelayed() {
    timeoutId = setTimeout(function () {
      hideMenu();
    }, 1000);
  }
</script>
