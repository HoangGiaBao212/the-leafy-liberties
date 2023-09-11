<?php
namespace App\Models;

use App\Models\Permission;
use Core\Model;

class RolePermission extends Model
{
  protected $table = "roles_permissions";
  protected $fillable = ["role_id", "permission_id", "status"];
  public function role(): Role
  {
    return Role::find($this->role_id);
  }
  public function permission(): Permission
  {
    return Permission::find($this->permission_id);
  }
}