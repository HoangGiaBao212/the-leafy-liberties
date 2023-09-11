<?php
namespace App\Models;

use Core\Model;

class ProductTag extends Model
{
  protected $table = "products_tags";
  protected $fillable = ["product_id", "tag_id"];
}