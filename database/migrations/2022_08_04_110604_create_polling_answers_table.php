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
    Schema::create('polling_answers', function (Blueprint $table) {
      $table->id();
      $table->foreignId('polling_id')->constrained()->on('pollings')->onUpdate('cascade')->onDelete('cascade');
      $table->foreignId('polling_option_id')->constrained()->on('polling_options')->onUpdate('cascade')->onDelete('cascade');
      $table->string('ip_address');
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
    Schema::dropIfExists('polling_answers');
  }
};
