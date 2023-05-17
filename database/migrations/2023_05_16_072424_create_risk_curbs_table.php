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
            $table->integer('workers')->nullable();
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
