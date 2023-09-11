<section class="flex flex-col items-center justify-center h-screen px-6 py-8 mx-auto lg:py-0">
  <div class="w-full bg-white rounded-lg shadow-[0_0_10px_3px_rgba(0,0,0,0.1)] md:mt-0 sm:max-w-md xl:p-0">
    <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
      <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl">
        Sign in to your account
      </h1>
      <form class="space-y-4 md:space-y-6" action="<?php echo BASE_URI .
        "/login"; ?>" method="post">
        <div>
          <label for="email" class="block mb-2 text-sm font-medium text-gray-900">
            Your email
          </label>
          <input type="email" name="email"
            class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-[#52938d] focus:border-[#52938d] block w-full p-2.5"
            placeholder="Enter email" required />
        </div>
        <div class="relative">
          <label for="password" class="block mb-2 text-sm font-medium text-gray-900">
            Password
          </label>
          <input type="password" name="password" id="password" placeholder="••••••••"
            class=" bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-[#52938d] focus:border-[#52938d] block w-full p-2.5"
            required />
          <i id="hide-icon" class="fa fa-eye-slash absolute top-[60%] right-4 cursor-pointer" aria-hidden="true"></i>
          <i id="show-icon" class="fa fa-eye absolute hidden top-[60%] right-4 cursor-pointer" aria-hidden="true"></i>
        </div>
        <div class="flex items-center justify-between">
          <div class="flex items-start">
            <div class="flex items-center h-5">
              <input id="remember" aria-describedby="remember" type="checkbox"
                class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-[#add1ce]" />
            </div>
            <div class="ml-3 text-sm ">
              <label for="remember" class="text-gray-500">
                Remember me
              </label>
            </div>
          </div>
          <a href="#" class="text-sm font-medium text-[#52938d] hover:underline">
            Forgot password?
          </a>
        </div>
        <button type="submit"
          class="w-full text-white bg-[#52938d] hover:bg-[#40736d] focus:ring-4 focus:outline-none focus:ring-[#add1ce] font-medium rounded-lg text-sm px-5 py-2.5 text-center">
          Sign in
        </button>
        <p class="text-sm font-light text-gray-500 dark:text-gray-400">
          Don't have an account yet?
          <button type="submit" name="submit" class="font-medium text-[#52938d] hover:underline">
            <a href="register">Sign up</a>
          </button>
        </p>
      </form>
    </div>
  </div>
</section>
<script>
  const passwordInput = document.getElementById("password");
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
