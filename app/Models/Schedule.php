<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
  use HasFactory;

  protected $fillable = [
    'title',
    'description',
    'date_start',
    'date_end',
  ];

  protected function serializeDate(\DateTimeInterface $date)
  {
    return $date->isoFormat('DD MMMM YYYY HH:mm');
  }
}
