<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestBook extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'email',
    'description',
    'status'
  ];


  protected function serializeDate(\DateTimeInterface $date)
  {
    return $date->isoFormat('DD MMMM YYYY HH:mm');
  }
}
