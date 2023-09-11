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
    <div class="flex justify-between">
      <h1 class="text-xl font-bold">Import</h1>
      <div class="box-border w-1/2 px-10">
        <form action="<?php echo BASE_URI . '/dashboard/import' ?>" method="GET"
          class="flex items-center justify-center w-full h-10 bg-gray-100 rounded-full shadow-lg">
          <input type="text" name="keywords" class="w-full h-full pl-5 rounded-tl-full rounded-bl-full"
            placeholder="Search.... "
            value="<?php echo isset($params['filter']['keywords']) ? $params['filter']['keywords'] : '' ?>" />
          <button class="flex items-center justify-center w-10 h-10 bg-white rounded-tr-full rounded-br-full">
            <i class="fa-solid fa-magnifying-glass"></i>
          </button>
        </form>
      </div>
      <a href="<?php echo BASE_URI . '/dashboard/import/create' ?>" class="w-5 h-5 text-2xl add-coupon">
        +
      </a>
    </div>
    <div class="my-8 bg-white shadow-lg cursor-pointer table-product-statistics rounded-2xl">
      <div class="relative">
        <?php if (count($imports) == 0): ?>
          <div class="flex flex-col justify-center items-center h-[70vh]">
            <i class="mb-4 text-5xl text-gray-400 fa-solid fa-file-import"></i>
            <h1 class="text-5xl font-medium tracking-widest text-gray-400 uppercase">Don't have any import yet</h1>
          </div>
        <?php else: ?>
          <table class="w-full text-sm text-center text-gray-500 rounded-2xl">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
              <tr>
                <?php
                $namess = [
                  "ID",
                  "User",
                  "Total Price",
                  "Created At",
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
              <?php foreach ($imports as $import): ?>
                <tr class="text-center transition-opacity bg-white border-b hover:bg-gray-200 even:bg-gray-100">
                  <td class="px-5 py-3">
                    <?php echo $import->id; ?>
                  </td>
                  <td class="px-5 py-3">
                    <?php echo $import->user()->name; ?>
                  </td>
                  <td class="px-5 py-3">
                    <?php echo $import->total_price; ?>
                  </td>
                  <td class="px-5 py-3">
                    <?php echo $import->created_at; ?>
                  </td>
                  <td class="px-5 py-3 w-44">
                    <div class="flex items-center justify-center gap-4 button">
                      <a href="<?php echo BASE_URI .
                        "/dashboard/import/update" .
                        "?id=" .
                        $import->id; ?>"
                        class="px-3 py-2 text-white transition-all bg-blue-400 edit-button rounded-xl hover:bg-blue-500">
                        <i class="fa-solid fa-pen-to-square"></i>
                      </a>
                      <button onclick="removeImport(<?php echo $import->id ?>)"
                        class="px-3 py-2 text-white transition-all bg-red-400 delete-button rounded-xl hover:bg-red-500">
                        <i class="fa-solid fa-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif ?>
      </div>
    </div>
  </div>
</div>
<script type="module">
  import FetchXHR from '<?php echo BASE_URI . "/resources/js/fetch-xhr.js"; ?>';
  document.removeImport = (id) => {
    const result = confirm("Delete this import bill?");
    if (result) {
      window.location.href = `<?php echo BASE_URI . '/dashboard/import/delete' ?>?id=${id}`;
    }
  }
</script>
