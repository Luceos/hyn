<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRouteTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection("website") -> create('route', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('path');
			$table->string('method')->nullable();
			$table->boolean('active')->default(true);
			$table->string('extension');
			$table->string('name');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection("website") -> drop('route');
	}

}
