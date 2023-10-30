<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToStages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stages', function (Blueprint $table) {
            Schema::table('stages', function (Blueprint $table) {
                $table->foreign('contract_id')->references('id')->on('contracts');
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stages', function (Blueprint $table) {
            Schema::table('stages', function (Blueprint $table) {
                $table->dropForeign(['contract_id']);
            });
        });
    }
}
