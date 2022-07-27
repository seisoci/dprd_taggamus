<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {
    // \App\Models\User::factory(10)->create();

    $settings = [
      [
        'name' => 'favicon',
        'value' => 'favicon.png',
      ],
      [
        'name' => 'logo_white',
        'value' => 'logo-light.png',
      ],
      [
        'name' => 'logo_black',
        'value' => 'logo.png',
      ],
    ];
    foreach ($settings as $item):
      Setting::updateOrCreate([
        'name' => $item['name']
      ], [
        'value' => $item['value']
      ]);
    endforeach;
  }
}
