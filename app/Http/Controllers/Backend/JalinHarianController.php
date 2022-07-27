<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\JalinHarian;
use App\Traits\CarbonFormat;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class JalinHarianController extends Controller
{
  use CarbonFormat;

  public function __construct()
  {
    $this->middleware('can:backend-jalin-harian-list', ['only' => ['index', 'show']]);
    $this->middleware('can:backend-jalin-harian-create', ['only' => ['create', 'store']]);
    $this->middleware('can:backend-jalin-harian-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:backend-jalin-harian-delete', ['only' => ['destroy']]);
  }
  public function index(Request $request)
  {
    $config['page_title'] = "Data Harian Jalin";
    $page_breadcrumbs = [
      ['url' => '#', 'title' => "Daftar Harian Jalin"],
    ];

    if ($request->ajax()) {
      $data = JalinHarian::query();
      return DataTables::of($data)
        ->filter(function ($query) use($request) {
          if ($request->filled('tgl')) {
            $query->where('tgl', $this->dMYToYmd($request['tgl']));
          }
          if ($request->filled('kode_report')) {
            $query->where('kode_report', $request['kode_report']);
          }
        })
        ->make(true);
    }

    return view('backend.jalin.harian.index', compact('config', 'page_breadcrumbs'));
  }

}
