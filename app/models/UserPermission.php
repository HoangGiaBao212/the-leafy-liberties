<?php
namespace App\Models;

use App\Models\Permission;
use App\Models\User;
use Core\Model;

class UserPermission extends Model
{
  protected $table = "users_permissions";
  protected $fillable = ["user_id", "permission_id", "status"];
  public function user(): User
  {
    return User::find($this->user_id);
  }
  public function permission(): Permission
  {
    return Permission::find($this->permission_id);
  }
}