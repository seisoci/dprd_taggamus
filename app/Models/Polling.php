<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Polling extends Model
{
  use HasFactory;

  protected $fillable = [
    'title',
    'description',
    'status',
    'publish_at',
  ];

  public function options()
  {
    return $this->hasMany(PollingOption::class);
  }
}
