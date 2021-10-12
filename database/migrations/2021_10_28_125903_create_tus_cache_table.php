<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tus_cache', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('key');
            $table->longText('value');
            $table->index('key');
        });
    }
};