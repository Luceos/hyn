<?PHP
namespace Hyn\Wm\Framework\Right;

final class Right extends \SplEnum
{
	/**
	*	Constants for both website and system
	*
	*/
	/**	@allows		Everything
	*/
	const Admin		= "admin";
	
	/**	@allows		Creating of items					*/
	const Create		= "create";
	
	/**	@allows		Modifying of items					*/
	const Modify		= "modify";
	
	/**	@allows		Deletion of items					*/
	const Delete		= "delete";
	
	/**	@allows		Writing of blogs, text and other			*/
	const Write		= "write";
	
	/**	@allows		Writing of comments					*/
	const Comment		= "comment";
	
	/**	@allows		Adding of users or other existing items			*/
	const Add		= "add";
	
	/**	@allows		Disabling of users or other items without removal	*/
	const Disable		= "disable";
	
	/**	@allows		Uploading of files					*/
	const Upload		= "upload";
	
	/**	@allows		Downloading of files					*/
	const Download		= "download";
	
	
	/**
	*	Fallback
	*
	*/
	const None		= NULL;
	const __default		= NULL;
}
