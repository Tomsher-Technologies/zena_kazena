<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{

  protected $fillable = [
    'name',
    'name_ar',
    'phone',
    'email',
    'address',
    'address_ar',
    'delivery_pickup_latitude',
    'delivery_pickup_longitude',
    'status',
  ];

  protected $with = ['user'];

  public function user()
  {
    return $this->belongsToMany(User::class);
  }

  public function order_transfer()
  {
      return $this->hasMany(OrderTransfers::class);
  }

}
