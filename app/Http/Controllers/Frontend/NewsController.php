<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;

class NewsController extends Controller
{
  public function show($slug, \Request $request)
  {
    $data = Post::where('slug', $slug)->first();
    visitor()->visit($data);
//    visitor()->visit();
//    dd($slug);
  }
}
