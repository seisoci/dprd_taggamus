<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('permission_role', function (Blueprint $table) {
        $table->foreignId('menu_manager_id')->constrained()->on('menu_managers')->onUpdate('cascade')->onDelete('cascade');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('permission_role', function (Blueprint $table) {
        $table->dropForeign('role_user_permission_role_id_foreign');
        $table->dropColumn('menu_manager_id');
      });
    }
};
