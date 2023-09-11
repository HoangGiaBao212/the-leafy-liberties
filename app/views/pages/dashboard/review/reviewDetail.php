<div class="bg-gray-100">
  <div class="container py-8 mx-auto">
    <div class="flex flex-wrap items-center justify-between mb-8">
      <div class="w-1/3">
        <img src="<?php

        use App\Models\Author;
        use App\Models\User;

        echo BASE_URI . $product->image; ?>" alt="Book Image" class="object-cover w-full rounded-lg shadow-lg">
      </div>
      <div class="w-2/3 px-4">
        <h1 class="mb-2 text-3xl font-bold">
          <?php echo $product->name ?>
        </h1>
        <p class="mb-4 text-lg text-gray-600">Author :
          <?php echo $product->author()->name ?>
        </p>
        <div class="flex items-center mb-2">
          <span class="mr-2 text-lg text-gray-600">Average rating :
          </span>
          <?php if (count($reviewsValid) > 0): ?>
            <div>
              <span class="mr-2 text-xl text-gray-600">
                <?php
                $averageRating = 0;
                foreach ($reviewsValid as $review) {
                  $averageRating += $review->rating;
                }
                echo (number_format(($averageRating / count($reviewsValid)), 1))
                  ?>
              </span>
              <span><i class="fa fa-star text-primary"></i></span>
            </div>
          <?php else: ?>
            <p>There are no reviews yet</p>
          <?php endif ?>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="w-full mx-auto my-0">
  <div class="box-border w-full min-h-screen px-10 mt-10 sm:px-5">
    <div class="flex justify-between">
      <h1 class="text-xl font-bold">Reviews</h1>
      <div class="box-border w-1/2">
        <form action="<?php echo BASE_URI . '/dashboard/review_detail' . '?id=' . $product->id ?>" method="GET"
          class="flex items-center justify-center w-full h-10 bg-gray-100 rounded-full shadow-md">
          <input type="text" name="keywords" class="w-full h-full pl-5 rounded-tl-full rounded-bl-full"
            placeholder="Search.... "
            value="<?php echo isset($params['filter']['keywords']) ? $params['filter']['keywords'] : '' ?>" />
          <button class="flex items-center justify-center w-10 h-10 bg-white rounded-tr-full rounded-br-full">
            <i class="fa-solid fa-magnifying-glass"></i>
          </button>
        </form>
      </div>
    </div>
    <div class="my-8 bg-white shadow-lg cursor-pointer table-product-statistics rounded-2xl">
      <div class="relative">
        <?php
        if (count($reviewsValid) > 0): ?>
          <table class="w-full text-sm text-center text-gray-500 rounded-2xl">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
              <tr>
                <?php
                $name = [
                  "ID",
                  "Image",
                  "Name",
                  "Content",
                  "Time",
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
              <?php foreach ($reviewsValid as $review):
                if (!$review->deleted_at):
                  $user = User::find($review->user_id);
                  ?>
                  <tr class="text-center transition-opacity bg-white border-b hover:bg-gray-200 even:bg-gray-100">
                    <td class="px-5 py-3">
                      <?php echo $review->id; ?>
                    </td>
                    <td class="w-32 px-5 py-3">
                      <img
                        src="<?php echo ($user->image == NULL) ? BASE_URI . '/resources/images/user/placeholder.png' : BASE_URI . $user->image ?>"
                        alt="" class="object-contain w-full h-full">
                    </td>
                    <td class="px-5 py-3">
                      <?php echo $user->name; ?>
                    </td>
                    <td class="px-5 py-3">
                      <?php echo $review->content; ?>
                    </td>
                    <td class="p-2">
                      <?php
                      $datetime = new DateTime($review->created_at);
                      echo $datetime->format('H:i d/m/Y');
                      // echo $product->created_at;
                      ?>
                    </td>
                    <td class="gap-2 px-5 py-3">
                      <div class="flex items-center justify-center gap-4 button">
                        <a href="<?php echo BASE_URI .
                          "/product" .
                          "?id=" .
                          $product->id; ?>"
                          class="px-3 py-2 text-white transition-all bg-blue-400 rounded-md edit-button hover:text-pink-300">
                          <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <span onclick="removeReviewConfirm(<?php echo $review->id ?>)"
                          class="px-3 py-2 text-white transition-all bg-red-400 rounded-md delete-button hover:text-blue-300">
                          Delete
                          <i class="fa-solid fa-pen-to-square"></i>
                        </span>
                      </div>
                    </td>
                  </tr>
                <?php endif; ?>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <div class="flex flex-col items-center justify-center h-full py-8">
            <i class="fa-solid fa-star-sharp-half-stroke text-[100px] text-gray-400"></i>
            <h1 class="text-6xl tracking-widest text-gray-400 uppercase">No reviews found</h1>
          </div>
        <?php endif;
        ?>
      </div>
    </div>
  </div>
</div>

<script type="module">
  import Toast from '<?php echo BASE_URI . "/resources/js/toast.js"; ?>';
  import FetchXHR from '<?php echo BASE_URI . "/resources/js/fetch-xhr.js"; ?>';

  document.removeReviewConfirm = (id) => {
    const result = confirm("Delete this comment?");
    if (result) {

      FetchXHR.post('<?php echo BASE_URI . "/dashboard/review/review_detail/delete"; ?>', {
        id
      }, {
        'Content-Type': 'application/json'
      }).then(response => {
        if (response.type === 'error') {
          alert(response.message);
        } else if (response.type === 'info') {
          alert(response.message);
        } else {
          alert('Comment deleted from review');
        }
      }).catch(error => {
        alert('Something went wrong');
      });
      setTimeout(() => {
        window.location.reload();
      }, 1000);
    }
  }
</script>
