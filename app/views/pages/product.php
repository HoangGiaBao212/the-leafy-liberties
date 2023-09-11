<?php

use App\Models\Wishlist;
use Core\Application;
use App\Models\Cart;
use App\Models\Review;
use App\Models\User;

$auth = Application::getInstance()->getAuthentication();
$user = $auth->getUser();
$reviewsActive = [];
foreach ($reviews as $review) {
  if ($review->deleted_at == null) {
    $reviewsActive[] = $review;
  }
}
// $checkReviewContent = null;
foreach ($reviewsActive as $review) {
  if ($user && $user->id == $review->user_id) {
    $checkReviewContent = $review;
    break;
  }
}

// !Important: About the $user variable, you must check if the user is authenticated or not before using it
// And code about working on user must be in the if block below to avoid error
if ($auth->isAuthenticated()) {
  // echo $user->email;
}

// I don't think use flag is a good idea
// You can only use Cart::findOne(["user_id" => $user->id, "product_id" => $product->id]) to check if the product is in the cart
// Same for wishlist, you can use Wishlist::findOne(["user_id" => $user->id, "product_id" => $product->id]) to check if the product is in the wishlist
if ($user != null) {
  $cartCheck = Cart::findOne(["user_id" => $user->id, "product_id" => $product->id]);
  $wishlistCheck = Wishlist::findOne(["user_id" => $user->id, "product_id" => $product->id]);
}
?>

<div class="box-border p-5 pt-3 sm:p-12 md:p-25 md:pt-12 lg:p-36 lg:pt-20">
  <!-- // ? option  -->
  <div class="grid grid-cols-1 gap-3 xl:grid-cols-2">
    <div
      class="box-border flex justify-center p-5 border border-gray-400 border-solid lg:mr-5 rounded-3xl w-full h-[600px] ">
      <img class="h-full w=h-full object-contain" src="<?php echo BASE_URI . $product->image; ?>" alt="Book info" />
    </div>
    <div class="box-border w-auto h-auto p-4 mt-5 border border-gray-400 border-solid lg:p-10 lg:mt-0 rounded-3xl">
      <p
        class="<?php echo ($product->quantity != 0) ? 'text-primary-700' : 'text-red-700' ?> p-1 mb-2 md:mb-6 text-[11px] sm:text-sm font-medium inline-block uppercase">
        <?php
        if ($product->quantity == 0) {
          echo "Out of stock";
        } else {
          echo "in stock";
        }
        ?>
      </p>
      <br />
      <label class="text-2xl md:text-3xl lg:text-4xl">
        <?php echo $product->name; ?>
      </label>
      <br />
      <div class="my-3 author-tag">
        <span class="">
          Author:
        </span>
        <span class="font-bold cursor-pointer text-primary hover:underline">
          <?php echo $author->name; ?>
        </span>
      </div>
      <div class="isbn-tag">
        <span>ISBN:</span>
        <span class="font-bold cursor-pointer text-primary hover:underline">
          <?php echo $product->isbn; ?>
        </span>
      </div>
      <div class="box-border py-3 border border-gray-400 border-solid border-x-0">
        <span class="text-2xl font-semibold text-green-800 sm:text-3xl">
          <?php echo $product->price; ?>$
        </span>

        <div class="flex items-center justify-start w-full gap-3 mt-4">
          <div
            class="flex items-center justify-between gap-2 px-3 py-2 text-lg transition-all border border-gray-300 rounded-full cursor-pointer bg-gray-50 hover:bg-primary-500 hover:text-gray-700 group"
            onclick="addToCart(<?php echo $product->id; ?>)">
            <i
              class="p-2 transition-all rounded-full fa-brands fa-opencart group-hover:text-white group-hover:bg-primary-400 <?php echo ($cartCheck) ? 'bg-primary-700 text-white' : 'bg-gray-50 text-black' ?>"></i>
            <?php if ($auth->hasPermission("product.access") && $auth->hasPermission("dashboard.access")): ?>
              <button class="text-sm font-medium sm:text-base md:text-lg"
                onclick="location.href='<?php echo BASE_URI . '/dashboard/product/update' . '?id=' . $product->id ?>'; event.stopPropagation();">
                Update Product
              </button>
            <?php elseif (isset($cartCheck)): ?>
              <button alt="" class="text-sm font-medium sm:text-base md:text-lg">
                Added to cart
              </button>
            <?php else: ?>
              <button alt="" class="text-sm font-medium sm:text-base md:text-lg">
                Add to cart
              </button>
            <?php endif ?>

          </div>
          <div
            class="<?php echo ($auth->hasPermission("product.access") && $auth->hasPermission("dashboard.access") ? 'hidden' : 'block') ?> flex items-center justify-between gap-2 px-3 py-2 text-lg transition-all border border-gray-300 rounded-full cursor-pointer bg-gray-50 hover:bg-primary-500 hover:text-gray-700 group"
            onclick="addToWishList(<?php echo $product->id; ?>)">
            <i
              class="p-2 transition-all rounded-full fa-regular fa-heart group-hover:text-white group-hover:bg-red-400 wishlist-icon <?php echo ($wishlistCheck) ? 'bg-red-400 text-white' : 'bg-gray-50 text-black' ?>"></i>
            <button type="submit" src="" alt="" class="text-sm font-medium sm:text-base md:text-lg add-to-wishlist">
              <?php
              if (isset($wishlistCheck)) {
                echo "Added to wishlist";
              } else {
                echo "Add to wishlist";
              }
              ?>
            </button>
          </div>
        </div>
      </div>
      <?php if (count($product->categories()) <= 0): ?>
        <p class="text-gray-400">No category</p>
      <?php else: ?>
        <p class="inline-block mt-5 text-gray-400">Category: </p>
        <p class="inline-block ml-1">
          <?php
          $categories = $product->categories();
          $count = count($categories);
          $categoryNames = [];
          foreach ($categories as $category) {
            $categoryNames[] = '<a href="' . BASE_URI . '/" class="font-medium hover:underline text-primary">' . $category->name . '</a>';
          }
          echo implode(', ', $categoryNames);
          ?>
        </p>
        <br />
      <?php endif ?>
      <?php if (count($product->tags()) <= 0): ?>
        <p class="text-gray-400">No tag</p>
      <?php else: ?>
        <p class="inline-block text-gray-400">Tags :</p>
        <p class="inline-block ml-1">
          <?php
          $tags = $product->tags();
          $count = count($tags);
          $tagNames = [];
          foreach ($tags as $tag) {
            $tagNames[] = '<a href="' . BASE_URI . '/" class="font-medium hover:underline text-primary">' . $tag->name . '</a>';
          }
          echo implode(', ', $tagNames);
          ?>
        </p>
      <?php endif ?>

      <div class="flex items-center justify-start gap-4 mt-10">
        <a href="https://www.facebook.com/jack.willam2003/">
          <i
            class="w-12 h-12 p-2 text-2xl text-center transition-all border border-gray-300 rounded-full cursor-pointer fa-brands fa-facebook-f hover:text-white hover:bg-blue-500"></i>
        </a>
        <a href="https://www.facebook.com/jack.willam2003/">
          <i
            class="w-12 h-12 p-2 text-2xl text-center transition-all border border-gray-300 rounded-full cursor-pointer fa-brands fa-twitter hover:text-white hover:bg-blue-500"></i>
        </a>
        <a href="https://www.facebook.com/jack.willam2003/">
          <i
            class="w-12 h-12 p-2 text-2xl text-center transition-all border border-gray-300 rounded-full cursor-pointer fa-brands fa-instagram hover:text-white hover:bg-pink-500"></i>
        </a>
      </div>
    </div>
  </div>
  <!-- //? info detail  -->
  <div class="mt-20">
    <ul class="box-content flex justify-center">
      <li id="description-tab"
        class="p-2 mb-5 mr-10 transition-all border-4 border-white cursor-pointer hover:border-b-primary-800 border-b-primary-800"
        onclick="showDescription()">
        Description
      </li>
      <li id="review-tab"
        class="p-2 mb-2 mr-5 transition-all border-4 border-white cursor-pointer sm:mr-10 sm:mb-5 hover:border-b-primary-800"
        onclick="showReview()">
        Review
        <?php echo "(" . count($reviewsActive) . ")" ?>
      </li>
    </ul>

    <div class="box-border p-2 border border-gray-400 border-solid rounded-3xl sm:h-auto sm:py-10 sm:px-20"
      id="description">
      <p class="text-lg text-center ">
        <?php echo $product->description; ?>
      </p>
    </div>
    <div class="box-border hidden p-2 border border-gray-400 border-solid rounded-3xl sm:h-auto sm:py-10 sm:px-20"
      id="review">
      <?php
      if ($user && $user->role_id == 3):
        ?>
        <form method="POST" action="<?php echo BASE_URI . "/product/review_status" . "?id=" . $product->id ?>"
          class="flex justify-end">
          <button class="px-4 py-2 font-bold text-white transition-all rounded bg-primary hover:bg-primary-600">
            <?php
            echo $review_status->status == 0 ? 'Turn on comment' : 'Turn off comment'
              ?>
          </button>
        </form>
      <?php endif ?>
      <!--  -->
      <?php
      if ($review_status->status == 0):
        ?>
        <p class="text-lg text-center">Comment feature is locked</p>
        <?php
      else:
        ?>
        <div>
          <?php
          if (count($reviewsActive) == 0):
            ?>
            <p class="text-lg text-center">No comments yet!</p>
          <?php else: ?>
            <h1 class="p-10 mb-20 text-3xl border-b-2 border-gray-300 ">
              <?php
              echo count($reviewsActive) . ' reviews for ' . $product->name;
              ?>
            </h1>
            <div class="overflow-y-auto max-h-[500px] bg-zinc-50 rounded-md shadow-md p-4">
              <?php
              foreach ($reviewsActive as $review):
                $userReview = User::find($review->user_id);
                ?>
                <div class="flex flex-row justify-start review ">
                  <div class="review-header">
                    <img
                      src="<?php echo ($userReview->image == NULL) ? BASE_URI . '/resources/images/user/placeholder.png' : BASE_URI . $userReview->image ?>"
                      alt="Avatar" class="object-contain w-20 h-full rounded-full cursor-pointer">
                  </div>
                  <div class="ml-2 mr-2 w-72 review-body">
                    <div class="flex flex-row mb-2">
                      <h3 class="inline-block mr-2 font-bold">
                        <?php echo $userReview->name;
                        echo $userReview->role_id == 3 ? '<span class="text-red-500">(admin)</span>' : '';
                        ?>
                      </h3>
                      <h4 class="ml-2 text-gray-400">
                        <?php

                        date_default_timezone_set('Asia/Ho_Chi_Minh');
                        $current_time = time();
                        $review_time = strtotime($review->created_at);
                        $time_diff = $current_time - $review_time;

                        $days = floor($time_diff / (60 * 60 * 24));
                        $hours = floor(($time_diff - ($days * 60 * 60 * 24)) / (60 * 60));
                        $minutes = floor(($time_diff - ($days * 60 * 60 * 24) - ($hours * 60 * 60)) / 60);
                        $seconds = $time_diff - ($days * 60 * 60 * 24) - ($hours * 60 * 60) - ($minutes * 60);

                        if ($days > 0) {
                          echo $days == 1 ? 'about ' . $days . ' day ago' : 'about ' . $days . ' days ago';
                        } else if ($hours > 0) {
                          echo $hours == 1 ? 'about ' . $hours . ' hour ago' : 'about ' . $hours . ' hours ago';
                        } else if ($minutes > 0) {
                          echo $minutes == 1 ? 'about ' . $minutes . ' minute ago' : 'about ' . $minutes . ' minutes ago';
                        } else {
                          echo $seconds == 1 ? 'about ' . $seconds . ' second ago' : 'about ' . $seconds . ' seconds ago';
                        }
                        ?>
                      </h4>
                    </div>
                    <h5 id="content<?php echo $review->id ?>" class="content w-52">
                      <?php echo $review->content ?>
                    </h5>
                  </div>
                  <?php if ($userReview->role_id != 3): ?>
                    <div class="review-footer">
                      <ul class="flex flex-row rating">
                        <?php
                        $stars = str_repeat('<li><i class="fa fa-star text-primary"></i></li>', $review->rating);
                        $stars .= str_repeat('<li><i class="text-gray-400 fa fa-star"></i></li>', 5 - $review->rating);
                        echo $stars;
                        ?>
                      </ul>
                    </div>
                  <?php endif; ?>
                  <!-- end of -->
                  <div class="">
                    <i id="comment-edit" onclick="openEditForm(<?php echo $review->id ?>);"
                      class="<?php echo $user ? ($userReview->id != $user->id ? "hidden" : "flex") : "hidden"; ?> comment-edit fa-solid fa-pen-to-square cursor-pointer mt-2 py-3 px-3 bg-blue-400 text-white rounded-xl hover:text-pink-500 transition-all"></i>
                  </div>
                </div>
                <form id="update-form<?php echo $review->id ?>"
                  action="<?php echo $user ? (BASE_URI . "/product/comment/update" . "?id=" . $review->id) : BASE_URI . "/login" ?>"
                  method="POST" class="flex-row justify-end hidden mt-2 update-form">
                  <div class="">
                    <input id="update-input<?php echo $review->id ?>" value="<?php echo $review->content ?>" type="text"
                      name="update-comment" placeholder="Edit a comment..." required
                      class="border-b-2 border-gray-300 w-[335px]"
                      oninvalid="this.setCustomValidity('Please enter a comment')" oninput="setCustomValidity('')">
                    <?php
                    if ($user && $user->role_id != 3):
                      ?>
                      <div class="flex items-center mt-4 mb-4">
                        <span class="mr-2">Rating : </span>
                        <div class="flex">
                          <label id="startLabel1" for="star-update<?php echo $review->id ?>1"
                            class="px-1 text-2xl text-gray-300 cursor-pointer">&#9733;</label>
                          <input type="radio" id="star-update<?php echo $review->id ?>1"
                            name="update-rating<?php echo $review->id ?>" value="1" class="hidden" />
                          <label id="startLabel2" for="star-update<?php echo $review->id ?>2"
                            class="px-1 text-2xl text-gray-300 cursor-pointer">&#9733;</label>
                          <input type="radio" id="star-update<?php echo $review->id ?>2"
                            name="update-rating<?php echo $review->id ?>" value="2" class="hidden" />
                          <label id="startLabel3" for="star-update<?php echo $review->id ?>3"
                            class="px-1 text-2xl text-gray-300 cursor-pointer">&#9733;</label>
                          <input type="radio" id="star-update<?php echo $review->id ?>3"
                            name="update-rating<?php echo $review->id ?>" value="3" class="hidden" />
                          <label id="startLabel4" for="star-update<?php echo $review->id ?>4"
                            class="px-1 text-2xl text-gray-300 cursor-pointer">&#9733;</label>
                          <input type="radio" id="star-update<?php echo $review->id ?>4"
                            name="update-rating<?php echo $review->id ?>" value="4" class="hidden" />
                          <label id="startLabel5" for="star-update<?php echo $review->id ?>5"
                            class="px-1 text-2xl text-gray-300 cursor-pointer">&#9733;</label>
                          <input type="radio" id="star-update<?php echo $review->id ?>5"
                            name="update-rating<?php echo $review->id ?>" value="5" class="hidden" />
                        </div>
                      </div>
                      <?php
                    endif;
                    ?>
                    <div class="flex flex-row">
                      <div class="box-border">
                        <button type="submit"
                          class="px-2 py-1 ml-2 font-bold text-white transition-all rounded-md mt-9 bg-primary hover:bg-primary-600">Comment</button>
                      </div>
                      <div
                        class="box-border <?php echo (!isset($checkReviewContent) || $user->role_id == 3) ? 'flex' : 'hidden' ?>">
                        <span id="new-comment-button" onclick="openNewComment(<?php echo $userReview->id ?>)" type="submit"
                          class="px-2 py-1 mb-5 ml-2 font-bold text-white transition-all rounded-md cursor-pointer mt-9 bg-primary hover:bg-primary-600">New
                          Comment</span>
                      </div>
                    </div>
                  </div>
                </form>
                <?php
              endforeach;
              ?>
            </div>

            <?php
          endif ?>
          <div class="mt-20 ">
            <form id="comment-form" action="<?php echo BASE_URI . "/product/comment" . "?id=" . $product->id ?>"
              method="POST"
              class=" <?php echo (!isset($checkReviewContent) || $user->role_id == 3) ? 'flex' : 'hidden' ?> flex justify-between items-center border-t-2 border-gray-300 pt-14 w-full">

              <img
                src="<?php echo $user ? (($user && $user->image == NULL) ? BASE_URI . '/resources/images/user/placeholder.png' : BASE_URI . $user->image) : BASE_URI . '/resources/images/user/placeholder.png' ?> "
                alt="Avatar" class="object-contain h-24 rounded-full cursor-pointer">

              <div class="w-full mx-5">
                <h1 class="mb-2 font-bold">
                  <?php echo $user ? $user->name : 'User'; ?>
                </h1>
                <input id="new-comment-input" type="text" name="new-comment" placeholder="Add a comment..." required
                  class="w-full border-b-2 border-gray-300" oninvalid="this.setCustomValidity('Please enter a comment')"
                  oninput="setCustomValidity('')">
                <?php
                if ($user && $user->role_id != 3):
                  ?>
                  <div class="flex items-center mt-4 mb-4">
                    <span class="mr-2">Rating : </span>
                    <div class="flex">
                      <label id="startLabel1" for="star1" class="px-1 text-2xl text-gray-300 cursor-pointer">&#9733;</label>
                      <input type="radio" id="star1" name="rating" value="1" class="hidden" />
                      <label id="startLabel2" for="star2" class="px-1 text-2xl text-gray-300 cursor-pointer">&#9733;</label>
                      <input type="radio" id="star2" name="rating" value="2" class="hidden" />
                      <label id="startLabel3" for="star3" class="px-1 text-2xl text-gray-300 cursor-pointer">&#9733;</label>
                      <input type="radio" id="star3" name="rating" value="3" class="hidden" />
                      <label id="startLabel4" for="star4" class="px-1 text-2xl text-gray-300 cursor-pointer">&#9733;</label>
                      <input type="radio" id="star4" name="rating" value="4" class="hidden" />
                      <label id="startLabel5" for="star5" class="px-1 text-2xl text-gray-300 cursor-pointer">&#9733;</label>
                      <input type="radio" id="star5" name="rating" value="5" class="hidden" />
                    </div>
                  </div>
                  <?php
                endif;
                ?>
              </div>
              <button type="submit"
                class="px-4 py-2 font-bold text-white transition-all rounded-md bg-primary hover:bg-primary-600">Comment</button>
            </form>

            <?php
      endif ?>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  function showDescription() {
    document.getElementById("description").style.display = "block"
    document.getElementById("review").style.display = "none"
    document.getElementById("description-tab").classList.add("border-b-primary-800")
    document.getElementById("review-tab").classList.remove("border-b-primary-800")
  }

  function showReview() {
    document.getElementById("review").style.display = "block";
    document.getElementById("description").style.display = "none"
    document.getElementById("review-tab").classList.add("border-b-primary-800")
    document.getElementById("description-tab").classList.remove("border-b-primary-800")

  }
</script>
<script type="module">
  import Toast from '<?php echo BASE_URI . "/resources/js/toast.js"; ?>';
  import FetchXHR from '<?php echo BASE_URI . "/resources/js/fetch-xhr.js"; ?>';

  document.addToWishList = (id) => {
    FetchXHR.post('<?php echo BASE_URI . '/api/wishlist/add' ?>', {
      id
    }, {
      'Content-Type': 'application/json'
    }).then(response => {
      const data = response.data;
      new Toast({
        message: data.message,
        type: data.type
      });
    }).catch(error => {
      console.error(error);
    });
    setTimeout(() => {
      window.location.reload();
    }, 1000);
  };
  document.addToCart = (id) => {
    FetchXHR.post('<?php echo BASE_URI . '/api/cart/add' ?>', {
      id
    }, {
      'Content-Type': 'application/json'
    }).then(response => {
      const data = response.data;
      new Toast({
        message: data.message,
        type: data.type
      });
    }).catch(error => {
      console.error(error);
    });
    setTimeout(() => {
      window.location.reload();
    }, 1000);
  };


  document.toggleCommentStatus = (id) => {
    FetchXHR.post('<?php echo BASE_URI . '/product/review_status' ?>', {
      id
    }, {
      'Content-Type': 'application/json'
    }).then(response => {
      const data = response.data;
      new Toast({
        message: data.message,
        type: data.type
      });
    }).catch(error => {
      console.error(error);
    });
    setTimeout(() => {
      window.location.reload();
    }, 1000);
  };


  document.querySelector('.wishlist-icon').addEventListener('click', (event) => {
    if (event.target.classList.contains('bg-red-400')) {
      event.target.classList.remove('bg-red-400');
      event.target.classList.add('bg-white');
      event.target.classList.remove('text-white');
      event.target.classList.add('text-red-400');
    } else {
      event.target.classList.add('bg-red-400');
      event.target.classList.remove('bg-white');
      event.target.classList.add('text-white');
      event.target.classList.remove('text-red-400');
    }
  });





  document.commentUpdate = (id) => {
    FetchXHR.post('<?php echo BASE_URI . '/api/wishlist/add' ?>', {
      id
    }, {
      'Content-Type': 'application/json'
    }).then(response => {
      const data = response.data;
      new Toast({
        message: data.message,
        type: data.type
      });
    }).catch(error => {
      console.error(error);
    });
  };
</script>
<script>
  let idReviewRating

  function openEditForm(id) {
    let forms = document.querySelectorAll('.update-form')
    let contents = document.querySelectorAll('.content')
    idReviewRating = id
    for (let i = 0; i < forms.length; i++) {
      forms[i].classList.add('hidden')
      forms[i].classList.remove('flex')
    }
    for (let i = 0; i < contents.length; i++) {
      contents[i].classList.add('inline-block')
      contents[i].classList.remove('hidden')
    }
    document.getElementById(`content${id}`).classList.add('hidden');
    document.getElementById(`update-form${id}`).classList.remove('hidden')
    document.getElementById(`update-form${id}`).classList.add('flex')
    document.getElementById(`update-input${id}`).focus()
    document.getElementById("comment-form").classList.add('hidden')
    document.getElementById("comment-form").classList.remove('flex')

    const labelsUpdate = document.querySelectorAll(`label[for^="star-update${idReviewRating}"]`);

    labelsUpdate.forEach((label, i) => {
      label.addEventListener('click', function () {
        labelsUpdate.forEach((l, j) => {
          if (j <= i) {
            l.classList.add('text-primary');
            l.classList.remove('text-gray-300');
          } else {
            l.classList.add('text-gray-300');
            l.classList.remove('text-primary');
          }
        });
      });
    });

    // let lastSelected;

    const inputsUpdate = document.querySelectorAll(`input[name="update-rating${idReviewRating}"]`)
    console.log(idReviewRating)

    labelsUpdate.forEach((label, i) => {
      label.addEventListener('mouseover', function () {
        labelsUpdate.forEach((l, j) => {
          if (j <= i) {
            l.classList.add('text-primary');
            l.classList.remove('text-gray-300');
          } else {
            l.classList.add('text-gray-300');
            l.classList.remove('text-primary');
          }
        });
      });

      label.addEventListener('mouseout', function () {
        let selectedValue;
        inputsUpdate.forEach(input => {
          if (input.checked) {
            selectedValue = input.value;
          }
        });
        console.log(selectedValue);
        labelsUpdate.forEach((l, j) => {
          if (j < selectedValue) {
            l.classList.add('text-primary')
            l.classList.remove('text-gray-300')
          } else {
            l.classList.add('text-gray-300')
            l.classList.remove('text-primary')
          }
        });
      });
    });

  }
  const openNewComment = (id) => {
    let forms = document.querySelectorAll('.update-form')
    for (let i = 0; i < forms.length; i++) {
      forms[i].classList.add('hidden')
      forms[i].classList.remove('flex');

    }
    document.getElementById("comment-form").classList.remove('hidden')
    // document.getElementById(`update-form${id}`).classList.add('hidden')
    // document.getElementById(`update-form${id}`).classList.remove('flex')
    document.getElementById("new-comment-input").focus();
  }




  const labels = document.querySelectorAll('label[for^="star"]');

  labels.forEach((label, i) => {
    label.addEventListener('click', function () {
      labels.forEach((l, j) => {
        if (j <= i) {
          l.classList.add('text-primary');
          l.classList.remove('text-gray-300');
        } else {
          l.classList.add('text-gray-300');
          l.classList.remove('text-primary');
        }
      });
    });
  });


  const inputs = document.querySelectorAll('input[name="rating"]');

  labels.forEach((label, i) => {
    label.addEventListener('mouseover', function () {
      labels.forEach((l, j) => {
        if (j <= i) {
          l.classList.add('text-primary');
          l.classList.remove('text-gray-300');
        } else {
          l.classList.add('text-gray-300');
          l.classList.remove('text-primary');
        }
      });
    });

    label.addEventListener('mouseout', function () {
      let selectedValue;
      inputs.forEach(input => {
        if (input.checked) {
          selectedValue = input.value;
        }
      });
      console.log(selectedValue);
      labels.forEach((l, j) => {
        if (j < selectedValue) {
          l.classList.add('text-primary');
          l.classList.remove('text-gray-300');
        } else {
          l.classList.add('text-gray-300');
          l.classList.remove('text-primary');
        }
      });
    });
  });
</script>
