<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id');
            $table->string('stage_title');
            $table->date('stage_start_date');
            $table->date('stage_due_date');
            $table->integer('state')->default(0);
            $table->text('note')->nullable();
            $table->tinyInteger('submit_to_review')->default(0);
            $table->string('submit_to_review_date')->default(0);
            $table->tinyInteger('review_period')->default(0);
            $table->tinyInteger('review_result')->default(0);
            $table->string('review_result_date')->default(0);
            $table->tinyInteger('is_accepted')->default(0);
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
        Schema::dropIfExists('stages');
    }
}
