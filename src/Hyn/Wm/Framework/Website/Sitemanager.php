<?PHP
namespace Hyn\Wm\Framework\Website;

use BaseController;
use Request;
use Config;
use Lang;
use Illuminate\Support\ClassLoader;

class Sitemanager extends BaseController
{	

	const PATH_SERVER	= "server";
	const PATH_LOG		= "log";

	public static function start()
	{
		if( !empty(Website::Current()))
		{
			throw new \Exception( "Sitemanager already started and website loaded" );
		}
		// Load visited website
		// or fail if no results
		$website		= Website::whereHas( 'domains' , function($q)
		{
			$q -> where( "hostname" , "=" , Request::server("HTTP_HOST") );
		}) -> firstOrFail();
		
		// Forward to global scope as static entity
		Website::Current($website);
		
		// Set config for website database based on configuration
		Website::Current() -> connectDatabase	= "website";
		
		// Set website views and global views
		$viewPaths		= array(  );
		if( Website::Current() -> pathViews )
			\App::make('view.finder')->addLocation(Website::Current() -> pathViews);
		
		// setup routes
		$routes					= Route::where( "active" , "=" , true ) -> get();
		
		foreach( $routes as $route )
		{
			$route -> registerRoute();
		}
		
		// Language specifics
		Lang::addNamespace( 'hynwm' , dirname(dirname(__DIR__))."/Lang" );
		
		// Add directory to autoloading for site vendors:
		if( Website::Current() -> pathVendors )
		{
			ClassLoader::addDirectories( array( Website::Current() -> pathVendors ) );
		}
		// Add directory for language files for current website
		if( Website::Current() -> pathLang )
		{
			Lang::addNamespace( 'hynsite' , Website::Current() -> pathLang );
		}
	}
	static public function systemDefault()
	{
		return Website::where( "systemdefault" , "=" , 1 ) -> first();
	}
	static public function pathWebsite()
	{
		return Website::PATH_WEBSITE;
	}
	static public function pathServer()
	{
		return sprintf( "%s/%s" , base_path() , self::PATH_SERVER );
	}
	static public function pathLog()
	{
		return sprintf( "%s/%s" , self::pathServer() , self::PATH_LOG );
	}
	
}
