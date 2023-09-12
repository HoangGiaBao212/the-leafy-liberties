<?php
?>

<div class="flex justify-center w-full bg-white">
  <div class="container">
    <div class="box-border relative flex flex-col w-full mt-10 border border-b-2 border-gray-300 md:flex-row">
      <?php include "menu.php"; ?>
      <!-- content -->
      <?php

      use App\Models\OrderProduct;
      use App\Models\Coupon;
      use App\Models\ShippingMethod;
      use App\Models\Setting;

      $totalMoney = 0;
      ?>

      <div class="w-[75%] mx-auto my-0">
        <div class="box-border w-full min-h-screen px-10 mt-10 sm:px-5">
          <div class="flex justify-between">
            <h1 class="text-xl font-bold">Order information</h1>
            <div class="box-border w-1/2 px-10">
            </div>
            <?php
            if ($order->status == 0) {
            ?>
              <a href="<?php echo BASE_URI .
                          "/profile/orders/order_detail/delete" .
                          "?id=" .
                          $order->id; ?>" class="px-3 py-2 text-white transition-all bg-red-400 delete-button rounded-xl hover:bg-red-500">
                Cancel order
                <i class="fas fa-shopping-cart"></i>
              </a>
            <?php
            }
            ?>
          </div>
          <div class="my-8 bg-white shadow-lg cursor-pointer table-product-statistics rounded-2xl">
            <div class="relative">
              <table class="w-full h-64 text-sm text-center text-gray-500 rounded-2xl">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                  <tr>
                    <?php
                    $name = [
                      "ID",
                      "Image",
                      "Title",
                      "Price",
                      "Quantity",
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
                  <?php foreach (OrderProduct::findAll(['order_id' => $order->id]) as $orderProduct) :
                    $totalMoney += $orderProduct->product()->price;
                  ?>
                    <tr class="text-center transition-opacity bg-white border-b hover:bg-gray-200 even:bg-gray-100">
                      <td class="px-5 py-3">
                        <?php echo $orderProduct->id; ?>
                      </td>
                      <td class="w-32 h-24 p-3">
                        <img src="<?php echo BASE_URI .
                                    $orderProduct->product()->image; ?>" alt="" />
                      </td>
                      <td class="px-5 py-3">
                        <?php echo $orderProduct->product()->name; ?>
                      </td>
                      <td class="px-5 py-3">
                        <?php echo $orderProduct->product()->price; ?>$
                      </td>
                      <td class="p-2">
                        <?php echo $orderProduct->quantity; ?>
                      </td>
                      <td class="flex items-center justify-center h-full gap-2 px-5 py-3">
                        <div class="flex items-center justify-center gap-4 button">
                          <a href="<?php echo BASE_URI .
                                      "/product" .
                                      "?id=" .
                                      $orderProduct->product()->id; ?>" class="px-3 py-2 text-white transition-all bg-blue-400 edit-button rounded-xl hover:bg-blue-500">
                            <i class="fa-solid fa-info-circle"></i>
                          </a>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="text-xl">
            Discount codes:
            <?php
            $coupon = Coupon::find($order->coupon_id);
            if ($coupon) {
              $sumDiscount = $totalMoney * ($coupon->percent / 100);
              echo $coupon->code;
              echo "  $" . number_format($sumDiscount, 2);
            } else {
              echo "Mã giảm giá không hợp lệ.";
            }
            ?>
            <br />
            Shipping methods:
            <?php
            $shippingMethod = ShippingMethod::find($order->shipping_method_id);
            echo $shippingMethod->name;
            echo "  $" . $shippingMethod->price;
            ?>
            <br />
            Tax:
            <?php
            $tax = 0;
            $setting = Setting::findOne(["name" => "tax"]);
            if ($setting) {
              $tax = $setting->value / 100;
              $taxMoney = number_format(($totalMoney * $tax) / 10, 2);
            }
            echo "\$$taxMoney"
            ?>
            <br />
            Grand total:
            <?php
            echo "\$$order->total_price";
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>