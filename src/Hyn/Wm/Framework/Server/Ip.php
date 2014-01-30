<?PHP 
namespace Hyn\Wm\Framework\Server;

use Eloquent;

class Ip extends Eloquent
{
	protected $connection		= "system";
	protected $guarded		= array("*");		// prevent mass changes on all columns
	protected $softDelete		= true;			// does not erase table row on erase
	
	protected $table		= "ip";
	protected $primaryKey		= "ip";
}