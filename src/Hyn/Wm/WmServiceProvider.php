<?php 
namespace Hyn\Wm;

# facades
use App;

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
		$aloader -> alias( "HynSystem" 		, __NAMESPACE__ . "\Framework\Facade\Hynsystem" );
		$aloader -> alias( "HynServerConfig" 	, __NAMESPACE__ . "\Framework\Facade\Hynserverconfig" );
		$aloader -> alias( "HynVisitor" 	, __NAMESPACE__ . "\Framework\Facade\Hynvisitor" );
		
		$aloader -> alias( "SystemEloquent" 	, __NAMESPACE__ . "\Framework\Facade\SystemEloquent" );
		$aloader -> alias( "SiteEloquent" 	, __NAMESPACE__ . "\Framework\Facade\SiteEloquent" );
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
		App::singleton( "hynsystem" , function( $app )
		{
				return new Framework\Server\System;
		});
		App::singleton( "hynserverconfig" , function( $app )
		{
				return new Framework\Server\Config;
		});
		App::singleton( "hynvisitor" , function( $app )
		{
				return new Framework\User\Visitor;
		});
		/*
		App::register( "hynsiteeloquent" , function( $app )
		{
				return new Framework\Database\SiteEloquent;
		});
		App::register( "hynsystemeloquent" , function( $app )
		{
				return new Framework\Database\SystemEloquent;
		});
		*/
		App::before(function($request)
		{
			Sitemanager::start();
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