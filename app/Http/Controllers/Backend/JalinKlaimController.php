<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\JalinKlaim;
use App\Traits\CarbonFormat;
use App\Traits\ConvertStringToDecimal;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class JalinKlaimController extends Controller
{
  use CarbonFormat;

  public function __construct()
  {
    $this->middleware('can:backend-jalin-klaim-list', ['only' => ['index', 'show']]);
    $this->middleware('can:backend-jalin-klaim-create', ['only' => ['create', 'store']]);
    $this->middleware('can:backend-jalin-klaim-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:backend-jalin-klaim-delete', ['only' => ['destroy']]);
  }

  public function index(Request $request)
  {
    $config['page_title'] = "Data Klaim Jalin";
    $page_breadcrumbs = [
      ['url' => '#', 'title' => "Daftar Klaim Jalin"],
    ];

    if ($request->ajax()) {
      $data = JalinKlaim::query();
      return DataTables::of($data)
        ->filter(function ($query) use($request) {
          if ($request->filled('tgl')) {
            $query->where('tgl', $this->dMYToYmd($request['tgl']));
          }
          if ($request->filled('jenis')) {
            $query->where('jenis', $request['jenis']);
          }
        })
        ->make(true);
    }

    return view('backend.jalin.klaim.index', compact('config', 'page_breadcrumbs'));
  }

}
