<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\DataStorage;
use App\Models\Post;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
  public function index(Request $request)
  {
    visitor()->visit();
    $data = Post::where([
      ['posts.type', 'galleries'],
      ['posts.published', '1']
    ])
      ->orderBy('posts.publish_at', 'desc')
      ->groupBy('posts.id')
      ->simplePaginate(10);

    if ($request->ajax()) {
      $view = view('components.frontend.gallery-list', compact('data'))->render();
      return [
        'html' => $view
      ];
    }

    return view('frontend.galleries', compact('data'));
  }

  public function show($id, Request $request)
  {
    visitor()->visit();
    $post = Post::where([
      ['posts.slug', $id],
      ['posts.type', 'galleries'],
      ['posts.published', '1']
    ])->first();

    $data = DataStorage::where([
      ['storage_data_type', 'post'],
      ['storage_data_id', $post['id']]
    ])
      ->orderBy('sort', 'asc')
      ->simplePaginate(10);


    $anotherAlbum = Post::where([
      ['posts.type', 'galleries'],
      ['posts.published', '1']
    ])
      ->orderBy('posts.publish_at', 'desc')
      ->groupBy('posts.id')
      ->limit(5)
      ->get();

    if ($request->ajax()) {
      $view = view('components.frontend.gallery-detail', compact('data'))->render();
      return [
        'html' => $view
      ];
    }

    return view('frontend.galleries-detail', compact('data', 'anotherAlbum', 'post'));
  }

}
