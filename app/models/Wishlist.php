<?php

namespace App\Models;

use Core\Model;

class Wishlist extends Model
{
  protected $table = "wishlists";

  protected $fillable = ["product_id", "user_id"];

  public function product()
  {
    return Product::find($this->product_id);
  }

  public function user()
  {
    return User::find($this->user_id);
  }
}