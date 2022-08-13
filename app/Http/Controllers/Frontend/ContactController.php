<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\GuestBook;
use App\Models\Setting;
use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\TwitterCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
  public function index(Request $request)
  {
    visitor()->visit();
    $settings = Setting::all()->keyBy('name');

    SEOTools::setTitle('Kontak Kami')
      ->addImages([asset("/storage/images/assets/" . $settings['logo_left_url']['value']), asset("/storage/images/assets/" . $settings['logo_right_url']['value'])]);

    TwitterCard::setTitle('Kontak Kami')
      ->setImages(asset("/storage/images/assets/" . $settings['logo_right_url']['value']));
    return view('frontend.contact');
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => ['required', 'string', 'max:255'],
      'email' => 'required|email',
      'description' => 'required|string',
      'captcha' => 'required|captcha',
    ]);

    if ($validator->passes()) {
      GuestBook::create($request->all());
      return redirect()->back()->with('success', 'Pesan Berhasil Dikirim');
    } else {
      return redirect()->back()->withErrors($validator->errors())->withInput();
    }
  }

  public function reloadCaptcha()
  {
    return response()->json(['captcha' => captcha_img('flat')]);
  }
}
