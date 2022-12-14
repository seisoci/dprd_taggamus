<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('visits', function (Blueprint $table) {
      $table->id();
      $table->string('method')->nullable();
      $table->mediumText('request')->nullable();
      $table->mediumText('url')->nullable();
      $table->mediumText('referer')->nullable();
      $table->text('languages')->nullable();
      $table->text('useragent')->nullable();
      $table->text('headers')->nullable();
      $table->text('device')->nullable();
      $table->text('platform')->nullable();
      $table->text('browser')->nullable();
      $table->ipAddress('ip')->nullable();
      $table->nullableMorphs('visitable');
      $table->nullableMorphs('visitor');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('visitors');
  }
};
