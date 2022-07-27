<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
  use HasFactory;

  protected $primaryKey = 'kode_bank';
  public $incrementing = false;
  protected $keyType = 'string';
  public $timestamps = false;

  protected $fillable = [
    'kode_bank',
    'bank_name'
  ];
}
