<?PHP
namespace Hyn\Wm\Framework\Server;

use DB;

class System
{
	// unified version of Hyn; only place this is recorded
	const VERSION			= "0.3";

	const LOAD_MAX_SOFT		= 5;		// soft
	const LOAD_MAX_CRIT		= 10;
	
	public function databaseSizes()
	{
		return DB::select( DB::raw( 'SELECT table_schema as `name`, sum( data_length + index_length ) as `used`, sum( data_free ) as `free`
					FROM information_schema.TABLES GROUP BY table_schema;'));
	}
	
	public function getVersion()
	{
		return static::VERSION;
	}
	public function systemLoadPercentage()
	{
		list( $now )		= $this -> systemLoad();
		return round(( $now / static::LOAD_MAX_SOFT ) * 100 );
	}
	public function systemLoad()
	{
		return sys_getloadavg();
	}
	/**
	*	Available disk space
	*	@return bytes
	*/
	public function diskSpaceAvailable()
	{
		return disk_free_space(base_path());
	}
	/**
	*	Used disk space
	*	@return bytes
	*/
	public function diskSpaceUsage()
	{
		return $this -> diskSpaceLimit() - $this -> diskSpaceAvailable();
	}
	/**
	*	Used diskspace in percentage
	*	@return percentage
	*/
	public function diskSpaceUsagePercentage()
	{
		return round(( $this -> diskSpaceUsage() / $this -> diskSpaceLimit() ) * 100 );
	}
	/**
	*	Disk space limit, max size
	*	@return bytes
	*/
	public function diskSpaceLimit()
	{
		return disk_total_space(base_path());
	}
	/**
	*	PHP memory limit
	*
	*/
	public function PHPMemoryLimit()
        {
                preg_match( "/^([0-9]+)([a-z]+)$/i" , ini_get("memory_limit") , $m );
                $limit                        = (int) $m[1];
                switch( $m[2] )
                {
                        case "G":
                                $limit        = $limit * 1024;
                        case "M":
                                $limit        = $limit * 1024;
                        case "K":
                                $limit        = $limit * 1024;
                                break;
                }
                return $limit;
        }
	/**
	*	PHP memory usage
	*
	*/
        public function PHPMemoryUsage()
        {
		return memory_get_usage();
        }
	/**
	*	PHP memory usage in percentage
	*
	*/
        public function PHPMemoryUsagePercentage()
        {
		return round(( $this -> PHPMemoryUsage() / $this -> PHPMemoryLimit() ) * 100);
        }
	/**
	*	Loads all available IP's from the ethx interfaces and stores them in our system
	*	@return <array> \Ip
	*/
	static public function IPs()
	{
		// [TODO] assuming linux machine: ubuntu at this time..
		$check				= true;
		$i				= 0;
		$ips				= array();
		do
		{
			
			$ip	= exec( "ifconfig eth{$i} | grep 'inet addr' | awk -F: '{print $2}' | awk '{print $1}'" );
			if( empty( $ip ))
			{
				$check				= false;
			}
			else
			{
				$IPRegistered	= Ip::find($ip);
				if( !$IPRegistered )
				{
					$IPRegistered			= new Ip;
					$IPRegistered -> ip		= $ip;
					$IPRegistered -> isIPv6		= (bool) strstr(":",$ip);
					$IPRegistered -> external	= !preg_match( "/^(192\.168\.)|(127\.0\.)/" , $ip );
					$IPRegistered -> save();
				}
				$ips[]	= $IPRegistered;
				$i++;
			}
		} while($check);
		
		return $ips;
	}
}