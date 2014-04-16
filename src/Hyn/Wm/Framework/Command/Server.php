<?php 
namespace Hyn\Wm\Framework\Command;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Hyn\Wm\Framework\Website\Website;
use Hyn\Wm\Framework\Server\System;

class Server extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'hyn:server';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Read, write and update server information/configuration';

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
		if( $this -> option('ips') )
		{
			$ips		= System::IPs();
			$this -> info('ips recalculated, found ' . count($ips));
		}
		else
		if( $this -> option('jobtest'))
		{
			\dd(\Queue::push( new \Hyn\Wm\Framework\Queue\WebsiteJob, '' ));
			$this -> info('sending job test');
		}
		else
		{
			$this -> error("no option selected");
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
			['ips', null, InputOption::VALUE_NONE, 'Update system ip\'s', null],
			['jobtest', null, InputOption::VALUE_NONE, 'Test queue job', null],
		];
	}

}
