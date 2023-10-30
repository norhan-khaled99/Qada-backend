<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained();
            $table->foreignId('offer_id')->constrained();
            $table->foreignId('project_owner_id')->constrained('users','id');
            $table->foreignId('offer_owner_id')->constrained('users', 'id');
            $table->integer('contract_duration'); // From Offer
            $table->integer('contract_value'); // From Offer
            $table->integer('contract_stages'); // From Offer
            $table->foreignId('contract_current_stage')->nullable()->constrained('stages','id'); // From Offer
            $table->tinyInteger('contract_state'); // 1 , 2 , 3 , 4 , 5
            $table->integer('penality'); // هيتم تطبيق الشرط الجزائي اذا تم الاخلال بوقت تسليم اي مرحلة
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
        Schema::dropIfExists('contracts');
    }
}
