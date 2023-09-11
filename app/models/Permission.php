<?php

namespace App\Models;

use App\Models\Role;
use App\Models\User;
use Core\Model;

class Permission extends Model
{
  protected $table = "permissions";
  protected $fillable = ["name", "status", "deleted_at"];

  public function roles()
  {
    $rolePermissions = RolePermission::findAll(["permission_id" => $this->id]);
    $roles = [];
    foreach ($rolePermissions as $rolePermission) {
      $roles[] = Role::find($rolePermission->role_id);
    }
    return $roles;
  }

  public function users()
  {
    $userPermissions = UserPermission::findAll(["permission_id" => $this->id]);
    $users = [];
    foreach ($userPermissions as $userPermission) {
      $users[] = User::find($userPermission->user_id);
    }
    return $users;
  }
}