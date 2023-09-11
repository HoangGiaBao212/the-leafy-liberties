<?php
use App\Models\Role; ?>
<div class="add-form min-h-screen w-full justify-center items-center bg-opacity-75 z-[300]">
  <div class="w-full p-8 my-8 bg-white rounded-md shadow-lg">
    <h2 class="mb-4 text-xl font-bold">Add User</h2>
    <form class="flex flex-col" action="<?php echo BASE_URI .
      "/dashboard/user/create"; ?>" method="POST" enctype="multipart/form-data">
      <label for="image" class="my-2">Image:</label>
      <input type="file" name="image" onchange="loadFile(event)" />
      <p>Preview Image:</p>
      <img id="output" class="object-contain h-56 w-80" />
      <label for="entered" class="my-2">Email:</label>
      <input type="email" value="" class="p-3 bg-gray-100 rounded-lg focus:outline-none" name="email" required />

      <label for="category" class="my-2">Name:</label>
      <input type="text" value="" class="p-3 bg-gray-100 rounded-lg focus:outline-none" name="name" required />

      <label for="remaining" class="my-2">Password:</label>
      <input type="password" value="" class="p-3 bg-gray-100 rounded-lg focus:outline-none" name="password" required />
      <i id="hide-icon-password" class="fa fa-eye-slash absolute top-[74%] right-20 cursor-pointer"
        aria-hidden="true"></i>
      <i id="show-icon-password" class="fa fa-eye absolute hidden top-[74%] right-20 cursor-pointer"
        aria-hidden="true"></i>
      <label for="remaining" class="my-2">Phone:</label>
      <input type="tel" value="" class="p-3 bg-gray-100 rounded-lg focus:outline-none" name="phone"
        pattern="^[0-9]{10}$" oninvalid="this.setCustomValidity('Please enter a valid phone number')"
        onvalid="this.setCustomValidity('')" required />

      <label for="role" class="my-2">Select role:</label>
      <select value="" name="role" class="p-3 bg-gray-100 rounded-lg appearance-none focus:outline-none">
        <?php foreach (Role::all() as $role): ?>
          <option value="<?php echo $role->id ?>"><?php echo ucfirst($role->name) ?></option>
        <?php endforeach ?>
      </select>
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
