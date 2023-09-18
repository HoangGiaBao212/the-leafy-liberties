<?php

namespace App\Models;

use App\Models\Permission;
use App\Models\Role;
use Core\Database;
use Core\Model;
use Core\Traits\SoftDeletes;

class User extends Model
{
  use SoftDeletes;
  protected $table = "users";

  protected $fillable = [
    "email",
    "name",
    "phone",
    "password",
    "gender",
    "image",
    "role_id",
    "status",
    "address",
    "birthday",
    "created_at",
    "deleted_at"
  ];

  public function setPassword($password)
  {
    $this->password = password_hash($password, PASSWORD_DEFAULT);
  }

  public function role(): Role
  {
    return Role::find($this->role_id);
  }

  public function setRole(Role $role)
  {
    if ($this->role_id == $role->id) {
      return;
    }
    $this->role_id = $role->id;
  }

  public function permissions(): array
  {
    return UserPermission::findAll([
      "user_id" => $this->id,
    ]);
  }
  public function addPermission(Permission $permission)
  {
    $userPermission = new UserPermission();
    $userPermission->user_id = $this->id;
    $userPermission->permission_id = $permission->id;
    $userPermission->save();
  }

  public function removePermission(Permission $permission)
  {
    $userPermission = UserPermission::findOne([
      "user_id" => $this->id,
      "permission_id" => $permission->id,
    ]);
    $userPermission->delete();
  }

  public function wishlist(): array
  {
    return Wishlist::findAll([
      "user_id" => $this->id,
    ]);
  }

  public function orders(): array
  {
    return Order::findAll([
      "user_id" => $this->id,"deleted_at" => "null"
    ]);
  }
  public static function filterAdvanced($filter)
  {
    $users = User::all();
    // Filter by keywords
    if (!empty($filter['keywords'])) {
      $filteredProducts = [];
      foreach ($users as $user) {
        if (strpos(strtolower($user->name), strtolower($filter['keywords'])) !== false) {
          $filteredProducts[] = $user;
        }
      }
      $users = $filteredProducts;
    }
    return $users;
  }
}
