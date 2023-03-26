<?php

declare(strict_types=1);

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
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('patient_ticket_number')->comment('診察券番号');
            $table->integer('gender')->nullable()->comment('1:オス 2:メス');
            $table->foreignId('pet_type_id')->constrained()->comment('ペットの種類');
            $table->foreignId('user_id')->nullable()->constrained()->comment('会員ID、会員ではない場合はnull');
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
        Schema::dropIfExists('pets');
    }
};
