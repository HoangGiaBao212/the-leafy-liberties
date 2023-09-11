<div class="w-full mx-auto my-0">
  <div class="box-border w-full min-h-screen px-10 mt-10 sm:px-5">
    <div class="flex justify-between">
      <h1 class="text-xl font-bold">Reviews</h1>
      <div class="box-border w-1/2">
        <form action="<?php echo BASE_URI . '/dashboard/review' ?>" method="GET"
          class="flex items-center justify-center w-full h-10 bg-gray-100 rounded-full shadow-lg">
          <input type="text" name="keywords" class="w-full h-full pl-5 rounded-tl-full rounded-bl-full"
            placeholder="Search.... "
            value="<?php echo isset($params['filter']['keywords']) ? $params['filter']['keywords'] : '' ?>" />
          <button class="flex items-center justify-center w-10 h-10 bg-white rounded-tr-full rounded-br-full">
            <i class="fa-solid fa-magnifying-glass"></i>
          </button>
        </form>
      </div>
    </div>
    <div class="my-8 bg-white shadow-lg cursor-pointer rounded-2xl">
      <div class="relative">
        <table class="w-full h-64 text-sm text-center text-gray-500 rounded-2xl">
          <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
              <?php

              use App\Models\Product;
              use App\Models\Review;

              $name = [
                "ID",
                "Image",
                "Name",
                "Comment quantity",
                "Action",
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
            if (isset($_POST["searchQuery"])) {
              $text = $_POST["searchQuery"];
              $coupons = Product::filterAdvanced($text);
            } else {
              $coupons = Product::all();
            }
            if (count($coupons) > 0): ?>
              <?php foreach ($products as $product):
                ?>
                <?php
                $reviewsValid = [];
                $reviews = Review::findAll(["product_id" => $product->id]);
                foreach ($reviews as $review) {
                  if ($review->deleted_at == null) {
                    $reviewsValid[] = $review;
                  }
                }
                ?>
                <tr class="text-center transition-opacity bg-white border-b hover:bg-gray-200 even:bg-gray-100">
                  <td class="px-5 py-3">
                    <?php echo $product->id; ?>
                  </td>
                  <td class="w-32 h-24 p-3 px-5 py-3">
                    <img src="<?php echo BASE_URI . $product->image; ?>" alt="" />
                  </td>
                  <td class="px-5 py-3">
                    <?php echo $product->name; ?>
                  </td>
                  <td class="px-5 py-3">
                    <?php echo count($reviewsValid); ?>
                  </td>
                  <td class="px-5 py-3 w-44">
                    <div class="flex items-center justify-center gap-4 button">
                      <a href="<?php echo BASE_URI .
                        "/dashboard/review/review_detail" .
                        "?id=" .
                        $product->id; ?>"
                        class="px-3 py-2 text-white transition-all bg-blue-400 edit-button rounded-xl hover:bg-blue-500">
                        <i class="fa-solid fa-pen-to-square"></i>
                      </a>
                      </a>
                    </div>
                  </td>
                </tr>
              <?php endforeach ?>
            <?php endif;
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>