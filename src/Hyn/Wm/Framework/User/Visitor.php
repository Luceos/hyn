<?PHP
namespace Hyn\Wm\Framework\User;

# facades
use Request, Input;

use Jenssegers\Agent\Agent;;

class Visitor
{

	public function __construct()
	{
		$this -> agent		= new Agent;
	}
}