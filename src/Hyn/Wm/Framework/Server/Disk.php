<?PHP 
namespace Hyn\Wm\Framework\Server;

class Disk
{
	private static $unit_sequence		= [ 'pb' , 'tb' , 'gb' , 'mb' , 'b' ];

	public static function used( $path="/" )
	{
		return disk_free_space( $path );
	}
	public static function toUnit( $value, $current="B", $to="MB" )
	{
		if( empty($current))		$current = "B";
		
		$passedCurrent			= false;
		foreach( static::$unit_sequence as $unit )
		{
			if( $unit == strtolower($current) )
				$passedCurrent	= true;
			
			if( $passedCurrent )
				$value		= (int) $value * 1024;
		}
		
		$value				= round($value);
		
		$reverse			= array_reverse( static::$unit_sequence );
		foreach( $reverse as $unit )
		{
			if( $unit == strtolower($to) )
				return $value;
			
			$value			= $value / 1024;
		}
		
		return round($value);
	}
}