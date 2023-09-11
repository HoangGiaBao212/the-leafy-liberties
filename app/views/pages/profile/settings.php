<div class="flex justify-center w-full bg-white">
  <div class="container">
    <div class="box-border flex flex-col w-full mt-10 border border-b-2 border-gray-300 md:flex-row">
      <?php include "menu.php"; ?>
      <div class="w-full p-2 md:w-3/4">
        <form class="flex flex-col" action="<?php echo BASE_URI . "/profile/settings/change_password"; ?>"
          method="post">
          <label>Password change</label>
          <div class="relative flex flex-col gap-1 px-2 py-4 border border-gray-300 border-solid rounded">
            <label for="password">
              Current password
            </label>
            <input id="current-password" name="current-password" type="password" required
              class="w-full p-5 border border-gray-300 border-solid rounded-md shadow h-9 focus:ring-2 focus:ring-primary-400 transition-all" />
            <i id="hide-icon" class="fa fa-eye-slash absolute top-[23%] right-4 cursor-pointer" aria-hidden="true"></i>
            <i id="show-icon" class="fa fa-eye absolute hidden top-[23%] right-4 cursor-pointer" aria-hidden="true"></i>
            <label for="new-password">New password</label>
            <input id="new-password" name="new-password" type="password" required
              class="w-full p-5 border border-gray-300 border-solid rounded-md shadow h-9 focus:ring-2 focus:ring-primary-400 transition-all" />
            <i id="hide-icon-new" class="fa fa-eye-slash absolute top-[53%] right-4 cursor-pointer"
              aria-hidden="true"></i>
            <i id="show-icon-new" class="fa fa-eye absolute hidden top-[53%] right-4 cursor-pointer"
              aria-hidden="true"></i>
            <label for="confirm-new-password">Confirm new password</label>
            <input id="confirm-new-password" name="confirm-new-password" type="password" required
              class="w-full p-5 border border-gray-300 border-solid rounded-md h-9 focus:ring-2 focus:ring-primary-400 transition-all" />
            <i id="hide-icon-confirm-new" class="fa fa-eye-slash absolute top-[82%] right-4 cursor-pointer"
              aria-hidden="true"></i>
            <i id="show-icon-confirm-new" class="fa fa-eye absolute hidden top-[82%] right-4 cursor-pointer"
              aria-hidden="true"></i>
          </div>
          <input type="submit" value="Save changes"
            class="w-3/6 p-2 mt-5 text-white transition-all bg-primary-800 border cursor-pointer hover:bg-primary-600 sm:w-1/6 rounded-md" />
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  const passwordInput = document.getElementById("current-password");
  const hideIcon = document.getElementById("hide-icon");
  const showIcon = document.getElementById("show-icon");

  hideIcon.addEventListener("click", function () {
    if (passwordInput.type === "password") {
      passwordInput.type = "text";
      hideIcon.style.display = "none";
      showIcon.style.display = "block";
    }
  });
  showIcon.addEventListener("click", function () {
    if (passwordInput.type === "text") {
      passwordInput.type = "password";
      hideIcon.style.display = "block";
      showIcon.style.display = "none";
    }
  });

  const newPasswordInput = document.getElementById("new-password");
  const hideIconNewPassword = document.getElementById("hide-icon-new");
  const showIconNewPassword = document.getElementById("show-icon-new");

  hideIconNewPassword.addEventListener("click", function () {
    if (newPasswordInput.type === "password") {
      newPasswordInput.type = "text";
      hideIconNewPassword.style.display = "none";
      showIconNewPassword.style.display = "block";
    }
  });
  showIconNewPassword.addEventListener("click", function () {
    if (newPasswordInput.type === "text") {
      newPasswordInput.type = "password";
      hideIconNewPassword.style.display = "block";
      showIconNewPassword.style.display = "none";
    }
  });

  const newConfirmPasswordInput = document.getElementById("confirm-new-password");
  const hideConfirmIconNewPassword = document.getElementById("hide-icon-confirm-new");
  const showConfirmIconNewPassword = document.getElementById("show-icon-confirm-new");

  hideConfirmIconNewPassword.addEventListener("click", function () {
    if (newConfirmPasswordInput.type === "password") {
      newConfirmPasswordInput.type = "text";
      hideConfirmIconNewPassword.style.display = "none";
      showConfirmIconNewPassword.style.display = "block";
    }
  });
  showConfirmIconNewPassword.addEventListener("click", function () {
    if (newConfirmPasswordInput.type === "text") {
      newConfirmPasswordInput.type = "password";
      hideConfirmIconNewPassword.style.display = "block";
      showConfirmIconNewPassword.style.display = "none";
    }
  });
</script>
