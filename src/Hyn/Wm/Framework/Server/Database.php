<?PHP 
namespace Hyn\Wm\Framework\Server;

use Eloquent, System, DB;

use Hyn\Wm\Framework\Website\Website;
use Hyn\Wm\Framework\Website\Domain;
use Hyn\Wm\Framework\Website\Usage;
use Hyn\Wm\Framework\Website\Limit;

class Database extends Eloquent
{
	protected $connection		= "system";
	protected $guarded		= array("*");		// prevent mass changes on all columns
	protected $softDelete		= true;			// does not erase table row on erase
	
	protected $primaryKey		= "name";
	
	public function getSizeAttribute()
	{
		$size			= DB::select( DB::raw( "SELECT table_schema as `name`, sum( data_length + index_length ) as `used`, sum( data_free ) as `free`
					FROM information_schema.TABLES WHERE table_schema = '{$this->name}' LIMIT 1;"));
		if( count($size) )
		{
			return $size[0] -> used;
		}
		return NULL;
	}
	
	/**
	*	Reindexes databases, adds them to database table and checks size and limit
	*
	*
	*/
	public static function Index()
	{
		$sizes			= System::databaseSizes();
		foreach( $sizes as $size )
		{
			$database			= Database::find( $size -> name );
			if( !$database )
			{
				$database		= new Database;
				$database -> name	= $size -> name;
				$database -> save();
			}
			
			$domain		= Domain::where( "hostname" , $size -> name ) -> first();
			
			if( $domain )
			{
				$database -> websiteID	= $domain -> websiteID;
				$database -> save();
				
				$usage			= new Usage;
				$usage -> websiteID	= $domain -> websiteID;
				$usage -> type		= "db";
				$usage -> unit		= "B";
				$usage -> value		= $size -> used;
				$usage -> save();
				
				$limit			= Limit::where("websiteID",$domain -> websiteID) -> where("type","db") -> first();
				if( !$limit )
				{
					$limit			= new Limit;
					$limit -> websiteID	= $domain -> websiteID;
					$limit -> type		= "db";
					$limit -> value		= ($size -> free + $size -> used );
					$limit -> unit		= "B";
					$limit -> save();
				}
			}
			unset($domain,$database);
		}
	}
}