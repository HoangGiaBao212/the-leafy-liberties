<?php
$permissions = $params["permissions"];
$allPermissions = [];

foreach ($permissions as $permission) {
  $allPermissions[$permission->name] = [
    "id" => $permission->id,
    "name" => ucwords(str_replace(".", " ", $permission->name)),
    "status" => "0",
  ];
}
$groupPermissions = [];

foreach ($allPermissions as $key => $permission) {
  $group = explode(".", $key)[0];
  $groupPermissions[$group][] = $permission;
}
$allPermissions = array_values($allPermissions);
?>

<form class="w-full h-full my-6" method="post" action="<?php echo BASE_URI . "/dashboard/role/create" ?>">
  <div class="mb-4">
    <label class="block font-bold text-gray-700" for="name">
      Name
    </label>
    <input
      class="block w-full p-2 mt-1 bg-white bg-gray-100 border-transparent rounded-md focus:border-gray-500 focus:ring-0"
      type="text" required name="name" id="name" placeholder="Role name">
  </div>

  <!-- submit -->
  <button type="submit"
    class="w-full px-4 py-2 text-base font-semibold text-center text-white transition duration-200 ease-in bg-blue-500 rounded-md shadow-md hover:bg-blue-600 focus:ring-blue-500 focus:ring-offset-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2">
    Create
  </button>
  <table class="w-full mt-5 text-sm text-center text-gray-500 rounded-2xl" width="100%">
    <thead class="p-2 text-gray-700 uppercase bg-gray-100 text-md">
      <tr>
        <th class="px-4 py-3" width="50%">Permissions</th>
        <th class="px-4 py-3">Access</th>
        <th class="px-4 py-3">Show</th>
        <th class="px-4 py-3">Delete</th>
        <th class="px-4 py-3">Create</th>
        <th class="px-4 py-3">Update</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($groupPermissions as $groupName => $groupPermission): ?>
        <tr class="text-center transition-opacity bg-white border-b hover:bg-gray-200 even:bg-gray-100 !text-md">
          <td class="px-5 py-3">
            <?php echo ucfirst($groupName); ?>
          </td>
          <?php foreach ($groupPermission as $permission): ?>
            <td>
              <input
                class="w-4 h-4 mr-3 text-indigo-600 border-gray-300 rounded shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                type="checkbox" name="permissions[]" id="<?php echo $permission["name"]; ?>"
                value="<?php echo $permission["id"]; ?>">
            </td>
          <?php endforeach; ?>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <a href="<?php echo BASE_URI . '/dashboard/role' ?>"
    class="block w-full px-4 py-2 my-10 text-base font-semibold text-center text-white transition duration-200 ease-in bg-blue-500 rounded-md shadow-md hover:bg-blue-600 focus:ring-blue-500 focus:ring-offset-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2">
    Cancel
  </a>
</form>
