<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_attributes', function (Blueprint $table) {
            $table->id();
            $table->integer('position')->default(0);
            $table->string('field_name', 100);
            $table->string('display_value', 100);
            $table->boolean('show')->default(true)->comment('0 - FALSE | 1 - TRUE');
            $table->boolean('enabled')->default(true)->comment('0 - FALSE | 1 - TRUE');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_attributes');
    }
}
