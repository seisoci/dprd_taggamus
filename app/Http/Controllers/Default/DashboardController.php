<?php

namespace App\Http\Controllers\Default;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Traits\DiskSpace;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Shetabit\Visitor\Models\Visit;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
  use DiskSpace;

  public function index(Request $request)
  {
    $config['page_title'] = "Dashboard";
    $config['page_description'] = "Dashboard";
    $page_breadcrumbs = [
      ['url' => '#', 'title' => "Dashboard"],
    ];

    $countPost = Visit::selectRaw('count(*) as total')
      ->join('posts', 'posts.id', '=', 'visits.visitable_id')
      ->where('visitable_type', 'post')
      ->where('posts.type', 'posts')
      ->whereDate('visits.created_at', Carbon::now()->toDateString())
      ->first();

    $countTotal = Visit::selectRaw('count(*) as total')
      ->leftJoin('posts', function ($join) {
        $join->on('posts.id', '=', 'visits.visitable_id')
          ->where('visitable_type', 'post');
      })
      ->where(function ($query) {
        $query->where('posts.type', '!=', 'posts')
          ->orWhereNull('posts.type');
      })
      ->whereDate('visits.created_at', Carbon::now()->toDateString())
      ->first();

    if($request->ajax()){
      $countPost = Visit::selectRaw('count(*) as total')
        ->join('posts', 'posts.id', '=', 'visits.visitable_id')
        ->where('visitable_type', 'post')
        ->where('posts.type', 'posts')
        ->whereDate('visits.created_at', '>=', $request['date_start'])
        ->whereDate('visits.created_at', '<=', $request['date_end'])
        ->first();

      $countTotal = Visit::selectRaw('count(*) as total')
        ->leftJoin('posts', function ($join) {
          $join->on('posts.id', '=', 'visits.visitable_id')
            ->where('visitable_type', 'post');
        })
        ->where(function ($query) {
          $query->where('posts.type', '!=', 'posts')
            ->orWhereNull('posts.type');
        })
        ->whereDate('visits.created_at', '>=', $request['date_start'])
        ->whereDate('visits.created_at', '<=', $request['date_end'])
        ->first();

      return response()->json([
        'countPost' => $countPost->total,
        'countTotal' => $countTotal->total,
      ]);
    }

    $data = [
      'countPost' => $countPost->total,
      'countTotal' => $countTotal->total,
      'dateNow' => Carbon::now()->toDateString() . " - " . Carbon::now()->toDateString(),
    ];

    return view('default.dashboard.index', compact('config', 'page_breadcrumbs', 'data'));
  }

  public function show(Request $request)
  {
    $config['page_title'] = "Detail Total Post";
    $page_breadcrumbs = [
      ['url' => '#', 'title' => "Daftar Detail Total Post"],
    ];

    $data = [
      'date_start' => $request['date_start'],
      'date_end' => $request['date_end'],
    ];

    if ($request->ajax()) {
      $data = Visit::selectRaw('posts.title, posts.publish_at, count(*) as total')
        ->join('posts', 'posts.id', '=', 'visits.visitable_id')
        ->where('visitable_type', 'post')
        ->where('posts.type', 'posts')
        ->whereDate('visits.created_at', '>=', $request['date_start'])
        ->whereDate('visits.created_at', '<=', $request['date_end'])
        ->orderBy('posts.publish_at', 'desc')
        ->groupBy('visits.visitable_id')
        ->get();
      return DataTables::of($data)
        ->make(true);
    }

    return view('default.dashboard.show', compact('config', 'page_breadcrumbs', 'data'));
  }

}
