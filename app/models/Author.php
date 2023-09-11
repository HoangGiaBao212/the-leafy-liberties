<?php

namespace App\Models;

use Core\Model;
use Core\Traits\SoftDeletes;

class Author extends Model
{
  use SoftDeletes;
  protected $table = "authors";
  protected $fillable = ["id", "image", "name", "deleted_at"];

  public function products()
  {
    return Product::findAll(["author_id" => $this->id]);
  }
  public static function filterAdvanced($filter)
  {
    $authors = Author::all();
    // Filter by keywords
    if (!empty($filter['keywords'])) {
      $filteredProducts = [];
      foreach ($authors as $author) {
        if (strpos(strtolower($author->name), strtolower($filter['keywords'])) !== false) {
          $filteredProducts[] = $author;
        }
      }
      $authors = $filteredProducts;
    }
    return $authors;
  }
}
