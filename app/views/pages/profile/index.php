<div class="flex justify-center w-full bg-white">
  <div class="container">
    <div class="box-border flex flex-col w-full mt-10 border border-b-2 border-gray-300 md:flex-row relative">
      <?php include "menu.php"; ?>
      <!-- content -->
      <div class="w-full p-2 md:w-3/4">
        <form class="flex flex-col gap-1" action="<?php echo BASE_URI .
          "/profile"; ?>" method="POST" enctype="multipart/form-data">
          <input type="file" name="avatar" id="image" class="hidden" onchange="loadFile(event)">

          <label>Name</label>
          <input name="name" value="<?php echo $user->name; ?>" type="text" required
            class="w-full px-2 py-5 duration-300 border border-gray-300 border-solid rounded-md h-9 hover:shadow-sm shadow focus:ring-2 focus:ring-primary-400" />
          <label>Email</label>
          <input name="email" value=" <?php echo $user->email; ?>" type="email" required pattern="[^@\s]+@[^@\s]+"
            class="w-full px-2 py-5 duration-300 border border-gray-300 border-solid rounded-md h-9 hover:shadow-sm shadow focus:ring-2 focus:ring-primary-400" />
          <label>Phone number</label>
          <input name="phone" value="<?php echo $user->phone; ?>" type="tel"
            class="w-full px-2 py-5 duration-300 border border-gray-300 border-solid rounded-md h-9 hover:shadow-sm shadow focus:ring-2 focus:ring-primary-400"
            pattern="^[0-9]{10}$" required oninvalid="this.setCustomValidity('Please enter a valid phone number')"
            onvalid="this.setCustomValidity('')" />
          <label>Address</label>
          <input name="address" value="<?php echo $user->address; ?>" type="text"
            class="w-full px-2 py-5 duration-300 border border-gray-300 border-solid rounded-md h-9 hover:shadow-sm shadow focus:ring-2 focus:ring-primary-400" />
          <label>Gender</label>
          <div class="relative inline-block">
            <select name="gender" id="gender"
              class="block w-full px-2 py-2 pr-8 leading-tight duration-300 bg-white border border-gray-300 rounded shadow appearance-none hover:shadow-sm focus:ring-2 focus:ring-primary-400">
              <option value="male" <?php if ($user->gender == "1") {
                echo "selected";
              } ?>>Male</option>
              <option value="female" <?php if ($user->gender == "2") {
                echo "selected";
              } ?>>Female</option>
              <option value="other" <?php if ($user->gender == "0") {
                echo "selected";
              } ?>>Other</option>
            </select>
          </div>
          <label for="password">
            Confirm password
          </label>
          <div class="relative flex flex-col py-4 px-2 gap-1 border border-gray-300 border-solid rounded">
            <input name="current-password" type="password"
              class="w-full p-5 border border-gray-300 border-solid rounded-md h-9 shadow focus:ring-2 focus:ring-primary-400"
              id="current-password" />
            <i id="hide-icon" class="fa fa-eye-slash absolute top-[40%] right-4 cursor-pointer" aria-hidden="true"></i>
            <i id="show-icon" class="fa fa-eye absolute hidden top-[40%] right-4 cursor-pointer" aria-hidden="true"></i>
          </div>
          <input type="submit" value="Save changes"
            class="w-3/6 p-2 mt-5 text-white  bg-primary-800 hover:bg-primary-700 border cursor-pointer sm:w-1/6 rounded-lg transition-all" />

        </form>
      </div>
    </div>
  </div>
</div>

<script>
  var loadFile = function (event) {
    var output = document.getElementById('output');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function () {
      URL.revokeObjectURL(output.src) // free memory
    }
  };
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
</script>
