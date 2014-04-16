<?PHP
namespace Hyn\Wm\Framework\User;

use Illuminate\Auth\UserProviderInterface;
use Illuminate\Auth\GenericUser;
use Illuminate\Auth\UserInterface;

class UserProvider implements UserProviderInterface
{
	
	public function __construct(  )
	{
	}
	public function retrieveByID( $identifier )
	{
		return Base::ByUserID( $identifier );
	}
	public function retrieveByCredentials( array $credentials )
	{
		return Base::ByUserID( $credentials['username'] );
	}
	public function validateCredentials( UserInterface $user, array $credentials )
	{
		// user disabled
		if( !empty( $user -> deleted_at ))
			return false;
		return $user -> validateCredentials( $credentials['username'] , $credentials['password'] );
	}
	public function updateRememberToken(UserInterface $user, $token)
	{
		
	}
	public function retrieveByToken($identifier, $token)
	{
	
	}
}
