<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\FileUpload;
use App\Http\Controllers\Controller;
use App\Models\PartaiMember;
use App\Models\Post;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PartaiMemberController extends Controller
{
  use ResponseStatus;

  public function __construct()
  {
    $this->middleware('can:backend-partai-member-list', ['only' => ['index', 'show']]);
    $this->middleware('can:backend-partai-member-create', ['only' => ['create', 'store']]);
    $this->middleware('can:backend-partai-member-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:backend-partai-member-delete', ['only' => ['destroy']]);
  }

  public function index(Request $request)
  {
    $config['page_title'] = "Anggota Partai";
    $page_breadcrumbs = [
      ['url' => route('backend.partai-member.index'), 'title' => "Anggota Partai"],
    ];

    if ($request->ajax()) {
      $data = PartaiMember::selectRaw('
        `partai_members`.*,
        `election_regions`.`name` AS `election_region_name`,
        `komisis`.`name` AS `partai_member_name`
      ')
        ->leftJoin('komisis', 'komisis.id', '=', 'partai_members.komisi_id')
        ->leftJoin('election_regions', 'election_regions.id', '=', 'partai_members.election_region_id');

      return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function ($row) {
          return
            '<div class="dropdown">
            <button type="button" class="btn btn-primary" data-bs-toggle="dropdown" aria-expanded="false">
              Aksi <i class="fa-regular fa-arrow-down"></i>
            </button>
            <ul class="dropdown-menu">
              <li><a href="' . route('backend.partai-member.educations.index', $row['id']) . '" class="dropdown-item">Detail</a></li>
              <li><a href="' . route('backend.partai-member.edit', $row['id']) . '" class="dropdown-item">Ubah</a></li>
              <li><a href="#" data-bs-toggle="modal" data-bs-target="#modalDelete" data-bs-id="' . $row->id . '" class="delete dropdown-item">Hapus</a></li>
            </ul>
          </div>';
        })
        ->make(true);
    }

    return view('backend.partai-members.index', compact('page_breadcrumbs', 'config'));
  }

  public function create()
  {
    $config['page_title'] = "Tambah Anggota Partai";
    $page_breadcrumbs = [
      ['url' => route('backend.partai-member.index'), 'title' => "Anggota Partai"],
      ['url' => '#', 'title' => "Tambah Anggota Partai"],
    ];

    return view('backend.partai-members.create', compact('page_breadcrumbs', 'config'));
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required|string',
      'image' => 'image|mimes:jpg,png,jpeg|max:5000',
    ]);

    if ($validator->passes()) {
      DB::beginTransaction();
      try {
        $dimensions = [['720', '1280 ', 'thumbnail']];
        $image = isset($request['image']) && !empty($request['image']) ? FileUpload::uploadImage('image', $dimensions) : NULL;
        $dataRequest = $request->all();
        $dataRequest['image'] = $image;

        $data = PartaiMember::create($dataRequest);

        DB::commit();
        $response = response()->json($this->responseStore(true, route('backend.partai-member.index')));
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
    $config['page_title'] = "Edit Anggota Partai";
    $page_breadcrumbs = [
      ['url' => route('backend.partai-member.index'), 'title' => "Anggota Partai"],
      ['url' => '#', 'title' => "Edit Anggota Partai"],
    ];

    $data = PartaiMember::with(['komisi', 'election_region'])->findOrFail($id);

    return view('backend.partai-members.edit', compact('page_breadcrumbs', 'config', 'data'));
  }

  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required|string',
      'image' => 'image|mimes:jpg,png,jpeg|max:5000',
    ]);

    if ($validator->passes()) {
      $dimensions = [['1280', '720', 'thumbnail']];
      DB::beginTransaction();
      try {
        $data = PartaiMember::find($id);
        $image = $data['image'];
        if (isset($request['image']) && !empty($request['image'])) {
          $image = Fileupload::uploadImage('image', $dimensions, 'storage', $data['image']);
        }
        $dataRequest = $request->all();
        $dataRequest['image'] = $image;

        $data->update($dataRequest);

        DB::commit();
        $response = response()->json($this->responseUpdate(true, route('backend.partai-member.index')));
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

  public function show($id){
    $config['page_title'] = "Detail Anggota Partai";
    $page_breadcrumbs = [
      ['url' => route('backend.partai-member.index'), 'title' => "Anggota Partai"],
      ['url' => '#', 'title' => "Detail Anggota Partai"],
    ];

    return view('backend.partai-members.show', compact('page_breadcrumbs', 'config', 'id'));

  }

  public function destroy($id)
  {
    $data = PartaiMember::find($id);
    $response = response()->json($this->responseDelete(true));
    if ($data->delete()) {
      Storage::disk('public')->delete(["images/original/$data->image", "images/thumbnail/$data->image"]);
      $response = response()->json($this->responseDelete(true));
    }
    return $response;
  }
}
