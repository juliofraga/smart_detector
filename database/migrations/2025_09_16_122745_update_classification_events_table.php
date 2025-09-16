<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateClassificationEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('threat_classification');

            $table->foreignId('classifications_id')
                ->nullable()
                ->constrained('classifications')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['classifications_id']);
            $table->dropColumn('classifications_id');

            $table->char('threat_classification', 5)->comment('- Baixo\n- Médio\n- Alto');
        });
    }
}
