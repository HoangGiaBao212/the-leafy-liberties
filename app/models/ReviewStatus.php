<?php

namespace App\Models;

use Core\Model;

class ReviewStatus extends Model
{
  protected $table = "review_status";
  protected $fillable = ["product_id", "status"];
}