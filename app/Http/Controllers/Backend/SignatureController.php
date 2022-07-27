<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Role;
use App\Models\Signature;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SignatureController extends Controller
{
  use ResponseStatus;

  public function __construct()
  {
    $this->middleware('can:backend-signature-list', ['only' => ['index', 'show']]);
    $this->middleware('can:backend-signature-create', ['only' => ['create', 'store']]);
    $this->middleware('can:backend-signature-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:backend-signature-delete', ['only' => ['destroy']]);
  }

  public function index(Request $request)
  {
    $config['page_title'] = "Tanda Tangan";
    $page_breadcrumbs = [
      ['url' => '#', 'title' => "Daftar Tanda Tangan"],
    ];

    if ($request->ajax()) {
      $data = Signature::query();
      return DataTables::of($data)
        ->addColumn('action', function ($row) {
          $actionBtn = '<div class="dropdown">
                          <button type="button" class="btn btn-primary" data-bs-toggle="dropdown" aria-expanded="false">
                            Aksi <i class="fa-regular fa-down"></i>
                          </button>
                          <ul class="dropdown-menu">
                            <li> <a href="#" data-bs-toggle="modal" data-bs-target="#modalEdit"
                            data-bs-id="' . $row->id . '"
                            data-bs-full_name="' . $row->full_name . '"
                            data-bs-position="' . $row->position . '"
                            data-bs-signature_title="' . $row->signature_title . '"
                            class="dropdown-item">Ubah</a></li>
                            <li> <a href="#" data-bs-toggle="modal" data-bs-target="#modalDelete" data-bs-id="' . $row->id . '" class="delete dropdown-item">Hapus</a></li>
                          </ul>
                        </div> ';
          return $actionBtn;
        })
        ->make(true);
    }

    return view('backend.masterdata.signature.index', compact('config', 'page_breadcrumbs'));
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'full_name' => 'required|string',
      'position' => 'nullable|string',
      'signature_title' => 'nullable|string',
    ]);

    if ($validator->passes()) {
      Signature::create($request->all());

      $response = $this->responseStore(true);
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }
    return $response;
  }

  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'full_name' => 'required|string',
      'position' => 'nullable|string',
      'signature_title' => 'nullable|string',
    ]);

    if ($validator->passes()) {
      $data = Signature::findOrFail($id);
      $data->update($request->all());

      $response = $this->responseUpdate(true);
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }
    return $response;
  }

  public function destroy($id)
  {
    $signature = Signature::findOrFail($id);
    $signature->delete();
    return $this->responseDelete(true);
  }

  public function select2(Request $request)
  {
    $page = $request->page;
    $resultCount = 10;
    $offset = ($page - 1) * $resultCount;
    $data = Signature::where('full_name', 'LIKE', '%' . $request->q . '%')
      ->orderBy('full_name')
      ->skip($offset)
      ->take($resultCount)
      ->selectRaw('id, full_name as text')
      ->get();

    $count = Signature::where('full_name', 'LIKE', '%' . $request->q . '%')
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
