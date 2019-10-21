<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('account_type')->default(1)->comment('账户类型');
            $table->string('account_email')->default('')->comment('账户邮箱');
            $table->string('client_id')->default('')->comment('client_id');
            $table->string('client_secret')->default('')->comment('client_secret');
            $table->string('redirect_uri')->default('')->comment('redirect_uri');
            $table->text('access_token')->nullable()->comment('access_token');
            $table->text('refresh_token')->nullable()->comment('refresh_token');
            $table->timestamp('access_token_expires')->nullable()->comment('超时时间');
            $table->boolean('status')->default(1)->comment('状态');
            $table->mediumText('extend')->nullable()->comment('扩展信息');
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
        Schema::dropIfExists('accounts');
    }
}
