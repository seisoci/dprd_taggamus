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

    $settings = Setting::all()->keyBy('name');

    SEOTools::setTitle('Pages')
      ->addImages([asset("/storage/images/assets/" . $settings['logo_left_url']['value']), asset("/storage/images/assets/" . $settings['logo_right_url']['value'])]);

    TwitterCard::setTitle('Pages')
      ->setImages(asset("/storage/images/assets/" . $settings['logo_right_url']['value']));

    return view('frontend.pages', compact('data'));
  }
}
