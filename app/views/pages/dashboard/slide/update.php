<?php $slide = $params["slide"];
use App\Models\Slide; ?>
<div class="w-full min-h-screen">
  <div class="w-full h-full p-5 m-5 bg-white rounded-md shadow-lg">
    <form class="flex flex-col" action="<?php echo BASE_URI .
      "/dashboard/slide/update"; ?>" method="POST" enctype="multipart/form-data">
      <input type="hidden" value="<?php echo $slide->id; ?>" name="id"
        class="p-3 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-400" required />

      <label for="title" class="my-2">Title:</label>
      <input type="text" value="<?php echo $slide->name; ?>" name="title"
        class="p-3 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-400" required />
      <label for="title" class="my-2">Image:</label>
      <input type="file" value="<?php echo $slide->image; ?>" name="image"
        class="p-3 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-400" onchange="loadFile(event)" />
      <div class="">
        <p>Preview Image:</p>
        <img id="output" class="object-contain h-56 w-80" src="<?php echo BASE_URI . $slide->image ?>" />
      </div>
      <input type="text" value="<?php echo $slide->image; ?>" name="old_img" class="opacity-0" />

      <label for="role" class="my-2">Select status:</label>
      <select value="" name="status" class="p-3 bg-gray-100 rounded-lg appearance-none focus:outline-none">
        <option value="<?php $slide->status ?>">
          <?php
          echo ($slide->status == 1) ? 'Active' : 'Banned';
          ?>
        </option>
        <option value="1">Active</option>
        <option value="0">Banned</option>

      </select>

      <button class="my-2 bg-[#2e524e] hover:bg-[#52938d] transition-colors text-white font-bold py-2 px-4 rounded"
        type="submit">
        Submit
      </button>
      <a class="px-4 py-2 my-1 font-bold text-center text-white bg-gray-500 rounded cancel-edit-button hover:bg-gray-700"
        href="<?php echo BASE_URI . "/dashboard/slide"; ?>">
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
