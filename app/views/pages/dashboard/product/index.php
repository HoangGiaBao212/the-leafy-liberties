<?php

use App\Models\Author;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Pagination;
use App\Models\Tag;

$filter = $params['filter'];
// dd($filter);
?>

<div class="w-full mx-auto my-0">
  <div class="box-border w-full min-h-screen px-10 mt-10 sm:px-5">
    <div class="w-full mb-5 border border-gray-200 rounded-lg shadow-lg">
      <form class="w-full" id="filter-form" method="GET" action="<?php echo BASE_URI . '/dashboard/product' ?>">
        <div class="pb-2 border-b border-gray-200">
          <div class="relative flex flex-col px-4 py-2">
            <div class="flex items-center justify-between cursor-pointer">
              <h1 class="mt-2 mb-2 text-xl font-medium">Search</h1>
              <div class="px-3 py-1 border rounded-md border-primary-300 toggle-btn">
                <span class="toggle-simple">Simple</span>
                <span class="hidden toggle-advanced">Advanced</span>
              </div>

            </div>
            <div class="flex border border-gray-300">
              <input type="text" name="keywords" class="w-full h-10 px-3 py-2 " placeholder="Anything you want.... "
                value="<?php echo isset($params['filter']['keywords']) ? $params['filter']['keywords'] : '' ?>" />
              <button class="flex items-center justify-center w-10 h-10 bg-white ">
                <i class="fa-solid fa-magnifying-glass"></i>
              </button>
            </div>
          </div>
        </div>
        <div class="hidden advanced-search">
          <div class="border-b border-gray-200">
            <div class="relative px-4 py-2">
              <div class="flex items-center justify-between cursor-pointer dropdown-category">
                <h1 class="mt-2 mb-2 text-xl font-medium">Categories</h1>
                <i class="cursor-pointer open-category fa-solid fa-plus"></i>
              </div>
              <ul class="h-0 ml-2 overflow-hidden category-list">
                <li>
                  <?php foreach (Category::all() as $category): ?>
                  <li>
                    <label>
                      <input type="checkbox" name="categories[]" value="<?php echo $category->id ?>" <?php echo in_array($category->id, $filter['categories']) ? 'checked' : '' ?>>
                      <span class="ml-2">
                        <?php echo $category->name ?>
                      </span>
                    </label>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
          <div class="border-b border-gray-200">
            <div class="px-4 py-2">
              <h1 class="mt-2 mb-2 text-xl font-medium">Author</h1>
              <select name="author" id="" class="w-full px-3 py-1 border border-gray-300 rounded-sm appearance-none">
                <option value="">All</option>
                <?php foreach (Author::all() as $author): ?>
                  <option value="<?php echo $author->id ?>" <?php echo $filter['author'] == $author->id ? 'selected' : '' ?>>
                    <?php echo $author->name ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="border-b border-gray-200">
            <div class="px-4 py-2">
              <h1 class="text-xl font-medium">Price range</h1>
              <div class="flex items-center justify-start gap-2 py-3">
                <input type="number" name="min-price" value="<?php echo $filter['price']['min'] ?>"
                  class="w-20 px-3 py-1 border border-gray-300 rounded-sm"
                  onkeydown="if (event.keyCode === 69 || event.keyCode === 189 || event.keyCode == 107 || event.keyCode == 110 || event.keyCode == 109) return false;">
                <span class="text-lg"> - </span>
                <input type="number" name="max-price" value="<?php echo $filter['price']['max'] ?>"
                  class="w-20 px-3 py-1 border border-gray-300 rounded-sm"
                  onkeydown="if (event.keyCode === 69 || event.keyCode === 189 || event.keyCode == 107 || event.keyCode == 110 || event.keyCode == 109) return false;">
              </div>
            </div>
          </div>
          <div class="border-b border-gray-200">
            <div class="px-4 py-2">
              <h1 class="text-xl font-medium">Order by</h1>
              <div class="flex items-center justify-start gap-2 py-3">
                <select name="order-by" id=""
                  class="w-full px-3 py-1 border border-gray-300 rounded-sm appearance-none">
                  <option value="name" <?php echo $filter['order-by'] == 'name' ? 'selected' : '' ?>>Name</option>
                  <option value="price" <?php echo $filter['order-by'] == 'price' ? 'selected' : '' ?>>Price</option>
                  <option value="id" <?php echo $filter['order-by'] == 'id' ? 'selected' : '' ?>>ID</option>
                  <option value="quantity" <?php echo $filter['order-by'] == 'quantity' ? 'selected' : '' ?>>Quantity
                </select>
                <select name="order-direction" id=""
                  class="w-full px-3 py-1 border border-gray-300 rounded-sm appearance-none">
                  <option value="asc" <?php echo $filter['order-direction'] == 'asc' ? 'selected' : '' ?>>Ascending
                  </option>
                  <option value="desc" <?php echo $filter['order-direction'] == 'desc' ? 'selected' : '' ?>>Descending
                  </option>
                </select>
              </div>
            </div>
          </div>
          <input type="submit" value="Filter"
            class="py-2 px-5 bg-[#315854] font-semibold text-white rounded-md my-5 mx-4 hover:bg-primary-600 transition-all cursor-pointer" />
        </div>
      </form>
    </div>
    <div class="flex justify-between">
      <h1 class="text-xl font-bold">Product</h1>
      <a class="w-5 h-5 text-2xl add-product" href="<?php echo BASE_URI . "/dashboard/product/create"; ?>">
        +
      </a>
    </div>
    <div class="my-8 bg-white shadow-lg cursor-pointer table-product-statistics rounded-2xl">
      <div class="relative">
        <table class="w-full text-sm text-center text-gray-500 rounded-2xl">
          <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
              <?php
              $namess = [
                "ID",
                "Image",
                "Title",
                "Price",
                "Quantity",
                "Action",
              ];
              for ($i = 1; $i <= count($namess); $i++) { ?>
                <th scope="col" class="px-6 py-3">
                  <?php echo $namess[$i - 1]; ?>
                </th>
              <?php }
              ?>
            </tr>
          </thead>
          <tbody>
            <?php
            // $products = Product::all();
            if (count($products) > 0): ?>
              <?php foreach ($products as $product): ?>
                <tr class="text-center transition-opacity bg-white border-b hover:bg-gray-200 even:bg-gray-100">
                  <td class="px-5 py-3">
                    <?php echo $product->id; ?>
                  </td>
                  <td class="w-32 h-24 p-3">
                    <img src="<?php echo BASE_URI . $product->image; ?>" alt="" />
                  </td>
                  <td class="px-5 py-3">
                    <?php echo $product->name; ?>
                  </td>
                  <td class="px-5 py-3">
                    <?php echo $product->price; ?>
                  </td>
                  <td class="p-2">
                    <?php echo $product->quantity ?>
                  </td>
                  <td class="px-5 py-3">
                    <div class="flex items-center justify-center gap-4 button">
                      <a href="<?php echo BASE_URI .
                        "/dashboard/product/update" .
                        "?id=" .
                        $product->id; ?>"
                        class="px-3 py-2 text-white transition-all bg-blue-400 edit-button rounded-xl hover:bg-blue-500">
                        <i class="fa-solid fa-pen-to-square"></i>
                      </a>
                      <button onclick="removeProductConfirm(<?php echo $product->id ?>)"
                        class="px-3 py-2 text-white transition-all bg-red-400 delete-button rounded-xl hover:bg-red-500">
                        <i class="fa-solid fa-trash"></i>
                      </button>
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
  </div>
</div>

<script>
  const toggleBtn = document.querySelector('.toggle-btn');
  const toggleSimple = document.querySelector('.toggle-simple');
  const toggleAdvanced = document.querySelector('.toggle-advanced');
  const advancedSearch = document.querySelector('.advanced-search');
  const simpleSearch = document.querySelector('.simple-search');
  toggleBtn.addEventListener('click', () => {
    toggleSimple.classList.toggle('hidden');
    toggleAdvanced.classList.toggle('hidden');
    advancedSearch.classList.toggle('hidden');
    simpleSearch.classList.toggle('hidden');
  })
</script>

<script type="module">
  import FetchXHR from '<?php echo BASE_URI . "/resources/js/fetch-xhr.js"; ?>';
  document.removeProductConfirm = (id) => {
    const result = confirm("Delete this product?");
    if (result) {
      FetchXHR.post('<?php echo BASE_URI . "/dashboard/product/delete" ?>', { id }, { 'Content-Type': 'application/json' })
        .then(response => {
          if (response.type === 'error') {
            alert(response.message);
          } else if (response.type === 'info') {
            alert(response.message);
          } else {
            alert('This product has been removed');
          }
        }).catch(error => {
          alert('Something went wrong');
        });
      setTimeout(() => {
        window.location.reload();
      }, 1000);
    }
  }
</script>
<script>
  let icon = document.querySelector(".open-category");
  let list = document.querySelector(".category-list");
  let categories = document.querySelector(".dropdown-category")
  categories.addEventListener("click", toggleAdvancedFilter)
  const toggleAdvancedFilter = () => {
    if (icon.classList.contains("fa-plus")) {
      icon.classList.remove("fa-plus")
      icon.classList.add("fa-minus")
    } else {
      icon.classList.add("fa-plus")
      icon.classList.remove("fa-minus")
    }
    list.classList.toggle("h-0");
  }

</script>
