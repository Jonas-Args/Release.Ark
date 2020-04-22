<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShippingFeeType extends Model
{
  protected $table = 'shipping_fee_type';

  public function packagingType() {
    return $this->hasOne(PackagingType::class , 'id', 'packaging_type_id');
  }

}
