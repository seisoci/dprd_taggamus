<?php

namespace App\Http\Controllers\Default;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
  use ResponseStatus;

  public function __construct()
  {
    $this->middleware('can:backend-roles-list', ['only' => ['index', 'show']]);
    $this->middleware('can:backend-roles-create', ['only' => ['create', 'store']]);
    $this->middleware('can:backend-roles-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:backend-roles-delete', ['only' => ['destroy']]);
  }

  public function index(Request $request)
  {
    $config['page_title'] = "Roles";
    $page_breadcrumbs = [
      ['url' => '#', 'title' => "Roles"],
    ];

    if ($request->ajax()) {
      $data = Role::whereNotIn('slug', ['super-admin']);
      return DataTables::of($data)
        ->addColumn('action', function ($row) {
          $actionBtn = '<div class="dropdown">
                          <button type="button" class="btn btn-primary" data-bs-toggle="dropdown" aria-expanded="false">
                            Aksi <i class="fa-regular fa-down"></i>
                          </button>
                          <ul class="dropdown-menu">
                            <li> <a href="#" data-bs-toggle="modal" data-bs-target="#modalEdit"
                            data-bs-id="' . $row->id . '"
                            data-bs-name="' . $row->name . '"
                            data-bs-dashboard_url="' . $row->dashboard_url . '"
                            class="dropdown-item">Ubah</a></li>
                            <li> <a href="#" data-bs-toggle="modal" data-bs-target="#modalDelete" data-bs-id="' . $row->id . '" class="delete dropdown-item">Hapus</a></li>
                          </ul>
                        </div> ';

          return $actionBtn;

        })
        ->make(true);
    }

    return view('default.roles.index', compact('config', 'page_breadcrumbs'));
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required|unique:roles,name',
      'dashboard_url' => 'required|string',
    ]);

    if ($validator->passes()) {
      Role::create([
        'name' => ucwords($request['name']),
        'dashboard_url' => $request['dashboard_url'],
      ]);

      $response = $this->responseStore(true);
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }
    return $response;
  }

  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required|unique:roles,name,' . $id,
      'dashboard_url' => 'required|string',
    ]);

    if ($validator->passes()) {
      $data = Role::find($id);
      if ($data->slug != 'super-admin') {
        $data->update([
          'name' => ucwords($request['name']),
          'dashboard_url' => $request['dashboard_url'],
        ]);
      }
      $response = $this->responseUpdate(true);
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }
    return $response;
  }

  public function destroy(Role $role)
  {
    $response = response()->json([
      'status' => 'error',
      'message' => 'Data gagal dihapus'
    ]);
    if ($role->slug != 'super-admin') {
      $role->delete();
      $response = $this->responseDelete(true);
    }
    return $response;
  }

  public function select2(Request $request)
  {
    $page = $request->page;
    $resultCount = 10;
    $offset = ($page - 1) * $resultCount;
    $data = Role::where('name', 'LIKE', '%' . $request->q . '%')
      ->orderBy('name')
      ->when($request['idArray'], function ($query, $role) use ($request) {
        return $query->whereIn('id', $request['idArray']);
      })
      ->skip($offset)
      ->take($resultCount)
      ->selectRaw('id, name as text')
      ->get();

    $count = Role::where('name', 'LIKE', '%' . $request->q . '%')
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
