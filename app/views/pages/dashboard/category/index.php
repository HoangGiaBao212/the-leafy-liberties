<?php
use App\Models\Category;
use App\Models\Product;
use App\Models\Pagination;

?>

<div class="w-full mx-auto my-0">
  <div class="box-border w-full min-h-screen px-10 mt-10 sm:px-5">
    <div class="flex justify-between">
      <h1 class="text-xl font-bold">Category</h1>
      <div class="box-border w-1/2 px-10">
        <form action="<?php echo BASE_URI . '/dashboard/category' ?>" method="GET"
          class="flex items-center justify-center w-full h-10 bg-gray-100 rounded-full shadow-lg">
          <input type="text" name="keywords" class="w-full h-full pl-5 rounded-tl-full rounded-bl-full"
            placeholder="Search.... "
            value="<?php echo isset($params['filter']['keywords']) ? $params['filter']['keywords'] : '' ?>" />
          <button class="flex items-center justify-center w-10 h-10 bg-white rounded-tr-full rounded-br-full">
            <i class="fa-solid fa-magnifying-glass"></i>
          </button>
        </form>
      </div>
      <a class="w-5 h-5 text-2xl add-category" href="<?php echo BASE_URI . '/dashboard/category/create' ?>">
        +
      </a>
    </div>
    <div class="my-8 bg-white shadow-lg cursor-pointer table-product-statistics rounded-2xl">
      <div class="relative">
        <table class="w-full text-sm text-center text-gray-500 rounded-2xl">
          <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
              <?php
              $name = [
                "ID",
                "Image",
                "Name",
                "Action"
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
            if (count($categories) > 0): ?>
              <?php foreach ($categories as $category): ?>
                <tr class="text-center transition-opacity bg-white border-b hover:bg-gray-200 even:bg-gray-100">
                  <td class="px-5 py-3">
                    <?php echo $category->id; ?>
                  </td>
                  <td class="h-24 p-3 w-44">
                    <img src="<?php echo BASE_URI . $category->image; ?>" alt="" class="object-cover w-full h-full" />
                  </td>
                  <td class="px-5 py-3">
                    <?php echo $category->name; ?>
                  </td>
                  <td class="gap-2 px-5 py-3">
                    <div class="flex items-center justify-center gap-4 button">
                      <a href="<?php echo BASE_URI .
                        "/dashboard/category/update" .
                        "?id=" .
                        $category->id; ?>"
                        class="px-3 py-2 text-white transition-all bg-blue-400 edit-button rounded-xl hover:bg-blue-500">
                        <i class="fa-solid fa-pen-to-square"></i>
                      </a>
                      <button onclick="removeCategory(<?php echo $category->id ?>)"
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
<script type="module">
  import FetchXHR from '<?php echo BASE_URI . "/resources/js/fetch-xhr.js"; ?>';
  document.removeCategory = (id) => {
    const result = confirm("Delete this category?");
    if (result) {
      FetchXHR.post('<?php echo BASE_URI . "/dashboard/category/delete" ?>', { id }, { 'Content-Type': 'application/json' })
        .then(response => {
          if (response.type === 'error') {
            alert(response.message);
          } else if (response.type === 'info') {
            alert(response.message);
          } else {
            alert('This category has been removed');
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