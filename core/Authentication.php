<?php
namespace Core;

use App\Models\Permission;
use App\Models\RolePermission;
use App\Models\User;
use App\Models\UserPermission;

class Authentication
{
  protected $userId;

  public function __construct()
  {
    $this->userId = $_SESSION["user_id"] ?? null;
  }

  public function getUser(): ?User
  {
    if ($this->userId == null) {
      return null;
    }
    return User::findOne(["id" => $this->userId]);
  }

  public function setUser(User $user)
  {
    $_SESSION["user_id"] = $user->id;
  }

  public function isAuthenticated(): bool
  {
    return $this->getUser() ? true : false;
  }

  public function isGuest(): bool
  {
    return !$this->isAuthenticated();
  }

  public function getPermissions()
  {
    if (!$this->isAuthenticated()) {
      return [];
    }
    $user = $this->getUser();
    $role = $user->role();

    $userPermissions = $user->permissions();
    $rolePermissions = $role->permissions();
    $permissions = [];

    foreach ($rolePermissions as $rolePermission) {
      $permission = $rolePermission->permission();
      if ($permission) {
        $permissions[$permission->name] = $permission;
      }
    }

    foreach ($userPermissions as $userPermission) {
      $permission = $userPermission->permission();
      if ($permission) {
        $permissions[$permission->name] = $permission;
      }
    }

    return array_values($permissions);
  }

  public function hasPermission($permission)
  {
    $permissions = $this->getPermissions();
    foreach ($permissions as $p) {
      if ($p->name == $permission) {
        return true;
      }
    }
    return false;
  }
}