<?php

namespace App\Http\Controllers\Default;

use App\Http\Controllers\Controller;
use App\Models\MenuManager;
use App\Models\MenuPermission;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MenuPermissionController extends Controller
{
  use ResponseStatus;

  public function __construct()
  {
    $this->middleware('can:menu-list', ['only' => ['index', 'show']]);
    $this->middleware('can:menu-create', ['only' => ['create', 'store']]);
    $this->middleware('can:menu-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:menu-delete', ['only' => ['destroy']]);
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'title' => 'required|unique:menu_permissions,title',
      'icon' => 'string',
    ]);

    if ($validator->passes()) {
      $data = MenuPermission::create([
        'title' => ucwords($request['title']),
        'icon' => $request['icon'],
      ]);

      if ($data->save()) {
        $response = response()->json($this->responseStore(true));
      }
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }
    return $response;
  }

  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required|unique:menu_permissions,title,' . $id,
    ]);

    if ($validator->passes()) {
      $data = MenuPermission::find($id);
      $data->update([
        'title' => ucwords($request['title']),
        'icon' => $request['icon'],
      ]);
      $response = response()->json($this->responseUpdate(true));
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }
    return $response;
  }

  public function destroy(MenuPermission $menuPermission)
  {
    if ($menuPermission->delete()) {
      $response = response()->json($this->responseDelete(true));
    }
    return $response;
  }

  public function select2(Request $request)
  {
    $menuManager = MenuManager::where('role_id', $request['role_id'])->whereNotNull('menu_permission_id')->get();
    $page = $request->page;
    $resultCount = 10;
    $offset = ($page - 1) * $resultCount;
    $data = MenuPermission::where('title', 'LIKE', '%' . $request->q . '%')
      ->whereNotIn('id', $menuManager->pluck('menu_permission_id'))
      ->orderBy('title')
      ->skip($offset)
      ->take($resultCount)
      ->selectRaw('`id`, `title` as `text`, path_url as `url`')
      ->get();

    $count = MenuPermission::where('title', 'LIKE', '%' . $request->q . '%')
      ->whereNotIn('id', $menuManager->pluck('menu_permission_id'))
      ->get()
      ->count();

    $endCount = $offset + $resultCount;
    $morePages = $count > $endCount;

    $results = array(
      "results" => $data,
      "pagination" => array(
        "more" => $morePages
      )
    );

    return response()->json($results);
  }
}
