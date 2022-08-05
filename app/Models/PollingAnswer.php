<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollingAnswer extends Model
{
  use HasFactory;

  protected $fillable = [
    'polling_id',
    'polling_option_id',
    'ip_address',
  ];
}
