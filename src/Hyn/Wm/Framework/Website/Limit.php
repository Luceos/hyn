<?PHP 
namespace Hyn\Wm\Framework\Website;

use Eloquent;

class Limit extends Eloquent
{
	protected $connection		= "system";
	protected $guarded		= array("*");		// prevent mass changes on all columns
	protected $table		= "limits";
	protected $primaryKey		= "websiteID";
	
	public function website()
	{
		return $this -> belongsTo( __NAMESPACE__."\Website" , "websiteID" , "websiteID" );
	}
	public function scopeType( $query , $type )
	{
		return $query -> where( "type" , $type) -> first();
	}
}
