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
        Schema::create('activations', function (Blueprint $table) {
            $table->id();
            $table->string('user_name')->comment("ユーザ名");
            $table->string('password')->comment("パスワード");
            $table->string('email')->comment("メールアドレス");
            $table->string('code')->comment("認証用コード");
            $table->datetime('email_verified_at')->nullable(true)->comment("メール認証完了日時");
            $table->timestamps();
            $table->index('code');
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activations');
    }
};
