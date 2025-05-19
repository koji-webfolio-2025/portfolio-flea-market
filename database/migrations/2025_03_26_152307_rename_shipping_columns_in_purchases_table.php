<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameShippingColumnsInPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->renameColumn('shipping_address', 'shipping_address_line1');
            $table->renameColumn('shipping_building_name', 'shipping_address_line2');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->renameColumn('shipping_address_line1', 'shipping_address');
            $table->renameColumn('shipping_address_line2', 'shipping_building_name');

        });
    }
}
