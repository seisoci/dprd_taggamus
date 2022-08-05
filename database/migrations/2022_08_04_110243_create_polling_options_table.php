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
    Schema::create('polling_options', function (Blueprint $table) {
      $table->id();
      $table->foreignId('polling_id')->constrained()->on('pollings')->onUpdate('cascade')->onDelete('cascade');
      $table->tinyInteger('sort');
      $table->string('name');
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('polling_options');
  }
};
