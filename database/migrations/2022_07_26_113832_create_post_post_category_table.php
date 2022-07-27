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
    Schema::create('post_post_category', function (Blueprint $table) {
      $table->id();
      $table->foreignId('post_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
      $table->foreignId('post_category_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('post_post_category');
  }
};
