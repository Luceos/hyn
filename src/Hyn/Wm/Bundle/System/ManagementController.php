<?PHP
namespace Hyn\Wm\Bundle\System;

use BaseController;

use Hyn\Wm\Framework\Website;
use Hyn\Wm\Framework\User;
# facades
use Validator, View, Auth, Lang, App, Input, Redirect, DB;

use Hyn\Wm\Framework\Right;

class ManagementController extends BaseController
{

	protected $layout	= "hynwmanage::index";
	public function __construct()
	{
		$this -> beforeFilter("auth");
		$config	= new \Hyn\Wm\Framework\Server\Config;
		App::make('view.finder')->addNamespace("hynwmanage" , __DIR__ . "/views" );
		Lang::addNamespace( 'hynwmanage' , __DIR__."/lang" );
	}

	public function show()
	{
		$this -> layout -> title	= _("Management");
		$websites			= Website\Website::count();
		$domains			= Website\Domain::count();
		$systemusers			= User\SystemUser::count();
		$this -> layout -> content	= View::make("hynwmanage::dashboard",compact("websites","domains","systemusers"));
	}
	
	public function websites()
	{
		$this -> layout -> title	= _("Websites");
		if( Auth::user() -> systemAdmin && Input::get('hostname') )
		{
			$validator			= Validator::make(
				array( "hostname"	=> Input::get("hostname") ),
				array( "hostname"	=> "required|Between:6,253" )
			);
			if( $validator -> fails() )
			{
				return Redirect::route("manage:websites")->withInput(Input::get())->withErrors($validator);
			}
			else
			{
				return Website\Website::autoCreate( Input::get("hostname") );
			}
		}
		
		// set sorting direction
		$sortDirection					= Input::get("sortdir") == "desc" ? "desc" : "asc";
		
		
		switch( Input::get("sort","hostname") )
		{
			// [TODO] does not work
			case "domains":
				$websites	= Website\Website::join( 'domain' , 'website.websiteID' , '=' , 'domain.websiteID' )
							-> select( DB::raw('website.*, domain.*, COUNT(domain.hostname) as domaincount' ))
							-> orderBy( 'domaincount' , $sortDirection) 
							-> paginate(10);
				break;
			case "hostname":
			default:
				$websites	= Website\Website::join( 'domain' , 'website.websiteID' , '=' , 'domain.websiteID' )
							-> where( "domain.primary", 1 ) 
							-> orderBy( 'hostname' , $sortDirection) 
							-> paginate(10);
		}
		$this -> layout -> content	= View::make("hynwmanage::websites",compact("websites","domains"));
	}
	
	public function website($websiteID)
	{
		$website			= Website\Website::where("websiteID","=",$websiteID) -> firstOrFail();
		// connect to tertiairy db connection
		if( Auth::user() -> systemAdmin )
		{
			$website -> connectDatabase = "website-modify";
		}
		if( Auth::user() -> systemAdmin && Input::get('recalculateStatistics'))
		{
			$website -> recalculateStatistics;
			return Redirect::route("manage:website",$website -> websiteID);
		}
		if( Auth::user() -> systemAdmin && Input::get('writeServerConfig'))
		{
			$website -> writeServerConfig();
			return Redirect::route("manage:website",$website -> websiteID);
		}
		if(  Auth::user() -> systemAdmin && Input::get('delete') && Input::get('hostname') )
		{
			$domain				= Website\Domain::findOrFail(Input::get("hostname"));
			$domain -> delete();
			
			return Redirect::route("manage:website",$website -> websiteID);
		}
		else
		if(  Auth::user() -> systemAdmin && Input::get('add') && Input::get('hostname') )
		{
			$validator			= Validator::make(
				array( "hostname"	=> Input::get("hostname") ),
				array( "hostname"	=> "required|Between:6,253" )
			);
			if( $validator -> fails() )
			{
				return Redirect::route("manage:website",$website -> websiteID)->withInput()->withErrors($validator);
			}
			else
			{
				$domain				= $website -> addDomain( Input::get('hostname') );
				$domain -> redirectPrimary 	= Input::get('redirectPrimary');
				$domain -> save();
				
				return Redirect::route("manage:website",$website -> websiteID);
			}
		}
		$this -> layout -> title			= $website -> primary -> hostname;
		$this -> layout -> content			= View::make("hynwmanage::.website",compact("website"));
	}
	
	public function users()
	{
		$this -> layout -> title	= _("System users");
		$users				= User\SystemUser::paginate(10);
		$this -> layout -> content	= View::make('hynwmanage::users', compact('users'));
	}
	public function user($userid,$username=NULL)
	{
		$user				= User\SystemUser::where("id","=",$userid ) -> firstOrFail();
		if( Auth::user() -> systemAdmin && Input::get("right") )
		{
			if( Input::get('add') )
			{
				$sr		= new Right\AllowedSystem;
				$sr -> user	= $user -> userID;
				$sr -> right	= Input::get("right");
				$sr -> level	= Input::get("level");
				$sr -> item	= Input::get("item");
				$sr -> save();
			}
			else
			if( Input::get('delete') )
			{
				Right\AllowedSystem::destroy( Input::get("idright") );
			}
			
			return Redirect::route("manage:user",$user -> id);
		}
		$r				= new Right\Right;
		$possibleRights			= $r -> getConstList();
		$this -> layout -> title	= $user -> username;
		$this -> layout -> content	= View::make('hynwmanage::user' , compact('user','possibleRights') );
	}
	public function system()
	{
		$this -> layout -> title	= Lang::get("hynwmanage::manage.system");
		$this -> layout -> content	= View::make('hynwmanage::system');
	}
	public function databases()
	{
		$this -> layout -> title	= Lang::get("hynwmanage::manage.databases");
		$this -> layout -> content	= View::make("hynwmanage::databases");
	}
}
