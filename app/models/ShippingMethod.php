<?php
namespace App\Models;

use Core\Model;

class ShippingMethod extends Model
{
  protected $table = "shipping_methods";

  protected $fillable = [
    "id",
    "name",
    "price",
    "status",
    "description",
    "deleted_at",
  ];
}