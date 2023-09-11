<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Pagination;

?>

<div class="w-full mx-auto my-0">
  <div class="box-border w-full min-h-screen px-10 mt-10 sm:px-5">
    <div class="flex justify-between">
      <h1 class="text-xl font-bold">Order</h1>
      <div class="box-border w-1/2">
        <form action="<?php echo BASE_URI . '/dashboard/order' ?>" method="GET"
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
    <?php if (count(Order::findAll(["deleted_at" => "null"])) == 0): ?>
      <div class="flex flex-col justify-center items-center h-[70vh]">
        <i class="mb-4 text-5xl text-gray-400 fa-solid fa-cart-circle-plus"></i>
        <h1 class="text-5xl font-medium tracking-widest text-gray-400 uppercase">Don't have any orders yet</h1>
      </div>
    <?php else: ?>
      <div class="my-8 bg-white shadow-lg cursor-pointer table-product-statistics rounded-2xl">
        <div class="relative">
          <table class="w-full overflow-y-scroll text-sm text-center text-gray-500 rounded-2xl">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
              <tr>
                <?php
                $name = [
                  "Order ID",
                  "Customer Name",
                  "Date",
                  "Status",
                  "Amount",
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
              foreach ($orders as $order):
                ?>
                <tr class="transition-opacity bg-white border-b hover:bg-gray-200 even:bg-gray-100">
                  <td class="px-5 py-4 font-medium text-gray-900 whitespace-nowrap">
                    <?php echo $order->id ?>
                  </td>
                  <td class="px-5 py-3">
                    <?php echo $order->name ?>
                  </td>
                  <td class="px-5 py-3">
                    <?php echo $order->create_at ?>
                  </td>
                  <td class="px-5 py-3 font-medium ">
                    <?php if ($order->status == 0): ?>
                      <p class="font-medium text-yellow-400">Pending</p>
                    <?php elseif ($order->status == 1): ?>
                      <p class="font-medium text-primary-800">Accept</p>
                    <?php elseif ($order->status == 2): ?>
                      <p class="font-medium text-red-800">Reject</p>
                    <?php elseif ($order->status == 3): ?>
                      <p class="font-medium text-gray-400">Cancelled</p>
                    <?php elseif ($order->status == 4): ?>
                      <p class="font-medium text-blue-400">Shipping</p>
                    <?php else: ?>
                      <p class="font-medium text-primary-400">Successfully</p>
                    <?php endif; ?>
                  </td>
                  <td class="px-5 py-3">
                    <?php
                    echo count($order->products());
                    ?>
                  </td>
                  <td class="px-5 py-3">
                    <a href="<?php echo BASE_URI .
                      "/dashboard/order/order_review" .
                      "?id=" .
                      $order->id; ?>" class="px-3 py-2 rounded-md edit-button">
                      <i class="text-xl text-black transition-colors fa-solid fa-circle-info hover:text-orange-600"></i>
                    </a>
                  </td>
                </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php endif ?>
  </div>
</div>
