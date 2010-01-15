# Kohana 3.0 Daemon Manager

The Daemon module is used commonly as a CRON alternative. Often in shared hosting or multi-platform enviroments, the ability to use a constant CRON API is not possible. This is where this module comes in handy.

> WARNING: Using CRON is highly advisable where at all possible.

## User Guide

### Creating Deamons

Simply use the instance($name) method for creating a new daemon, where the name parameter is a unique name. If the name is not unique, the method will return the existing daemon with the same name.

	$daemon = Daemon::instance('test');

### Retrieving Daemons

As stated before, the instance method will retrieve an existin daemon if one exists with that name, otherwise it will create a new one.

	$daemon = Daemon::instance('test2');

### Adding Tasks

A task is defined as an event, with a specific interval. To add one, first retrieve the instance of the daemon you wish to add it to, and use the task() method.

	$daemon = Daemon::instance();
	$daemon->task(Event::instance('test'), 3600);

The code above will execute the test event every hour. The data and callbacks assigned to that event can be defined after having assigned it to a task. Note if the event instance already exists, you can just type;

	$daemon->task('test', 3600);

To save you a little overhead.

> NB: The interval parameter is measured in seconds.

### Updating the Daemon

The process of updating involves looping through each task, if it hasn't been processed for the time interval specified, then execute the event.

Last run times are stored in the kohana cache, and are updated automatically by the daemon.

	$daemon->update();

### Retrieving Daemons

To fetch a list of all the active daemons, use the static all() method.

	$daemons = Daemon::all();

> NB: The process will return an associative array.

This method is used internally for automatic updating of all daemons' tasks on request. See the init.php file for more details.
