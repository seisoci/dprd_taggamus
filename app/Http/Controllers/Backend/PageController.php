<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\FileUpload;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PageController extends Controller
{
  use ResponseStatus;

  public function __construct()
  {
    $this->middleware('can:backend-page-list', ['only' => ['index', 'show']]);
    $this->middleware('can:backend-page-create', ['only' => ['create', 'store']]);
    $this->middleware('can:backend-page-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:backend-page-delete', ['only' => ['destroy']]);
  }

  public function index(Request $request)
  {
    $config['page_title'] = "Pages";
    $page_breadcrumbs = [
      ['url' => route('backend.pages.index'), 'title' => "Pages"],
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
        ->where('type', 'pages')
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
              <li><a href="' . route('backend.pages.edit', $row['id']) . '" class="dropdown-item">Ubah</a></li>
              <li><a href="#" data-bs-toggle="modal" data-bs-target="#modalDelete" data-bs-id="' . $row->id . '" class="delete dropdown-item">Hapus</a></li>
            </ul>
          </div>';
        })
        ->make(true);
    }

    return view('backend.pages.index', compact('page_breadcrumbs', 'config'));
  }

  public function create()
  {
    $config['page_title'] = "Tambah Pages";
    $page_breadcrumbs = [
      ['url' => route('backend.pages.index'), 'title' => "Pages"],
      ['url' => '#', 'title' => "Tambah Pages"],
    ];

    return view('backend.pages.create', compact('page_breadcrumbs', 'config'));
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'title' => 'required|string',
      'post_categories' => 'nullable|array',
      'post_categories.*' => 'nullable|integer',
      'image' => 'image|mimes:jpg,png,jpeg|max:5000',
      'status' => 'in:0,1',
    ]);

    if ($validator->passes()) {
      DB::beginTransaction();
      try {
        $dimensions = [['1280', '720', 'thumbnail']];
        $image = isset($request['image']) && !empty($request['image']) ? FileUpload::uploadImage('image', $dimensions) : NULL;
        $data = $request->all();
        $data['image'] = $image;

        $post = Post::create($data);

        DB::commit();
        $response = response()->json($this->responseStore(true, route('backend.pages.index')));
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
    $config['page_title'] = "Edit Pages";
    $page_breadcrumbs = [
      ['url' => route('backend.pages.index'), 'title' => "Pages"],
      ['url' => '#', 'title' => "Edit Pages"],
    ];

    $data = Post::findOrFail($id);

    return view('backend.pages.edit', compact('page_breadcrumbs', 'config', 'data'));
  }

  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'title' => 'required|string',
      'post_categories' => 'nullable|array',
      'post_categories.*' => 'nullable|integer',
      'image' => 'image|mimes:jpg,png,jpeg|max:2048',
      'status' => 'in:0,1',
    ]);

    if ($validator->passes()) {
      $dimensions = [['1280', '720', 'thumbnail']];
      DB::beginTransaction();
      try {
        $post = Post::with('post_categories')->find($id);
        $image = $post['image'];
        if (isset($request['image']) && !empty($request['image'])) {
          $image = Fileupload::uploadImage('image', $dimensions, 'storage', $post['image']);
        }
        $data = $request->all();
        $data['image'] = $image;

        $post->update($data);

        DB::commit();
        $response = response()->json($this->responseUpdate(true, route('backend.pages.index')));
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
