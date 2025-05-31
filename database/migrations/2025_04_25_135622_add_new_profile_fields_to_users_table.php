<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('new_name')->nullable();
        $table->string('new_email')->nullable();
        $table->string('new_faculty')->nullable();
        $table->string('new_major')->nullable();
        $table->text('new_bio')->nullable();
        $table->string('new_profile_image')->nullable();
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn([
            'new_name', 'new_email', 'new_faculty', 'new_major', 'new_bio', 'new_profile_image'
        ]);
    });
}
};
