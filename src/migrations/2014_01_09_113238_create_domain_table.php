<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDomainTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection("system") -> create('domain', function(Blueprint $table)
		{
			$table->string('hostname');
			$table->integer("websiteID");
			$table->boolean("active")->default(true);
			$table->boolean("primary")->default(false);
			$table->dateTime("lastvisit")->nullable();
			$table->dateTime("registry_register")->nullable();
			$table->dateTime("registry_expire")->nullable();
			$table->dateTime("registry_end")->nullable();
			$table->boolean("redirectPrimary")->default(false);
			$table->softDeletes();
			$table->timestamps();
			
			$table->primary('hostname');
			
			$table->foreign('websiteID') -> references('websiteID') -> on('website');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::connection("system") -> drop('domain');
	}

}
