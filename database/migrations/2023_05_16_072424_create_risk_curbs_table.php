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
        Schema::create('risk_curbs', function (Blueprint $table) {
            $table->id();
            $table->integer('tenant_id')->nullable();
            $table->text('organization')->nullable();
            $table->text('organization_type')->nullable();
            $table->text('location')->nullable();
            $table->text('city')->nullable();
            $table->text('state')->nullable();
            $table->text('country')->nullable();
            $table->text('assets')->nullable();
            $table->text('products')->nullable();
            $table->text('services')->nullable();
            $table->text('structure_type')->nullable();
            $table->text('components')->nullable();
            $table->text('customer_types')->nullable();
            $table->text('stakeholders')->nullable();
            $table->text('workers')->nullable();
            $table->text('steps')->nullable();
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
        Schema::dropIfExists('risk_curbs');
    }
};
