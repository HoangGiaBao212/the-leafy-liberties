<?php

namespace App\Models;

use Core\Model;

class Cart extends Model
{
  protected $table = "carts";

  protected $fillable = ["user_id", "product_id", "quantity"];

  public function product()
  {
    return Product::findOne(["id" => $this->product_id]);
  }
}