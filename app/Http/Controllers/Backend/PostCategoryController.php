<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\PostCategory;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PostCategoryController extends Controller
{
  use ResponseStatus;

  public function __construct()
  {
    $this->middleware('can:backend-post-category-list', ['only' => ['index', 'show']]);
    $this->middleware('can:backend-post-category-create', ['only' => ['create', 'store']]);
    $this->middleware('can:backend-post-category-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:backend-post-category-delete', ['only' => ['destroy']]);
  }

  public function index(Request $request)
  {
    $config['page_title'] = "Kategori Post";
    $page_breadcrumbs = [
      ['url' => route('backend.post-categories.index'), 'title' => "Kategori Post"],
    ];

    if ($request->ajax()) {
      $data = PostCategory::query();

      return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function ($row) {
          return
            '<div class="dropdown">
            <button type="button" class="btn btn-primary" data-bs-toggle="dropdown" aria-expanded="false">
              Aksi <i class="fa-regular fa-arrow-down"></i>
            </button>
            <ul class="dropdown-menu">
              <li><a href="#" data-bs-toggle="modal" data-bs-target="#modalEdit" data-bs-id="' . $row->id . '" data-bs-name="' . $row->name . '" class="dropdown-item">Ubah</a></li>
              <li><a href="#" data-bs-toggle="modal" data-bs-target="#modalDelete" data-bs-id="' . $row->id . '" class="delete dropdown-item">Hapus</a></li>
            </ul>
          </div>';
        })
        ->make(true);
    }

    return view('backend.post-categories.index', compact('page_breadcrumbs', 'config'));
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required|string',
    ]);

    if ($validator->passes()) {
      DB::beginTransaction();
      try {
        $data = $request->all();
        PostCategory::create($data);
        DB::commit();
        $response = response()->json($this->responseStore(true, route('backend.post-categories.index')));
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

  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required|string',
    ]);

    if ($validator->passes()) {
      DB::beginTransaction();
      try {
        $data = PostCategory::findOrFail($id);
        $dataRequest = $request->all();
        $data->update($dataRequest);

        DB::commit();
        $response = response()->json($this->responseUpdate(true, route('backend.post-categories.index')));
      } catch (\Throwable $throw) {
        dd($throw);
        Log::error($throw);
        DB::rollback();
        $response = response()->json($this->responseUpdate(false));
      }
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }

    return $response;
  }

  public function destroy($id)
  {
    $data = PostCategory::find($id);
    if ($data->delete()) {
      $response = response()->json($this->responseDelete(true));
    }
    return $response;
  }

  public function select2(Request $request)
  {
    $page = $request->page;
    $resultCount = 10;
    $offset = ($page - 1) * $resultCount;
    $data = PostCategory::where('name', 'LIKE', '%' . $request->q . '%')
      ->when(!empty(request('type')), function ($q) {
        return $q->where('type', request('type'));
      })
      ->orderBy('name')
      ->skip($offset)
      ->take($resultCount)
      ->selectRaw('id, name as text')
      ->get();

    $count = PostCategory::where('name', 'LIKE', '%' . $request->q . '%')
      ->when(!empty(request('type')), function ($q) {
        return $q->where('type', request('type'));
      })
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
