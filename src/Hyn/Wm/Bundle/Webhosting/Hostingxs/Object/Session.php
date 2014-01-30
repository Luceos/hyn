<?PHP
namespace Hyn\Wm\Bundle\Webhosting\Hostingxs\Object;

class Session
{
	const SESSION_KEY_NAME		= "hostingxs-key";
	
	private $key			= NULL;
	
	public function __construct($key=false)
	{
		// create session if not done by the native code
		if( session_id() == "" )
		{
			@session_start();
		}
		// force new session, with (perhaps) obsolete key
		if( $key && !empty($key) )
		{
			$this -> key	= $key;
		}
		// test for existing key in session variables
		// or create new session
		else
		if( !$this -> continueSession() )
		{
			$this -> key	= FALSE;
		}
	}
	public function getKey()
	{
		return $this -> key;
	}
	public function setKey($key)
	{
		return $this -> key = $key;
	}
	private function continueSession(  )
	{
		// session exists and key exists in session
		if( array_key_exists( static::SESSION_KEY_NAME , $_SESSION ))
		{
			$this -> key		= $_SESSION[ static::SESSION_KEY_NAME ];
			return true;
		}
		
		return false;
	}
	public function __destruct()
	{
		$_SESSION[ static::SESSION_KEY_NAME ]	= $this -> key;
	}
}