<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Komisi;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class KomisiController extends Controller
{
  use ResponseStatus;

  public function __construct()
  {
    $this->middleware('can:backend-komisi-list', ['only' => ['index', 'show']]);
    $this->middleware('can:backend-komisi-create', ['only' => ['create', 'store']]);
    $this->middleware('can:backend-komisi-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:backend-komisi-delete', ['only' => ['destroy']]);
  }

  public function index(Request $request)
  {
    $config['page_title'] = "Komisi";
    $page_breadcrumbs = [
      ['url' => '#', 'title' => "Daftar Komisi"],
    ];

    if ($request->ajax()) {
      $data = Komisi::query();
      return DataTables::of($data)
        ->addColumn('action', function ($row) {
          $actionBtn = '<div class="dropdown">
                          <button type="button" class="btn btn-primary" data-bs-toggle="dropdown" aria-expanded="false">
                            Aksi <i class="fa-regular fa-down"></i>
                          </button>
                          <ul class="dropdown-menu">
                            <li><a href="#" data-bs-toggle="modal" data-bs-target="#modalEdit"
                            data-bs-id="' . $row->id . '"
                            data-bs-name="' . $row->name . '"
                            class="dropdown-item">Ubah</a></li>
                            <li><a href="#" data-bs-toggle="modal" data-bs-target="#modalDelete" data-bs-id="' . $row->id . '" class="dropdown-item">Hapus</a></li>
                          </ul>
                        </div> ';
          return $actionBtn;
        })
        ->make(true);
    }

    return view('backend.komisi.index', compact('config', 'page_breadcrumbs'));
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required|string',
    ]);

    if ($validator->passes()) {
      Komisi::create($request->all());

      $response = $this->responseStore(true);
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }
    return $response;
  }

  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required|string',
    ]);

    if ($validator->passes()) {
      $data = Komisi::find($id);
      $data->update($request->all());
      $response = $this->responseUpdate(true);
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }
    return $response;
  }

  public function destroy($id)
  {
    $signature = Komisi::findOrFail($id);
    $signature->delete();
    return $this->responseDelete(true);
  }

  public function select2(Request $request)
  {
    $page = $request->page;
    $resultCount = 10;
    $offset = ($page - 1) * $resultCount;
    $data = Komisi::where('name', 'LIKE', '%' . $request->q . '%')
      ->orderBy('name')
      ->skip($offset)
      ->take($resultCount)
      ->selectRaw('id, name as text')
      ->get();

    $count = Komisi::where('name', 'LIKE', '%' . $request->q . '%')
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
