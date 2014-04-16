<?PHP
namespace Hyn\Wm\Framework\Queue;

use	Log;

use	Hyn\Wm\Framework\Website\Website,
	Hyn\Wm\Framework\Server\Config,
	Hyn\Wm\Framework\Server\Root;

class WebsiteJob
{
	/**
	*
	*	[todo] fire ssh to local system or api call and catch result
	*/
	public function fire( $job, $data = NULL )
	{
		if( is_array($data))
			extract($data);
		
		$w		= Website::findOrFail( $website_id );
		
		if( Config::write( $w ) && Root::restartWebserver() )
		{
			$job -> delete();
		}
		if( $job->attempts() < 3 )
		{
			$job -> release();
		}
		else
		{
			Log::error( "Failed to save and restart webserver for website {$w->primary->hostname}." );
			$job -> delete();
		}
		Log::info( "job for {$w->primary->hostname} ran" );
	}
}
