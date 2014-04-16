<?PHP
namespace Hyn\Wm\Framework\User;

# facades
use Eloquent, Hash;
use Illuminate\Auth\UserInterface;

use Hyn\Wm\Framework\Website\Website;

use Hyn\Wm\Framework\Right\AllowedSystem;
use Hyn\Wm\Framework\Right\AllowedWebsite;
use Hyn\Wm\Framework\Right\Right;

use Carbon\Carbon;

class Base extends Eloquent implements UserInterface
{
	protected $table	= "users";
	protected $softDelete	= true;
	
	public function getUserIDAttribute()
	{
		return sprintf( "%s:%s:%s" , $this -> connection , $this -> isWebsite ? Website::Current() -> websiteID : 0 , $this -> id );
	}
	public function getIsWebsiteAttribute()
	{
		return ($this -> connection == "website");
	}
	public function getIsSystemAttribute()
	{
		return ($this -> connection == "system");
	}
	/**
	*	Allows use of SiteUser::UserID( $userID );
	*/
	public static function ByUserID( $userID )
	{
		// find by e-mail address
		if(strstr($userID,"@"))
		{
			if( ($u = SystemUser::where( "email",$userID) -> first()))
			{
				return $u;
			}
			if( ($u = WebsiteUser::where( "email",$userID) -> first()))
			{
				return $u;
			}
		}
		// find by username
		if(!preg_match( "/(system|website):([0-9]+):([0-9]+)/" , $userID , $m ))
		{
			if( ($u = SystemUser::where( "username",$userID) -> first()))
			{
				return $u;
			}
			if( ($u = WebsiteUser::where( "username",$userID) -> first()))
			{
				return $u;
			}
		} else
		// find by unique ID
		{
			if( $m[1] == "system" && ($u = SystemUser::where( "id",$m[3]) -> first()))
			{
				return $u;
			}
			else

			if( $m[1] == "website" && $m[2] == Website::Current() -> getKey() && ($u = WebsiteUser::where( "id",$m[3]) -> first()))
			{
				return $u;
			}
		}
		return false;
	}
	public function getReminderEmail()
	{
		return $this -> email;
	}
	public function getAuthPassword()
	{
		return $this -> password;
	}
	public function getAuthIdentifier()
	{
		return $this -> userID;
	}
	public function validateCredentials( $un , $pw )
	{
		return Hash::check( $pw , $this -> password );
	}
	public function rights()
	{
		if( $this -> isSystem )
			return AllowedSystem::where("user","=",$this -> userID ) -> get();
		if( $this -> isWebsite )
			return AllowedWebsite::where("user","=",$this -> userID ) -> get();
		return array();
	}
	public function getSystemAdminAttribute()
	{
		return AllowedSystem::has( $this, new Right( Right::Admin ) );
	}
	public function getAgeAttribute()
	{
		return Carbon::parse($this -> created_at) -> diffForHumans();
	}
	public function gravatarUri( $size = 100 )
	{
		return $this -> email ? sprintf( "//gravatar.com/avatar/%s.png?size={$size}", md5( $this -> email ) ) : NULL;
	}
	public function getRememberToken()
	{
	
	}
	public function setRememberToken($token)
	{
	
	}
	public function getRememberTokenName()
	{
	}
}