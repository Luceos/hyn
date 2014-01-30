<?PHP
namespace Hyn\Wm\Framework\Right;

use Eloquent;

use Hyn\Wm\Framework\User\Base as BaseUser;

/**
*	The base class for SystemAllowed and WebsiteAllowed
*	@info provides a central interface for defining human readable rights
*/

abstract class AllowedBase extends Eloquent
{
	protected $table	= "rights";
	
	/**
	*	Checks whether current user has a certain right
	*
	*	@return boolean
	*/
	public static function has( BaseUser $user , Right $right , $item = false, $level = false )
	{
		if( $item && $level )
		{
			return (bool) static::where( "user" , "=" , $user -> getUserID() ) -> where( "right" , "=" , $right ) -> where( "item" , "=" , $item ) -> where( "level" , ">=" , $level ) -> count();
		}
		elseif( $item )
		{
			return (bool) static::where( "user" , "=" , $user -> getUserID() ) -> where( "right" , "=" , $right ) -> where( "item" , "=" , $item ) -> count();
		}
		elseif( $level )
		{
			return (bool) static::where( "user" , "=" , $user -> getUserID() ) -> where( "right" , "=" , $right ) -> where( "level" , ">=" , $level ) -> count();
		}
		return (bool) static::where( "user" , "=" , $user -> getUserID() ) -> where( "right" , "=" , $right ) -> count();
	}
	public static function give( BaseUser $user , Right $right , $item = false , $level = false )
	{
		if( static::has( $user , $right , $item , $level ))
			return true;
		
		$right		= static::__construct();
		$right -> user	= $user -> getUserID();
		$right -> right	= $right;
		if( $item )
			$right -> item	= $item;
		if( $level )
			$right -> level	= $level;
		
		return $right -> save();
		
	}
} 
