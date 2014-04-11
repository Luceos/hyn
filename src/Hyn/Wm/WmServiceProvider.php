<?php 
namespace Hyn\Wm;

# facades
use App, Auth;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use \Illuminate\Auth\Guard;

use Hyn\Wm\Framework\Website\Sitemanager;
use Hyn\Wm\Framework\User\UserProvider;


class WmServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('hyn/wm');
		// add custom authentication
		\Auth::extend( "HynWM" , function($app)
		{
			$provider		= new UserProvider;
			return new Guard($provider, App::make('session.store') );
		});
		$aloader	= AliasLoader::getInstance();
		$aloader -> alias( "System" 		, __NAMESPACE__ . "\Framework\Facade\System" );
		$aloader -> alias( "ServerConfig" 	, __NAMESPACE__ . "\Framework\Facade\ServerConfig" );
				
		$aloader -> alias( "Setting" 		, __NAMESPACE__ . "\Framework\Facade\Setting" );
		
		/* disable multitenancy users for now */
		\Config::set("database.multitenancy"	, false );
	}

	/**
	 * Register the service provider.
	 *	This is done at runtime
	 *
	 * @return void
	 */
	public function register()
	{
		// enable availability of System
		App::singleton( "System" , function( $app )
		{
			return new Framework\Server\System;
		});
		App::singleton( "Serverconfig" , function( $app )
		{
			return new Framework\Server\Config;
		});
		App::singleton( "Visitor" , function( $app )
		{
			return new Framework\User\Visitor;
		});
		App::singleton( "Setting" , function( $app )
		{
			return new Framework\Website\Setting;
		});
		App::before(function($request)
		{
			Sitemanager::start();
			
			$visitor		= App::make("Visitor");
			
			App::singleton( "Website" , function( $app )
			{
				return Framework\Website\Website::Current();
			});
			$aloader	= AliasLoader::getInstance();
			$aloader -> alias( "Website" 		, __NAMESPACE__ . "\Framework\Facade\Website" );
			
			// inject globals to twig
			$this -> app['config']->set('twigbridge::globals', 
				array_merge( 
					$this -> app['config']->get('twigbridge::globals') 
					, array(
						"Website"			=> App::make("Website"),
						"Visitor"			=> App::make("Visitor"),
						"User"				=> Auth::user(),
					) 
				)
			);
		});
		
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}