<?php

namespace App\Http\Controllers\Default;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Traits\DiskSpace;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
  use DiskSpace;

  public function __invoke()
  {
    $config['page_title'] = "Dashboard";
    $config['page_description'] = "Dashboard";
    $page_breadcrumbs = [
      ['url' => '#', 'title' => "Dashboard"],
    ];


    $data = [
      'diskpace' => $this::diskpace(),
      'dbusage' => $this::getDBSizeInMB(),
    ];

    return view('default.dashboard.index', compact('config', 'page_breadcrumbs', 'data'));
  }
}
