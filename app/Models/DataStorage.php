<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataStorage extends Model
{
  use HasFactory;

  protected $fillable = [
    'type',
    'name',
    'sort',
    'storage_data_type',
    'storage_data_id',
  ];

  public function storagable()
  {
    return $this->morphTo();
  }
}
