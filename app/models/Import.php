<?php

namespace App\Models;

use Core\Model;
use Core\Traits\SoftDeletes;

class Import extends Model
{
  use SoftDeletes;
  protected $table = "import";

  protected $fillable = ["id", "user_id", "total_price", "created_at", "deleted_at"];
  public function user()
  {
    return User::find($this->user_id);
  }

  public function importProducts()
  {
    return ImportProduct::findAll(["import_id" => $this->id]);
  }

  public function addProduct($product, $quantity, $price)
  {
    $importProduct = ImportProduct::create([
      "import_id" => $this->id,
      "product_id" => $product->id,
      "quantity" => $quantity,
      "price" => $price,
    ]);
    $product->quantity += $quantity;
    $product->save();
    $this->total_price += $price * $quantity;
    $this->save();
    return $importProduct;
  }
  public static function filterAdvanced($filter)
  {
    $imports = Import::all();
    // Filter by keywords
    if (!empty($filter['keywords'])) {
      $filteredProducts = [];
      foreach ($imports as $import) {
        if (strpos(strtolower($import->user_id), strtolower($filter['keywords'])) !== false) {
          $filteredProducts[] = $import;
        }
      }
      $imports = $filteredProducts;
    }
    return $imports;
  }
}
