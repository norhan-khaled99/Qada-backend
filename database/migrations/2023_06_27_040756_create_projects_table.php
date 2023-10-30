<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('contract_id')->nullable();
            $table->foreignId('offer_id')->nullable();
            $table->string('project_title');
            $table->text('project_details');
            $table->string('space');
            $table->string('service_category');
            $table->date('offer_choosing_date');
            $table->string('project_days_limit');
            $table->date('last_offers_date');
            $table->date('delivery_date');
            $table->string('area');
            $table->string('city');
            $table->tinyInteger('request_qty_tables')->default(1);
            $table->tinyInteger('request_engs')->default(1);
            $table->tinyInteger('state')->default(0);
            $table->text('note')->default('Waitting For Admin Approval');
            $table->string('title_deed')->nullable();//Image File
            $table->string('owner_id')->nullable();//Image File
            $table->text('other_files')->nullable();//Images Files
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
        Schema::dropIfExists('projects');
    }
}
