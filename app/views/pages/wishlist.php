<?php
$products = $params["products"]; ?>
<div class="container mx-auto">
  <div class="flex items-center justify-start my-8 md:my-14">
    <h1 class="text-xl tracking-widest uppercase text-primary-700 md:text-3xl">wishlist</h1>
  </div>
  <div class="w-full px-8 overflow-y-scroll bg-white shadow-lg h-fit rounded-2xl wishlist-list" total="<?php echo count($products) ?>">
    <?php if (count($products) == 0) : ?>
      <div class="flex flex-col items-center justify-center h-full my-8">
        <i class="fa-solid fa-heart-pulse text-6xl md:text-[100px] text-gray-400"></i>
        <h1 class="text-4xl md:text-6xl tracking-widest text-gray-400 uppercase">Wishlist is
          empty</h1>
      </div>
    <?php else : ?>
      <table class="w-full border-collapse border-0 border-solid border-b-[1px]">
        <thead class="sticky top-0 w-full m-3 text-gray-600 border-b border-gray-300 rounded-sm">
          <tr class="font-normal text-left uppercase">
            <th>
            </th>
            <th class="py-2 px-9">
              Product
            </th>
            <th class="py-2">
            </th>
            <th>
              Author
            </th>
            <th class="py-2">
              Price
            </th>
            <th class="py-2">
              Action
            </th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($products as $product) : ?>
            <tr class="border-0 border-solid border-b-[1px]">
              <td class="text-right">
                <button class="px-4 py-2 font-bold text-gray-800 transition-all hover:text-black" onclick="removeFromWishlist(<?php echo $product->id; ?>)">
                  <i class="fa-solid fa-x"></i>
                </button>
              </td>
              <td class="p-3 w-36 h-36">
                <a href="<?php echo BASE_URI . '/product' . '?id=' . $product->id ?>">
                  <img src="<?php echo BASE_URI . $product->image; ?>" alt="<?php echo $product->name; ?>" class="object-contain w-full h-full mx-auto" />
                </a>
              </td>
              <td>
                <?php echo $product->name; ?>
              </td>
              <td>
                <?php echo $product->author()->name ?>
              </td>
              <td>
                <?php echo $product->price; ?>
              </td>
              <td class="px-1 py-2">
                <button class="w-full px-4 py-2 font-bold text-white transition-all bg-primary hover:bg-primary-700" onclick="addToCart(<?php echo $product->id; ?>)">
                  <i class="fa-solid fa-cart-plus"></i>
                  Add to cart
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif ?>
  </div>
  <div class="flex justify-end gap-3 mt-4">
    <!-- move all product in wishlist to cart -->

    <button class="px-4 py-2 font-bold text-white transition-all move-all-to-cart bg-primary hover:bg-primary-700" onclick="moveAllToCart()">
      <i class="fa-solid fa-cart-plus"></i>
      Move all to cart
    </button>

    <!-- remove all product in wishlist -->

    <button class="remove-all px-4 py-2 font-bold text-white transition-all bg-red-500 hover:bg-red-400" onclick="removeAllFromWishlist()">
      <i class="fa-solid fa-trash"></i>
      Remove all
    </button>

  </div>
</div>
<script>
  document.querySelectorAll('.plus, .minus').forEach(button => {
    button.addEventListener('click', event => {
      const span = event.currentTarget.parentElement.querySelector('.text-count');
      let quantity = parseInt(span.textContent);
      if (event.currentTarget.classList.contains('plus')) {
        quantity++;
      } else if (event.currentTarget.classList.contains('minus') && quantity > 0) {
        quantity--;
      }
      span.textContent = quantity;
    });
  });
</script>

<script type="module">
  import Toast from '<?php echo BASE_URI . "/resources/js/toast.js"; ?>';
  import FetchXHR from '<?php echo BASE_URI . "/resources/js/fetch-xhr.js"; ?>';

  document.addToCart = (id) => {
    FetchXHR.post('<?php echo BASE_URI . "/api/wishlist/add-to-cart"; ?>', {
      id
    }, {
      'Content-Type': 'application/json'
    }).then(response => {
      const data = response.data;
      new Toast({
        message: data.message,
        type: data.type,
      });
    }).catch(error => {
      new Toast({
        message: 'Something went wrong',
        type: 'error',
      });
    });
    setTimeout(() => {
      window.location.reload();
    }, 1000);

  }
  document.removeFromWishlist = (id) => {
    if (confirm("Do you want to remove this product from wishlist")) {
      FetchXHR.post('<?php echo BASE_URI . "/api/wishlist/remove"; ?>', {
        id
      }, {
        'Content-Type': 'application/json'
      }).then(response => {
        if (response.type === 'error') {
          alert(response.message);
        } else if (response.type === 'info') {
          alert(response.message);
        } else {
          alert('Product deleted from wishlist');
        }
      }).catch(error => {
        alert('Something went wrong');
      });
      setTimeout(() => {
        window.location.reload();
      }, 1000);
    }
  }

  document.moveAllToCart = () => {
    if (<?php echo count($products) ?> == 0) {
      alert('Your wishlist is empty');
    } else {
      if (confirm("Do you want to move all product to cart?")) {
        FetchXHR.post('<?php echo BASE_URI . "/api/wishlist/move-all-to-cart"; ?>').then(response => {
          const data = response.data;
          new Toast({
            message: data.message,
            type: data.type,
          });
        }).catch(error => {
          new Toast({
            message: 'Something went wrong',
            type: 'error',
          });
        });
        setTimeout(() => {
          window.location.reload();
        }, 1000);
      }
    }
  }


  document.removeAllFromWishlist = () => {
    if (<?php echo count($products) ?> == 0) {
      alert('Your wishlist is empty');
    } else {
      if (confirm("Do you want to remove all products from wishlist?")) {
        FetchXHR.post('<?php echo BASE_URI . "/api/wishlist/empty"; ?>').then(response => {
          if (response.type === 'error') {
            alert(response.message);
          } else if (response.type === 'info') {
            alert(response.message);
          } else {
            alert('All products deleted from wishlist');
          }
        }).catch(error => {
          alert('Something went wrong');
        });
        setTimeout(() => {
          window.location.reload();
        }, 1000);
      }
    }
  }
</script>