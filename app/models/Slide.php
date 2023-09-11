<?php
namespace App\Models;

use Core\Model;
use Core\Traits\SoftDeletes;

class Slide extends Model
{
  protected $table = "slides";
  // use SoftDeletes;
  protected $fillable = ["name", "image", "status", "deleted_at"];
  public static function filterAdvanced($filter)
  {
    $slides = Slide::all();
    // Filter by keywords
    if (!empty($filter['keywords'])) {
      $filteredProducts = [];
      foreach ($slides as $slide) {
        if (strpos(strtolower($slide->name), strtolower($filter['keywords'])) !== false) {
          $filteredProducts[] = $slide;
        }
      }
      $slides = $filteredProducts;
    }
    return $slides;
  }
}
