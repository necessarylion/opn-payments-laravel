<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('opn_payments_attempts', function (Blueprint $table) {
            $table->id();
            $table->string('order_id', 255)->unique();
            $table->boolean('payment_successful')->default(false);
            $table->boolean('test_mode')->default(true);
            $table->integer('amount')->default(0);
            $table->string('currency', 10)->default('thb');
            $table->string('language', 10)->default('en');
            $table->text('payment_methods', 255)->nullable();
            $table->string('redirect_uri', 255)->nullable();
            $table->string('cancel_uri', 255)->nullable();
            $table->text('meta_data')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('opn_payments_charges', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('opn_payments_attempt_id');
            $table->string('charge_id', 255)->unique()->nullable();
            $table->boolean('payment_successful')->default(false);
            $table->string('status', 255)->nullable();
            $table->string('payment_method', 255)->nullable();
            $table->string('failure_code', 255)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('opn_payments_charges');
        Schema::dropIfExists('opn_payments_attempts');
    }
};
