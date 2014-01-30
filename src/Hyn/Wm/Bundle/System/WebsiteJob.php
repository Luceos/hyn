<?PHP
namespace Hyn\Wm\Bundle\System;

use Hyn\Wm\Framework\Website\Website;

class WebsiteJob 
{
	public function fire( $job , $data )
	{
		extract( $data );
		
		if( !$websiteid )
		{
			throw new \Exception( "Need a website to execute job on" );
		}
		
		$website		= Website::findOrFail($websiteid);
		
		
		// create a website as job
		if( isset($serverconfig))
		{
			if( $website -> writeServerConfig() )
			{
				$job -> delete();
			}
			else
			{
				$job -> release();
			}
		}
	}
}