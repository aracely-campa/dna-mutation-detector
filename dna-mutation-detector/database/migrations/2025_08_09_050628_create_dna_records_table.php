<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDnaRecordsTable extends Migration
{
    public function up()
    {
        Schema::create('dna_records', function (Blueprint $table) {
            $table->id();
            $table->string('hash')->unique();
            $table->text('dna');
            $table->boolean('has_mutation');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dna_records');
    }
}