<?PHP
namespace Hyn\Wm\Bundle\Website;

use BaseController, Lang, Setting, View;

class Controller extends BaseController
{
	protected $layout	= "index";
	
	public function show( $template=NULL )
	{
		$this -> layout -> title	= Lang::get("hynsite::website.home_title");
		if( !is_null($template))
			$this -> layout -> template
						= View::make( $template );
	}
	public function contact()
	{
		$this -> layout -> title	= Lang::get("hynsite::website.contact_title");
		$send				= NULL;
		$validators			= false;
		if( Input::get( "contact_send" , false ))
		{
			$validators		= Validator::make(
				Input::all(),
				array( 
					"name"		=> "required|min:5",
					"message"	=> "required",
					"email"		=> "required|email",
					"humanity"	=> "same:name",
				)
			);
			$name		= Input::get("name");
			$humanity	= Input::get("humanity");
			$email		= Input::get("email");
			$message	= Input::get("message");
			
			if( !$validators -> fails() )
			{
				if( !Setting::name("mailer_sendto"))
				{
					throw new \Exception( "Cannot send mail without mailer configuration" );
				}
				
				$transport	= \Swift_SmtpTransport::newInstance( Setting::name("mailer_hostname") 
							, Setting::name("mailer_port")
							, $config['encryption'] )
					-> setUsername( Setting::name("mailer_username") )
					-> setPassword( Setting::name("mailer_password") )
					;
				$mailer		= \Swift_Mailer::newInstance( $transport );
				$message 	= \Swift_Message::newInstance()
					-> setSubject( _("New message received from: ") . Website::Current() -> PrimaryDomain -> hostname )
					-> setFrom( array( $email => $name ) )
					-> setTo( Setting("mailer_sendto") )
					-> setBody( $message )
					;
				$send		= $mailer -> send( $message );
			}
			return Redirect::route("tonklabbers:contact") -> withInput() -> withErrors($validators);
		}
		return $this -> layout -> content	= View::make("contact",compact('send'));
		
	}
}
