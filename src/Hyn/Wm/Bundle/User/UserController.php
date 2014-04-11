<?PHP
namespace Hyn\Wm\Bundle\User;

# facades
use BaseController, Input, Auth, View, Redirect, Lang, App;



class UserController extends BaseController
{
	public function login()
	{
		if( Input::get('user-login'))
		{
			$userdata	= array(
				"username"	=> Input::get("username"),
				"password"	=> Input::get("password")
			);
			if( Auth::attempt( $userdata ))
			{
				return Redirect::intended();
			}
			else
			{
				return Redirect::to("login") -> with( "loginMessage" , _("Login failed") );
			}
		}
		$bodyclass		= "focusedform";
		$title			= Lang::get("login.title");
		return View::make( "user.login" , compact("bodyclass","title") );
	}
}
