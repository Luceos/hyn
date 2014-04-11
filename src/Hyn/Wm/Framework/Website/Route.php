<?PHP 
namespace Hyn\Wm\Framework\Website;

use Eloquent;

class Route extends Eloquent
{
	protected $connection		= "website";
	protected $table		= "route";
	
	public function registerRoute()
	{
		// Set routes and routing
		// Currently routes all Get, Post, Head, Put etc requests to the same controller; perfect the Route table
		call_user_func( "\Route::{$this->method}" , $this -> path ,  array(
			'as'     	=> $this->name,
			'uses'		=> $this->extension,
		));
	}
	public function getMethodAttribute($value)
	{
		return empty($value) ? "any" : strtolower($value);
	}
} 
