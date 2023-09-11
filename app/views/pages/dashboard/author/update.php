<?php $author = $params["author"];
use App\Models\Author;
use App\Models\Publisher;

?>
<div class="w-full min-h-screen">
  <div class="w-full h-full p-5 m-5 bg-white rounded-md shadow-lg">
    <form class="flex flex-col" action="<?php echo BASE_URI . "/dashboard/author/update"; ?>" method="POST"
      enctype="multipart/form-data">
      <input type="hidden" value="<?php echo $author->id; ?>" name="id" class="" />

      <label for="title" class="my-2">Title:</label>
      <input type="text" value="<?php echo $author->name; ?>" name="name"
        class="p-3 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-400" required
        pattern="^[a-zA-Z]+(([',. -][a-zA-Z ])?[a-zA-Z]*)*$"
        oninvalid="this.setCustomValidity('Please enter alphabets only')" onvalid="this.setCustomValidity('')" />

      <label for="img" class="my-2">Image:</label>
      <input type="file" name="image" id="imgInp" onchange="loadFile(event)" />
      <p>Preview Image:</p>
      <img id="output" class="object-contain h-56 w-80" src="<?php echo BASE_URI . $author->image ?>" />
      <input type="text" value="<?php echo $author->image; ?>" name="old_img" class="opacity-0" />
      <button class="my-2 bg-[#2e524e] hover:bg-[#52938d] transition-colors text-white font-bold py-2 px-4 rounded"
        type="submit">
        Submit
      </button>
      <a class="px-4 py-2 my-1 font-bold text-center text-white bg-gray-500 rounded cancel-edit-button hover:bg-gray-700"
        href="<?php echo BASE_URI . "/dashboard/author"; ?>">
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