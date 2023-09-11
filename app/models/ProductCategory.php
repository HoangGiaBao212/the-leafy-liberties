<?php
namespace App\Models;

use Core\Model;

class ProductCategory extends Model
{
  protected $table = "products_categories";
  protected $fillable = ["product_id", "category_id"];

  public function category()
  {
    return Category::find($this->category_id);
  }
}