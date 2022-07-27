<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\GuestBook;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class GuestBookController extends Controller
{
  public function __construct()
  {
    $this->middleware('can:backend-guestbook-list', ['only' => ['index', 'show']]);
    $this->middleware('can:backend-guestbook-create', ['only' => ['create', 'store']]);
    $this->middleware('can:backend-guestbook-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:backend-guestbook-delete', ['only' => ['destroy']]);
  }

  public function index(Request $request)
  {
    $config['page_title'] = "Guestbook (Saran)";
    $page_breadcrumbs = [
      ['url' => '#', 'title' => "Daftar Guestbook (Saran)"],
    ];

    if ($request->ajax()) {
      $data = GuestBook::query();
      return DataTables::of($data)
        ->addColumn('action', function ($row) {
          $actionBtn = '<div class="dropdown">
                          <button type="button" class="btn btn-primary" data-bs-toggle="dropdown" aria-expanded="false">
                            Aksi <i class="fa-regular fa-down"></i>
                          </button>
                          <ul class="dropdown-menu">
                            <li><a href="#" data-bs-toggle="modal" data-bs-target="#modalEdit"
                            data-bs-id="' . $row->kode_bank . '"
                            data-bs-bank_name="' . $row->bank_name . '"
                            class="dropdown-item">Ubah</a></li>
                            <li><a href="#" data-bs-toggle="modal" data-bs-target="#modalDelete" data-bs-id="' . $row->kode_bank . '" class="dropdown-item">Hapus</a></li>
                          </ul>
                        </div> ';
          return $actionBtn;
        })
        ->make(true);
    }

    return view('backend.guestbook.index', compact('config', 'page_breadcrumbs'));
  }


  public function show($id)
  {
    $config['page_title'] = "Detail Guestbook";
    $page_breadcrumbs = [
      ['url' => route('backend.guestbooks.index'), 'title' => "Daftar Guestbook (Saran)"],
      ['url' => '#', 'title' => "Detail Guestbook"],
    ];

    $data = GuestBook::findOrFail($id);

    return view('backend.guestbook.show', compact('config', 'page_breadcrumbs', 'data'));

  }
}
