<?PHP
namespace Hyn\Wm\Framework\User;

# facades
use Request, Input, Auth, App;

# user agent by Jens Segers
use Jenssegers\Agent\Agent;
# IoC like model
use Jenssegers\Model\Model;

class Visitor extends Model
{

	public function __construct()
	{
		$this -> agent		= new Agent;
		if( !in_array( App::getLocale() , $this -> agent -> languages() ))
		{
			App::setLocale( array_first( $this -> agent -> languages() ));
		}
	}
	public function getUserAttribute()
	{
		return Auth::check() ? Auth::user() : NULL;
	}
}