<?php

namespace App\Http\Controllers\Default;

use App\Http\Controllers\Controller;
use App\Models\MenuManager;
use App\Models\MenuPermission;
use App\Models\Role;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class MenuManagerController extends Controller
{
  use ResponseStatus;

//  public function __construct()
//  {
//    $this->middleware('can:dkmp-menu-list', ['only' => ['index']]);
//    $this->middleware('can:dkmp-menu-create', ['only' => ['create', 'store']]);
//    $this->middleware('can:dkmp-menu-edit', ['only' => ['edit', 'update']]);
//    $this->middleware('can:dkmp-menu-delete', ['only' => ['destroy']]);
//  }

  public function index(Request $request)
  {
    $config['page_title'] = "Menu Manager";
    $config['page_description'] = "Menu Manager";
    $page_breadcrumbs = [
      ['url' => '#', 'title' => "Menu Manager"],
    ];
    $data = NULL;
    $role = Role::find($request['role_id']);
    $sortable = NULL;
    if ($role) {
      $sortable = self::getByRole($request['role_id']);
    } elseif ($request['role_id'] != NULL) {
      abort(401, "Halaman tidak diizinkan");
    }

    return view('default.menumanager.index', compact('config', 'page_breadcrumbs', 'role', 'sortable', 'data'));
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'role_id' => 'required|integer',
      'menu_permission_id' => 'required_if:type,database|integer|nullable',
      'type' => 'required|in:database,static',
      'title' => 'required_if:type,static|nullable|string',
      'path_url' => 'nullable|string',
      'icon' => 'nullable',
      'permission' => 'array',
      'permission.*' => 'string'
    ]);

    if ($validator->passes()) {
      DB::beginTransaction();
      try {
        if ($request['type'] == 'database') {
          $menuManager = MenuManager::create([
            'menu_permission_id' => $request['menu_permission_id'],
            'role_id' => $request['role_id'],
            'sort' => MenuManager::where([
                ['role_id', $request['role_id']],
                ['parent_id', $request['parent_id'] ?? 0]
              ])->max('sort') + 1
          ]);
          $menuPermission = MenuPermission::with('permissions')->find($request['menu_permission_id']);
          collect($menuPermission->permissions)->map(function ($permission) use ($request, $menuManager) {
            foreach ($request['permission'] ?? array() as $item):
              $exp = explode('-', $permission->slug);
              if ($exp[array_key_last($exp)] == $item):
                $menuManager->permissions()->attach($permission->id, ['role_id' => $request['role_id']]);
              endif;
            endforeach;
          });
        } else {
          MenuManager::create([
            'role_id' => $request['role_id'],
            'title' => $request['title'],
            'path_url' => $request['path_url'],
            'icon' => $request['icon'],
            'sort' => MenuManager::where([
                ['role_id', $request['role_id']],
                ['parent_id', $request['parent_id'] ?? 0]
              ])->max('sort') + 1
          ]);
        }
        DB::commit();
        $response = $this->responseStore(true);
      } catch (Throwable $throw) {
        DB::rollBack();
        $response = $throw;
      }
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }
    return $response;
  }

  public function edit($id, Request $request)
  {
    $config['page_title'] = "Menu Manager";
    $config['page_description'] = "Menu Manager";
    $page_breadcrumbs = [
      ['url' => '#', 'title' => "Menu Manager"],
    ];
    $role = Role::find($request['role_id']);
    $data = MenuManager::with(['menupermission', 'permissions'])->find($id);
    $sortable = NULL;
    if ($role) {
      $sortable = self::getByRole($request['role_id']);
    } elseif ($request['role_id'] != NULL) {
      abort(401, "Halaman tidak diizinkan");
    }
    $permissions = collect($data->permissions)->map(function ($item) use ($data) {
      $exp = explode("-", $item->slug);
      return $exp[array_key_last($exp)];
    })->toArray();

    return view('default.menumanager.index', compact('config', 'page_breadcrumbs', 'data', 'permissions', 'role', 'sortable'));
  }

  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'role_id' => 'required|integer',
      'menu_permission_id' => 'required_if:type,database|integer|nullable',
      'type' => 'required|in:database,static',
      'title' => 'required_if:type,static|nullable|string',
      'path_url' => 'nullable|string',
      'icon' => 'nullable',
      'permission' => 'array',
      'permission.*' => 'string'
    ]);

    if ($validator->passes()) {

      DB::beginTransaction();
      try {
        $roleId = $request['role_id'];
        $menuManager = MenuManager::with(['menupermission', 'permissions'])->findOrFail($id);
        if ($request['type'] == 'database') {
          $menuManager->update([
            'menu_permission_id' => $request['menu_permission_id'],
            'role_id' => $request['role_id'],
            'title' => NULL,
            'path_url' => NULL,
            'icon' => NULL,
          ]);
          $menuPermission = MenuPermission::with('permissions')->find($request['menu_permission_id']);
          $menuManager->permissions()->detach();
          collect($menuPermission->permissions)->map(function ($permission) use ($request, $menuManager) {
            foreach ($request['permission'] ?? array() as $item):
              $exp = explode('-', $permission->slug);
              if ($exp[array_key_last($exp)] == $item):
                $menuManager->permissions()->attach($permission->id, ['role_id' => $request['role_id']]);
              endif;
            endforeach;
          });
        } else {
          $menuManager->update([
            'role_id' => $request['role_id'],
            'menu_permission_id' => NULL,
            'title' => $request['title'],
            'path_url' => $request['path_url'],
            'icon' => $request['icon']
          ]);
        }
        DB::commit();
        $response = response()->json([
          'status' => 'success',
          'message' => 'Data berhasil diupdate',
          'redirect' => "/backend/menu?role_id=$roleId"
        ]);
      } catch (Throwable $throw) {
        DB::rollback();
        $response = $this->responseDelete(false);
      }
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }
    return $response;
  }

  public function destroy($id, Request $request)
  {
    DB::beginTransaction();
    try {
      $roleId = $request['role_id'];
      $data = MenuManager::findOrFail($id);
      $child = MenuManager::where('parent_id', $id)->get();
      $data->delete();
      foreach ($child as $item):
        MenuManager::find($item['id'])->update([
          'parent_id' => '0'
        ]);
      endforeach;
      DB::commit();
      $response = response()->json([
        'status' => 'success',
        'message' => 'Data berhasil dihapus',
        'redirect' => "/backend/menu?role_id=$roleId"
      ]);
    } catch (Throwable $throw) {
      DB::rollback();
      $response = response()->json([
        'status' => 'error',
        'message' => 'Gagal menghapus data'
      ]);
    }
    return $response;
  }

  public static function getByRole($roleId)
  {
    $menuManager = new MenuManager;
    $menu_list = $menuManager->getall($roleId);
    $roots = $menu_list->where('parent_id', 0);
    return self::tree($roots, $menu_list, $roleId);
  }

  private static function tree($roots, $menu_list, $roleId)
  {
    $html = '<ol class="dd-list"> ';
    foreach ($roots as $item) {
      $find = $menu_list->where('parent_id', $item['id']);
      $html .= '
      <li class="dd-item dd3-item" data-id="' . $item->id . '">
        <div class="dd-handle dd3-handle"></div>
        <div class="dd3-content">' . (!$item->menu_permission_id ? $item->title : $item->menupermission->title) . '</div>
        <div class="dd3-actions">
          <div class="btn-group">
            <a href="#" class="btn btn-sm font-weight-bold">' . ($item->menu_permission_id ? "F" : "S") . '</a>
            <a href="/backend/menu/' . $item->id . '/edit?role_id=' . $roleId . '" class="btn btn-sm btn-default"
              ><i class="fa fa-fw fa-edit"></i>
            </a>
            <button
              type="button"
              class="btn btn-sm btn-default"
              data-bs-id="' . $item->id . '"
              data-bs-toggle="modal"
              data-bs-target="#modalDelete"
              ><i class="fa fa-fw fa-trash"></i>
            </button>
          </div>
        </div>
      ';
      if ($find->count()) {
        $html .= self::tree($find, $menu_list, $roleId);
      }
      $html .= '</li>';
    }
    $html .= '</ol>';
    return $html;
  }

  public function changeHierarchy(Request $request)
  {
    $data = json_decode($request['hierarchy'], TRUE);
    $menuItems = $this->render_menu_hierarchy($data);

    DB::beginTransaction();
    try {
      foreach ($menuItems as $item):
        MenuManager::find($item['id'])->update([
          'parent_id' => $item['parent_id'],
          'sort' => $item['sort']
        ]);
      endforeach;
      DB::commit();
      $response = response()->json([
        'status' => 'success',
        'message' => 'Data berhasil dihapus',
        'redirect' => "reload"
      ]);
    } catch (Throwable $throw) {
      DB::rollback();
      $response = response()->json([
        'status' => 'error',
        'message' => 'Gagal menghapus data'
      ]);
    }
    return $response;

  }

  public function render_menu_hierarchy($data = array(), $parentMenu = 0, $result = array())
  {
    foreach ($data as $key => $val) {
      $row['id'] = $val['id'];
      $row['parent_id'] = $parentMenu;
      $row['sort'] = ($key + 1);
      array_push($result, $row);
      if (isset($val['children']) && $val['children'] > 0) {
        $result = array_merge($result, $this->render_menu_hierarchy($val['children'], $val['id']));
      }
    }
    return $result;
  }
}
