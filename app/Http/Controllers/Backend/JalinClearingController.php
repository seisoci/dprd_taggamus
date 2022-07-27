<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\JalinClearing;
use App\Models\JalinKlaim;
use App\Traits\CarbonFormat;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class JalinClearingController extends Controller
{
  use CarbonFormat;

  public function __construct()
  {
    $this->middleware('can:backend-jalin-clearing-list', ['only' => ['index', 'show']]);
    $this->middleware('can:backend-jalin-clearing-create', ['only' => ['create', 'store']]);
    $this->middleware('can:backend-jalin-clearing-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:backend-jalin-clearing-delete', ['only' => ['destroy']]);
  }

  public function index(Request $request)
  {
    $config['page_title'] = "Data Clearing Jalin";
    $page_breadcrumbs = [
      ['url' => '#', 'title' => "Daftar Clearing Jalin"],
    ];

    if ($request->ajax()) {
      $data = JalinClearing::query();
      return DataTables::of($data)
        ->filter(function ($query) use($request) {
          if ($request->filled('tgl')) {
            $query->where('tgl', $this->dMYToYmd($request['tgl']));
          }
          if ($request->filled('kode_bank')) {
            $query->where('kode_bank', $request['kode_bank']);
          }
        })
        ->make(true);
    }

    return view('backend.jalin.clearing.index', compact('config', 'page_breadcrumbs'));
  }
}
