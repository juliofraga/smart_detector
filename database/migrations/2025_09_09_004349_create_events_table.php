<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            $table->char('ip_address', 15)->nullable();
            $table->string('type', 100);
            $table->char('threat_classification', 5)->comment('- Baixo\n- Médio\n- Alto');
            $table->text('ai_analysys');
            $table->string('geographical_origin', 100)->nullable();
            $table->text('request')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
