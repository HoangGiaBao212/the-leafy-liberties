<?php
namespace App\Models;

use App\Models\Permission;
use Core\Database;
use Core\Model;
use Core\Traits\SoftDeletes;

class Role extends Model
{
  protected $table = "roles";
  protected $fillable = ["name", "deleted_at"];

  public function permissions(): array
  {
    return RolePermission::findAll([
      "role_id" => $this->id,
    ]);
  }

  public function addPermission(Permission $permission)
  {
    $rolePermission = new RolePermission();
    $rolePermission->role_id = $this->id;
    $rolePermission->permission_id = $permission->id;
    $rolePermission->save();
  }

  public function removePermission(Permission $permission)
  {
    $rolePermission = RolePermission::findOne([
      "role_id" => $this->id,
      "permission_id" => $permission->id,
    ]);
    $rolePermission->delete();
  }
  public static function filterAdvanced($filter)
  {
    $roles = Role::all();
    // Filter by keywords
    if (!empty($filter['keywords'])) {
      $filteredProducts = [];
      foreach ($roles as $role) {
        if (strpos(strtolower($role->name), strtolower($filter['keywords'])) !== false) {
          $filteredProducts[] = $role;
        }
      }
      $roles = $filteredProducts;
    }
    return $roles;
  }
}