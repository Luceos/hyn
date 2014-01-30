<?PHP
namespace Hyn\Wm\Framework\Database;

use Eloquent;

class SystemEloquent extends Eloquent
{
	protected $connection		= "system";
}
