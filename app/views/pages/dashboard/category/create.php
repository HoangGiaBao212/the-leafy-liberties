<div class="items-center justify-center w-full min-h-screen add-form">
  <div class="p-8 bg-white rounded-md shadow-lg ">
    <h2 class="mb-4 text-xl font-bold">Add Category</h2>
    <form class="flex flex-col" action="<?php BASE_URI . "/dashboard/category/create"; ?>" method="POST"
      enctype="multipart/form-data">
      <label for="image123" class="my-2">Image:</label>
      <input type="file" name="image" onchange="loadFile(event)" required />
      <p>Preview Image:</p>
      <img id="output1" class="object-contain h-56 w-80" />
      <label for="entered" class="my-2">Name:</label>
      <input type="text" value="" name="name" class="p-3 bg-gray-100 rounded-lg focus:outline-none" required />
      <button class="my-2 bg-[#2e524e] hover:bg-[#52938d] transition-colors text-white font-bold py-2 px-4 rounded"
        type="submit">
        Submit
      </button>
      <a href="<?php echo BASE_URI . '/dashboard/category' ?>"
        class="px-4 py-2 my-1 font-bold text-center text-white bg-gray-500 rounded cancel-button hover:bg-gray-700">
        Cancel
      </a>
    </form>
  </div>
</div>
<script>
  var loadFile = function (event) {
    var output = document.getElementById('output1');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function () {
      URL.revokeObjectURL(output.src) // free memory
    }
  };
</script>