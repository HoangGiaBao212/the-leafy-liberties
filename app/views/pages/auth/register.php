<section class="flex flex-col items-center justify-center h-screen px-6 py-8 mx-auto lg:py-0">
  <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0">
    <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
      <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
        Create and account
      </h1>
      <!-- Start form -->
      <form class="space-y-4 md:space-y-6" action="<?php echo BASE_URI .
        "/register"; ?>" method="post">
        <!-- Email -->
        <div>
          <label for="email" class="block mb-2 text-sm font-medium text-gray-900">
            Email
          </label>
          <input type="email" name="email"
            class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-[#52938d] focus:border-[#52938d] block w-full p-2.5"
            placeholder="Enter your email here" required />
        </div>
        <!-- Name -->
        <div>
          <label for="name" class="block mb-2 text-sm font-medium text-gray-900">
            Name
          </label>
          <input type="text" name="name"
            class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-[#52938d] focus:border-[#52938d] block w-full p-2.5"
            placeholder="Enter your name here" required />
        </div>
        <!-- Phone -->
        <div>
          <label for="phone" class="block mb-2 text-sm font-medium text-gray-900">
            Phone
          </label>
          <input type="tel" name="phone" pattern="(84|0[3|5|7|8|9])+([0-9]{8})\b" required
            class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-[#52938d] focus:border-[#52938d] block w-full p-2.5"
            placeholder="Enter your phone here" />
        </div>
        <!-- Password -->
        <div class='relative'>
          <label for="password" class="block mb-2 text-sm font-medium text-gray-900">
            Password
          </label>
          <input type="password" name="password" placeholder="••••••••"
            pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$"
            class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-[#52938d] focus:border-[#52938d] block w-full p-2.5"
            required />
          <i id="hide-icon-password" class="fa fa-eye-slash absolute top-[60%] right-4 cursor-pointer"
            aria-hidden="true"></i>
          <i id="show-icon-password" class="fa fa-eye absolute hidden top-[60%] right-4 cursor-pointer"
            aria-hidden="true"></i>
        </div>
        <!-- Terms and Conditions -->
        <div class="flex items-start">
          <div class="flex items-center h-5">
            <input id="terms" aria-describedby="terms" type="checkbox"
              class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-[#add1ce] accent-[#52938d]"
              required />
          </div>
          <div class="ml-3 text-sm">
            <label for="terms" class="font-light text-gray-500 dark:text-gray-300">
              I accept the
              <a class="font-medium text-[#52938d] hover:underline" href="#">
                Terms and Conditions
              </a>
            </label>
          </div>
        </div>
        <!-- Submit button -->
        <button type="submit"
          class="w-full text-white bg-[#52938d] hover:bg-[#40736d] focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
          Create an account
        </button>
      </form>
      <p class="text-sm font-light text-gray-500">
        Already have an account?
        <a href="login" class="font-medium text-[#52938d] hover:underline" id="my-link1">
          Login here
        </a>
      </p>
    </div>
  </div>
</section>
<script>
  const password = document.querySelector("[name=password]")

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

  // validate all input when mouse out if not match pattern then show error
  let btnSubmit = document.querySelector("button[type='submit']")
  let inputEmail = document.querySelector("input[type='email']")
  btnSubmit.addEventListener("click", () => {
    let parts = inputEmail.value.split("@");
    if (parts[0] === '') {
      alert("The mail address must not be empty")
    }
    if (!isNaN(parts[0])) {
      alert("The email must contain one alphabet!!!")
    }
  })
</script>
