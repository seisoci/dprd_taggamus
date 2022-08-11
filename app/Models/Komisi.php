<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komisi extends Model
{
  use HasFactory;

  protected $fillable = [
    'name'
  ];

  public function partai_members(){
    return $this->hasMany(PartaiMember::class, 'komisi_id');
  }
}
