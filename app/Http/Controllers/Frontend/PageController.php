<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PageController extends Controller
{
  public function index(Request $request)
  {
    $data = Post::selectRaw('
      `posts`.`slug`,
      `posts`.`title`,
      `posts`.`publish_at`
      ')
      ->where([
        ['posts.type', 'pages'],
        ['posts.published', '1']
      ])
      ->orderBy('posts.publish_at', 'desc')
      ->groupBy('posts.id')
      ->get();

    return view('frontend.pages', compact('data'));
  }
}
