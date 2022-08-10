<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Schedule;
use App\Models\Setting;
use App\Models\Slider;
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
      ->join('post_post_category', 'post_post_category.post_id', '=', 'posts.id')
      ->join('post_categories', 'post_categories.id', '=', 'post_post_category.post_category_id')
      ->orderBy('posts.publish_at','desc' )
      ->groupBy('posts.id')
      ->limit(11)
      ->get();

    $jdih = Post::where([
        ['posts.type', 'jdih'],
        ['posts.published', '1']
      ])
      ->orderBy('posts.publish_at','desc' )
      ->groupBy('posts.id')
      ->limit(4)
      ->get();

    $schedules = Schedule::whereDate('date_start', '<=' , Carbon::now()->toDateString())
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
    return view('frontend.home', compact('data'));
  }
}
