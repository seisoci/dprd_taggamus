<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Schedule;
use App\Models\Setting;
use App\Models\Slider;
use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\TwitterCard;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{

  public function index(Request $request)
  {
    visitor()->visit();
    $settings = Setting::all()->keyBy('name');
    $sliders = Slider::orderBy('sort', 'asc')->get();
    $galleries = Post::where([
      ['type', 'galleries'],
      ['published', '1']
    ])->latest()->get();

    $news = Post::selectRaw('
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
      ->limit(11)
      ->get();

    $jdih = Post::where([
      ['posts.type', 'jdih'],
      ['posts.published', '1']
    ])
      ->orderBy('posts.publish_at', 'desc')
      ->groupBy('posts.id')
      ->limit(4)
      ->get();

    $schedules = Schedule::whereDate('date_start', '<=', Carbon::now()->toDateString())
      ->whereDate('date_end', '>=', Carbon::now()->toDateString())
      ->get();

    $data = [
      'settings' => $settings,
      'sliders' => $sliders,
      'galleries' => $galleries,
      'news' => $news,
      'schedules' => $schedules,
      'jdih' => $jdih
    ];

    SEOTools::setTitle($settings['web_title']['value'])
      ->setDescription($settings['web_description']['value'])
      ->addImages([asset("/storage/images/assets/" . $settings['logo_left_url']['value']), asset("/storage/images/assets/" . $settings['logo_right_url']['value'])]);

    TwitterCard::setTitle($settings['web_title']['value'])
      ->setDescription($settings['web_description']['value'])
      ->setImages(asset("/storage/images/assets/" . $settings['logo_right_url']['value']));

    return view('frontend.home', compact('data'));
  }

  public function q(Request $request){
    visitor()->visit();
    $data = Post::selectRaw('
      IF(`posts`.`type` = "posts", "berita", `posts`.`type`) AS `type`,
      `posts`.`slug`,
      `posts`.`title`,
      `posts`.`publish_at`
      ')
      ->where([
        ['posts.published', '1']
      ])
      ->orderBy('posts.publish_at', 'desc')
      ->groupBy('posts.id')
      ->simplePaginate(10);

    if ($request->ajax()) {
      $view = view('components.frontend.q-list', compact('data'))->render();
      return [
        'html' => $view
      ];
    }

    $settings = Setting::all()->keyBy('name');
    SEOTools::setTitle('Pencarian')
      ->setDescription($settings['web_description']['value'])
      ->addImages([asset("/storage/images/assets/" . $settings['logo_left_url']['value']), asset("/storage/images/assets/" . $settings['logo_right_url']['value'])]);

    TwitterCard::setTitle('Pencarian')
      ->setDescription($settings['web_description']['value'])
      ->setImages(asset("/storage/images/assets/" . $settings['logo_right_url']['value']));

    return view('frontend.q', compact('data'));
  }
}
