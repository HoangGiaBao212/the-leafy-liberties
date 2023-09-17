<?php
use App\Models\Author;
use App\Models\Category;
use App\Models\Publisher;
use App\Models\Tag;

?>
<div class="add-form min-h-screen w-full justify-center items-center bg-opacity-75 z-[500] my-5">
    <div class="w-full p-8 bg-white rounded-md shadow-lg ">
        <h2 class="mb-4 text-xl font-bold">Add Product</h2>
        <form class="flex flex-col" action="<?php echo BASE_URI . "/dashboard/product/create"; ?>" method="POST"
            enctype="multipart/form-data">
            <label for="title" class="my-2">Title:</label>
            <input type="text" value="" name="name"
                class="p-3 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-400" required
                pattern="^[a-zA-Z]+(([',. -][a-zA-Z ])?[a-zA-Z]*)*$"
                oninvalid="this.setCustomValidity('Please enter alphabets only')"
                onvalid="this.setCustomValidity('')" />

            <label for="image" class="my-2">Image:</label>
            <input type="file" name="image" onchange="loadFile(event)" required accept="image/*" />
            <p>Preview Image:</p>
            <img id="output1" class="object-contain h-56 w-80" />

            <label for="entered" class="my-2">ISBN:</label>
            <input type="number" value="" name="isbn"
                class="p-3 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-400" required
                onkeydown="if (event.keyCode === 69 || event.keyCode === 189 || event.keyCode == 107 || event.keyCode == 110 || event.keyCode == 109) return false;" />

            <label for="price" class="my-2">Price:</label>
            <input type="number" value="" step="0.01" name="price"
                class="p-3 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-400" required
                onkeydown="if (event.keyCode === 69 || event.keyCode === 189 || event.keyCode == 107 || event.keyCode == 110 || event.keyCode == 109) return false;" />

            <?php
      $categories = Category::all();
      ?>
            <label for="category" class="my-2">Category:</label>

            <div class="flex flex-wrap">
                <?php foreach ($categories as $category): ?>
                <div class="w-1/2">
                    <input type="checkbox" name="categories[]" id="category-<?php echo $category->id ?>"
                        value="<?php echo $category->id ?>"
                        class="p-3 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-400" />
                    <label for="category-<?php echo $category->id ?>" class="my-2">
                        <?php echo $category->name ?>
                    </label>
                </div>
                <?php endforeach ?>
            </div>
            <?php
      $tags = Tag::all();
      ?>
            <label for="tag" class="my-2">Tag:</label>
            <div class="flex flex-wrap">
                <?php foreach ($tags as $tag): ?>
                <div class="w-1/2">
                    <input type="checkbox" name="tags[]" id="tag-<?php echo $tag->id ?>" value="<?php echo $tag->id ?>"
                        class="p-3 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-400" />
                    <label for="tag-<?php echo $tag->id ?>" class="my-2">
                        <?php echo $tag->name ?>
                    </label>
                </div>
                <?php endforeach ?>
            </div>

            <label for="gender" class="my-2">Author:</label>
            <div class="flex items-center justify-between gap-4">
                <select value="" name="author"
                    class="w-full p-3 bg-gray-100 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-primary-400"
                    required>
                    <option value=""></option>
                    <?php foreach (Author::all() as $author): ?>
                    <option value="<?php echo $author->id ?>">
                        <?php echo $author->name ?>
                    </option>
                    <?php endforeach ?>
                </select>
                <i class="p-2 text-white rounded-full fa-solid fa-plus plus-icon bg-primary"></i>
            </div>
            <div class="flex-col hidden author-add">
                <input type="hidden" name="author-id" />
                <label for="title" class="my-2">Add new author name:</label>
                <input type="text" value="" name="author-name"
                    class="p-3 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-400" />
            </div>
            <label for="gender" class="my-2">Publisher:</label>
            <div class="flex items-center justify-between gap-4">
                <select value="" name="publisher"
                    class="w-full p-3 bg-gray-100 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-primary-400"
                    required>
                    <option value=""></option>
                    <?php foreach (Publisher::all() as $publish): ?>
                    <option value="<?php echo $publish->id ?>">
                        <?php echo $publish->name ?>
                    </option>
                    <?php endforeach ?>
                </select>
            </div>
            <label for="category" class="my-2">Description:</label>
            <textarea name="description" id="" cols="30" rows="4"
                class="p-3 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-400"
                required></textarea>

            <label for="quantity" class="my-2">Quantity:</label>
            <input type="number" value="0" name="quantity"
                class="p-3 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-400" required
                onkeydown="if (event.keyCode === 69 || event.keyCode === 189 || event.keyCode == 107) return false;"
                disabled />
            <button
                class="my-2 bg-[#2e524e] hover:bg-[#52938d] transition-colors text-white font-bold py-2 px-4 rounded"
                type="submit">
                Submit
            </button>
            <a class="px-4 py-2 my-1 font-bold text-center text-white bg-gray-500 rounded cancel-button hover:bg-gray-700"
                href="<?php echo BASE_URI . '/dashboard/product' ?>">
                Cancel
            </a>
        </form>
    </div>
</div>
<script>
var loadFile = function(event) {
    var output = document.getElementById('output1');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
        URL.revokeObjectURL(output.src) // free memory
    }
};
let icon = document.querySelector(".plus-icon");
icon.addEventListener("click", () => {
    if (icon.classList.contains("fa-plus")) {
        icon.classList.add("fa-minus");
        icon.classList.remove("fa-plus");
        document.querySelector(".author-add").classList.remove("hidden")
        document.querySelector(".author-add").classList.add("flex")


    } else {
        icon.classList.remove("fa-minus");
        icon.classList.add("fa-plus");
        document.querySelector(".author-add").classList.add("hidden")
        document.querySelector(".author-add").classList.remove("flex")
    }

})
let button = document.querySelector("button[type='submit']");
let inputName = document.querySelector("input[name='name']");
let inputImage = document.querySelector("input[name='image']");
let inputIsbn = document.querySelector("input[name='isbn']");
let inputPrice = document.querySelector("input[name='price']");
let inputDescription = document.querySelector("input[name='description']");
let inputQuantity = document.querySelector("input[name='quantity']");
let inputAuthor = document.querySelector("select[name='description']");
let inputPublisher = document.querySelector("select[name='description']");
button.addEventListener("click", () => {
    if (inputName.value.trim() === '') {
        alert('Please enter a name');
        inputName.focus()
        return;
    }
    if (inputImage.value.trim() === '') {
        alert('Please select an image');
        inputImage.focus()

        return;
    }
    if (inputIsbn.value.trim() === '') {
        alert('Please enter an ISBN');
        inputIsbn.focus()

        // console.log(typeof inputIsbn.value);
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

</script>