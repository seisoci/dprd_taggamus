<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartaiMember extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'position',
    'place_birth',
    'no_member',
    'komisi_id',
    'election_region_id',
    'religion',
    'partai',
    'period',
    'image',
    'sort',
  ];

  public function komisi()
  {
    return $this->belongsTo(Komisi::class);
  }

  public function election_region()
  {
    return $this->belongsTo(ElectionRegion::class);
  }

  public function education()
  {
    return $this->hasMany(Education::class);
  }

  public function organization()
  {
    return $this->hasMany(Organization::class);
  }

  public function movement()
  {
    return $this->hasMany(Movement::class);
  }

  public function profession()
  {
    return $this->hasMany(Profession::class);
  }
}
