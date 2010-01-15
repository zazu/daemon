<?php defined('SYSPATH') or die('No direct script access.');
/**
 * The daemon manager.
 *
 * @package		Daemon
 * @uses		Event
 * @author		Oliver Morgan
 * @copyright	(c) 2009 Oliver Morgan
 * @license		MIT
 */
class Daemon {
	
	/**
	 * The list of daemon instances.
	 * 
	 * @var array
	 */
	protected static $_instances = array();
	
	/**
	 * Returns an array of all daemon instances created.
	 * 
	 * @return array
	 */
	public static function all()
	{
		return self::$_instances;
	}
	
	/**
	 * Retrieves an existing instance of a daemon and createsit if it doesnt exist.
	 * 
	 * @param	string	The unique name of the daemon.
	 * @return	Daemon
	 */
	public static function instance($name = 'default')
	{
		if ( ! isset(self::$_instances[$name]))
		{
			self::$_instances[$name] = new self($name);
		}
		
		return self::$_instances[$name];
	}
	
	/**
	 * The name of the daemon.
	 * 
	 * @var	string
	 */
	public $name;
	
	/**
	 * The list of tasks associated with the daemon.
	 * 
	 * @var	array
	 */
	protected $_tasks;
	
	/**
	 * Initializes the daemon object.
	 * 
	 * @param	string	The name of the daemon.
	 * @return	void
	 */
	protected function __construct($name)
	{
		$this->name = $name;
	}
	
	/**
	 * Updates the daemon object, invoking any tasks that need updating.
	 * 
	 * @return	void
	 */
	public function update()
	{
		$time = time();
		
		foreach ($this->_tasks as $event => $interval)
		{
			$last_run = Kohana::cache($this->_cache_name($event));
			
			if ($last_run == NULL OR time() > ($last_run + $interval))
			{
				$this->_execute_event($event);
			}
		}
	}
	
	/**
	 * Adds a new task to the list of tasks performed by the daemon.
	 * 
	 * @param	string	The name of the event to invoke.
	 * @param	int	The interval between invokes.
	 * @return	Daemon
	 */
	public function task($event, $interval)
	{
		$event = is_object($event) ? $event->name : $event;
		
		$this->_tasks[$event] = $interval;
		
		return $this;
	}
	
	/**
	 * Executes the event, updating the event cache to the time of invoke.
	 * 
	 * @throws	Kohana_Exception If the cache is unwritable.
	 * @param	string	The name of the event to invoke.
	 * @return	void
	 */
	protected function _execute_event($event)
	{
		Event::instance($event)
			->execute();
		
		if ( ! Kohana::cache($this->_cache_name($event), time(), time() * 2))
		{
			throw new Kohana_Exception('Unable to write to event cache :event in daemon :daemon.', array(
				':event'	=> $event,
				':daemon'	=> $this->name
			));
		}
	}
	
	/**
	 * Returns the unique cache name.
	 * 
	 * @param	string	The name of the event.
	 * @return	string
	 */
	protected function _cache_name($event)
	{
		return 'daemon.'.$this->name.'.'.$event;
	}
	
} // End Daemon