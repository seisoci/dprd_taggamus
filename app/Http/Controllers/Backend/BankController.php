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

class BankController extends Controller
{
  use ResponseStatus;

  public function __construct()
  {
    $this->middleware('can:backend-bank-list', ['only' => ['index', 'show']]);
    $this->middleware('can:backend-bank-create', ['only' => ['create', 'store']]);
    $this->middleware('can:backend-bank-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:backend-bank-delete', ['only' => ['destroy']]);
  }

  public function index(Request $request)
  {
    $config['page_title'] = "Bank";
    $page_breadcrumbs = [
      ['url' => '#', 'title' => "Daftar Bank"],
    ];

    if ($request->ajax()) {
      $data = Bank::query();
      return DataTables::of($data)
        ->addColumn('action', function ($row) {
          $actionBtn = '<div class="dropdown">
                          <button type="button" class="btn btn-primary" data-bs-toggle="dropdown" aria-expanded="false">
                            Aksi <i class="fa-regular fa-down"></i>
                          </button>
                          <ul class="dropdown-menu">
                            <li><a href="#" data-bs-toggle="modal" data-bs-target="#modalEdit"
                            data-bs-id="' . $row->kode_bank . '"
                            data-bs-bank_name="' . $row->bank_name . '"
                            class="dropdown-item">Ubah</a></li>
                            <li><a href="#" data-bs-toggle="modal" data-bs-target="#modalDelete" data-bs-id="' . $row->kode_bank . '" class="dropdown-item">Hapus</a></li>
                          </ul>
                        </div> ';
          return $actionBtn;
        })
        ->make(true);
    }

    return view('backend.masterdata.bank.index', compact('config', 'page_breadcrumbs'));
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'kode_bank' => 'required|unique:banks,kode_bank',
      'bank_name' => 'required|string',
    ]);

    if ($validator->passes()) {
      Bank::create($request->all());

      $response = $this->responseStore(true);
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }
    return $response;
  }

  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'bank_name' => 'required|string',
    ]);

    if ($validator->passes()) {
      $data = Bank::find($id);
      $data->update($request->all());
      $response = $this->responseUpdate(true);
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }
    return $response;
  }

  public function destroy($id)
  {
    $signature = Bank::findOrFail($id);
    $signature->delete();
    return $this->responseDelete(true);
  }

  public function select2(Request $request)
  {
    $page = $request->page;
    $resultCount = 10;
    $offset = ($page - 1) * $resultCount;
    $data = Bank::where('bank_name', 'LIKE', '%' . $request->q . '%')
      ->orderBy('bank_name')
      ->skip($offset)
      ->take($resultCount)
      ->selectRaw('kode_bank AS id, bank_name as text')
      ->get();

    $count = Bank::where('bank_name', 'LIKE', '%' . $request->q . '%')
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
