<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuManagersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('menu_managers', function (Blueprint $table) {
      $table->id();
      $table->tinyInteger('parent_id')->default(0);
      $table->foreignId('menu_permission_id')->nullable()->constrained()->on('menu_permissions')->onUpdate('cascade')->onDelete('cascade');
      $table->foreignId('role_id')->constrained()->on('roles')->onUpdate('cascade')->onDelete('cascade');
      $table->string('title')->nullable();
      $table->string('path_url')->nullable();
      $table->string('icon')->nullable();
      $table->string('type')->nullable();
      $table->integer('sort');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('menu_managers');
  }
}
