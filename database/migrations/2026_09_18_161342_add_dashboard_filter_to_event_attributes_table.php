<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDashboardFilterToEventAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_attributes', function (Blueprint $table) {
            $table->tinyInteger('dashboard_filter')->default(0)->after('enabled');
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
            $table->dropColumn('dashboard_filter');
        });
    }
}
