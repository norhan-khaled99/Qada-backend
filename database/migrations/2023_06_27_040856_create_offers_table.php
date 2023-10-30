<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('project_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('offer_value');
            $table->bigInteger('offer_duration'); // Days
            $table->integer('offer_stages');
            $table->text('offer_details');
            $table->tinyInteger('state')->default(0);
            $table->tinyInteger('discount_requested')->default(0);
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
        Schema::dropIfExists('offers');
    }
}
