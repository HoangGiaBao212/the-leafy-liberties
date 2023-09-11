<?php

use App\Models\Setting;
use App\Models\ShippingMethod;
use Core\Application;
use Core\Database;

$auth = Application::getInstance()->getAuthentication();
$user = $auth->getUser();

$shippingMethods = ShippingMethod::findAll(["status" => 1]);

$tax = 0;
$setting = Setting::findOne(["name" => "tax"]);
if ($setting) {
  $tax = $setting->value / 100;
}

$cartItems = $params["cartItems"];

$totalMoney = 0;

foreach ($cartItems as $cartItem) {
  $totalMoney += $cartItem->quantity * $cartItem->product()->price;
}

$shipping = 0;
$taxMoney = number_format(($totalMoney * $tax) / 10, 2);
$grandTotal = ($totalMoney + $shipping + $taxMoney);

$db = Database::getInstance();
$coupons = $db->select("SELECT * FROM coupons c
WHERE $totalMoney >= c.required AND c.deleted_at IS NULL AND c.expired >= CURRENT_DATE
ORDER BY c.required DESC
LIMIT 1;
", []);
// dd($coupons)
?>
<div class="flex items-center justify-center w-full mt-10">
  <form action="<?php echo BASE_URI . '/checkout/confirm' ?>" method="POST"
    class="container flex flex-wrap xl:flex-nowrap">
    <div class="flex flex-col w-full left-container">
      <div class="w-full p-5 border-0 shadow-lg bill-container rounded-2xl shadow-gray-300">
        <h2 class="mb-5 text-xl font-bold bill-header">
          Billing Address
        </h2>
        <div class="px-5 sm:w-full md:w-full ">
          <fieldset class="border-[1px] border-gray-600 border-solid rounded-md p-2 w-full mt-3">
            <legend class="px-1">Name</legend>
            <input type="text" name="name" id="name" placeholder="Your name" required
              class="w-full px-2 py-1 focus:outline-none focus:border-0" value="<?php echo $user->name ?>" />
          </fieldset>
          <fieldset class="border-[1px] border-gray-600 border-solid rounded-md p-2 w-full mt-3">
            <legend class="px-1">Email</legend>
            <input type="email" name="email" id="email" placeholder="Your Email" required
              class="w-full px-2 py-1 focus:outline-none focus:border-0" value="<?php echo $user->email ?>" />
          </fieldset>
          <fieldset class="border-[1px] border-gray-600 border-solid rounded-md px-2 py-1 mt-3">
            <legend class="px-1">Address</legend>
            <input type="text" name="address" id="address" required value="<?php echo $user->address ?>"
              class="w-full p-2 focus:outline-none focus:border-0" placeholder="12 Wall Street,. . . . ." />
          </fieldset>

          <fieldset class="border-[1px] border-gray-600 border-solid rounded-md p-2 w-full">
            <legend class="px-1">Phone</legend>
            <input type="number" name="phone" id="phone" required placeholder="Your phone"
              class="w-full px-2 py-1 focus:outline-none focus:border-0"
              pattern="^(\+84|0)(1\d{9}|3\d{8}|5\d{8}|7\d{8}|8\d{8}|9\d{8})$" value="<?php echo $user->phone ?>" />
          </fieldset>
        </div>
      </div>
      <div class="w-full p-5 mt-5 border-0 shadow-lg shipping-container rounded-2xl shadow-gray-300 ">
        <h2 class="mb-5 text-xl font-bold bill-header">
          Shipping Method
        </h2>
        <div class="flex flex-col justify-between gap-5 px-5 choice">
          <?php foreach ($shippingMethods as $shippingMethod): ?>
            <div
              class="shipping-choices border-[1px] border-gray-500 p-3 border-solid rounded-lg flex items-center justify-start cursor-pointer"
              onclick="document.getElementById('shipping-method-<?php echo $shippingMethod->id ?>').checked = true;">
              <input type="radio" name="shipping-method-id" required
                id="shipping-method-<?php echo $shippingMethod->id ?>" value="<?php echo $shippingMethod->id ?>"
                class="accent-[#315854]" autocomplete="on" />
              <label for="shipping-method-<?php echo $shippingMethod->id ?>"
                class="ml-2 text-lg font-bold cursor-pointer">
                <?php echo $shippingMethod->price ?>$
              </label>
              <p class="ml-6">
                <?php echo $shippingMethod->name ?>
              </p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="w-full p-5 mt-5 mb-10 border-0 shadow-lg payment-container rounded-2xl shadow-gray-300">
        <h2 class="mb-5 text-xl font-bold payment-header">
          Payment method
        </h2>
        <div class="flex flex-col justify-between gap-5 px-5 choice">
          <div
            class="payment-choice border-[1px] border-gray-500 p-3 border-solid rounded-lg flex items-center justify-start cursor-pointer transition-all">
            <input type="radio" name="payment-method-type" id="cash" required class="accent-[#315854]" value="cash" />
            <label for="cash" class="ml-2 text-lg font-bold">Cash on delivery
              <span class="ml-6 text-sm font-normal ">
                You will pay when your order is delivered</span>
            </label>
          </div>
          <div
            class="payment-choice border-[1px] border-gray-500 p-3 border-solid rounded-lg flex items-center justify-start cursor-pointer"
            for="credit">
            <input type="radio" name="payment-method-type" id="credit" required class="accent-[#315854]"
              value="credit" />
            <label for="credit" class="ml-2 text-lg font-bold">Credit
              <span class="ml-6 text-sm font-normal">
                You will pay with your credit card and your order will be delivered
              </span>
            </label>
          </div>
          <div id="credit-info" class="hidden mt-5">
            <label for="card-number" class="block mb-2 font-medium">Card Number:</label>
            <input type="text" name="card-number" id="card-number" required
              class="block w-full px-4 py-2 mb-4 leading-tight bg-white border border-gray-400 rounded shadow-md appearance-none focus:outline-none focus:shadow-outline-blue focus:border-blue-300">
            <label for="expiry-date" class="block mb-2 font-medium">Expiration Date:</label>
            <input type="text" name="expiry-date" id="expiry-date" required
              class="block w-full px-4 py-2 mb-4 leading-tight bg-white border border-gray-400 rounded shadow-md appearance-none focus:outline-none focus:shadow-outline-blue focus:border-blue-300">
            <label for="cvv" class="block mb-2 font-medium">CVV:</label>
            <input type="text" name="cvv" id="cvv" required
              class="block w-full px-4 py-2 mb-4 leading-tight bg-white border border-gray-400 rounded shadow-md appearance-none focus:outline-none focus:shadow-outline-blue focus:border-blue-300">
          </div>
        </div>
      </div>
    </div>
    <div class="flex flex-col items-end justify-start w-full mb-10 right-container sm:w-full md:w-full xl:w-3/4">
      <div class="w-full p-5 mt-5 border-0 shadow-lg order-preview rounded-2xl shadow-gray-300 md:w-full xl:w-3/4">
        <div class="flex justify-between header">
          <div class="left">
            <h2 class="text-xl font-bold">Order review</h2>
            <p class="pt-1 pl-2 number-items">
              <?php echo count($cartItems) ?> items in cart
            </p>
          </div>
          <div class="w-3 h-3 right" id="btn-toggle-order-preview">
            <i class="cursor-pointer fa-solid fa-chevron-down"></i>
          </div>
        </div>
        <div class="w-full" id="order-preview-list">
          <?php foreach ($cartItems as $cartItem): ?>
            <?php $product = $cartItem->product(); ?>
            <div class="flex items-center justify-between mt-5">
              <div class="flex items-center">
                <a href="<?php echo BASE_URI . '/product' . '?id=' . $product->id ?>">
                  <img src="<?php echo BASE_URI . $product->image ?>" alt="<?php echo $product->name ?>"
                    class="object-cover w-20 h-24 rounded-md">
                </a>
                <div class="ml-4 cursor-pointer">
                  <h3 class="font-medium text-gray-700">
                    <?php echo $product->name ?>
                  </h3>
                  <div class="mt-1 text-sm text-gray-500">
                    <?php echo $product->author()->name ?>
                  </div>
                  <div class="mt-1 text-sm font-medium text-gray-700">
                    <?php echo $cartItem->quantity ?> x
                    <?php echo $product->price ?> $
                  </div>
                </div>
              </div>
              <div class="text-gray-600 whitespace-nowrap">
                <?php echo $product->price * $cartItem->quantity ?> $
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div
        class="flex items-start justify-between flex-col w-full p-5 mt-5 border-0 shadow-lg discount-container rounded-2xl shadow-gray-300 md:w-full xl:w-3/4">
        <div class="mb-5 text-xl font-bold discount-code">
          <h2>Discount Codes</h2>

        </div>
        <?php if (isset($coupons)): ?>
          <div class="flex justify-between items-center w-full px-5">
            <p class="discount-code">
              <?php echo $coupons[0]['code']; ?>
            </p>
            <input type="hidden" name="discount" value="<?php echo $coupons[0]['id'] ?>">
            <p class="discount-money font-bold">
              $
              <?php
              $sumDiscount = $totalMoney * ($coupons[0]['percent'] / 100);
              echo number_format($sumDiscount, 2);
              ?>
            </p>
          </div>
        <?php else: ?>
          <div class="flex justify-between items-center w-full px-5">
            <p class="discount-code text-gray-500 font-bold">
              No discount avaiable
            </p>
            <i class="fa-solid fa-x text-md"></i>
          </div>
        <?php endif; ?>
      </div>
      <div class="w-full p-5 mt-5 border-0 shadow-lg billing-summary rounded-2xl shadow-gray-300 md:w-full xl:w-3/4">
        <div class="flex items-start justify-between header">
          <div class="mb-5 text-xl font-bold">
            <h2>Billing Summary</h2>
          </div>
        </div>
        <div class="body-summary">
          <div class="top-summary flex flex-col gap-5 border-0 border-solid border-gray-400 border-b-[1px] pb-5 px-5">
            <div class="flex items-center justify-between subtotal">
              <p class="f">Subtotal</p>
              <div class="font-bold subtotal-money">$
                <?php echo $totalMoney ?>
              </div>
            </div>
            <div class="flex items-center justify-between shipping">
              <p>Shipping</p>
              <div class="font-bold shipping-money">$
                <?php echo $shipping ?>
              </div>
            </div>
            <div class="flex items-center justify-between tax">
              <p>Tax</p>
              <div class="font-bold tax-money">
                $
                <?php echo $taxMoney ?>
              </div>
            </div>
          </div>
          <div class="mt-5 bottom-summary">
            <div class="flex items-center justify-between px-5 total-money">
              <h2 class="text-lg font-medium">Grand Total</h2>
              <div class="font-bold grand-money">
                $
                <?php echo number_format($grandTotal - $sumDiscount + $taxMoney, 2) ?>
              </div>
            </div>
            <fieldset class="border-[1px] border-gray-600 border-solid rounded-md p-1 mr-10 w-full h-auto mt-4">
              <legend class="px-2">Description</legend>
              <textarea type="text" name="description" placeholder="Type here..." rows="7"
                class="w-full p-1 text-lg focus:outline-none focus:border-0"></textarea>
            </fieldset>
            <div
              class="btn-pay w-full bg-[#2e524e] text-center p-2 text-white rounded-lg mt-5 cursor-pointer hover:bg-[#52938d] hover:transition-all">
              <button type="submit" class="text-xl grand-total w-full h-full">
                Pay $
                <?php echo number_format($grandTotal - $sumDiscount + $taxMoney, 2) ?>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
<script>
  const cashOnDelivery = document.getElementById("cash");
  const creditCard = document.getElementById("credit");

  const creditInfo = document.getElementById("credit-info");

  creditCard.addEventListener("click", function () {
    creditInfo.classList.remove("hidden");
  });

  cashOnDelivery.addEventListener("click", function () {
    creditInfo.classList.add("hidden");
  });

  const btnToggleOrderPreview = document.getElementById("btn-toggle-order-preview");
  const orderPreviewList = document.getElementById("order-preview-list");

  btnToggleOrderPreview.addEventListener("click", function () {
    orderPreviewList.classList.toggle("hidden");
  });

  // if credit card is checked or credit card is not checked
  if (creditCard.checked) {
    document.getElementById("card-number").required = true
    document.getElementById("expiry-date").required = true
    document.getElementById("cvv").required = true
  } else {
    document.getElementById("card-number").required = false
    document.getElementById("expiry-date").required = false
    document.getElementById("cvv").required = false
  }

  let shipping_money = document.querySelector(".shipping-money");
  let ship_method = ["5.99", "14.99", "29.99", "24.99"];
  let tax = document.querySelector(".tax-money");
  let choices = document.querySelectorAll(".shipping-choices");
  let discount = document.querySelector(".discount-money");
  let grand_total = document.querySelector(".grand-total");
  let grand_money = document.querySelector(".grand-money");
  let subtotal = document.querySelector(".subtotal-money");
  let taxMoney = 0, sum = 0, discountMoney = 0
  subtotal = subtotal.innerHTML.replace("$", "");
  choices.forEach((element, index) => {
    element.addEventListener("click", () => {
      let option = document.querySelectorAll("input[name=shipping-method-id]")[index];
      if (option.checked == true) {
        shipping_money.innerHTML = "$" + ship_method[index];
        taxMoney = tax.innerHTML.replace("$", "");
        console.log(taxMoney)
        discountMoney = discount.innerHTML.replace("$", "")
        console.log(discountMoney);
        sum = (parseFloat(subtotal) + parseFloat(ship_method[index]) + parseFloat(taxMoney)).toFixed(2)
        grand_money.innerHTML = "$ " + parseFloat(sum - discountMoney).toFixed(2);
        grand_total.innerHTML = "Pay $ " + parseFloat(sum - discountMoney).toFixed(2);
      }
    })
  });
</script>
