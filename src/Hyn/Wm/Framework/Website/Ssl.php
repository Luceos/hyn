<?PHP
namespace Hyn\Wm\Framework\Website;

class Ssl
{
	protected $connection		= "system";
	protected $guarded		= array("*");		// prevent mass changes on all columns
	protected $softDelete		= true;			// does not erase table row on erase
	protected $table		= "ssl";
	
	/**
	*	Verifies that both files and database settings are valid
	*
	*/
	public function getActiveAttribute()
	{
		
	}
	/**
	*	Verifies whether files are written to disk
	*
	*/
	public function getInstalledAttribute()
	{
		
	}
}