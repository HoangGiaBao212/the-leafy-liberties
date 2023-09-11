<?php $product = $params["product"];
use App\Models\Author;
use App\Models\Publisher;
use App\Models\Category;
use App\Models\Tag; ?>
<div class="w-full min-h-screen">
  <div class="w-full h-full p-5 m-5 bg-white rounded-md shadow-lg">
    <form class="flex flex-col" action="<?php echo BASE_URI . "/dashboard/product/update"; ?>" method="POST"
      enctype="multipart/form-data">
      <input type="hidden" value="<?php echo $product->id; ?>" name="id"
        class="p-3 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-400" required />

      <label for="title" class="my-2">Title:</label>
      <input type="text" value="<?php echo $product->name; ?>" name="name"
        class="p-3 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-400" required />

      <label for="img" class="my-2">Image:</label>
      <input type="file" name="image" id="imgInp" onchange="loadFile(event)" />
      <p>Preview Image:</p>
      <img id="output" class="object-contain h-56 w-80" src="<?php if ($product)
        echo BASE_URI . $product->image;
      else
        echo BASE_URI . "/resources/images/product-placeholder.png" ?>" accept="image/*" />
        <button type="button" class="px-4 py-2 my-2 font-bold text-white bg-red-500 rounded hover:bg-red-700"
          onclick="removeImage()">Remove Image</button>

        <input type="text" value="<?php echo $product->image; ?>" id="old-image" name="old_img" class="opacity-0" />
      <label for="entered" class="my-2">ISBN:</label>
      <input type="number" value="<?php echo $product->isbn; ?>" name="isbn"
        class="p-3 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-400" required
        onkeydown="if (event.keyCode === 69 || event.keyCode === 189 || event.keyCode == 107 || event.keyCode == 110 || event.keyCode == 109) return false;" />
      <label for="price" class="my-2">Price:</label>
      <input type="number" value="<?php echo $product->price; ?>" name="price"
        class="p-3 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-400" required
        onkeydown="if (event.keyCode === 69 || event.keyCode === 189 || event.keyCode == 107 || event.keyCode == 110 || event.keyCode == 109) return false;" />
      <?php
      $categories = Category::all();
      $categoriesOfProduct = $product->categories();
      ?>
      <label for="category" class="my-2">Category:</label>

      <div class="flex flex-wrap">
        <?php foreach ($categories as $category): ?>
          <div class="w-1/2">
            <input type="checkbox" name="categories[]" id="category-<?php echo $category->id ?>"
              value="<?php echo $category->id ?>"
              class="p-3 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-400" <?php foreach ($categoriesOfProduct as $categoryOfProduct) {
                if ($categoryOfProduct->id == $category->id) {
                  echo "checked";
                }
              }
              ?> />
            <label for="category-<?php echo $category->id ?>" class="my-2">
              <?php echo $category->name ?>
            </label>
          </div>
        <?php endforeach ?>
      </div>
      <?php
      $tags = Tag::all();
      $tagsOfProduct = $product->tags();
      ?>
      <label for="tag" class="my-2">Tag:</label>
      <div class="flex flex-wrap">
        <?php foreach ($tags as $tag): ?>
          <div class="w-1/2">
            <input type="checkbox" name="tags[]" id="tag-<?php echo $tag->id ?>" value="<?php echo $tag->id ?>"
              class="p-3 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-400" <?php foreach ($tagsOfProduct as $tagOfProduct) {
                if ($tagOfProduct->id == $tag->id) {
                  echo "checked";
                }
              }
              ?> />
            <label for="tag-<?php echo $tag->id ?>" class="my-2">
              <?php echo $tag->name ?>
            </label>
          </div>
        <?php endforeach ?>
      </div>

      <label for="author" class="my-2">Author:</label>
      <select value="" name="author"
        class="p-3 bg-gray-100 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-primary-400"
        required>
        <option value="<?php echo $product->author()->id ?>">
          <?php echo $product->author()->name ?>
        </option>
        <?php foreach (Author::all() as $authorItem): ?>
          <option value="<?php echo $authorItem->id ?>">
            <?php echo $authorItem->name ?>
          </option>
        <?php endforeach ?>
      </select>
      <label for="publisher" class="my-2">Publisher:</label>
      <select value="" name="publisher"
        class="w-full p-3 bg-gray-100 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-primary-400"
        required>
        <option value="<?php echo $product->publisher()->id ?>">
          <?php echo $product->publisher()->name ?>
        </option>
        <?php foreach (Publisher::all() as $publish): ?>
          <option value="<?php echo $publish->id ?>">
            <?php echo $publish->name ?>
          </option>
        <?php endforeach ?>
      </select>
      <label for="category" class="my-2">Description:</label>
      <textarea name="description" id="" cols="30" rows="6"
        class="p-3 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-400 "><?php echo $product->description; ?>
      </textarea>

      <label for="quantity" class="my-2">Quantity:</label>
      <input type="number" value="<?php echo $product->quantity; ?>" name="quantity"
        class="p-3 bg-gray-100 rounded-lg focus:outline-none" required
        onkeydown="if (event.keyCode === 69 || event.keyCode === 189 || event.keyCode == 107) return false;" />

      <button class="my-2 bg-[#2e524e] hover:bg-[#52938d] transition-colors text-white font-bold py-2 px-4 rounded"
        type="submit">
        Submit
      </button>
      <a class="px-4 py-2 my-1 font-bold text-center text-white bg-gray-500 rounded cancel-edit-button hover:bg-gray-700"
        href="<?php echo BASE_URI . "/dashboard/product"; ?>">
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
<script>
  let button = document.querySelector("button[type='submit']");
  let inputName = document.querySelector("input[name='name']");
  let inputIsbn = document.querySelector("input[name='isbn']");
  let inputPrice = document.querySelector("input[name='price']");
  let inputDescription = document.querySelector("input[name='description']");
  let inputQuantity = document.querySelector("input[name='quantity']");
  let inputAuthor = document.querySelector("select[name='author']");
  let inputPublisher = document.querySelector("select[name='publisher']");
  button.addEventListener("click", () => {
    if (inputName.value.trim() === '') {
      alert('Please enter a name');
      inputName.focus()
      return;
    }
    if (inputIsbn.value.trim() === '') {
      alert('Please enter an ISBN');
      inputIsbn.focus()
      return;
    }
    if (inputPrice.value.trim() === '') {
      alert('Please enter a price');
      inputPrice.focus()

      return;
    }
    if (inputPrice.value < 0) {
      alert("Please enter a number greater than 0")
    }
    if (inputDescription.value.trim() === '') {
      alert('Please enter a description');
      inputDescription.focus()

      return;
    }
    if (inputQuantity.value.trim() === '') {
      alert('Please enter a quantity');
      inputQuantity.focus()
      return;
    }
    if (inputQuantity.value < 0) {
      alert("Please enter a number greater than 0")
    }
    if (inputAuthor.value.trim() === '') {
      alert('Please select an author');
      inputAuthor.focus()

      return;
    }
    if (inputPublisher.value.trim() === '') {
      alert('Please select a publisher');
      inputPublisher.focus()
      return;
    }
  });
  document.removeImage = () => {
    document.getElementById('output').src = "<?php echo BASE_URI . "/resources/images/product-placeholder.png" ?>";
    document.getElementById('old-image').value = "<?php echo BASE_URI . "/resources/images/product-placeholder.png" ?>";
  }
</script>