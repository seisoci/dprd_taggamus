<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperMenuPermission
 */
class MenuPermission extends Model
{

    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'title',
        'slug',
        'path_url',
        'icon',
    ];

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
}
