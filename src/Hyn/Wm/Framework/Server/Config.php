<?php 
namespace Hyn\Wm\Framework\Server;


use Hyn\Wm\Framework\Website\Sitemanager;
use Hyn\Wm\Framework\Website\Website;

# facades
use Request, View, App, File;

class Config 
{
	public $webserver		= NULL;
	public $webserver_version	= NULL;
	public $php_version		= NULL;
	/**
	*	Identifies current system
	*
	*/
	public function __construct()
	{
		if( Request::server("SERVER_SOFTWARE") )
			list( 
				$this -> webserver, 
				$this -> webserver_version 
			)			= explode("/", strtolower(Request::server("SERVER_SOFTWARE")));
		// fallback
		if( empty( $this -> webserver ) && php_sapi_name() == "apache" )
			$this -> webserver	= "apache";
		
		$this -> php_version		= PHP_VERSION;
	}
	
	private function webserverMap()
	{
		return array(
			"nginx"		=> array(
				"php"		=> "fpm",
				"config"	=> true,
				"support"	=> "1.5",
			),
			"apache"	=> array(
				"php"		=> false,
				"config"	=> true,
				"support"	=> false,
			),
		);
	}
	public function write( Website $website )
	{
		App::make('view.finder')->addNamespace("hynwmserverconfig" , __DIR__ . "/views" );
		# [todo] based on apache or nginx or other webserver?
		$domains			= array();
		$ssldomains			= array();
		$systemSubDomains		= array();
		$imagedomains			= array();
		if( !$website -> systemdefault )
		{
			$systemSubDomains[]		= sprintf( ".%s.%s" , $website -> primary -> hostname , SiteManager::systemDefault() -> primary -> hostname );
			$imagedomains[]			= sprintf( "image.%s.%s" , $website -> primary -> hostname , SiteManager::systemDefault() -> primary -> hostname );
		}
		else
		{
			$ssldomains[]		= sprintf( ".%s", $website -> primary -> hostname );
		}
		foreach( $website -> domains as $domain )
		{
			$domains[]		= sprintf( ".%s" , $domain -> hostname );
			$imagedomains[]		= sprintf( "image.%s" , $domain -> hostname );
		}
		
		$domains			= array_merge( $systemSubDomains , $domains );
		$ssldomains			= array_merge( $systemSubDomains , $ssldomains );
		$file				= sprintf( "%s/webserver/nginx/sites/%d-%s" , SiteManager::pathServer() , $website -> websiteID , $website -> primary -> hostname );
		$nginx				= File::put( $file , View::make( "hynwmserverconfig::nginx" , compact( "website" , "domains" , "imagedomains" , "ssldomains" ) ) -> render() );
		
		$file				= sprintf( "%s/php/fpm/sites/%d-%s" , SiteManager::pathServer() , $website -> websiteID , $website -> primary -> hostname );
		$fpm				= File::put( $file , View::make( "hynwmserverconfig::php-fpm" , compact( "website" )) -> render() );
		
		return ( $nginx > 0 && $fpm > 0 );
	}
}
