<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holder extends Model
{
  protected $guarded = ['id'];
  /**
  * Get all of the accounts for the Holder.
  */
  public function accounts()
  {
   return $this->hasMany('App\Models\Account');
  }
}
