<?PHP
namespace Hyn\Wm\Bundle\System;

use Hyn\Wm\Framework\Website\Website;
use Hyn\Wm\Framework\Server\Root;

class WebsiteJob 
{
	public function fire( $job , $data )
	{
		extract( $data );
		
		if( !$website_id )
		{
			throw new \Exception( "Need a website to execute job on" );
		}
		
		$website		= Website::findOrFail($website_id);
		
		
		// create a website as job
		if( isset($serverconfig))
		{
			if( $website -> writeServerConfig() )
			{
				Root::restartWebserver();
				$job -> delete();
			}
			else
			{
				$job -> release();
			}
		}
	}
}