<?PHP
namespace Hyn\Wm\Framework\Website;

use Eloquent;

class Setting extends Eloquent
{
	protected $connection		= "website";
	protected $table		= "settings";
	
	public function scopeName( $query , $name=NULL )
	{
		return $query -> where( "name" , "=" , $name );
	}
}
