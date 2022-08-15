<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Polling;
use App\Models\PollingAnswer;
use App\Models\Setting;
use App\Traits\ResponseStatus;
use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\TwitterCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PollingController extends Controller
{
  use ResponseStatus;

  public function index(Request $request)
  {
    visitor()->visit();
    $data = Polling::with('options')->where('status', '1')->orderBy('publish_at', 'desc')->get();
    $settings = Setting::all()->keyBy('name');

    SEOTools::setTitle('Polling')
      ->addImages([asset("/storage/images/assets/" . $settings['logo_left_url']['value']), asset("/storage/images/assets/" . $settings['logo_right_url']['value'])]);

    TwitterCard::setTitle('Polling')
      ->setImages(asset("/storage/images/assets/" . $settings['logo_right_url']['value']));
    return view('frontend.polling', compact('data'));
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'polling_id' => 'required|integer',
      'polling_option_id' => 'required|integer',
    ]);

    if ($validator->passes()) {
      $polling = PollingAnswer::where([
        ['polling_id', $request['polling_id']],
        ['ip_address', $request->ip()]
      ])->first();

      if($polling){
        return response()->json(['status' => 'error', 'message' => 'Anda sudah pernah mengikuti polling ini']);
      }

      $request->request->add(['ip_address' => $request->ip()]);
      PollingAnswer::create([
        'polling_id' => $request['polling_id'],
        'polling_option_id' => $request['polling_option_id'],
        'ip_address' => $request->ip()
      ]);

      $response = $this->responseStore(true, 'reload', 'Anda Berhasil Voting');
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }
    return $response;
  }

}
