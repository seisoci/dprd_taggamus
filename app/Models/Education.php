<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;

    protected $fillable = [
      'partai_member_id',
      'name_institution',
      'major',
      'faculty',
      'entry_year',
      'graduation_year',
    ];
}
