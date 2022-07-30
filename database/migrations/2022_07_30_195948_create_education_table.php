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
    Schema::create('education', function (Blueprint $table) {
      $table->id();
      $table->foreignId('partai_member_id')->constrained()->on('partai_members')->onUpdate('cascade')->onDelete('cascade');
      $table->string('name_institution');
      $table->string('major')->nullable();
      $table->string('faculty')->nullable();
      $table->year('entry_year')->nullable();
      $table->year('graduation_year')->nullable();
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
    Schema::dropIfExists('education');
  }
};