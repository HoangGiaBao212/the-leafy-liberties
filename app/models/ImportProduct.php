<?php

namespace App\Models;

use Core\Model;
use Core\Traits\SoftDeletes;

class ImportProduct extends Model
{
  use SoftDeletes;
  protected $table = "imports_products";

  protected $fillable = ["id", "import_id", "product_id", "quantity", "price"];

  public function product()
  {
    return Product::find($this->product_id);
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