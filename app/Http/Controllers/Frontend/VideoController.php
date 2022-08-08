<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class VideoController extends Controller
{
  public function index(Request $request)
  {
    $data = Post::where([
      ['posts.type', 'videos'],
      ['posts.published', '1']
    ])
      ->orderBy('posts.publish_at', 'desc')
      ->groupBy('posts.id')
      ->simplePaginate(10);

    if ($request->ajax()) {
      $view = view('components.frontend.videos-list', compact('data'))->render();
      return [
        'html' => $view
      ];
    }

    return view('frontend.videos', compact('data'));
  }
}
