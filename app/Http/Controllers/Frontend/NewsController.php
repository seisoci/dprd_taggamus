<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Setting;
use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\TwitterCard;
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
      ->leftJoin('post_post_category', 'post_post_category.post_id', '=', 'posts.id')
      ->leftJoin('post_categories', 'post_categories.id', '=', 'post_post_category.post_category_id')
      ->orderBy('posts.publish_at', 'desc')
      ->groupBy('posts.id')
      ->paginate(10);

    if ($request->ajax()) {
      $view = view('components.frontend.news-list', compact('data'))->render();
      return [
        'html' => $view
      ];
    }

    $settings = Setting::all()->keyBy('name');

    SEOTools::setTitle('Berita')
      ->addImages([asset("/storage/images/assets/" . $settings['logo_left_url']['value']), asset("/storage/images/assets/" . $settings['logo_right_url']['value'])]);

    TwitterCard::setTitle('Berita')
      ->setImages(asset("/storage/images/assets/" . $settings['logo_right_url']['value']));

    return view('frontend.news', compact('data'));
  }

  public function show($slug)
  {
    $data = Post::selectRaw('
      `posts`.*,
      GROUP_CONCAT(`post_categories`.`name` SEPARATOR ",") as `categories`
      ')
      ->with('datastorage')
      ->where([
        ['posts.type', 'posts'],
        ['posts.published', '1'],
        ['posts.slug', $slug]
      ])
      ->leftJoin('post_post_category', 'post_post_category.post_id', '=', 'posts.id')
      ->leftJoin('post_categories', 'post_categories.id', '=', 'post_post_category.post_category_id')
      ->orderBy('posts.publish_at', 'desc')
      ->groupBy('posts.id')
      ->firstOrFail();

    visitor()->visit($data);

    SEOTools::setTitle($data['title'])
      ->setDescription($data['synopsis'])
      ->addImages([asset("/storage/images/assets/" . $data['image'])]);

    TwitterCard::setTitle($data['title'])
      ->setDescription($data['synopsis'])
      ->setImages(asset("/storage/images/assets/" . $data['image']));

    return view('frontend.news-detail', compact('data'));
  }
}
