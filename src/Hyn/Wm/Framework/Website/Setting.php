<?PHP
namespace Hyn\Wm\Framework\Website;

use Eloquent;

class Setting extends Eloquent
{
	protected $connection		= "website";
	protected $table		= "settings";
	
	public function scopeName( $query , $name=NULL )
	{
		return $query -> where( "name" , "=" , $name ) -> first();
	}
	public function scopeNameValue( $query, $name )
	{
		$setting		= $query -> where( "name" , "=" , $name ) -> first();
		if( $setting -> count() )
		{
			return $setting -> value;
		}
		return NULL;
	}
}
