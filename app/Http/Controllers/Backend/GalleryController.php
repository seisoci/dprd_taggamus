<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\FileUpload;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class GalleryController extends Controller
{
  use ResponseStatus;

  public function __construct()
  {
    $this->middleware('can:backend-gallery-list', ['only' => ['index', 'show']]);
    $this->middleware('can:backend-gallery-create', ['only' => ['create', 'store']]);
    $this->middleware('can:backend-gallery-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:backend-gallery-delete', ['only' => ['destroy']]);
  }

  public function index(Request $request)
  {
    $config['page_title'] = "Gallery";
    $page_breadcrumbs = [
      ['url' => route('backend.galleries.index'), 'title' => "Gallery"],
    ];

    if ($request->ajax()) {
      $data = Post::selectRaw('
        `posts`.`id`,
        `posts`.`image`,
        `posts`.`title`,
        `posts`.`published`,
        `posts`.`publish_at`,
        `users`.`name` AS `profile_name`
      ')
        ->where('posts.type', 'galleries')
        ->leftJoin('users', 'users.id', '=', 'posts.user_id');

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
              <li><a href="' . route('backend.galleries.photos.index', $row['id']) . '" class="dropdown-item">Masukan Isi Foto</a></li>
              <li><a href="' . route('backend.galleries.edit', $row['id']) . '" class="dropdown-item">Ubah</a></li>
              <li><a href="#" data-bs-toggle="modal" data-bs-target="#modalDelete" data-bs-id="' . $row->id . '" class="delete dropdown-item">Hapus</a></li>
            </ul>
          </div>';
        })
        ->make(true);
    }

    return view('backend.galleries.index', compact('page_breadcrumbs', 'config'));
  }

  public function create()
  {
    $config['page_title'] = "Tambah Gallery";
    $page_breadcrumbs = [
      ['url' => route('backend.videos.index'), 'title' => "Gallery"],
      ['url' => '#', 'title' => "Tambah Gallery"],
    ];
    return view('backend.galleries.create', compact('page_breadcrumbs', 'config'));
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'title' => 'required|string',
      'image' => 'image|mimes:jpg,png,jpeg|max:5000',
      'status' => 'in:0,1',
    ]);

    if ($validator->passes()) {
      DB::beginTransaction();
      try {
        $dimensions = [['1024', '768', 'thumbnail']];

        $image = isset($request['image']) && !empty($request['image']) ? FileUpload::uploadImage('image', $dimensions) : NULL;
        $dataRequest = $request->all();
        $dataRequest['image'] = $image;

        Post::create($dataRequest);
        DB::commit();
        $response = response()->json($this->responseStore(true, route('backend.galleries.index')));
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
    $config['page_title'] = "Edit Video";
    $page_breadcrumbs = [
      ['url' => route('backend.videos.index'), 'title' => "Video"],
      ['url' => '#', 'title' => "Edit Video"],
    ];

    $data = Post::findOrFail($id);

    return view('backend.galleries.edit', compact('page_breadcrumbs', 'config', 'data'));
  }

  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'title' => 'required|string',
      'image' => 'image|mimes:jpg,png,jpeg|max:5000',
      'status' => 'in:0,1',
    ]);

    if ($validator->passes()) {
      DB::beginTransaction();
      try {
        $dimensions = [['1280', '1280', 'thumbnail']];
        $post = Post::find($id);
        $image = $post['image'];
        if (isset($request['image']) && !empty($request['image'])) {
          $image = Fileupload::uploadImage('image', $dimensions, 'storage', $post['image']);
        }
        $data = $request->all();
        $data['image'] = $image;

        $post->update($data);

        DB::commit();
        $response = response()->json($this->responseUpdate(true, route('backend.galleries.index')));
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

  public function destroy($id)
  {
    $data = Post::find($id);
    $response = response()->json($this->responseDelete(true));
    if ($data->delete()) {
      Storage::disk('public')->delete(["images/original/$data->image", "images/thumbnail/$data->image"]);
      $response = response()->json($this->responseDelete(true));
    }
    return $response;
  }
}
