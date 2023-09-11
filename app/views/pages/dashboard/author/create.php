<?php
use App\Models\Author;
use App\Models\Publisher;

?>
<div class="add-form min-h-screen w-full justify-center items-center bg-opacity-75 z-[500] my-5">
  <div class="w-full p-8 bg-white rounded-md shadow-lg ">
    <h2 class="mb-4 text-xl font-bold">Add Author</h2>
    <form class="flex flex-col" action="<?php echo BASE_URI . "/dashboard/author/create"; ?>" method="POST"
      enctype="multipart/form-data">
      <label for="title" class="my-2">Author Name:</label>
      <input type="text" value="" name="name"
        class="p-3 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-400" required
        pattern="^[a-zA-Z]+(([',. -][a-zA-Z ])?[a-zA-Z]*)*$"
        oninvalid="this.setCustomValidity('Please enter alphabets only')" onvalid="this.setCustomValidity('')" />

      <label for="image" class="my-2">Image:</label>
      <input type="file" name="image" onchange="loadFile(event)" required />
      <p>Preview Image:</p>
      <img id="output1" class="object-contain h-56 w-80" />

      <button class="my-2 bg-[#2e524e] hover:bg-[#52938d] transition-colors text-white font-bold py-2 px-4 rounded"
        type="submit">
        Submit
      </button>
      <a class="px-4 py-2 my-1 font-bold text-center text-white bg-gray-500 rounded cancel-button hover:bg-gray-700"
        href="<?php echo BASE_URI . '/dashboard/author' ?>">
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