<div class="items-center justify-center w-full min-h-screen my-8 add-form">
  <div class="p-8 bg-white rounded-md shadow-lg">
    <h2 class="mb-4 text-xl font-bold">Add Coupon</h2>
    <form class="flex flex-col" method="POST" action="<?php BASE_URI .
      "/dashboard/coupon/create"; ?>">
      <label for="image" class="my-2">Code:</label>
      <input type="text" value=""
        class="p-3 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-400" name="code"
        required />
      <label for="category" class="my-2">Description:</label>
      <textarea name="description"
        class="p-3 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-400" rows="4"
        required></textarea>
      <!-- <script>
            CKEDITOR.replace('description');
          </script> -->
      <label for="expired" class="my-2">Expired:</label>
      <input type="date" value="" id="task_date"
        class="p-3 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-400" name="expired"
        onchange="return CheckExpired();" required />
      <label for="remaining" class="my-2">Quantity:</label>
      <input type="number" value=""
        class="p-3 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-400" name="quantity"
        onkeydown="if (event.keyCode === 69 || event.keyCode === 189 || event.keyCode == 107 || event.keyCode == 110 || event.keyCode == 109) return false;"
        required />
      <button class="my-2 bg-[#2e524e] hover:bg-[#52938d] transition-colors text-white font-bold py-2 px-4 rounded"
        type="submit">
        Submit
      </button>
      <button id="cancel" class="px-4 py-2 my-1 font-bold text-white bg-gray-500 rounded hover:bg-gray-700">
        Cancel
      </button>
    </form>
  </div>
</div>
<script>
  function CheckExpired() {
    var current = new Date(document.getElementById('task_date').value);
    var today = new Date();
    if (current.getTime() <= today.getTime()) {
      alert("You Can't Assign Task For Expired Date");
      document.getElementById('task_date').value = "";
    }
    else {
      return true;
    }
  }
</script>
