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
    Schema::create('data_storages', function (Blueprint $table) {
      $table->id();
      $table->enum('type', ['image', 'file']);
      $table->string('name');
      $table->smallInteger('sort');
      $table->morphs('storage_data');
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
    Schema::dropIfExists('data_storages');
  }
};
