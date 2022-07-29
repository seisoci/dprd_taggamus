<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElectionRegion extends Model
{
  use HasFactory;

  protected $fillable = [
    'name'
  ];
}
