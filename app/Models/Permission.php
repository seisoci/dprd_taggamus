<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @mixin IdeHelpbackendermission
 */
class Permission extends Model
{
  use HasFactory;

  public $timestamps = false;

  protected $fillable = [
    'menu_permission_id',
    'name',
    'slug',
  ];

  protected static function boot()
  {
    parent::boot();
    static::creating(function ($permission) {
      $permission->slug = Str::slug($permission->slug);
    });

    static::updating(function ($permission) {
      $permission->slug = Str::slug($permission->slug);
    });
  }

  public function roles()
  {
    return $this->belongsToMany(Role::class);
  }
}
