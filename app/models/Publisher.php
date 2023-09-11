<?php
namespace App\Models;

use Core\Model;

class Publisher extends Model
{
  protected $table = "publishers";
  protected $fillable = ["id", "image", "name", "deleted_at"];
}