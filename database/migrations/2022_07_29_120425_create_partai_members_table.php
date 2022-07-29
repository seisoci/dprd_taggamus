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
    Schema::create('partai_members', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('position')->nullable();
      $table->string('place_birth')->nullable();
      $table->string('no_member')->nullable();
      $table->foreignId('komisi_id')->nullable()->constrained()->on('komisis')->onUpdate('cascade')->onDelete('cascade');
      $table->foreignId('election_region_id')->nullable()->constrained()->on('election_regions')->onUpdate('cascade')->onDelete('cascade');
      $table->string('religion')->nullable();
      $table->string('fraksi')->nullable();
      $table->string('partai')->nullable();
      $table->string('period')->nullable();
      $table->string('image')->nullable();
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
    Schema::dropIfExists('partai_members');
  }
};
