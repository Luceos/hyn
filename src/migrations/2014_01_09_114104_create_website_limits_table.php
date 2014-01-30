<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebsiteLimitsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::connection("system")->create('website_limits', function(Blueprint $table)
		{
			$table->integer('websiteID');
			$table->integer('diskspace')->nullable();
			$table->integer('bandwidth')->nullable();
			$table->integer('emailaddress')->nullable();
			$table->timestamps();
			
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
		Schema::connection("system")->drop('website_limits');
	}

}
