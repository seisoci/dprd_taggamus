<?php

namespace App\Http\Controllers\Default;

use App\Facades\Fileupload;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
  use ResponseStatus;

  public function __construct()
  {
    $this->middleware('can:backend-setting-list', ['only' => ['index', 'show']]);
    $this->middleware('can:backend-setting-create', ['only' => ['create', 'store']]);
    $this->middleware('can:backend-setting-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:backend-setting-delete', ['only' => ['destroy']]);
  }

  public function index()
  {
    $config['page_title'] = "Pengaturan";
    $page_breadcrumbs = [
      ['url' => '#', 'title' => "Pengaturan"],
    ];
    $favicon = Setting::where('name', 'favicon')->first();
    $logoWhite = Setting::where('name', 'logo_white')->first();
    $logoBlack = Setting::where('name', 'logo_black')->first();
    $all = Setting::all();
    $data = [
      'favicon' => $favicon,
      'logoWhite' => $logoWhite,
      'logoBlack' => $logoBlack,
      'all' => $all
    ];
    return view('default.setting.index', compact('config', 'page_breadcrumbs', 'data'));
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'logo' => 'image|mimes:jpg,png,jpeg|max:2048',
      'logo_white' => 'image|mimes:jpg,png,jpeg|max:2048',
      'logo_black' => 'image|mimes:jpg,png,jpeg|max:2048',
    ]);

    if ($validator->passes()) {
      $logo_dimensions = [array('534', '437', 'thumbnail')];
      $favicon_dimensions = [['45', '45', 'favicon'], ['100', '100', 'thumbnail']];
      DB::beginTransaction();
      try {
        $favicon = Setting::where('name', 'favicon')->first();
        $logoWhite = Setting::where('name', 'logo_white')->first();
        $logoBlack = Setting::where('name', 'logo_black')->first();

        $logoWhiteVal = isset($request['logo_white']) && !empty($request['logo_white']) ? Fileupload::uploadImage('logo_white', $logo_dimensions, 'storage', $logoWhite['value'], 'logo-light.png') : NULL;
        if ($logoWhiteVal != NULL) {
          $logoWhite->value = $logoWhiteVal;
          $logoWhite->save();
        }

        $logoBlackVal = isset($request['logo_white']) && !empty($request['logo_white']) ? FileUpload::uploadImage('logo_black', $logo_dimensions, 'storage', $logoBlack['value'], 'logo.png') : NULL;
        if ($logoBlackVal != NULL) {
          $logoBlack->value = $logoBlackVal;
          $logoBlack->save();
        }

        $favicon_val = isset($request->favicon) && !empty($request->favicon) ? FileUpload::uploadImage('favicon', $favicon_dimensions, 'storage', $favicon['value'], 'favicon.png') : NULL;
        if ($favicon_val != NULL) {
          $favicon->value = $favicon_val;
          $favicon->save();
        }

        foreach ($request['id'] ?? array() as $key => $id) {
          $data = Setting::find($id);
          $data->update(['value' => $request->value[$key]]);
        }

        DB::commit();
        $response = response()->json($this->responseStore(true, route('backend.settings.index')));
      } catch (\Throwable $throw) {
        DB::rollBack();
        $response = response()->json($this->responseStore(false));
      }
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }
    return $response;
  }
}
