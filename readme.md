# Underpin Logger Loader

Loader That assists with adding loggers to a WordPress website.

## Installation

### Using Composer

`composer require underpin/loaders/logger`

### Manually

This plugin uses a built-in autoloader, so as long as it is required _before_
Underpin, it should work as-expected.

`require_once(__DIR__ . '/underpin-logger/logger.php');`

## Setup

1. Install Underpin. See [Underpin Docs](https://www.github.com/underpin-wp/underpin)
1. Register new event types as-needed.


## Debug Logger
If you're logged in and add `underpin_debug=1` to the end of any URL, an "Underpin events" button appears in the admin bar. This provides a debugging interface that dumps out all of the items that were registered in the request, as well as any events that were logged in that request. This context can be useful, especially in production environments where debugging can be difficult.

## Event Logging Utility

This plugin includes a utility that makes it possible to log events in this plugin. These logs are written to files in
the `wp_uploads` directory, and comes equipped with a cron job that automatically purges old logs. Additinally, the method in which the logger saves data can be extended by creating a custom Writer class.

### Using the Error Logger

This plugin comes with 3 event types - `error`, `warning`, and `notice`. `error` events get written to a log,
and `warning` or `notice` only display in the on-screen console when `WP_DEBUG` is enabled. This allows you to add
a ton of `notices` and `warnings` without bogging down the system with a lot of file writing.

To write to the logger, simply chain into the `logger` method.

```php
plugin_name_replace_me()->logger()->log(
'error',
'error_code',
'error_message',
['arbitrary' => 'data', 'that' => 'is relevant', 'ref' => 1]
);
```

You can also log `WP_Error` objects directly.

```php
$error = new \WP_Error('code','Message',['data' => 'to use']);
plugin_name_replace_me()->logger()->log_wp_error('error',$error);
```

Caught exceptions can be captured, too.

```php
try{
echo 'hi';
}catch(Exception $e ){
plugin_name_replace_me()->logger()->log_exception('error', $e);
}
```

By default, the logger will return a `Log_Item` class, but you can also _return_ a `WP_Error` object, instead with `log_as_error`

```php
$wp_error_object = plugin_name_replace_me()->logger()->log_as_error(
'error',
'error_code',
'error_message',
['arbitrary' => 'data', 'that' => 'is relevant']
);

var_dump($wp_error_object); // WP_Error...
```

### Gather Errors

Sometimes, you will run several functions in a row that could potentially return an error. Gather errors will lump them
into a single `WP_Error` object, if they are actually errors.

```php
$item_1 = function_that_returns_errors();
$item_2 = another_function_that_returns_errors();

$errors = underpin()->logger()->gather_errors($item_1,$item_2);

if($errors->has_errors()){
  // Do do something if either of the items were a WP Error.
} else{
 // All clear, proceed.
}
```

### Event Types

You can register your own custom event types if you want to log things that do not fit in any of the three defaults. A
common example is when a background process runs - it would be nice to have a log of when that runs, and what happened.

To do this, you would need to create a custom event type. That is done by extending the `Event_Type` class.

```php

namespace Plugin_Name_Replace_Me\Event_Types;
/**
 * Class Background_Process
 * Error event type.
 *
 * @since 1.0.0
 *
 * @since
 * @package
 */
class Background_Process extends Event_Type {

	/**
	 * Event type
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $type = 'background_process';

	/**
	 * Writes this to the log.
	 * Set this to true to cause this event to get written to the log.
	 *
	 * @since 1.0.0
	 *
	 * @var bool
	 */
	protected $write_to_log = true;

	/**
	 * @var inheritDoc
	 */
	public $description = 'Logs when background processes run.';

	/**
	 * @var inheritDoc
	 */
	public $name = "Background Processes";
}
```

Then, you need to add this item to your logger registry. This is usually done in the `setup` method inside `Service_Locator`

```php
	/**
	 * Set up active loader classes.
	 *
	 * This is where you can add anything that needs "registered" to WordPress,
	 * such as shortcodes, rest endpoints, blocks, and cron jobs.
	 *
	 * All supported loaders come pre-packaged with this plugin, they just need un-commented here
	 * to begin using.
	 *
	 * @since 1.0.0
	 */
	protected function _setup() {
      plugin_name_replace_me()->logger()->add('background_process', '\Plugin_Name_Replace_Me\Event_Types\Background_Process');
	}
```

That's it! Now you can use the background process event type anywhere you want.

### Writers

The Event_Type uses a class, called a `Writer` to write error logs to a file. Underpin comes bundled with a file writing
system that works for most situations, but if for some reason you wanted your logger to write events in a different manner,
a good way to-do that is by overriding the `$writer_class` variable of your event type.

Let's say we wanted to receive an email every time our background process logged an event. Writers can help us do that.
First, we specify the namespace and class name of the writer that we're going to create.

```php

namespace Plugin_Name_Replace_Me\Event_Types;
/**
 * Class Background_Process
 * Error event type.
 *
 * @since 1.0.0
 *
 * @since
 * @package
 */
class Background_Process extends Event_Type {

	/**
	 * Event type
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public $type = 'background_process';

	/**
	 * Writes this to the log.
	 * Set this to true to cause this event to get written to the log.
	 *
	 * @since 1.0.0
	 *
	 * @var bool
	 */
	protected $write_to_log = true;

	/**
	 * @var inheritDoc
	 */
	public $description = 'Logs when background processes run.';

	/**
	 * @var inheritDoc
	 */
	public $name = "Background Processes";


	/**
	 * The class to instantiate when writing to the error log.
	 *
	 * @since 1.0.0
	 *
	 * @var string Namespaced instance of writer class.
	 */
	public $writer_class = 'Plugin_Name_Replace_Me\Factories\Email_Logger';
}
```

Then, we create the class in the correct directory that matches our namespace. It should extend the `Writer` class.

## Example

A very basic example could look something like this.

```php
underpin()->scripts()->add( 'test', [
	'handle'      => 'test',
	'src'         => 'path/to/script/src',
	'name'        => 'test',
	'description' => 'The description',
] );
```

Alternatively, you can extend `Event_Type` and reference the extended class directly, like so:

```php
underpin()->logger()->add('key','Namespace\To\Class');
```
