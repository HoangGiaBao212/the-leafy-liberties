<?php
use App\Models\Product;
use App\Models\User;
use Core\Application;
use Core\Authentication;

$auth = Application::getInstance()->getAuthentication();
$user = $auth->getUser();
$products = Product::all();

?>
<div class="add-form min-h-screen w-full justify-center items-center bg-opacity-75 z-[500] my-5">
  <div class="w-full p-8 bg-white rounded-md shadow-lg ">
    <h2 class="mb-4 text-xl font-bold">Add Import Bill</h2>
    <form class="flex flex-col" action="<?php echo BASE_URI . "/dashboard/import/create"; ?>" method="POST"
      enctype="multipart/form-data">

      <input type="hidden" name="user-id" value="<?php echo $user->id ?>">

      <div class="flex flex-col">
        <label class="mb-2 text-lg font-bold text-gray-900" for="name">List Product</label>
        <div class="flex flex-row">
          <table class="w-full text-center border-collapse">
            <thead>
              <tr>
                <th class="p-3 font-bold bg-gray-100">Product Name</th>
                <th class="p-3 font-bold bg-gray-100">Quantity</th>
                <th class="p-3 font-bold bg-gray-100">Price</th>
                <th class="p-3 font-bold bg-gray-100">Total</th>
                <th class="p-3 font-bold bg-gray-100">Action</th>
              </tr>
            </thead>
            <tbody id="list-product">
            </tbody>
          </table>
        </div>
      </div>

      <!-- button choose product to table -->
      <div class="flex flex-col">
        <label class="mb-2 text-lg font-bold text-gray-900" for="name">Choose Product</label>
        <div class="flex flex-row">
          <select
            class="w-full px-3 py-2 mr-4 leading-tight text-gray-700 border border-gray-400 rounded focus:ring-2 focus:ring-primary-400 appearance-none"
            name="product-id" id="product-id">
            <?php foreach ($products as $product): ?>
              <option value="<?php echo $product->id ?>"><?php echo $product->name ?></option>
            <?php endforeach; ?>
          </select>
          <input
            class="w-full px-3 py-2 mr-4 leading-tight text-gray-700 border border-gray-400 rounded focus:ring-2 focus:ring-primary-400"
            type="number" name="quantity" id="quantity" placeholder="Quantity"
            onkeydown="if (event.keyCode === 69 || event.keyCode === 189 || event.keyCode == 107 || event.keyCode == 110 || event.keyCode == 109 || event.keyCode == 190) return false;" />
          <input
            class="w-full px-3 py-2 mr-4 leading-tight text-gray-700 border border-gray-400 rounded focus:ring-2 focus:ring-primary-400"
            type="number" name="price" id="price" placeholder="Price"
            onkeydown="if (event.keyCode === 69 || event.keyCode === 189 || event.keyCode == 107 || event.keyCode == 110) return false;" />
          <button class="my-2 bg-[#2e524e] hover:bg-[#52938d] transition-colors text-white font-bold py-2 px-4 rounded"
            type="button" id="add-product">
            Add
          </button>
        </div>
      </div>


      <button class="my-2 bg-[#2e524e] hover:bg-[#52938d] transition-colors text-white font-bold py-2 px-4 rounded"
        type="submit">
        Submit
      </button>
      <a class="px-4 py-2 my-1 font-bold text-center text-white transition-none bg-gray-500 rounded cancel-button hover:bg-gray-700"
        href="<?php echo BASE_URI . '/dashboard/import' ?>">
        Cancel
      </a>
    </form>
  </div>
</div>
<script type="module">
  import FetchXHR from '<?php echo BASE_URI . "/resources/js/fetch-xhr.js"; ?>';
  const productList = document.getElementById("list-product");
  const productIdInput = document.getElementById("product-id");
  const quantityInput = document.getElementById("quantity");
  const priceInput = document.getElementById("price");
  const addButton = document.getElementById("add-product");

  let products = {};

  addButton.addEventListener("click", async () => {
    const productId = productIdInput.value;
    const quantity = Number(quantityInput.value);
    const price = Number(priceInput.value);

    if (isNaN(quantity) || quantity <= 0) {
      alert("Quantity must be a number and greater than 0");
      return;
    }

    if (isNaN(price) || price <= 0) {
      alert("Price must be a number and greater than 0");
      return;
    }

    let product = await getProductById(productId);

    if (products[productId]) {
      products[productId].quantity += quantity;
    } else {
      product = {
        name: product.name,
        quantity: quantity,
        price: price
      };

      products[productId] = product;
    }

    productIdInput.value = "";
    quantityInput.value = "";
    priceInput.value = "";

    updateTable();
  });

  function updateTable() {
    productList.innerHTML = "";
    Object.keys(products).forEach((productId) => {
      const product = products[productId];
      const row = document.createElement("tr");
      row.classList.add("even:bg-gray-100")
      const nameCell = document.createElement("td");
      nameCell.classList.add("px-5", "py-3")
      nameCell.textContent = product.name;
      row.appendChild(nameCell);
      const quantityCell = document.createElement("td");
      quantityCell.classList.add("px-5", "py-3")
      quantityCell.textContent = product.quantity;
      row.appendChild(quantityCell);
      const priceCell = document.createElement("td");
      priceCell.classList.add("px-5", "py-3")
      priceCell.textContent = product.price;
      row.appendChild(priceCell);
      const totalCell = document.createElement("td");
      totalCell.classList.add("px-5", "py-3")
      const total = product.quantity * product.price;
      totalCell.textContent = total.toFixed(2);
      row.appendChild(totalCell);
      const actionCell = document.createElement("td");
      const removeButton = document.createElement("button");
      removeButton.classList.add("px-4", "py-2", "bg-red-400", "text-white", "rounded-md", "shadow");
      removeButton.textContent = "Remove";
      removeButton.addEventListener("click", () => {
        delete products[productId];
        updateTable();
      });
      actionCell.appendChild(removeButton);
      row.appendChild(actionCell);
      productList.appendChild(row);
    });
  }

  const getProductById = async (id) => {
    const product = await FetchXHR.get('<?php echo BASE_URI . "/api/products/show?id=" ?>' + id)
    return product.data
  }

  productIdInput.addEventListener("change", async () => {
    const productId = productIdInput.value;
    const product = await getProductById(productId);
    priceInput.value = product.price;
  });

  const form = document.querySelector("form");
  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const form = document.createElement("form");
    form.action = '<?php echo BASE_URI . "/dashboard/import/create" ?>';
    form.method = "POST";

    const userIdInput = document.createElement("input");
    userIdInput.name = "user-id";
    userIdInput.value = <?php echo $user->id ?>;
    form.appendChild(userIdInput);

    Object.keys(products).forEach((productId) => {
      const product = products[productId];

      const productIdInput = document.createElement("input");
      productIdInput.name = "product-id[]";
      productIdInput.value = productId;
      form.appendChild(productIdInput);

      const quantityInput = document.createElement("input");
      quantityInput.name = "quantity[]";
      quantityInput.value = product.quantity;
      form.appendChild(quantityInput);

      const priceInput = document.createElement("input");
      priceInput.name = "price[]";
      priceInput.value = product.price;
      form.appendChild(priceInput);
    });

    document.body.appendChild(form);
    form.submit();
  });

</script>
