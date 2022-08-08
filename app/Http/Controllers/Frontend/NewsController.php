<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class NewsController extends Controller
{

  public function index(Request $request)
  {
    $data = Post::selectRaw('
      `posts`.*,
      GROUP_CONCAT(`post_categories`.`name` SEPARATOR ",") as `categories`
      ')
      ->where([
        ['posts.type', 'posts'],
        ['posts.published', '1']
      ])
      ->join('post_post_category', 'post_post_category.post_id', '=', 'posts.id')
      ->join('post_categories', 'post_categories.id', '=', 'post_post_category.post_category_id')
      ->orderBy('posts.publish_at', 'desc')
      ->groupBy('posts.id')
      ->paginate(10);

    if ($request->ajax()) {
      $view = view('components.frontend.news-list', compact('data'))->render();
      return [
        'html' => $view
      ];
    }

    return view('frontend.news', compact('data'));
  }

  public function show($slug, \Request $request)
  {
    $data = Post::where('slug', $slug)->first();
    visitor()->visit($data);
//    visitor()->visit();
//    dd($slug);
  }
}
