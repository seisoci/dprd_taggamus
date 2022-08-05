<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Polling;
use App\Models\PollingOption;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PollingController extends Controller
{
  use ResponseStatus;

  public function __construct()
  {
    $this->middleware('can:backend-polling-list', ['only' => ['index', 'show']]);
    $this->middleware('can:backend-polling-create', ['only' => ['create', 'store']]);
    $this->middleware('can:backend-polling-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:backend-polling-delete', ['only' => ['destroy']]);
  }

  public function index(Request $request)
  {
    $config['page_title'] = "Polling";
    $page_breadcrumbs = [
      ['url' => route('backend.pollings.index'), 'title' => "Polling"],
    ];

    if ($request->ajax()) {
      $data = Polling::query();

      if ($request->filled('published')) {
        $data->where('posts.published', request('published'));
      }

      return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function ($row) {
          return
            '<div class="dropdown">
            <button type="button" class="btn btn-primary" data-bs-toggle="dropdown" aria-expanded="false">
              Aksi <i class="fa-regular fa-arrow-down"></i>
            </button>
            <ul class="dropdown-menu">
              <li><a href="' . route('backend.pollings.show', $row['id']) . '" class="dropdown-item">Detail Polling</a></li>
              <li><a href="' . route('backend.pollings.edit', $row['id']) . '" class="dropdown-item">Ubah</a></li>
              <li><a href="#" data-bs-toggle="modal" data-bs-target="#modalDelete" data-bs-id="' . $row->id . '" class="delete dropdown-item">Hapus</a></li>
            </ul>
          </div>';
        })
        ->make(true);
    }

    return view('backend.pollings.index', compact('page_breadcrumbs', 'config'));
  }

  public function create()
  {
    $config['page_title'] = "Tambah Polling";
    $page_breadcrumbs = [
      ['url' => route('backend.pages.index'), 'title' => "Polling"],
      ['url' => '#', 'title' => "Tambah Polling"],
    ];

    return view('backend.pollings.create', compact('page_breadcrumbs', 'config'));
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'title' => 'required|string',
      'description' => 'required|string',
      'status' => 'in:0,1',
    ]);

    if ($validator->passes()) {
      DB::beginTransaction();
      try {
        $polling = Polling::create($request->all());
        foreach ($request['options'] as $key => $item):
          PollingOption::create([
            'polling_id' => $polling->id,
            'sort' => ++$key,
            'name' => $item,
          ]);
        endforeach;

        DB::commit();
        $response = response()->json($this->responseStore(true, route('backend.pollings.index')));
      } catch (\Throwable $throw) {
        Log::error($throw);
        DB::rollBack();
        $response = response()->json($this->responseStore(false));
      }
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }
    return $response;
  }

  public function edit($id)
  {
    $config['page_title'] = "Edit Polling";
    $page_breadcrumbs = [
      ['url' => route('backend.pollings.index'), 'title' => "Polling"],
      ['url' => '#', 'title' => "Edit Polling"],
    ];

    $data = Polling::with('options')->findOrFail($id);

    return view('backend.pollings.edit', compact('page_breadcrumbs', 'config', 'data'));
  }

  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'title' => 'required|string',
      'description' => 'required|string',
      'status' => 'in:0,1',
    ]);

    if ($validator->passes()) {
      DB::beginTransaction();
      try {
        $data = Polling::with('options')->findOrFail($id);
        $data->update($request->all());
        foreach ($data['options'] ?? array() as $item):
          PollingOption::find($item['id'])->delete();
        endforeach;

        $i = 0;
        foreach ($request['old_options'] ?? array() as $key => $item):
          PollingOption::withTrashed()->find($key)->restore();
          PollingOption::find($key)->update([
            'name' => $item,
            'sort' => ++$i,
          ]);
        endforeach;

        foreach ($request['options'] ?? array() as $key => $item):
          PollingOption::create([
            'polling_id' => $data['id'],
            'sort' => ++$i,
            'name' => $item,
          ]);
        endforeach;

        DB::commit();
        $response = response()->json($this->responseUpdate(true, route('backend.pollings.index')));
      } catch (\Throwable $throw) {
        Log::error($throw);
        DB::rollback();
        $response = response()->json($this->responseUpdate(false));
      }
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }

    return $response;
  }

  public function show($id, Request $request)
  {
    $config['page_title'] = "Polling Detail";
    $page_breadcrumbs = [
      ['url' => route('backend.pollings.index'), 'title' => "Polling"],
      ['url' => '#', 'title' => "Polling Detail"],
    ];

    if ($request->ajax()) {
      $data = PollingOption::withCount('answers')
        ->where('polling_id', $id)
        ->get();

      return DataTables::of($data)
        ->addIndexColumn()
        ->make(true);
    }

    return view('backend.pollings.show', compact('page_breadcrumbs', 'config', 'id'));
  }

  public function destroy($id)
  {
    $data = Polling::find($id);
    $response = response()->json($this->responseDelete(true));
    if ($data->delete()) {
      $response = response()->json($this->responseDelete(true));
    }
    return $response;
  }
}
