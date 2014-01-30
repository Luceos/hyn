<?PHP
namespace Hyn\Wm\Bundle\Webhosting\Hostingxs;

use Guzzle\Http\Client as Guzzle;

class Client
{
	const NAME			= "HostingXS/WS/Client";
	const HEADER_PREFIX		= "HXS/WS/C-";
	const VERSION			= "3.0.0";

	const API_URI			= "https://controlpanel.hostingxs.nl/api/";
	
	const REQ_AUTH			= "auth";
	
	const REQ_DOMAIN		= "domain";
	const REQ_PRODUCT		= "product";
	const REQ_ACCOUNT		= "account";
	
	const OBJ_SESSION		= __NAMESPACE__ . '\Object\Session';
	
	const OBJ_DOMAIN		= __NAMESPACE__ . '\Object\Domain';
	const OBJ_PRODUCT		= __NAMESPACE__ . '\Object\Product';
	const OBJ_CUSTOMER		= __NAMESPACE__ . '\Object\Customer';
	const OBJ_VIRTUALMACHINE	= __NAMESPACE__ . '\Object\VirtualMachine';
	
	const MODE_GET			= "get";
	const MODE_PUT			= "put";
	const MODE_POST			= "post";
	const MODE_DELETE		= "delete";
	
	
	private $connection		= NULL;
	
	private $mode			= "get";
	
	private $headers		= array();
	/**
	*	
	*	@param ?$key	optional authentication key for the current session; this is a manual param, will default to session stored information
	*/
	public function __construct( $username, $password, $key=NULL)
	{
		$this -> client		= new Guzzle( static::API_URI );
		
		$this -> client -> setUserAgent( sprintf( "%s:%s" , static::NAME , static::VERSION ) );
		
		// set some default headers:
		$this -> client -> setDefaultOption( "auth" , array( $username , $password , "Basic" ) );
		$this -> client -> setDefaultOption( "headers/X-Forwarded-For" , array_key_exists( "REMOTE_ADDR" , $_SERVER ) ? $_SERVER['REMOTE_ADDR'] : "0.0.0.0" );
		$this -> client -> setDefaultOption( "headers/". static::HEADER_PREFIX . "Version" 		, static::VERSION );
		$this -> client -> setDefaultOption( "headers/". static::HEADER_PREFIX . "Client-IP" 	, $_SERVER['SERVER_ADDR'] );
		$this -> client -> setDefaultOption( "headers/". static::HEADER_PREFIX . "Client-Hostname" 	, $_SERVER['SERVER_NAME'] );
		
		// restore an existing session or force it based on a manual $key
		$this -> session	= new static::OBJ_SESSION( $key );
		// preset default mode GET
		$this -> setMode( static::MODE_GET );
		if( !$this -> session -> getKey() )
		{
			$this -> session -> setKey( $this -> authenticate() );
		}
	}
	private function authenticate()
	{
		$request		= $this -> client -> get( static::REQ_AUTH );
		$response		= $request -> send();
		\dd($response);
	}
}
