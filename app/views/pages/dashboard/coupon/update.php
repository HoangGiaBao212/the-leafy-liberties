<?php $coupon = $params["coupon"]; ?>
<div class="w-full min-h-screen">
  <div class="w-full h-full p-5 m-5 bg-white rounded-md shadow-lg">
    <form class="flex flex-col" action="<?php echo BASE_URI .
      "/dashboard/coupon/update"; ?>" method="POST">
      <input type="hidden" value="<?php echo $coupon->id; ?>" name="id"
        class="p-3 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-400" required />

      <label for="code" class="my-2">Code:</label>
      <input type="text" value="<?php echo $coupon->code; ?>" name="code"
        class="p-3 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-400" required />

      <label for="expired" class="my-2">Expired:</label>
      <input type="date" value="<?php echo $coupon->expired; ?>" name="expired"
        class="p-3 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-400" id="task_date"
        onchange="return CheckExpired();" required />

      <label for="description" class="my-2">Description:</label>
      <textarea name="description" id="" cols="30" rows="6"
        class="p-3 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-400" required><?php echo $coupon->description; ?>
      </textarea>

      <label for="quantity" class="my-2">Quantity:</label>
      <input type="text" value="<?php echo $coupon->quantity; ?>" name="quantity"
        class="p-3 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-400" required
        onkeydown="if (event.keyCode === 69 || event.keyCode === 189 || event.keyCode == 107 || event.keyCode == 110 || event.keyCode == 109) return false;" />

      <button class="my-2 bg-[#2e524e] hover:bg-[#52938d] transition-colors text-white font-bold py-2 px-4 rounded"
        type="submit">
        Submit
      </button>
      <a class="px-4 py-2 my-1 font-bold text-center text-white bg-gray-500 rounded cancel-edit-button hover:bg-gray-700"
        href="<?php echo BASE_URI . "/dashboard/coupon"; ?>">
        Cancel
      </a>
    </form>
  </div>
</div>
<script>
  function CheckExpired() {
    var current = new Date(document.getElementById('task_date').value);
    var today = new Date();
    if (current.getTime() <= today.getTime()) {
      alert("You Can't Assign Task For Expired Date");
      document.getElementById('task_date').value = "<?php echo $coupon->expired; ?>";
    }
    else {
      return true;
    }
  }
</script>
