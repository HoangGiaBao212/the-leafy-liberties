<?php

$import = $params["import"];

?>


<div class="add-form min-h-screen w-full justify-center items-center bg-opacity-75 z-[500] my-5">
  <div class="w-full p-8 bg-white rounded-md shadow-lg ">
    <form class="flex flex-col" action="<?php echo BASE_URI . "/dashboard/import/update"; ?>" method="POST"
      enctype="multipart/form-data">
      <input type="hidden" name="import-id" value="<?php echo $import->id ?>">
      <input type="hidden" name="user-id" value="<?php echo $import->user_id ?>">

      <div class="flex flex-col">
        <label class="mb-2 text-lg font-bold text-gray-900" for="name">List Product</label>
        <div class="flex flex-row">
          <table class="w-full text-center border-collapse">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
              <tr>
                <?php
                $namess = [
                  "Product Name",
                  "Quantity",
                  "Price",
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
              <?php foreach ($import->importProducts() as $importProduct): ?>
                <?php
                $product = $importProduct->product();
                ?>
                <tr class="text-center transition-opacity bg-white border-b hover:bg-gray-200 even:bg-gray-100">
                  <td class="px-5 py-3">
                    <?php echo $product->name ?>
                    <input type="hidden" name="product-id[]" value="<?php echo $product->id ?>">
                  </td>
                  <td class="px-5 py-3">
                    <input class="w-full text-center even:bg-gray-100" type="number" name="quantity[]" id="quantity"
                      value="<?php echo $importProduct->quantity ?>"
                      onkeydown="if (event.keyCode === 69 || event.keyCode === 189 || event.keyCode == 107 || event.keyCode == 110 || event.keyCode == 109 || event.keyCode == 190) return false;" />
                  </td>
                  <td class="px-5 py-3">
                    <input class="w-full text-center even:bg-gray-100" type="number" name="price[]" id="price"
                      value="<?php echo $importProduct->price ?>"
                      onkeydown="if (event.keyCode === 69 || event.keyCode === 189 || event.keyCode == 107 || event.keyCode == 110) return false;" />
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <button class="my-2 bg-[#2e524e] hover:bg-[#52938d] transition-colors text-white font-bold py-2 px-4 rounded"
          type="submit">
          Submit
        </button>
        <a class="px-4 py-2 my-1 font-bold text-center text-white bg-gray-500 rounded cancel-edit-button hover:bg-gray-700"
          href="<?php echo BASE_URI . "/dashboard/import"; ?>">
          Cancel
        </a>
      </div>
    </form>
  </div>
</div>
