<?php 
namespace Hyn\Wm\Framework\Command;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Hyn\Wm\Framework\Website\Website;

class ServerConfig extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'hyn:serverconfig';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Creates server configurations for nginx, apache, fpm etc.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		//
		$website		= Website::whereHas( 'domains' , function($q)
		{
			$q -> where( "hostname" , "=" , $this -> argument('hostname') );
		}) -> firstOrFail();
		$website -> writeServerConfig();
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('hostname', InputArgument::REQUIRED, 'Domain name for website manipulation'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
#			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
