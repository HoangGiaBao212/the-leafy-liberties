<?php
$users = $params["users"];
?>
<div class="w-full mx-auto my-0">
  <div class="box-border w-full min-h-screen px-10 mt-10 sm:px-5">
    <div class="flex justify-between">
      <h1 class="text-xl font-bold">Permissions</h1>
      <div class="box-border w-1/2">
        <form action="<?php echo BASE_URI . '/dashboard/permission' ?>" method="GET"
          class="flex items-center justify-center w-full h-10 bg-gray-100 rounded-full shadow-lg">
          <input type="text" name="keywords" class="w-full h-full pl-5 rounded-tl-full rounded-bl-full"
            placeholder="Search by name.... "
            value="<?php echo isset($params['filter']['keywords']) ? $params['filter']['keywords'] : '' ?>" />
          <button class="flex items-center justify-center w-10 h-10 bg-white rounded-tr-full rounded-br-full">
            <i class="fa-solid fa-magnifying-glass"></i>
          </button>
        </form>
      </div>
    </div>
    <div class="my-8 bg-white shadow-lg cursor-pointer table-customer-statistics rounded-2xl">
      <div class="relative overflow-x-scroll md:overflow-hidden">
        <table class="min-w-full overflow-x-scroll text-sm text-center text-gray-500 table-auto rounded-2xl">
          <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
            <tr>
              <?php
              use App\Models\Role;
              use App\Models\User;

              $name = [
                "ID",
                "Image",
                "Email",
                "Name",
                "Role",
                "Status",
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
            if (count($users) > 0): ?>
              <?php foreach ($users as $user): ?>
                <tr class="text-center transition-opacity bg-white border-b hover:bg-gray-200 even:bg-gray-100">
                  <td class="px-5 py-3">
                    <?php echo $user->id; ?>
                  </td>
                  <td class="px-5 py-3 w-28">
                    <img
                      src="<?php echo ($user->image == NULL) ? BASE_URI . '/resources/images/user/placeholder.png' : BASE_URI . $user->image ?>"
                      alt="" class="object-cover w-full h-full rounded-full">
                  </td>
                  <td class="px-5 py-3">
                    <?php echo $user->email; ?>
                  </td>
                  <td class="px-5 py-3">
                    <?php echo $user->name; ?>
                  </td>
                  <td class="px-5 py-3">
                    <?php
                    $role = Role::find($user->role_id);
                    echo ucfirst($role->name);
                    ?>
                  </td>
                  <td
                    class="px-5 py-3 <?php echo ($user->status == 1) ? 'text-primary-700 font-medium' : 'text-red-700 font-medium' ?>">
                    <?php
                    if ($user->status == 1) {
                      echo "Active";
                    } else {
                      echo "Banned";
                    }
                    ?>
                  </td>
                  <td class="px-5 py-3 w-44">
                    <div class="flex items-center justify-center gap-4 button">
                      <a href="<?php echo BASE_URI . "/dashboard/user/update" . "?id=" . $user->id; ?>"
                        class="px-3 py-2 text-white transition-all bg-blue-400 edit-button rounded-xl hover:bg-blue-500">
                        <i class="fa-solid fa-pen-to-square"></i>
                      </a>
                      <button
                        class="px-3 py-2 text-white transition-all bg-red-400 delete-button rounded-xl hover:bg-red-500"
                        onclick="removeUser(<?php echo $user->id ?>)">
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
  document.removeUser = (id) => {
    const result = confirm("Delete this user?");
    if (result) {
      FetchXHR.post('<?php echo BASE_URI . "/dashboard/user/delete" ?>', { id }, { 'Content-Type': 'application/json' })
        .then(response => {
          if (response.type === 'error') {
            alert(response.message);
          } else if (response.type === 'info') {
            alert(response.message);
          } else {
            alert('This user has been removed');
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
