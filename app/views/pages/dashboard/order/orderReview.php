<div class="flex justify-center w-full bg-white">
  <div class="container">
    <div class="box-border relative flex flex-col w-full mt-10 border border-b-2 border-gray-300 md:flex-row">

      <?php

      use App\Models\OrderProduct;
      use App\Models\Coupon;
      use App\Models\ShippingMethod;
      use App\Models\Setting;
      $totalMoney=0;
      ?>

      <div class="w-full mx-auto my-0">
        <div class="box-border w-full min-h-screen px-10 mt-10 sm:px-5">

          <div class="flex justify-between">
            <h1 class="text-xl font-bold">Order information</h1>
            <div class="box-border w-1/2 px-10">
            </div>

            <ul>
              <!-- <li>
                                <?php if ($order->status == 1):
                                  ?>
                                    <span class="px-3 py-2 text-white transition-all bg-green-400 accept-button rounded-xl hover:text-blue-500">
                                        Accepted
                                        <i class="fas fa-check-circle"></i>
                                    </span>
                                <?php elseif ($order->status == 2): ?>
                                    <span class="px-3 py-2 text-white transition-all bg-red-400 reject-button rounded-xl hover:text-blue-500">
                                        Rejected
                                        <i class="fas fa-times-circle"></i>
                                    </span>
                                <?php elseif ($order->status == 0): ?>
                                    <span class="px-3 py-2 text-white transition-all bg-yellow-400 pending-button rounded-xl hover:text-blue-500">
                                        Pending
                                        <i class="fas fa-clock"></i>
                                    </span>
                                <?php endif; ?>
                            </li> -->
              <li>
                <?php if ($order->status == 1): ?>
                  <span
                    class="px-3 py-2 text-white transition-all rounded-md cursor-pointer accept-button bg-primary-400 hover:text-blue-300">
                    Accepted
                    <i class="fas fa-check-circle"></i>
                  </span>
                <?php elseif ($order->status == 2): ?>
                  <span
                    class="px-3 py-2 text-white transition-all bg-red-400 rounded-md cursor-pointer reject-button hover:text-blue-300">
                    Rejected
                    <i class="fas fa-times-circle"></i>
                  </span>
                <?php elseif ($order->status == 0): ?>
                  <span
                    class="px-3 py-2 text-white transition-all bg-yellow-400 rounded-md cursor-pointer pending-button hover:text-blue-300">
                    Pending
                    <i class="fas fa-clock"></i>
                  </span>
                <?php elseif ($order->status == 3): ?>
                  <span
                    class="px-3 py-2 text-white transition-all bg-gray-400 rounded-md cursor-pointer cancel-button hover:text-blue-300">
                    Cancelled
                    <i class="fas fa-ban"></i>
                  </span>
                <?php elseif ($order->status == 4): ?>
                  <span
                    class="px-3 py-2 text-white transition-all bg-blue-400 rounded-md cursor-pointer shipping-button hover:text-blue-300">
                    Shipping
                    <i class="fas fa-truck"></i>
                  </span>
                <?php elseif ($order->status == 5): ?>
                  <span
                    class="px-3 py-2 text-white transition-all rounded-md cursor-pointer success-button bg-primary-600 hover:text-blue-300">
                    Successful
                    <i class="fas fa-check-circle"></i>
                  </span>
                <?php endif; ?>
              </li>

            </ul>
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
                  <?php foreach (OrderProduct::findAll(['order_id' => $order->id]) as $orderProduct):
                    $totalMoney+= $orderProduct->product()->price;
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
                            $orderProduct->product()->id; ?>"
                            class="px-3 py-2 text-white transition-all bg-blue-400 edit-button rounded-xl hover:bg-blue-500">
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
            <br/>
            Shipping methods:
            <?php 
              $shippingMethod = ShippingMethod::find($order->shipping_method_id);
              echo $shippingMethod->name;
              echo "  $" . $shippingMethod->price;
            ?>     
            <br/>
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
            <br/>
            Grand total: 
            <?php 
             echo "\$$order->total_price";
            ?> 
          </div>
          <?php if ($order->status == 0 || $order->status == 4 || $order->status == 1) {
            ?>
            <form class="flex flex-col" action="<?php echo BASE_URI .
              "/dashboard/order/order_review" .
              "?id=" .
              $order->id; ?>" method="POST">
              <select name="status" class="p-2 border border-gray-300 rounded-lg appearance-none">
                <option value="1">Accepted</option>
                <option value="2">Rejected</option>
                <option value="0">Pending</option>
                <option value="3">Cancelled</option>
                <option value="4">Shipping</option>
                <option value="5">Successful</option>
              </select>
              <button type="submit"
                class="my-2 bg-[#2e524e] hover:bg-[#52938d] transition-colors text-white font-bold py-2 px-4 rounded">Submit</button>
            </form>
            <?php
          } ?>
        </div>
      </div>
    </div>
  </div>
</div>
