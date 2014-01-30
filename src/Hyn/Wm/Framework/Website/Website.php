<?PHP 
namespace Hyn\Wm\Framework\Website;

# facades
use Eloquent, File, DB, Redirect, Schema, Config, HynServerConfig;

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
	protected $primaryKey		= "websiteID";
	
	static private $_website	= NULL;
	
	public function getRequiredTablesAttribute()
	{
		return [ "route" , "settings" , "users" ];
	}
	
	public function domains()
	{
		return $this -> hasMany(__NAMESPACE__."\Domain","websiteID","websiteID");
	}
	public function limits()
	{
		return $this -> hasMany(__NAMESPACE__."\Limits","websiteID","websiteID");
	}
	public function usage()
	{
		return $this -> hasMany(__NAMESPACE__."\Usage","websiteID","websiteID");
	}
	public function ips()
	{
		return $this -> hasMany(static::NAMESPACE_SYSTEM . "\Ip", "websiteID","websiteID");
	}
	public function primary()
	{
		return $this -> primary;
		throw new \Exception("use attribute through [getPrimaryAttribute]");
	}
	public function getPrimaryAttribute()
	{
		return Domain::where( "websiteID",$this -> websiteID)
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
		$dir		= sprintf( "%s/%s/%s" , base_path() , self::PATH_WEBSITE, $this -> websiteID );
		return File::exists( $dir ) ? $dir : FALSE;
	}
	/**
	*	Provides current diskspace
	*	@param $unit alter output to match unit < KB, MB, GB or TB >
	*	SIZE is an integer and optional unit (example: 10M is 10*1024*1024).  Units
	*		are K, M, G, T, P, E, Z, Y (powers of 1024) or KB, MB, ... (powers of 1000).
	*/
	public function diskspaceUsed( $unit="B" )
	{
		if( !$this -> path )	return 0;
		
		exec( sprintf( "du -s -B%s %s" , (strlen($unit) > 1 ? substr($unit,0,1) : $unit ) , $this -> path ) , $out , $state );
		
		if( $state == 0 )
		{
			$out		= explode( "\t" , $out[0] );
			$b		= array_shift( $out );
			return substr($b,0,-1);
		}
		return false;
	}
	/**
	*	Provides used diskspace as percentage of current limit
	*	@info if no limit or usage exists, will return 0
	*/
	public function diskspacePercentage()
	{
		if( !count($this -> limits) || !$this -> limits() -> type("disk") || !$this -> diskspaceUsed("MB") )
			return 0;
		$perc			= ceil(($this -> diskspaceUsed("MB") / $this -> limits() -> type("disk") -> value) * 100);
		
		return min( $perc , 100 );
	}
	public function getRecalculateStatisticsAttribute()
	{
		$diskusage		= Usage::create( array( 
							"websiteID" 	=> $this -> websiteID,
							"type"		=> "disk",
							"value"		=> $this -> diskspaceUsed()
						));
		
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
	public function setConnectDatabaseAttribute( $value )
	{
		Config::set("database.connections.{$value}.database"  , $this -> databaseDatabase );
		Config::set("database.connections.{$value}.username"  , $this -> databaseUsername );
		Config::set("database.connections.{$value}.password"  , $this -> databasePassword );
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
		$domain -> websiteID	= $this -> websiteID;
		$domain -> primary	= (!count($this -> domains));
		$domain -> save();
		return $domain;
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
			\Log::warning("Website for {$hostname} already exists, continueing to test folder and database existence" );
			$website		= Website::where( "websiteID" , "=" , $domain -> websiteID ) -> first();
		}
		else
		{
			
			$website		= new Website;
			$website -> save();
			
			$domain			= $website -> addDomain($hostname);
		}
		
		// set up folders
		$baseDir	= sprintf( "%s/%s/%s" , base_path() , self::PATH_WEBSITE, $website -> websiteID );
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
		$database		= $website -> databaseDatabase;
		$password		= $website -> databasePassword;
		$username		= $website -> databaseUsername;
		// next step would be to setup database and create settings for database
		DB::connection("system") -> transaction(function() use ($hostname,$website,$database,$password,$username)
		{
			// add table if not exists
			DB::connection("system") -> statement( "CREATE DATABASE IF NOT EXISTS `{$database}`" );			
			// creates user if not exists
			DB::connection("system") -> statement( "GRANT ALL PRIVILEGES ON `{$database}`.* TO '{$username}'@'localhost' IDENTIFIED BY '{$password}'" );
		});
		// set database connection ;)
		Config::set('database.connections.website-modify.database',$database);
		Config::set('database.connections.website-modify.username',$username);
		Config::set('database.connections.website-modify.password',$password);
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
		
		return Redirect::route("manage:website" , $website -> websiteID);
	}
	public function writeServerConfig()
	{
		return HynServerConfig::write($this);
	}
} 
