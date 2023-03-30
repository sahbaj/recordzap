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
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('stripe_payment_id')->nullable()->after('status');
            $table->string('stripe_customer_id')->nullable()->after('stripe_payment_id');
            $table->string('stripe_subscription_id')->nullable()->after('stripe_customer_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('stripe_customer_id');
            $table->dropColumn('stripe_subscription_id');
            $table->dropColumn('stripe_payment_id');
        });
    }
};
