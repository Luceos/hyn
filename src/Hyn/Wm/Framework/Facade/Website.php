<?PHP
namespace Hyn\Wm\Framework\Facade;

use Illuminate\Support\Facades\Facade;

class Website extends Facade
{
	protected static function getFacadeAccessor() { return 'Website'; }
}
