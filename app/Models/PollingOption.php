<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PollingOption extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
      'polling_id',
      'sort',
      'name',
    ];

    public function polling()
    {
      return $this->belongsTo(Polling::class);
    }

    public function answers()
    {
      return $this->hasMany(PollingAnswer::class);
    }
}
