<?php

use App\Models\Author;
use App\Models\Product;
use App\Models\Pagination;

$filter = $params['filter'];
// dd($filter);
?>

<div class="w-full mx-auto my-0">
  <div class="box-border w-full min-h-screen px-10 mt-10 sm:px-5">
    <div class="flex justify-between">
      <h1 class="text-xl font-bold">Author</h1>
      <div class="box-border w-1/2 px-10">
        <form action="<?php echo BASE_URI . '/dashboard/author' ?>" method="GET"
          class="flex items-center justify-center w-full h-10 bg-gray-100 rounded-full shadow-lg">
          <input type="text" name="keywords" class="w-full h-full pl-5 rounded-tl-full rounded-bl-full"
            placeholder="Search.... "
            value="<?php echo isset($params['filter']['keywords']) ? $params['filter']['keywords'] : '' ?>" />
          <button class="flex items-center justify-center w-10 h-10 bg-white rounded-tr-full rounded-br-full">
            <i class="fa-solid fa-magnifying-glass"></i>
          </button>
        </form>
      </div>
      <a class="w-5 h-5 text-2xl add-product" href="<?php echo BASE_URI . "/dashboard/author/create"; ?>">
        +
      </a>
    </div>
    <div class="my-8 bg-white shadow-lg cursor-pointer table-author-statistics rounded-2xl">
      <div class="relative">
        <table class="w-full text-sm text-center text-gray-500 rounded-2xl">
          <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
              <?php
              $namess = [
                "ID",
                "Image",
                "Name",
                "Product Quantity",
                "Action",
              ];
              for ($i = 1; $i <= count($namess); $i++) { ?>
                <th scope="col" class="px-6 py-3">
                  <?php echo $namess[$i - 1]; ?>
                </th>
              <?php }
              ?>
            </tr>
          </thead>
          <tbody>
            <?php
            if (count($authors) > 0): ?>
              <?php foreach ($authors as $author): ?>
                <tr class="text-center transition-opacity bg-white border-b hover:bg-gray-200 even:bg-gray-100">
                  <td class="px-5 py-3">
                    <?php echo $author->id; ?>
                  </td>
                  <td class="w-40 h-40 px-5 py-3">
                    <img
                      src="<?php echo ($author->image == NULL) ? BASE_URI . '/resources/images/authors/placeholder.png' : BASE_URI . $author->image ?>"
                      alt="" class="object-cover w-full h-full rounded-full">
                  </td>
                  <td class="px-5 py-3">
                    <?php echo $author->name; ?>
                  </td>
                  <td class="px-5 py-3">
                    <?php echo count($author->products()) ?>
                  </td>
                  <td class="px-5 py-3">
                    <div class="flex items-center justify-center gap-4 button">
                      <a href="<?php echo BASE_URI .
                        "/dashboard/author/update" .
                        "?id=" .
                        $author->id; ?>"
                        class="px-3 py-2 text-white transition-all bg-blue-400 edit-button rounded-xl hover:bg-blue-500">
                        <i class="fa-solid fa-pen-to-square"></i>
                      </a>
                      <button onclick="removeAuthor(<?php echo $author->id; ?>)"
                        class="px-3 py-2 text-white transition-all bg-red-400 delete-button rounded-xl hover:bg-red-500">
                        <i class="fa-solid fa-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif;
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<script type="module">
  import FetchXHR from '<?php echo BASE_URI . "/resources/js/fetch-xhr.js"; ?>';
  document.removeAuthor = (id) => {
    const result = confirm("Delete this author?");
    if (result) {
      FetchXHR.post('<?php echo BASE_URI . "/dashboard/author/delete" ?>', { id }, { 'Content-Type': 'application/json' })
        .then(response => {
          if (response.type === 'error') {
            alert(response.message);
          } else if (response.type === 'info') {
            alert(response.message);
          } else {
            alert('This author has been removed');
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