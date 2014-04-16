<?PHP
namespace Hyn\Wm\Bundle\System;

use Hyn\Wm\Framework\Website\Website;

die("should be scheduled command");
class ManagementCron
{
	public function fire( $job , $data )
	{
		// something went wrong after 5 attempts
		if( $job -> attempts() > 5 )
		{
			throw new \Exception( "Could not run management queue job for 5 times" );
		}
		// measure all website stats
		foreach( Website::all() as $website )
		{
			if( !$website -> recalculateStatistics )
			{
				throw new \Exception( "Could not calculate site statistics for site {$website->websiteID} in job: ".$job->getJobId() );
			}
		}
		// more?
		
		// wait 1 minute to append it to the queue
		$job -> release( 60 * 60 );
	}
}
