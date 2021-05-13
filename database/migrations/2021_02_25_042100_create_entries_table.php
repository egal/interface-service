<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_entry_metadata', function (Blueprint $table) {
            $table->string('id')->primary()->unique();
            $table->string('name');
            $table->string('icon')->nullable();

            $table->string('menu_metadata_id');
            $table->foreign('menu_metadata_id')->references('id')->on('menu_metadata')->onDelete('cascade');

            $table->string('interface_metadata_id')->nullable();
            $table->foreign('interface_metadata_id')->references('id')->on('interface_metadata')->onDelete('cascade');

            $table->string('parent_menu_entry_metadata_id')->nullable();

            $table->timestamps();
        });

        Schema::table('menu_entry_metadata', function (Blueprint $table) {
            $table->foreign('parent_menu_entry_metadata_id')->references('id')->on('menu_entry_metadata')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_entry_metadata');
    }
}
