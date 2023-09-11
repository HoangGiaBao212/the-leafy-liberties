<?php
namespace App\Models;

use App\Models\OrderProduct;
use App\Models\ShippingMethod;
use App\Models\User;
use Core\Model;
use Core\Traits\SoftDeletes;

class Order extends Model
{
  use SoftDeletes;
  protected $table = "orders";
  protected $fillable = [
    'user_id',
    "name",
    "email",
    "address",
    "phone",
    "shipping_method_id",
    "coupon_id",
    "description",
    "payment_method_type",
    "card_number",
    "expiry_date",
    'total_price',
    "cvv",
    "total_price",
    "status",
    "created_at",
    "deleted_at",
  ];

  public function user()
  {
    return User::find($this->user_id);
  }

  public function products()
  {
    return OrderProduct::findAll(["order_id" => $this->id]);
  }
  public function shippingMethod()
  {
    return ShippingMethod::find($this->shipping_method_id);
  }

  public function paymentMethod()
  {
    return new Model([
      "type" => $this->payment_method_type,
      "card_number" => $this->card_number,
      "expiry_date" => $this->expiry_date,
      "cvv" => $this->cvv,
    ]);
  }
  public static function filterAdvanced($filter)
  {
    $orders = Order::all();
    // Filter by keywords
    if (!empty($filter['keywords'])) {
      $filteredProducts = [];
      foreach ($orders as $order) {
        if (strpos(strtolower($order->name), strtolower($filter['keywords'])) !== false) {
          $filteredProducts[] = $order;
        }
      }
      $orders = $filteredProducts;
    }
    return $orders;
  }
}
