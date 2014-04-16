<?PHP 
namespace Hyn\Wm\Framework\Website;

use Hyn\Wm\Framework\Server\Ip;

use Eloquent;

class Domain extends Eloquent
{
	protected $connection		= "system";
	protected $guarded		= array("*");		// prevent mass changes on all columns
	protected $softDelete		= true;			// does not erase table row on erase
	protected $table		= "domain";
	protected $primaryKey		= "hostname";
	
	public function website()
	{
		return $this -> belongsTo( __NAMESPACE__."\Website" , "websiteID" , "websiteID" );
	}
	/**
	*	Loads resolving IP of hostname
	*	@return <string> IP
	*/
	public function getResolvingIpAttribute()
	{
		return exec( "host {$this->hostname} | head -1 | awk '{ print $4 }'" );
	}
	/**
	*	Whether domain is connected to the system
	*	@return <bool>
	*/
	public function getResolvingSystemAttribute()
	{
		$ip	= $this -> resolvingIp;
		return (!empty($ip) && Ip::find($ip));
	}
	/**
	*	Whether domain is connected to the system and resolves to the default IP
	*	@return <bool>
	*/
	public function getResolvingSystemDefaultAttribute()
	{
		$ip	= $this -> resolvingIp;
		return (!empty($ip) && ($found = Ip::find($ip)) && $found -> systemdefault);
	}
}