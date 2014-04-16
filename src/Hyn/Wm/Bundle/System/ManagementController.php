<?PHP
namespace Hyn\Wm\Bundle\System;

use BaseController;

use Hyn\Wm\Framework\Website;
use Hyn\Wm\Framework\User;
# facades
use 	Validator,
	View,
	Auth,
	Lang,
	App,
	Input,
	Redirect,
	DB,
	Queue;

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
	protected function allowAccess()
	{
		return Auth::user();
	}
	protected function allowAdmin()
	{
		return false;
	}
	public function show()
	{
		$this -> layout -> title	= _("Management");
		$admin				= $this -> allowAdmin();
		$websites			= Website\Website::count();
		$domains			= Website\Domain::count();
		$systemusers			= User\SystemUser::count();
		$this -> layout -> content	= View::make("hynwmanage::dashboard",compact("websites","domains","systemusers","admin"));
	}
	
	public function websites()
	{
		$this -> layout -> title	= _("Websites");
		$admin				= $this -> allowAdmin();
		if( $this -> allowAdmin() && Input::get('hostname') )
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
				$websites	= Website\Website::join( 'domain' , 'website.id' , '=' , 'domain.websiteID' )
							-> select( DB::raw('website.*, domain.*, COUNT(domain.hostname) as domaincount' ))
							-> orderBy( 'domaincount' , $sortDirection) 
							-> paginate(10);
				break;
			case "hostname":
			default:
				$websites	= Website\Website::join( 'domain' , 'website.id' , '=' , 'domain.websiteID' )
							-> where( "domain.primary", 1 ) 
							-> orderBy( 'hostname' , $sortDirection) 
							-> paginate(10);
		}
		$this -> layout -> content	= View::make("hynwmanage::websites",compact("websites","domains","admin"));
	}
	
	public function website($websiteID)
	{
		$website			= Website\Website::findOrFail($websiteID);
		$admin				= $this -> allowAdmin();
		// connect to tertiairy db connection
		if( $this -> allowAdmin() )
		{
			$website -> connectDatabase = "website-modify";
		}
		if( $this -> allowAdmin() && Input::get('recalculateStatistics'))
		{
			$website -> recalculateStatistics;
			return Redirect::route("manage:website",$website -> id);
		}
		if( $this -> allowAdmin() && Input::get('writeServerConfig'))
		{
			$website -> writeServerConfig();
			return Redirect::route("manage:website",$website -> id);
		}
		if(  $this -> allowAdmin() && Input::get('delete') && Input::get('hostname') )
		{
			$domain				= Website\Domain::findOrFail(Input::get("hostname"));
			$domain -> delete();
			
			$website -> writeServerConfig();
			return Redirect::route("manage:website",$website -> id);
		}
		else
		if(  $this -> allowAdmin() && Input::get('add') && Input::get('hostname') )
		{
			$validator			= Validator::make(
				array( "hostname"	=> Input::get("hostname") ),
				array( "hostname"	=> "required|Between:6,253" )
			);
			if( $validator -> fails() )
			{
				return Redirect::route("manage:website",$website -> id)->withInput()->withErrors($validator);
			}
			else
			{
				$domain				= $website -> addDomain( Input::get('hostname') );
				$domain -> redirectPrimary 	= Input::get('redirectPrimary');
				$domain -> save();
				
				# now add a task to update configuration files
				$website -> writeServerConfig();
				
				return Redirect::route("manage:website",$website -> id);
			}
		}
		$this -> layout -> title			= $website -> primary -> hostname;
		$this -> layout -> content			= View::make("hynwmanage::.website",compact("website","admin"));
	}
	
	public function users()
	{
		$this -> layout -> title	= _("System users");
		$admin				= $this -> allowAdmin();
		$users				= User\SystemUser::paginate(10);
		$this -> layout -> content	= View::make('hynwmanage::users', compact('users','admin'));
	}
	public function user($userid,$username=NULL)
	{
		$user				= User\SystemUser::where("id","=",$userid ) -> firstOrFail();
		$admin				= $this -> allowAdmin();
		if( $this -> allowAdmin() && Input::get("right") )
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
		$this -> layout -> content	= View::make('hynwmanage::user' , compact('user','possibleRights','admin') );
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
