<div class="w-full mx-auto my-0">
  <div class="box-border w-full min-h-screen px-10 mt-10 sm:px-5">
    <div class="flex justify-between">
      <h1 class="text-xl font-bold">Settings</h1>
      <div class="box-border w-1/2 px-10">
        <form class="flex items-center justify-center w-full h-10 bg-white rounded-full input shadow-lg"
          action="<?php BASE_URI . "/dashboard/product"; ?>" method="POST">
          <input type="text" name="searchQuery"
            class="w-full h-full pl-5 bg-transparent rounded-tl-full rounded-bl-full" placeholder="Search.... " />
          <button class="flex items-center justify-center w-10 h-10 bg-white rounded-tr-full rounded-br-full">
            <i class="fa-solid fa-magnifying-glass"></i>
          </button>
        </form>
      </div>
    </div>
    <div class="my-8 bg-white shadow-lg cursor-pointer table-customer-statistics rounded-2xl">
      <div class="relative">
        <table class="w-full text-sm text-center text-gray-500 rounded-2xl">
          <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
              <?php

              use App\Models\Role;
              use App\Models\Setting;

              $name = [
                "ID",
                "Name",
                "Value",
                "Action",
              ];
              for ($i = 1; $i <= count($name); $i++) { ?>
                <th scope="col" class="px-6 py-3">
                  <?php echo $name[$i - 1]; ?>
                </th>
              <?php }
              ?>
            </tr>
          </thead>
          <tbody>
            <?php
            $settings = Setting::all();
            if (count($settings) > 0): ?>
              <?php foreach ($settings as $setting): ?>
                <tr class="text-center transition-opacity bg-white border-b hover:bg-gray-200 even:bg-gray-100">
                  <td class="px-5 py-3">
                    <?php echo $setting->id; ?>
                  </td>
                  <td class="px-5 py-3">
                    <?php echo $setting->name; ?>
                  </td>
                  <td class="px-5 py-3">
                    <?php echo $setting->value; ?>
                  </td>
                  <td class="px-5 py-3 w-44">
                    <div class="flex items-center justify-center gap-4 button">
                      <a href="<?php echo BASE_URI .
                        "/dashboard/setting/update" .
                        "?id=" .
                        $setting->id; ?>"
                        class="px-3 py-2 text-white transition-all bg-blue-400 edit-button rounded-xl hover:bg-blue-500">
                        <i class="fa-solid fa-pen-to-square"></i>
                      </a>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif;
            ?>
          </tbody>
        </table>
      </div>
    </div>
    <!-- <div class="add-form fixed top-0 left-0 h-full w-full hidden justify-center items-center bg-gray-400 bg-opacity-75 z-[300]">
            <div class="bg-white p-8 rounded-md shadow-lg w-[550px]">
                <h2 class="mb-4 text-xl font-bold">Add Customer</h2>
                <form class="flex flex-col" action="<?php echo BASE_URI .
                  "/dashboard/setting/update"; ?>" method="POST">
                    <label for="image" class="my-2">Image:</label>
                    <input type="file" name="image" />
                    <label for="entered" class="my-2">Email:</label>
                    <input type="number" value="" class="p-3 bg-gray-100 rounded-lg focus:outline-none" name="email" />
                    <label for="category" class="my-2">Name:</label>
                    <input type="text" value="" class="p-3 bg-gray-100 rounded-lg focus:outline-none" name="name" />
                    <label for="remaining" class="my-2">Password:</label>
                    <input type="number" value="" class="p-3 bg-gray-100 rounded-lg focus:outline-none" name="password" />
                    <label for="remaining" class="my-2">Phone:</label>
                    <input type="number" value="" class="p-3 bg-gray-100 rounded-lg focus:outline-none" name="phone" />
                    <label for="status" class="my-2">Gender:</label>
                    <select value="" name="gender" class="p-3 bg-gray-100 rounded-lg focus:outline-none">
                        <option value="">Select gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>

                    <button class="my-2 bg-[#2e524e] hover:bg-[#52938d] transition-colors text-white font-bold py-2 px-4 rounded" type="submit">
                        Submit
                    </button>
                    <button class="px-4 py-2 my-1 font-bold text-white bg-gray-500 rounded cancel-button hover:bg-gray-700" type="button">
                        Cancel
                    </button>
                </form>
            </div>
        </div> -->
  </div>
</div>
<script>
  let btn = document.querySelector(".add-setting")
  btn.addEventListener("click", () => {
    document.querySelector(".add-form").classList.add("flex");
    document.querySelector(".add-form").classList.remove("hidden");

  })
  let cancel = document.querySelector(".cancel-button");
  cancel.addEventListener("click", (event) => {
    event.preventDefault();
    document.querySelector(".add-form").classList.add("hidden");
  })
</script>