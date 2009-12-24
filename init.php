<?php defined('SYSPATH') or die('No direct script access.');
/**
 * The daemon initializer.
 *
 * @package		Daemon
 * @uses		Event
 * @author		Oliver Morgan
 * @copyright	(c) 2009 Oliver Morgan
 * @license		MIT
 */
foreach (Daemon::all() as $daemon)
{
	$daemon->update();
}