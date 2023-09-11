<?php
namespace App\Models;

use App\Models\Product;
use Core\Model;

class OrderProduct extends Model
{
  protected $table = "orders_products";
  protected $fillable = [
    "order_id",
    "product_id",
    "quantity",
    "price",
    "created_at",
    "updated_at",
    "deleted_at",
  ];

  public function order()
  {
    return Order::find($this->order_id);
  }

  public function product()
  {
    return Product::find($this->product_id);
  }
}