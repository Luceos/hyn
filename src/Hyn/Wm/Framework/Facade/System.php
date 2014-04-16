<?PHP
namespace Hyn\Wm\Framework\Facade;

use Illuminate\Support\Facades\Facade;

class System extends Facade
{
	protected static function getFacadeAccessor() { return 'System'; }
}
