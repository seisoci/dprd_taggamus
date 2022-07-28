<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\FileUpload;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use App\Traits\ResponseStatus;
use Carbon\Carbon;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class JDIHController extends Controller
{
  use ResponseStatus;

  public function __construct()
  {
    $this->middleware('can:backend-jdih-list', ['only' => ['index', 'show']]);
    $this->middleware('can:backend-jdih-create', ['only' => ['create', 'store']]);
    $this->middleware('can:backend-jdih-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:backend-jdih-delete', ['only' => ['destroy']]);
  }

  public function index(Request $request)
  {
    $config['page_title'] = "JDIH";
    $page_breadcrumbs = [
      ['url' => route('backend.jdih.index'), 'title' => "JDIH"],
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
        ->where('posts.type', 'jdih')
        ->leftJoin('users', 'users.id', '=', 'posts.user_id')
        ->leftJoin('post_post_category', function ($join) {
          $join->on('post_post_category.post_id', '=', 'posts.id');
          $join->leftJoin('post_categories', 'post_categories.id', '=', 'post_post_category.post_category_id');
        })
        ->groupBy('posts.id');

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
              <li><a href="' . route('backend.jdih.edit', $row['id']) . '" class="dropdown-item">Ubah</a></li>
              <li><a href="#" data-bs-toggle="modal" data-bs-target="#modalDelete" data-bs-id="' . $row->id . '" class="delete dropdown-item">Hapus</a></li>
            </ul>
          </div>';
        })
        ->make(true);
    }

    return view('backend.jdih.index', compact('page_breadcrumbs', 'config'));
  }

  public function create()
  {
    $config['page_title'] = "Tambah JDIH";
    $page_breadcrumbs = [
      ['url' => route('backend.news.index'), 'title' => "JDIH"],
      ['url' => '#', 'title' => "Tambah JDIH"],
    ];
    return view('backend.jdih.create', compact('page_breadcrumbs', 'config'));
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'title' => 'required|string',
      'post_categories' => 'nullable|array',
      'post_categories.*' => 'nullable|integer',
      'image' => 'image|mimes:pdf|max:5000',
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

        foreach ($request['post_items'] ?? array() as $key => $item):
          $file = request()->file("post_items.{$key}");
          $ext = $file->getClientOriginalExtension();
          $fileName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '_' . Carbon::now()->timestamp . rand(1, 9999)) . '.' . $ext;
          Storage::putFileAs('public/document', new File($file), $fileName);

          $post->datastorage()->create([
            'sort' => ++$key,
            'type' => 'file',
            'name' => $fileName,
          ]);
        endforeach;

        DB::commit();
        $response = response()->json($this->responseStore(true, route('backend.jdih.index')));
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
    $config['page_title'] = "Edit JDIH";
    $page_breadcrumbs = [
      ['url' => route('backend.jdih.index'), 'title' => "JDIH"],
      ['url' => '#', 'title' => "Edit JDIH"],
    ];

    $data = Post::with(['post_categories', 'datastorage'])->findOrFail($id);

    return view('backend.jdih.edit', compact('page_breadcrumbs', 'config', 'data'));
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

        foreach ($request['post_items'] ?? array() as $key => $item):
          $file = request()->file("post_items.{$key}");
          $ext = $file->getClientOriginalExtension();
          $fileName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '_' . Carbon::now()->timestamp . rand(1, 9999)) . '.' . $ext;
          Storage::putFileAs('public/document', new File($file), $fileName);
          $max = $post->datastorage()->max('sort');

          $post->datastorage()->create([
            'sort' => ++$max,
            'type' => 'file',
            'name' => $fileName,
          ]);
        endforeach;

        DB::commit();
        $response = response()->json($this->responseUpdate(true, route('backend.jdih.index')));
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
      Storage::disk('public')->delete(["document/$data->name"]);
      $response = response()->json($this->responseDelete(true));
    }
    return $response;
  }
}
