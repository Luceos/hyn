<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebsiteTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection("system") -> create('website', function(Blueprint $table)
		{
			$table->increments('websiteID');
			$table->string("owner") -> default("0");
			$table->boolean("active") -> default(true);
			$table->dateTime("lastvisit") -> nullable();
			$table->integer("theme") -> nullable();
			$table->boolean("systemdefault") -> default(false);
			$table->softDeletes();
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
		Schema::connection("system") -> drop('website');
	}

}
