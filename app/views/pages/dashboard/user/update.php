<?php
use App\Models\Role;
use App\Models\User;

?>
<div class="w-full min-h-screen ">
  <div class="w-full p-5 my-5 bg-white rounded-md shadow-lg">
    <form class="flex flex-col" action="<?php echo BASE_URI . "/dashboard/user/update"; ?>" method="POST"
      enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?php echo $user->id; ?>" />

      <label for="title" class="my-2">Image:</label>
      <input type="file" name="image"
        class="p-3 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-400"
        onchange="loadFile(event)" />
      <div class="">
        <p>Preview Image:</p>
        <img id="output" class="object-contain h-56 w-80"
          src="<?php echo ($user->image == NULL) ? BASE_URI . '/resources/images/user/placeholder.png' : BASE_URI . $user->image ?>"
          alt="null" />
      </div>
      <input type="text" value="<?php echo $user->image; ?>" name="old_img" class="opacity-100" />

      <label for="name" class="my-2">Name:</label>
      <input type="text" value="<?php echo $user->name; ?>" name="name"
        class="p-3 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-400" required
        pattern="^[a-zA-Z]+(([',. -][a-zA-Z ])?[a-zA-Z]*)*$"
        oninvalid="this.setCustomValidity('Please enter alphabets only')" onvalid="this.setCustomValidity('')" />

      <label for="expired" class="my-2">Email:</label>
      <input type="email" disabled value="<?php echo $user->email; ?>" name="email"
        class="p-3 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-400" required />

      <label for="expired" class="my-2">Password:</label>
      <div class="relative">
        <input type="password" name="password"
          class="w-full p-3 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-400" />
        <i id="hide-icon-password" class="fa fa-eye-slash absolute top-[35%] right-3 cursor-pointer"
          aria-hidden="true"></i>
        <i id="show-icon-password" class="fa fa-eye absolute hidden top-[35%] right-3 cursor-pointer"
          aria-hidden="true"></i>
      </div>
      <label for="expired" class="my-2">Phone:</label>
      <input type="tel" value="<?php echo $user->phone; ?>" name="phone"
        class="p-3 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-400" required
        pattern="^[0-9]{10}$" required oninvalid="this.setCustomValidity('Please enter a valid phone number')"
        onvalid="this.setCustomValidity('')" />
      <label for="role" class="my-2">Select role:</label>
      <select value="" name="role"
        class="p-3 bg-gray-100 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-primary-400">
        <?php $roless = Role::find($user->role_id); ?>
        <option value="<?php echo $roless->id ?>">
          <?php
          echo ucfirst($roless->name);
          ?>
        </option>
        <?php foreach (Role::all() as $role): ?>
          <option value="<?php echo $role->id ?>"><?php echo ucfirst($role->name) ?></option>
        <?php endforeach ?>
      </select>
      <label for="status" class="my-2">Select status:</label>
      <select value="" name="status"
        class="p-3 bg-gray-100 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-primary-400">
        <option value="<?php echo $user->status ?>">
          <?php
          if ($user->status == 1) {
            echo "Active";
          } else {
            echo "Banned";
          }
          ?>
        </option>
        <option value="1">Active</option>
        <option value="0">Banned</option>
      </select>

      <a class="px-4 py-2 my-1 font-bold text-center text-white bg-gray-500 rounded cancel-edit-button hover:bg-gray-700"
        href="<?php echo BASE_URI . "/dashboard/permission/update?id=" . $user->id; ?>">
        Permission
      </a>

      <button class="my-2 bg-[#2e524e] hover:bg-[#52938d] transition-colors text-white font-bold py-2 px-4 rounded"
        type="submit">
        Submit
      </button>
      <a class="px-4 py-2 my-1 font-bold text-center text-white bg-gray-500 rounded cancel-edit-button hover:bg-gray-700"
        href="<?php echo BASE_URI . "/dashboard/user"; ?>">
        Cancel
      </a>
    </form>
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
</script>
<script>
  const password = document.querySelector("input[type=password]");
  // console.log(password)
  const hideIconPassword = document.getElementById("hide-icon-password");
  const showIconPassword = document.getElementById("show-icon-password");

  hideIconPassword.addEventListener("click", function () {
    if (password.type === "password") {
      password.type = "text";
      hideIconPassword.style.display = "none";
      showIconPassword.style.display = "block";
    }
  });
  showIconPassword.addEventListener("click", function () {
    if (password.type === "text") {
      password.type = "password";
      hideIconPassword.style.display = "block";
      showIconPassword.style.display = "none";
    }
  });
  // const inputs = document.querySelectorAll("input");
  // inputs.forEach((input) => {
  //   input.addEventListener("blur", function () {
  //     if (input.type === "email") {
  //       if (!input.value.match(/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/g)) {
  //         input.style.border = "1px solid red";
  //       } else {
  //         input.style.border = "1px solid #d1d5db";
  //       }
  //     }
  //     if (input.type === "tel") {
  //       if (!input.value.match(/(84|0[3|5|7|8|9])+([0-9]{8})\b/g)) {
  //         input.style.border = "1px solid red";
  //       } else {
  //         input.style.border = "1px solid #d1d5db";
  //       }
  //     }
  //     if (input.type === "password") {
  //       if (!input.value.match(/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/g)) {
  //         input.style.border = "1px solid red";
  //       } else {
  //         input.style.border = "1px solid #d1d5db";
  //       }
  //     }
  //   });
  // });
</script>