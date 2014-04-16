<?PHP
namespace Hyn\Wm\Framework\User;

use Hyn\Wm\Bundle\System\ManagementController;
# facades
use BaseController, Input, Auth, View, Redirect, Lang, App, HynVisitor;



class LoginController extends BaseController
{
	public function __construct()
	{
		$mc		= new ManagementController;
		App::make('view.finder')->addNamespace("hynwmlogin" , __DIR__ . "/views" );
		Lang::addNamespace( 'hynwmlogin' , __DIR__."/lang" );
	}
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
				return Redirect::intended('manage');
			}
			else
			{
				return Redirect::to("login") -> with( "loginMessage" , _("Login failed") );
			}
		}
		$bodyclass		= "focusedform";
		$title			= Lang::get("Login.title");
		return View::make( "hynwmlogin::user-login" , compact("bodyclass","title") );
	}
}
