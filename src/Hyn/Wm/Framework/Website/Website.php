<?PHP 
namespace Hyn\Wm\Framework\Website;

# facades
use Eloquent, File, DB, Redirect, Schema, Config, ServerConfig, Log;

use Hyn\Wm\Framework\Server\Disk;
use Hyn\Wm\Framework\Server\Database;

class Website extends Eloquent
{

	const PATH_WEBSITE		= "website";
	const PATH_WEBSITE_VIEWS	= "views";
	const PATH_WEBSITE_MEDIA	= "media";
	const PATH_WEBSITE_CACHE	= "cache";
	const PATH_WEBSITE_VENDORS	= "vendors";
	const PATH_WEBSITE_LANG		= "lang";
	
	const NAMESPACE_SYSTEM		= "Hyn\Wm\Framework\System";

	protected $connection		= "system";
	protected $guarded		= array("*");		// prevent mass changes on all columns
	protected $softDelete		= true;			// does not erase table row on erase
	protected $table		= "website";
	
	static private $_website	= NULL;
	
	public function getRequiredTablesAttribute()
	{
		return [ "route" , "settings" , "users" ];
	}
	
	public function domains()
	{
		return $this -> hasMany(__NAMESPACE__."\Domain","websiteID","id");
	}
	public function limits()
	{
		return $this -> hasMany(__NAMESPACE__."\Limit","websiteID","id");
	}
	public function usage()
	{
		return $this -> hasMany(__NAMESPACE__."\Usage","websiteID","id");
	}
	public function ips()
	{
		return $this -> hasMany(static::NAMESPACE_SYSTEM . "\Ip", "websiteID","id");
	}
	public function getPrimaryAttribute()
	{
		return Domain::where( "websiteID",$this -> id)
				-> where( "primary" , true )
				-> first();
	}
	/**
	*	Magic getter AND setter for current website
	*	@param $website if set will change current website
	*	@return \Hyn\Wm\Framework\Website\Website
	*/
	static public function Current( $website = FALSE )
	{
		if( $website && $website instanceof self )
		{
			static::$_website	= $website;
		}
		else
		if( $website && !($website instanceof self ))
		{
			throw new \Exception( "Current website can only be set to ".__CLASS__." objects" );
		}
		return static::$_website;
	}
	/**
	*	Returns website path for vendors or fails if it does not exist
	*	@return path or false
	*/
	public function getPathLangAttribute()
	{
		$dir		= sprintf( "%s/%s" , $this -> path , self::PATH_WEBSITE_LANG );
		return $this -> path && File::exists( $dir ) ? $dir : FALSE;
	}
	/**
	*	Returns website path for vendors or fails if it does not exist
	*	@return path or false
	*/
	public function getPathVendorsAttribute()
	{
		$dir		= sprintf( "%s/%s" , $this -> path , self::PATH_WEBSITE_VENDORS );
		return $this -> path && File::exists( $dir ) ? $dir : FALSE;
	}
	/**
	*	Returns website path for views or fails if it does not exist
	*	@return path or false
	*/
	public function getPathViewsAttribute()
	{
		$dir		= sprintf( "%s/%s" , $this -> path , self::PATH_WEBSITE_VIEWS );
		return $this -> path && File::exists( $dir ) ? $dir : FALSE;
	}
	/**
	*	Returns website path for media or fails if it does not exist
	*	@return path or false
	*/
	public function getPathMediaAttribute()
	{
		$dir		= sprintf( "%s/%s" , $this -> path , self::PATH_WEBSITE_MEDIA );
		return $this -> path && File::exists( $dir ) ? $dir : FALSE;
	}
	/**
	*	Returns public url to media
	*	@return <path>
	*/
	public function getMediaURLAttribute()
	{
		if( $this -> pathMedia )
		{
			return sprintf( "//image.%s/%s/" , $this -> domain -> hostname , self::PATH_WEBSITE_MEDIA );
		}
		return false;
	}
	/**
	*	Returns website path for cache or fails if it does not exist
	*	@return path or false
	*/
	public function getPathCacheAttribute()
	{
		$dir		= sprintf( "%s/%s" , $this -> path , self::PATH_WEBSITE_CACHE );
		return $this -> path && File::exists( $dir ) ? $dir : FALSE;
	}
	/**
	*	Returns website path or fails if it does not exist
	*	@return path or false
	*/
	public function getPathAttribute()
	{
		$dir		= sprintf( "%s/%s/%s" , base_path() , self::PATH_WEBSITE, $this -> id );
		return File::exists( $dir ) ? $dir : FALSE;
	}
	/**
	*	Provides current diskspace
	*	@param $unit alter output to match unit < KB, MB, GB or TB >
	*	SIZE is an integer and optional unit (example: 10M is 10*1024*1024).  Units
	*		are K, M, G, T, P, E, Z, Y (powers of 1024) or KB, MB, ... (powers of 1000).
	*/
	public function diskspaceUsed( $unit = "B" )
	{
		$usage		= Usage::where( "websiteID", $this -> id ) -> lastByType( "disk" );
		return $usage -> count() ? Disk::toUnit($usage -> value, $usage -> unit, $unit ) : null;
	}
	/**
	*	Provides used diskspace as percentage of current limit
	*	@info if no limit or usage exists, will return 0
	*/
	public function diskspacePercentage()
	{
		if( !count($this -> limits) || !$this -> limits() -> type("disk") -> count() || !$this -> diskspaceUsed("MB") )
			return 0;
		$perc			= ceil(($this -> diskspaceUsed("MB") / $this -> limits() -> type("disk") -> value) * 100);
		
		return min( $perc , 100 );
	}
	public function getRecalculateStatisticsAttribute()
	{
		if( Disk::used( $this -> path ) )
		{
			Usage::create( array( 
							"websiteID" 	=> $this -> id,
							"type"		=> "disk",
							"unit"		=> "B",
							"value"		=> Disk::used( $this -> path )
						));
		}
		else
		{
			Log::warning( sprintf("Cannot recalculate disk space usage on website %d:%s" , $this -> id, $this -> primary -> hostname ));
		}
		
		$database			= Database::where( "websiteID", $this -> id ) -> first();
		if( $database )
		{
			Usage::create( array( 
							"websiteID" 	=> $this -> id,
							"type"		=> "db",
							"unit"		=> "B",
							"value"		=> $database -> size
						));
		}
		else
		{
			Log::warning( sprintf("Cannot recalculate database usage on website %d:%s" , $this -> id, $this -> primary -> hostname ));

		}
		
		return TRUE;
	}
	/**
	*	Pre-formats databasename for website connection
	*	@return <string> databasename
	*/
	public function getDatabaseDatabaseAttribute()
	{
		return $this -> primary -> hostname;
	}
	/**
	*	Pre-formats database password
	*	@return <string> database password
	*/
	public function getDatabasePasswordAttribute()
	{
		return md5($this -> primary -> hostname.Config::get('app.key'));
	}
	/**
	*	Pre-formats database username
	*	@return <string> database username
	*/
	public function getDatabaseUsernameAttribute()
	{
		return substr($this -> primary -> hostname,0,16);
	}
	/**
	*	Database driver
	*	@return <string>
	*	@default mariadb
	*/
	public function getDatabaseDriverAttribute()
	{
		return "mysql";
	}
	/**
	*	Database hostname
	*	@return <string>
	*	@default localhost
	*/
	public function getDatabaseHostAttribute()
	{
		return "localhost";
	}
	/**
	*	Database collation
	*	@return <string>
	*	@default utf8_unicode_ci
	*/
	public function getDatabaseCollationAttribute()
	{
		return "utf8_unicode_ci";
	}
	/**
	*	Database charset
	*	@return <string>
	*	@default utf8
	*/
	public function getDatabaseCharsetAttribute()
	{
		return "utf8";
	}
	public function setConnectDatabaseAttribute( $value )
	{
		Config::set("database.connections.{$value}.database"  	, $this -> databaseDatabase );
		Config::set("database.connections.{$value}.username"  	, $this -> databaseUsername );
		Config::set("database.connections.{$value}.password"  	, $this -> databasePassword );
		Config::set("database.connections.{$value}.driver"  	, $this -> databaseDriver );
		Config::set("database.connections.{$value}.host"  	, $this -> databaseHost );
		Config::set("database.connections.{$value}.collation"  	, $this -> databaseCollation );
		Config::set("database.connections.{$value}.charset"  	, $this -> databaseCharset );
	}
	/**
	*	Adds a domain to this website
	*	@param <string> Hostname of the domain
	*	@return \Hyn\Wm\Framework\Website\Domain
	*/
	public function addDomain( $hostname )
	{
		$domain			= new Domain;
		$domain -> hostname	= $hostname;
		$domain -> websiteID	= $this -> id;
		$domain -> primary	= (!count($this -> domains));
		$domain -> save();
		return $domain;
	}
	/**
	*	Calculate size of current website database
	*	@return <integer> bytes
	*/
	public function getDatabaseSizeAttribute()
	{
		$tables			= DB::select(DB::raw( "SHOW TABLE STATUS"));
		$size			= 0;
		foreach( $tables as $table )
		{
			$size		=+ $table -> Data_length + $table -> Index_length;
		}
		return $size;
		
	}
	/**
	*	Generates a complete new website
	*	@param $hostname	valid domain name used as primary domain for website
	*	@return \Redirect
	*/
	public static function autoCreate( $hostname )
	{
		$domain			= Domain::where( "hostname" , "=" , $hostname ) -> first();
		
		if( $domain )
		{
			Log::warning("Website for {$hostname} already exists, continueing to test folder and database existence" );

			$website		= Website::where( "id" , "=" , $domain -> websiteID ) -> first();

		}
		else
		{
			
			$website		= new Website;
			$website -> save();
			
			$domain			= $website -> addDomain($hostname);
		}
		
		// set up folders
		$baseDir	= sprintf( "%s/%s/%s" , base_path() , self::PATH_WEBSITE, $website -> id );
		$dir		= sprintf( "%s/%s" , $baseDir , self::PATH_WEBSITE_MEDIA );
		if( !File::exists( $dir ))
		{
			File::makeDirectory( $dir , 0777 , true );
		}
		$dir		= sprintf( "%s/%s" , $baseDir , self::PATH_WEBSITE_VIEWS );
		if( !File::exists( $dir ))
		{
			File::makeDirectory( $dir , 0777 , true );
		}
		$dir		= sprintf( "%s/%s" , $baseDir , self::PATH_WEBSITE_VENDORS );
		if( !File::exists( $dir ))
		{
			File::makeDirectory( $dir , 0777 , true );
		}
		$dir		= sprintf( "%s/%s" , $baseDir , self::PATH_WEBSITE_LANG );
		if( !File::exists( $dir ))
		{
			File::makeDirectory( $dir , 0777 , true );
		}
		$dir		= sprintf( "%s/%s" , $baseDir , self::PATH_WEBSITE_CACHE );
		if( !File::exists( $dir ))
		{
			File::makeDirectory( $dir , 0777 , true );
		}
		$database		= $website -> databaseDatabase;
		$password		= $website -> databasePassword;
		$username		= $website -> databaseUsername;
		// next step would be to setup database and create settings for database
		DB::connection("system") -> transaction(function() use ( $database , $password , $username )
		{
			// add table if not exists
			DB::connection("system") -> statement( "CREATE DATABASE IF NOT EXISTS `{$database}`" );			
			// creates user if not exists
			DB::connection("system") -> statement( "GRANT ALL PRIVILEGES ON `{$database}`.* TO '{$username}'@'localhost' IDENTIFIED BY '{$password}'" );
		});
		
		// set up connection
		$website -> connectDatabase = "website-modify";
		
		// create schema's
		if( !Schema::connection("website-modify") -> hasTable('route') )
		{
			Schema::connection("website-modify") -> create("route",function($table)
			{
				$table -> increments('id');
				$table -> string('path');
				$table -> string('method') -> nullable();
				$table -> boolean('active') -> default(true);
				$table -> string('extension');
				$table -> string('name');
			});
		}
		if( !Schema::connection("website-modify") -> hasTable('settings') )
		{
			Schema::connection("website-modify") -> create("settings",function($table)
			{
				$table -> string('name');
				$table -> text('value') -> nullable();
			});
		}
		if( !Schema::connection("website-modify") -> hasTable('users') )
		{
			Schema::connection("website-modify") -> create("users",function($table)
			{
				$table->bigIncrements('id');
				$table->string("username");
				$table->string("email");
				$table->string("password");
				$table->softDeletes();
				$table->timestamps();
			});
		}
		
		$website -> writeServerConfig();
		
		return Redirect::route("manage:website" , $website -> id);
	}
	public function writeServerConfig()
	{
		\Queue::push( "Hyn\Wm\Framework\Queue\WebsiteJob", ['website_id' => $this -> id, 'write_config' => true] );
		# now add a task to update configuration files
		#return ServerConfig::writeAndRestart($this);
	}
} 
