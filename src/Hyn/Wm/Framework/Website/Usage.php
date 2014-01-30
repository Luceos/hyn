<?PHP
namespace Hyn\Wm\Framework\Website;

use Eloquent;

class Usage extends Eloquent
{
	protected $table		= "usage";
	protected $connection		= "system";
	
	protected $fillable		= array( "websiteID","type","value");
	
	public function scopeLastByType( $query , $type )
	{
		return $query -> where( "type" , "=" , $type ) -> orderBy( "created_at","desc") -> first();
	}
}
