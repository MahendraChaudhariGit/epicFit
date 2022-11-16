<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
//            Schema::create('products', function(Blueprint $table) {
//                $table->increments('id');
//                $table->string('name');
//$table->string('sku_id');
//$table->text('description');
//$table->string('logo');
//$table->integer('sale_price');
//$table->string('tax');
//$table->string('stock_location');
//$table->string('stock_level');
//$table->string('history');
//$table->integer('cost_price');
//$table->text('stock_note');
//
//                $table->timestamps();
//            });
            
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('products');
    }

}
