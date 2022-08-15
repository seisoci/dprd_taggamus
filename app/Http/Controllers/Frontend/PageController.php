<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Setting;
use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\TwitterCard;
use Illuminate\Http\Request;

class PageController extends Controller
{
  public function index(Request $request)
  {
    visitor()->visit();
    $data = Post::where([
        ['posts.type', 'pages'],
        ['posts.published', '1']
      ])
      ->orderBy('posts.publish_at', 'desc')
      ->get();

    $dataPages =  Post::where([
      ['posts.type', 'pages'],
      ['posts.published', '1'],
    ])
      ->orderBy('posts.publish_at', 'desc')
      ->first();

    $settings = Setting::all()->keyBy('name');

    SEOTools::setTitle('Pages')
      ->addImages([asset("/storage/images/assets/" . $settings['logo_left_url']['value']), asset("/storage/images/assets/" . $settings['logo_right_url']['value'])]);

    TwitterCard::setTitle('Pages')
      ->setImages(asset("/storage/images/assets/" . $settings['logo_right_url']['value']));

    return view('frontend.pages', compact('data', 'dataPages'));
  }

  public function show($slug)
  {
    visitor()->visit();
    $data = Post::where([
      ['posts.type', 'pages'],
      ['posts.published', '1']
    ])
      ->orderBy('posts.publish_at', 'desc')
      ->get();

    $dataPages =  Post::where([
      ['posts.type', 'pages'],
      ['posts.published', '1'],
      ['posts.slug', $slug],
    ])
      ->firstOrFail();

    SEOTools::setTitle($dataPages['title'])
      ->setDescription($dataPages['synopsis'])
      ->addImages([asset("/storage/images/assets/" . $dataPages['image'])]);

    TwitterCard::setTitle($dataPages['title'])
      ->setDescription($dataPages['synopsis'])
      ->setImages(asset("/storage/images/assets/" . $dataPages['image']));

    return view('frontend.pages', compact('data', 'dataPages'));
  }
}
