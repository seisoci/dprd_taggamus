<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Setting;
use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\TwitterCard;
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
      ->simplePaginate(4);

    if ($request->ajax()) {
      $view = view('components.frontend.jdih-list', compact('data'))->render();
      return [
        'html' => $view
      ];
    }

    $settings = Setting::all()->keyBy('name');

    SEOTools::setTitle('JDIH')
      ->addImages([asset("/storage/images/assets/" . $settings['logo_left_url']['value']), asset("/storage/images/assets/" . $settings['logo_right_url']['value'])]);

    TwitterCard::setTitle('JDIH')
      ->setImages(asset("/storage/images/assets/" . $settings['logo_right_url']['value']));

    return view('frontend.jdih', compact('data'));
  }

  public function show($slug)
  {
    $data = Post::with('datastorage')
      ->where([
        ['posts.type', 'jdih'],
        ['posts.published', '1'],
        ['posts.slug', $slug]
      ])
      ->firstOrFail();

    visitor()->visit();

    SEOTools::setTitle($data['title'])
      ->setDescription($data['synopsis'])
      ->addImages([asset("/storage/images/assets/" . $data['image'])]);

    TwitterCard::setTitle($data['title'])
      ->setDescription($data['synopsis'])
      ->setImages(asset("/storage/images/assets/" . $data['image']));

    return view('frontend.jdih-detail', compact('data'));
  }

}
