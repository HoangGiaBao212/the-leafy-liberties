<?php
use Core\Application;

$auth = Application::getInstance()->getAuthentication();
?>
<div class="flex flex-row justify-around w-3/4 p-4 md:flex-col md:justify-start md:w-1/4">
  <div class="flex flex-col items-center justify-center">
    <div class="relative w-40 h-40 cursor-pointer">
      <img id="output" src="<?php echo $user->image
        ? BASE_URI . $user->image
        : BASE_URI .
        "/resources/images/avatar.png"; ?>" class="object-cover w-full h-full rounded-full" alt="avatar" />
    </div>
    <div onclick="document.querySelector('#image').click()" class="absolute text-4xl cursor-pointer">
      <!-- upload -->
      <i
        class="p-16 transition-all rounded-full opacity-0 fa-regular fa-camera hover:bg-gray-200 hover:opacity-100 "></i>
    </div>
  </div>
  <div class="box-border flex flex-col items-start justify-center px-6">
    <a href="<?php echo BASE_URI .
      "/profile"; ?>"
      class="flex p-2 mt-5 transition-all rounded-lg w-44 hover:bg-primary-600 hover:text-white whitespace-nowrap">
      <i class="flex items-center mr-2 fa fa-home"></i>
      <h3 class="text-xl cursor-pointer">Profile settings</h3>
    </a>
    <a href="<?php echo BASE_URI .
      "/profile/settings"; ?>"
      class="flex p-2 mt-5 transition-all rounded-lg w-44 hover:bg-primary-600 hover:text-white whitespace-nowrap">
      <i class="flex items-center mr-2 fa fa-user"></i>
      <h3 class="cursor-pointer">Account settings</h3>
    </a>
    <?php if (!$auth->hasPermission('dashboard.access')): ?>
      <a href="<?php echo BASE_URI .
        "/profile/orders"; ?>"
        class="flex p-2 mt-5 transition-all rounded-lg w-44 hover:bg-primary-600 hover:text-white whitespace-nowrap">
        <i class="flex items-center mr-2 fa fa-bag-shopping"></i>
        <h3 class="cursor-pointer">My Order</h3>
      </a>
    <?php endif ?>
  </div>
</div>

<script>
  var currentPath = window.location.pathname;
  var links = document.getElementsByTagName("a");
  for (var i = 0; i < links.length; i++) {
    if (links[i].getAttribute("href") === currentPath) {
      links[i].classList.add("bg-primary-200", "text-primary");
      console.log(links[i].classList)
    } else {
      links[i].classList.remove("bg-primary-200", "text-primary");
    }
  }
</script>

<script>
  var loadFile = function (event) {
    var output = document.getElementById('output');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function () {
      URL.revokeObjectURL(output.src) // free memory
    }
  };
</script>
