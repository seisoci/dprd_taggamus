<?php

namespace App\Http\Controllers\Default;

use App\Http\Controllers\Controller;
use App\Models\Theme;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ThemeController extends Controller
{
  use ResponseStatus;

  public function index()
  {
    $config['page_title'] = "Tema Web";
    $page_breadcrumbs = [
      ['url' => '#', 'title' => "Tema Web"],
    ];
    return view('backend.theme.index', compact('config', 'page_breadcrumbs'));
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'direction' => 'nullable',
      'sidebar_layout' => 'nullable',
      'sidemenu' => 'nullable',
      'theme_layout' => 'nullable',
      'sidebar_color' => 'nullable',
    ]);

    if ($validator->passes()) {
      DB::beginTransaction();
      try {
        Theme::updateOrCreate(['user_id' => auth()->id()], [
          'direction' => $request['direction'],
          'sidebar_layout' => $request['sidebar_layout'],
          'sidemenu' => $request['sidemenu'],
          'theme_layout' => $request['theme_layout'],
          'sidebar_color' => $request['sidebar_color'],
        ]);

        DB::commit();
        $response = response()->json($this->responseStore(true, route('backend.theme.index')));
      } catch (Throwable $throw) {
        DB::rollBack();
        $response = response()->json($this->responseStore(false));
      }
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }
    return $response;
  }
}
