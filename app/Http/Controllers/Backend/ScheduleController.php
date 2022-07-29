<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Schedule;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ScheduleController extends Controller
{
  use ResponseStatus;

  public function __construct()
  {
    $this->middleware('can:backend-schedule-list', ['only' => ['index', 'show']]);
    $this->middleware('can:backend-schedule-create', ['only' => ['create', 'store']]);
    $this->middleware('can:backend-schedule-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:backend-schedule-delete', ['only' => ['destroy']]);
  }

  public function index(Request $request)
  {
    $config['page_title'] = "Agenda";
    $page_breadcrumbs = [
      ['url' => '#', 'title' => "Daftar Agenda"],
    ];

    if ($request->ajax()) {
      $data = Schedule::query();
      return DataTables::of($data)
        ->addColumn('action', function ($row) {
          $actionBtn = '<div class="dropdown">
                          <button type="button" class="btn btn-primary" data-bs-toggle="dropdown" aria-expanded="false">
                            Aksi <i class="fa-regular fa-down"></i>
                          </button>
                          <ul class="dropdown-menu">
                            <li><a href="#" data-bs-toggle="modal" data-bs-target="#modalEdit"
                            data-bs-id="' . $row->id . '"
                            data-bs-title="' . $row->title . '"
                            data-bs-description="' . $row->description . '"
                            data-bs-date_start="' . $row->date_start . '"
                            data-bs-date_end="' . $row->date_end . '"
                            class="dropdown-item">Ubah</a></li>
                            <li><a href="#" data-bs-toggle="modal" data-bs-target="#modalDelete" data-bs-id="' . $row->id . '" class="dropdown-item">Hapus</a></li>
                          </ul>
                        </div> ';
          return $actionBtn;
        })
        ->make(true);
    }

    return view('backend.schedules.index', compact('config', 'page_breadcrumbs'));
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'title' => 'required|string',
      'description' => 'nullable|string',
      'date_start' => 'required',
      'date_end' => 'required',
    ]);

    if ($validator->passes()) {
      Schedule::create($request->all());

      $response = $this->responseStore(true);
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }
    return $response;
  }

  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'title' => 'required|string',
      'description' => 'nullable|string',
      'date_start' => 'required',
      'date_end' => 'required',
    ]);

    if ($validator->passes()) {
      $data = Schedule::find($id);
      $data->update($request->all());
      $response = $this->responseUpdate(true);
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }
    return $response;
  }

  public function destroy($id)
  {
    $signature = Schedule::findOrFail($id);
    $signature->delete();
    return $this->responseDelete(true);
  }
}
