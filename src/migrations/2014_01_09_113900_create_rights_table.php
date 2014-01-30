<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRightsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection("system") -> create('rights', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->string("user");
			$table->string("right");
			$table->integer("item")->default(0);
			$table->integer("level")->default(0);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection("system") -> drop('rights');
	}

}
