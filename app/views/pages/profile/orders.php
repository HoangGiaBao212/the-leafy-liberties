<?php

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;

?>

<div class="flex justify-center w-full bg-white">
  <div class="container">
    <div class="box-border flex flex-col w-full mt-10 border border-b-2 border-gray-300 md:flex-row">
      <?php include "menu.php"; ?>
      <!-- content -->
      <div
        class="my-5 overflow-hidden bg-white shadow-lg cursor-pointer table-statistics rounded-2xl w-[95%] md:w-[70%] mx-4">
        <div class="relative w-full">
          <?php if (count($orders) == 0): ?>
            <div class="flex flex-col justify-center items-center h-[70vh]">
              <i class="mb-4 text-5xl text-gray-400 fa-solid fa-cart-circle-plus"></i>
              <h1 class="text-5xl font-medium tracking-widest text-gray-400 uppercase">Don't have any orders yet</h1>
            </div>
          <?php else: ?>
            <table class="w-full overflow-y-scroll text-sm text-center text-gray-500 rounded-2xl" width="100%">
              <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                  <?php
                  $tableName = [
                    "Name",
                    "Email",
                    "Order",
                    "Total price",
                    "Status",
                    "Action",
                    ""
                  ];
                  for ($i = 1; $i <= count($tableName); $i++) { ?>
                    <th scope="col" class="px-6 py-3">
                      <?php echo $tableName[$i - 1]; ?>
                    </th>
                  <?php }
                  ?>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($orders as $order): ?>
                  <tr class="px-5 py-3">
                    <td class="px-5 py-3">
                      <?php echo $order->name; ?>
                    </td>
                    <td class="px-5 py-3">
                      <?php echo $order->email; ?>
                    </td>
                    <td class="px-5 py-3" width="50%">
                      <ul>
                        <?php foreach (array_slice(OrderProduct::findAll(['order_id' => $order->id]), 0, 3) as $orderProduct): ?>
                          <li>
                            <?php echo $orderProduct->product()->name ?>
                          </li>
                        <?php endforeach; ?>
                      </ul>
                    </td>

                    <td class="px-5 py-3" width="15%">
                      <?php echo $order->total_price; ?>
                    </td>
                    <td class="px-5 py-3">
                      <!-- if status is 0 is pending, 1 is accept, 2 is shipping, 3 is successful, 4 is cancel, 5 is reject -->
                      <?php if ($order->status == 0): ?>
                        <p class="font-medium text-yellow-400">Pending</p>
                      <?php elseif ($order->status == 1): ?>
                        <p class="font-medium text-green-800">Accept</p>
                      <?php elseif ($order->status == 2): ?>
                        <p class="font-medium text-red-800">Reject</p>
                      <?php elseif ($order->status == 3): ?>
                        <p class="font-medium text-gray-400">Cancelled</p>
                      <?php elseif ($order->status == 4): ?>
                        <p class="font-medium text-blue-400">Shipping</p>
                      <?php else: ?>
                        <p class="font-medium text-primary-400">Successfull</p>
                      <?php endif; ?>
                    </td>
                    <td>
                      <!-- <i class="fa-solid fa-pen-to-square"></i> -->
                      <a href="<?php echo BASE_URI .
                        "/profile/orders/order_detail" .
                        "?id=" .
                        $order->id; ?>" class="px-3 py-2 rounded-md edit-button">
                        <i class="text-xl text-black transition-colors fa-solid fa-circle-info hover:text-orange-600"></i>
                      </a>
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
</div>
