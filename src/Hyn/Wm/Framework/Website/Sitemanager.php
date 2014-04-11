<?PHP
namespace Hyn\Wm\Framework\Website;

use App, BaseController, Request, Config, Lang, Redirect, Log;
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
		
		$domain			= Domain::where( "hostname" , Request::server("HTTP_HOST"))
					-> orWhere( "hostname" , str_replace( "." . Sitemanager::systemDefault() -> primary -> hostname , NULL , Request::server("HTTP_HOST") ))
					-> first();
		if( !$domain )
		{
			Log::error( "Domain " . Request::server("HTTP_HOST") . " not available on Hyn, redirected to a default page" );
			
			return Redirect::away( "http://hostingxs.com.hyn.me/domain/". Request::server("HTTP_HOST"), 302);
		}
		
		$website		= Website::find( $domain -> websiteID );
		
		// redirect to primary
		if( $domain -> redirectPrimary && $website -> primary -> systemResolving && $website -> primary -> active )
		{
			return Redirect::away( sprintf( "http://%s" , $website -> primary -> hostname )) -> with( "SystemRedirect" , $domain -> hostname );
		}
		
		// set current domain
		$website -> domain	= $domain;
		
		// Forward to global scope as static entity
		Website::Current($website);
		
		// Set config for website database based on configuration
		Website::Current() -> connectDatabase	= "website";
		
		// Set website views and global views
		$viewPaths		= array(  );
		if( Website::Current() -> pathViews )
			App::make('view.finder')->addLocation(Website::Current() -> pathViews);
		
		// setup routes from local website
		$routes					= Route::where( "active" , "=" , true ) -> get();
		foreach( $routes as $route )
		{
			$route -> registerRoute();
		}
		
		// Language specifics
		Lang::addNamespace( 'hynwm' , dirname(dirname(__DIR__))."/Lang" );
		
		// Add directory for language files for current website
		// [BUG] this is obsolete, we now overrule * namespacing see hyn\wm\framework\translate
		if( Website::Current() -> pathLang )
		{
			Lang::addNamespace( 'hynsite' 	, Website::Current() -> pathLang );
		}
		
		// if default lang in site, if setting with defaultlang exists
		if( Setting::name( "defaultlang" ) -> count())
		{
			App::setLocale( Setting::name( "defaultlang" ) -> value );
		}
		// Add directory to autoloading for site vendors:
		if( Website::Current() -> pathVendors )
		{
			ClassLoader::addDirectories( array( Website::Current() -> pathVendors ) );
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
