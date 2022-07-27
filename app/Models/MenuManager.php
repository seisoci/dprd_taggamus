<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperMenuManager
 */
class MenuManager extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'parent_id',
        'menu_permission_id',
        'role_id',
        'title',
        'path_url',
        'icon',
        'type',
        'sort',
    ];

    public function menupermission()
    {
        return $this->belongsTo(MenuPermission::class, 'menu_permission_id');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role')->withPivot('role_id');
    }

    public function getall($id)
    {
        return $this->with('menupermission')->where("role_id", $id)->orderBy("sort", "asc")->get();
    }
}
