<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
  protected $guarded = ['id'];
  /**
  * Get the holder of this account.
  */
  public function holder()
  {
     return $this->belongsTo('App\Models\Holder');
  }
}
