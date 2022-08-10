<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\GuestBook;
use App\Models\Movement;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
  public function index(Request $request)
  {
    visitor()->visit();
    return view('frontend.contact');
  }

  public function store(Request $request){
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
    return response()->json(['captcha'=> captcha_img()]);
  }
}
