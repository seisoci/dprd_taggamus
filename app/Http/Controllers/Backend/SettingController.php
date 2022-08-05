<?php

namespace App\Http\Controllers\Backend;

use App\Facades\Fileupload;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
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
    $config['page_title'] = "Settings";
    $page_breadcrumbs = [
      ['url' => '#', 'title' => "Daftar Settings"],
    ];

    $data = Setting::whereIn('type', ['text', 'textarea'])->get();
    $image = [];
    foreach (Setting::where('type', 'image')->get() as $item) {
      $image[$item['name']] = $item['value'];
    }


    return view('backend.settings.index', compact('config', 'page_breadcrumbs', 'data', 'image'));
  }

  public function store(Request $request){
    $validator = Validator::make($request->all(), [
    ]);

    if ($validator->passes()) {
      $dimensions = $request['name'] == 'favicon_url' ? [['50', '50', 'assets']] :  [['640', '480', 'assets']];

      if($request['pk']){
        Setting::find($request['pk'])->update([
          'value' => $request->value,
        ]);
      }else{
        $settings = Setting::where('name', $request['name'])->first();
        $image = $settings['value'];
        if (isset($request['image']) && !empty($request['image'])) {
          $image = Fileupload::uploadImage('image', $dimensions, 'storage', $settings['value']);
        }
        $data['value'] = $image;
        $settings->update($data);
      }

      $response = $this->responseStore(true);
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }
    return $response;
  }

}
