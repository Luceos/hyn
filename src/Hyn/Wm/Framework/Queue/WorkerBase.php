<?PHP
namespace Hyn\Wm\Framework\Queue;

use	Illuminate\Queue\QueueInterface;

class WorkerBase implements QueueInterface
{
	public function push( $job, $data = '', $queue = null )
	{
	
	}
	public function pushRaw($payload, $queue = null, array $options = array())
	{
	
	}
	public function later($delay, $job, $data = '', $queue = null)
	{
	
	}
	public function pop($queue = null)
	{
	
	}
}