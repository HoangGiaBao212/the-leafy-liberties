<?php $setting = $params["setting"]; ?>
<div class="w-full min-h-screen ">
  <div class="w-full p-5 my-5 bg-white rounded-md shadow-lg">
    <form class="flex flex-col" action="<?php echo BASE_URI .
      "/dashboard/setting/update"; ?>" method="POST">
      <input type="hidden" value="<?php echo $setting->id; ?>" name="id"
        class="p-3 bg-gray-100 rounded-lg focus:outline-none" required />

      <label for="name" class="my-2">Name:</label>
      <input type="text" value="<?php echo $setting->name; ?>" name="name"
        class="p-3 bg-gray-100 rounded-lg focus:outline-none" required />

      <label for="expired" class="my-2">Value:</label>
      <input type="number" value="<?php echo $setting->value; ?>" name="value"
        class="p-3 bg-gray-100 rounded-lg focus:outline-none" required />

      <button class="my-2 bg-[#2e524e] hover:bg-[#52938d] transition-colors text-white font-bold py-2 px-4 rounded"
        type="submit">
        Submit
      </button>
      <a class="px-4 py-2 my-1 font-bold text-center text-white bg-gray-500 rounded cancel-edit-button hover:bg-gray-700"
        href="<?php echo BASE_URI . "/dashboard/customer"; ?>">
        Cancel
      </a>
    </form>
  </div>
</div>
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
  const inputs = document.querySelectorAll("input");
  inputs.forEach((input) => {
    input.addEventListener("blur", function () {
      if (input.type === "email") {
        if (!input.value.match(/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/g)) {
          input.style.border = "1px solid red";
        } else {
          input.style.border = "1px solid #d1d5db";
        }
      }
      if (input.type === "tel") {
        if (!input.value.match(/(84|0[3|5|7|8|9])+([0-9]{8})\b/g)) {
          input.style.border = "1px solid red";
        } else {
          input.style.border = "1px solid #d1d5db";
        }
      }
      if (input.type === "password") {
        if (!input.value.match(/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/g)) {
          input.style.border = "1px solid red";
        } else {
          input.style.border = "1px solid #d1d5db";
        }
      }
    });
  });
</script>