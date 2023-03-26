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
        Schema::create('consultation_reservations', function (Blueprint $table) {
            $table->id();
            $table->integer('visit')->comment('1:初診 2:再診');
            $table->string('date')->comment('予約日');
            $table->string('owner_name')->comment('飼い主名');
            $table->string('phone_number')->comment('電話番号');
            $table->string('mail_address')->comment('メールアドレス');
            $table->text('remark')->comment('備考欄(症状・その他)');
            $table->string('token')->unique()->nullable()->comment('キャンセル用トークン');
            $table->timestampTz('expires_at')->nullable()->comment('トークン有効期限');
            $table->boolean("revoked")->default(false)->comment("キャンセル済みかどうか");
            $table->foreignId('medical_type_id')->constrained()->comment('診察の種類');
            $table->foreignId('user_id')->nullable()->constrained()->comment('会員ID、会員ではない場合はnull');
            $table->foreignId('time_zone_id')->constrained()->comment('予約された時間帯');
            $table->foreignId('pet_id')->constrained()->comment('ペット情報');
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
        Schema::dropIfExists('consultation_reservations');
    }
};
