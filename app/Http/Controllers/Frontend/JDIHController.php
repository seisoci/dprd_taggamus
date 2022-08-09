<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class JDIHController extends Controller
{
  public function index(Request $request)
  {
    visitor()->visit();
    $data = Post::where([
      ['posts.type', 'jdih'],
      ['posts.published', '1']
    ])
      ->orderBy('posts.publish_at', 'desc')
      ->groupBy('posts.id')
      ->simplePaginate(1);

    if ($request->ajax()) {
      $view = view('components.frontend.jdih-list', compact('data'))->render();
      return [
        'html' => $view
      ];
    }

    return view('frontend.jdih', compact('data'));
  }
}
