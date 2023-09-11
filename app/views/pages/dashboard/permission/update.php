<?php

$user = $params["user"];

$permissions = $params["permissions"];
$userPermissions = $params["userPermissions"];
$allPermissions = [];

foreach ($permissions as $permission) {
  $allPermissions[$permission->name] = [
    "id" => $permission->id,
    "name" => ucwords(str_replace(".", " ", $permission->name)),
    "status" => "0",
  ];
}
foreach ($userPermissions as $userPermission) {
  $allPermissions[$userPermission->name] = [
    "id" => $userPermission->permission_id,
    "name" => ucwords(str_replace(".", " ", $userPermission->name)),
    "status" => "1",
  ];
}

if ($allPermissions["dashboard.access"]["status"] == 0) {
  $allPermissions = [];
  $allPermissions["dashboard.access"] = [
    "id" => 1,
    "name" => "Dashboard Access",
    "status" => "0",
  ];
} else {
  $allPermissions["dashboard.access"] = [
    "id" => 1,
    "name" => "Dashboard Access",
    "status" => "1",
  ];
}

$groupPermissions = [];

foreach ($allPermissions as $key => $permission) {
  $group = explode(".", $key)[0];
  $groupPermissions[$group][] = $permission;
}

?>
<form class="min-h-screen mt-6" action="<?php echo BASE_URI . '/dashboard/permission/update' ?>" method="POST">
  <div class="mb-4">
    <!-- show user -->
    <label class="block mb-2 text-sm font-bold text-gray-700" for="name">
      Name
    </label>
    <input
      class="w-full px-3 py-2 mb-3 text-sm leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
      id="name" name="name" type="text" placeholder="Name" disabled value="<?php echo $user->name; ?>">
  </div>

  <input type="hidden" name="id" value="<?php echo $user->id; ?>">

  <!-- submit -->
  <button type="submit"
    class="w-full px-4 py-2 text-base font-semibold text-center text-white transition duration-200 ease-in bg-blue-500 rounded-md shadow-md hover:bg-blue-600 focus:ring-blue-500 focus:ring-offset-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2">
    Update
  </button>

  <table class="w-full mt-5 text-sm text-center text-gray-500 rounded-2xl" width="100%">
    <thead class="p-2 text-gray-700 uppercase text-md bg-gray-50">
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
                value="<?php echo $permission["id"]; ?>" <?php echo $permission["status"] == 1 ? "checked" : ""; ?> />
            </td>
          <?php endforeach; ?>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</form>