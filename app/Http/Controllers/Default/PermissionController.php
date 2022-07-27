<?php

namespace App\Http\Controllers\Default;

use App\Http\Controllers\Controller;
use App\Models\MenuPermission;
use App\Models\Permission;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Throwable;

class PermissionController extends Controller
{
  use ResponseStatus;

  public function index(Request $request)
  {
    $config['page_title'] = "Permission";
    $page_breadcrumbs = [
      ['url' => '#', 'title' => "Permission"],
    ];
    if ($request->ajax()) {
      $data = MenuPermission::query();
      return DataTables::of($data)
        ->addColumn('action', function ($row) {
          return
            '<div class="dropdown">
            <button type="button" class="btn btn-primary" data-bs-toggle="dropdown" aria-expanded="false">
              Aksi <i class="fa-regular fa-down"></i>
            </button>
            <ul class="dropdown-menu">
              <li> <a href="#" data-bs-toggle="modal" data-bs-target="#modalEdit" data-bs-id="' . $row->id . '" data-bs-title="' . $row->title . '" data-bs-slug="' . $row->slug . '" data-bs-path_url="' . $row->path_url . '" data-bs-icon="' . $row->icon . '" class="dropdown-item">Edit</a></li>
              <li> <a href="#" data-bs-toggle="modal" data-bs-target="#modalDelete" data-bs-id="' . $row->id . '" class="delete dropdown-item">Delete</a></li>
            </ul>
          </div>';

        })
        ->make(true);
    }

    return view('default.permissions.index', compact('config', 'page_breadcrumbs'));
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'title' => 'required|unique:menu_permissions,title',
      'path_url' => 'required|string',
    ]);

    if ($validator->passes()) {
      DB::beginTransaction();
      try {
        $menuPermission = MenuPermission::create([
          'title' => ucwords($request['title']),
          'slug' => $request['slug'],
          'path_url' => $request['path_url'],
          'icon' => $request['icon']
        ]);

        $defaultPermission = ['List', 'Create', 'Edit', 'Delete'];
        foreach ($defaultPermission as $item):
          Permission::create([
            'menu_permission_id' => $menuPermission->id,
            'slug' => $request['slug']. " " . $item,
            'name' => ($request['title']) . " " . $item
          ]);
        endforeach;
        DB::commit();
        $response = response()->json($this->responseStore(true));
      } catch (Throwable $throw) {
        dd($throw);

        Log::error($throw);
        DB::rollBack();
        $response = response()->json($this->responseStore(false));
      }
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }
    return $response;
  }

  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'title' => 'required|unique:menu_permissions,title,' . $id,
      'path_url' => 'required|string',
    ]);

    if ($validator->passes()) {
      DB::beginTransaction();
      try {
        $data = MenuPermission::find($id);
        $permission = Permission::where('menu_permission_id', $id)->orderBy('id', 'asc')->get();
        $data->update([
          'title' => ucwords($request['title']),
          'slug' => $request['slug'],
          'path_url' => $request['path_url'],
          'icon' => $request['icon']
        ]);

        $defaultPermission = ['List', 'Create', 'Edit', 'Delete'];
        foreach ($permission as $key => $item):
          $item->update([
            'slug' => $request['slug']. " " . $defaultPermission[$key],
            'name' => ($request['title']) . " " . $defaultPermission[$key]
          ]);
        endforeach;
        DB::commit();
        $response = response()->json($this->responseUpdate(true));

      } catch (Throwable $throw) {
        Log::error($throw);
        DB::rollBack();
        $response = response()->json($this->responseUpdate(false));
      }
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }
    return $response;
  }

  public function destroy($id)
  {
    $data = MenuPermission::findOrFail($id);
    if ($data->delete()) {
      $response = response()->json($this->responseDelete(true));

    }
    return $response;
  }
}
