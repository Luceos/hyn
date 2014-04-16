<?PHP
namespace Hyn\Wm\Framework\Server;

/**
*	Specifies abilities able to be run as root
*	@info can only be run in commandline
*	@info can only include methods that are safe to run from any task
*	@user root or any sudo (without pass) user
*/
class Root
{
	private $dir_sbin		= "/usr/sbin/";
	private $sbin_nginx		= "nginx";
	private $sbin_apache		= "apache2";
	
	private static $instance		= NULL;
	
	private function __construct()	{}
	private static function getInstance()
	{
		if( is_null(static::$instance))
		{
			static::$instance	= new Root;
		}
		return static::$instance;
	}
	
	/**
	*	test for both nginx and apache and restart
	*	
	*/
	public static function restartWebserver()
	{
	
		$root			= static::getInstance();
	
		$nginx			= sprintf( "%s%s", $root -> dir_sbin, $root -> sbin_nginx );
		if( is_file($nginx))
		{
			system( sprintf( "%s restart", $nginx ) );
		}
		$apache			= sprintf( "%s%s", $root -> dir_sbin, $root -> sbin_apache );
		if( is_file($apache))
		{
			system( sprintf( "%s restart", $apache ));
		}
	}
}
