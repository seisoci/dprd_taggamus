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
    Schema::create('movements', function (Blueprint $table) {
      $table->id();
      $table->foreignId('partai_member_id')->constrained()->on('partai_members')->onUpdate('cascade')->onDelete('cascade');
      $table->string('name');
      $table->year('year')->nullable();
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
    Schema::dropIfExists('movements');
  }
};
