<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\DataStorage;
use App\Models\Post;
use App\Models\Setting;
use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\TwitterCard;
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

    $post = Post::where([
      ['posts.type', 'galleries'],
      ['posts.published', '1']
    ])
      ->orderBy('publish_at', 'desc')
      ->limit(1)
      ->first();

    SEOTools::setTitle('Gallery');

    $settings = Setting::all()->keyBy('name');
    TwitterCard::setTitle('Gallery')
      ->setImages(asset("/storage/images/thumbnail/" . ($post['image'] ?? $settings['logo_left_url']['value'])));

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

    $settings = Setting::all()->keyBy('name');
    SEOTools::setTitle('Gallery - ' . $post['title']);

    TwitterCard::setTitle('Gallery')
      ->setImages(asset("/storage/images/thumbnail/" . ($post['image'] ?? $settings['logo_left_url']['value'])));

    return view('frontend.galleries-detail', compact('data', 'anotherAlbum', 'post'));
  }

}
