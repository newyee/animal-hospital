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
        Schema::create('reservation_cancels', function (Blueprint $table) {
            $table->id();
            $table->string('cancellation_reason')->comment('キャンセル理由');
            $table->foreignId('reservation_id')->constrained('consultation_reservations');
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
        Schema::dropIfExists('reservation_cancels');
    }
};
