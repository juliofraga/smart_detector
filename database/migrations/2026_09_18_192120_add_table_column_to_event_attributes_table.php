<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableColumnToEventAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_attributes', function (Blueprint $table) {
            $table->tinyInteger('table_column')->default(0)->after('dashboard_filter');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_attributes', function (Blueprint $table) {
            $table->dropColumn('table_column');
        });
    }
}
