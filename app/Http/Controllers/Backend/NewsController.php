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

class NewsController extends Controller
{
  use ResponseStatus;

  public function __construct()
  {
    $this->middleware('can:backend-news-list', ['only' => ['index', 'show']]);
    $this->middleware('can:backend-news-create', ['only' => ['create', 'store']]);
    $this->middleware('can:backend-news-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:backend-news-delete', ['only' => ['destroy']]);
  }

  public function index(Request $request)
  {
    $config['page_title'] = "Berita";
    $page_breadcrumbs = [
      ['url' => route('backend.news.index'), 'title' => "Berita"],
    ];

    if ($request->ajax()) {
      $data = Post::selectRaw('
        `posts`.`id`,
        `posts`.`image`,
        `posts`.`title`,
        `posts`.`published`,
        `posts`.`publish_at`,
        `users`.`name` AS `profile_name`,
         GROUP_CONCAT(`post_categories`.`name`) AS `post_category_name`
      ')
        ->where('posts.type', 'posts')
        ->leftJoin('users','users.id', '=', 'posts.user_id')
        ->leftJoin('post_post_category', function ($join) {
          $join->on('post_post_category.post_id', '=', 'posts.id');
          $join->leftJoin('post_categories', 'post_categories.id', '=', 'post_post_category.post_category_id');
        })
        ->groupBy('posts.id');

      if ($request->filled('published')) {
        $data->where('posts.published', request('published'));
      }

      if ($request->filled('post_category_id')) {
        $data->where('post_post_category.post_category_id', request('post_category_id'));
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
              <li><a href="' . route('backend.news.edit', $row['id']) . '" class="dropdown-item">Ubah</a></li>
              <li><a href="#" data-bs-toggle="modal" data-bs-target="#modalDelete" data-bs-id="' . $row->id . '" class="delete dropdown-item">Hapus</a></li>
            </ul>
          </div>';
        })
        ->make(true);
    }

    return view('backend.news.index', compact('page_breadcrumbs', 'config'));
  }

  public function create()
  {
    $config['page_title'] = "Tambah Berita";
    $page_breadcrumbs = [
      ['url' => route('backend.news.index'), 'title' => "Berita"],
      ['url' => '#', 'title' => "Tambah Berita"],
    ];
    return view('backend.news.create', compact('page_breadcrumbs', 'config'));
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
        $dimensions = [['1280', '1280', 'thumbnail']];
        $image = isset($request['image']) && !empty($request['image']) ? FileUpload::uploadImage('image', $dimensions) : NULL;
        $data = $request->all();
        $data['image'] = $image;

        $post = Post::create($data);
        $postCategories = PostCategory::find($request['post_categories']);
        $post->post_categories()->attach($postCategories);

        DB::commit();
        $response = response()->json($this->responseStore(true, route('backend.news.index')));
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
    $config['page_title'] = "Edit Berita";
    $page_breadcrumbs = [
      ['url' => route('backend.news.index'), 'title' => "Berita"],
      ['url' => '#', 'title' => "Edit Berita"],
    ];

    $data = Post::with(['post_categories'])->findOrFail($id);

    return view('backend.news.edit', compact('page_breadcrumbs', 'config', 'data'));
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
      $dimensions = [['1280', '1280', 'thumbnail']];
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
        $postCategories = PostCategory::find($request['post_categories']);
        $post->post_categories()->sync($postCategories);

        DB::commit();
        $response = response()->json($this->responseUpdate(true, route('backend.news.index')));
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
    $data->taggables()->delete();
    $response = response()->json($this->responseDelete(true));
    if ($data->delete()) {
      Storage::disk('public')->delete(["images/original/$data->image", "images/thumbnail/$data->image"]);
      $response = response()->json($this->responseDelete(true));
    }
    return $response;
  }

  public function uploadimagecke(Request $request){
    if ($request->hasFile('upload')) {
      $originName = $request->file('upload')->getClientOriginalName();
      $fileName = pathinfo($originName, PATHINFO_FILENAME);
      $extension = $request->file('upload')->getClientOriginalExtension();
      $fileName = $fileName . '_' . time() . '.' . $extension;
      $dimensions = [['1280', '720', 'thumbnail']];
      $image = isset($request['upload']) && !empty($request['upload']) ? FileUpload::uploadImage('upload', $dimensions) : NULL;

      return response()->json(['fileName' => $fileName, 'uploaded'=> 1, 'url' => asset("/storage/images/original/".$image)]);
    }
  }

}
