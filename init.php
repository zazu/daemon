<?php defined('SYSPATH') or die('No direct script access.');
/**
 * The daemon initializer.
 *
 * @package    Daemon
 * @uses    Event
 * @author    Oliver Morgan
 * @copyright  (c) 2009 Oliver Morgan
 * @license    MIT
 * 
 * 2010-02-20 changed to config file task definition (R. Blumenthal)
 * 
 * // add this to the bootstrap file to check/run events
 * // has to be after module definition 
 * // has to be after route settings in case callbacks uses routing
 * foreach (Daemon::all() as $daemon) 
 *  $daemon->update();
 *  
 */
// get task definition from config and define events
if ( $tasks = Kohana::config( 'daemon.tasks' ))
	foreach ($tasks as $name => $task) 
	{
		Daemon::instance()->task(
			Event::instance( $name )
				->callback( $task[ 'callback' ] ), $task[ 'interval' ] );
	}

