<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
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
}
