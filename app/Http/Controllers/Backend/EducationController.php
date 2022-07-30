<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Education;
use App\Models\Komisi;
use App\Models\PartaiMember;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class EducationController extends Controller
{
  use ResponseStatus;

  public function __construct()
  {
    $this->middleware('can:backend-partai-member-list', ['only' => ['index', 'show']]);
    $this->middleware('can:backend-partai-member-create', ['only' => ['create', 'store']]);
    $this->middleware('can:backend-partai-member-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:backend-partai-member-delete', ['only' => ['destroy']]);
  }

  public function index(PartaiMember $partaiMember, Request $request)
  {
    $config['page_title'] = "Detail Anggota Partai";
    $page_breadcrumbs = [
      ['url' => route('backend.partai-member.index'), 'title' => "Anggota Partai"],
      ['url' => route('backend.partai-member.show', $partaiMember), 'title' => "Detail Anggota Partai"],
      ['url' => '#', 'title' => "Pendidikan"],
    ];

    if ($request->ajax()) {
      $data = Education::where('partai_member_id', $partaiMember['id']);
      return DataTables::of($data)
        ->addColumn('action', function ($row) use($partaiMember) {
          $actionBtn = '<div class="dropdown">
                          <button type="button" class="btn btn-primary" data-bs-toggle="dropdown" aria-expanded="false">
                            Aksi <i class="fa-regular fa-down"></i>
                          </button>
                          <ul class="dropdown-menu">
                            <li><a href="'.route('backend.partai-member.educations.edit', ['partai_member' => $partaiMember['id'], $row['id']]).'" class="dropdown-item">Ubah</a></li>
                            <li><a href="#" data-bs-toggle="modal" data-bs-target="#modalDelete" data-bs-id="' . $row->id . '" class="dropdown-item">Hapus</a></li>
                          </ul>
                        </div> ';
          return $actionBtn;
        })
        ->make(true);
    }

    return view('backend.educations.index', compact('page_breadcrumbs', 'config', 'partaiMember'));
  }

  public function create(PartaiMember $partaiMember)
  {
    $config['page_title'] = "Tambah Anggota Partai";
    $page_breadcrumbs = [
      ['url' => route('backend.partai-member.index'), 'title' => "Anggota Partai"],
      ['url' => route('backend.partai-member.show', $partaiMember), 'title' => "Detail Anggota Partai"],
      ['url' => '#', 'title' => "Tambah Pendidikan"],
    ];

    return view('backend.educations.create', compact('page_breadcrumbs', 'config', 'partaiMember'));
  }

  public function store(PartaiMember $partaiMember, Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name_institution' => 'required|string',
    ]);

    if ($validator->passes()) {
      $request->request->add(['partai_member_id' => $partaiMember['id']]);
      Education::create($request->all());

      $response = $this->responseStore(true, route('backend.partai-member.show', $partaiMember['id']));
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }
    return $response;
  }

  public function edit(PartaiMember $partaiMember, $id)
  {
    $config['page_title'] = "Edit Anggota Partai";
    $page_breadcrumbs = [
      ['url' => route('backend.partai-member.index'), 'title' => "Anggota Partai"],
      ['url' => route('backend.partai-member.show', $partaiMember), 'title' => "Detail Anggota Partai"],
      ['url' => '#', 'title' => "Edit Pendidikan"],
    ];

    $data = Education::find($id);

    return view('backend.educations.edit', compact('page_breadcrumbs', 'config', 'partaiMember', 'data'));
  }

  public function update(PartaiMember $partaiMember, Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'name_institution' => 'required|string',
    ]);

    if ($validator->passes()) {
      $data = Education::find($id);
      $request->request->add(['partai_member_id' => $partaiMember['id']]);
      $data->update($request->all());
      $response = $this->responseUpdate(true, route('backend.partai-member.show', $partaiMember['id']));
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }
    return $response;
  }

  public function destroy(PartaiMember $partaiMember, $id)
  {
    $data = Education::findOrFail($id);
    $data->delete();
    return $this->responseDelete(true);
  }
}
