Monaca
======

Monad for CakePHP 2

This project refers to [ircmaxell's monad-php library](https://github.com/ircmaxell/monad-php), and arranged that CakePHP can use Monad. [The entry of his blog](http://blog.ircmaxell.com/2013/07/taking-monads-to-oop-php.html) is also useful.

Usage
=====
Basic usage is similar to monad-php.
Values are "wrapped" in the monad via either the constructor: `new Identity($value)` or the `unit()` method on an existing instance: `$monad->unit($value);`

Functions can be called on the wrapped value using `bind()`:

    App::uses('Identity', 'Monaca.Lib');
    $monad = new Identity(1);
    $monad->bind(function($value) { var_dump($value); });
    // Prints int(1)

All calls to bind return a new monad instance wrapping the return value of the function.

    App::uses('Identity', 'Monaca.Lib');
    $monad = new Identity(1);
    $monad->bind(function($value) {
            return 2 * $value;
        })->bind(function($value) { 
            var_dump($value); 
        });
    // Prints int(2)

Additionally, "extracting" the raw value is supported as well (since this is PHP and not a pure functional language)...

    App::uses('Identity', 'Monaca.Lib');
    $monad = new Identity(1);
    var_dump($monad->extract());
    // Prints int(1)

Also, optional getter is useful. `get($key)`, `getOrElse($key, $default)`, `getOrCall($key, callable $function)`, `getOrThrow($key, Exception $ex)`, the first argument of these functions is to specify offset or key of array if the value might be array. If the value is non-array, use null for the first argument.

    App::uses('Identity', 'Monaca.Lib');
    $monad = new Identity(1);
    var_dump($monad->get());
    // Prints int(1)
    
    $monad = new Identity(array('foo'=>100));
    var_dump($monad->get('foo'));
    // Prints int(100)
    
    $monad = new Identity(null);
    $default = 100;
    var_dump($monad->getOrElse(null, $default));
    // Prints int(100)
    
    $monad = new Identity(null);
    $function = function() { return 1; };
    var_dump($monad->getOrCall(null, $function);
    // Prints int(1)
    
    $monad = new Identity(null);
    $exception = new Exception('That is NULL!!!');
    var_dump($monad->getOrThrow(null, $exception);
    // Throws specified exception.


Maybe Monad
===========

One of the first useful monads, is the Maybe monad. The value here is that it will only call the callback provided to `bind()` if the value it wraps is not `null`.

    App::uses('Maybe', 'Monaca.Lib');
    $monad = new Maybe(1);
    $monad->bind(function($value) { var_dump($value); });
    // prints int(1)

    $monad = new Maybe(null);
    $monad->bind(function($value) { var_dump($value); });
    // prints nothing (callback never called)...


List Monad
==========

This abstracts away the concept of a list of items (an array):

    App::uses('ListMonad', 'Monaca.Lib');
    $monad = new ListMonad(array(1, 2, 3, 4));
    $doubled = $monad->bind(function($value) { return 2 * $value; });
    var_dump($doubled->extract());
    // Prints array(2, 4, 6, 8)

Note that the passed in function gets called once per value, so it only ever deals with a single element, never the entire array...

It also works with any `Traversable` object (like iterators, etc). Just be aware that returning the new monad that's wrapped will alwyas become an array...

Hash Monad
==========

This abstracts away the concept of a hash of items (an array):

    App::uses('HashMonad', 'Monaca.Lib');
    $monad = new HashMonad(array(
      'foo' => 1, 
      'bar' => 2,
      'buz' => 3
    ));
    $doubled = $monad->bind(function($value) { return 2 * $value; });
    var_dump($doubled->extract());
    // Prints array('foo' => 2, 'bar' => 4, 'buz' => 6)

Composition
===========

These Monads can be composed together to do some really useful things:

    $monad = new ListMonad(array(1, 2, 3, null, 4));
    $newMonad = $monad->bind(function($value) { return new Maybe($value); });
    $doubled = $newMonad->bind(function($value) { return 2 * $value; });
    var_dump($doubled->extract());
    // Prints array(2, 4, 6, null, 8)

Or, what if you want to deal with multi-dimensional arrays?

    $monad = new ListMonad(array(array(1, 2), array(3, 4), array(5, 6)));
    $newMonad = $monad->bind(function($value) { return new ListMonad($value); });
    $doubled = $newMonad->bind(function($value) { return 2 * $value; });
    var_dump($doubled->extract());
    // Prints array(array(2, 4), array(6, 8), array(10, 12))

There also exist helper constants on each of the monads to get a callback to the `unit` method:

    $newMonad = $monad->bind(Maybe::UNIT);
    // Does the same thing as above 


Real World Example
==================

Imagine that you want to traverse a multi-dimensional array to create a list of values of a particular sub-key. For example:

    $posts = array(
        array("title" => "foo", "author" => array("name" => "Bob", "email" => "bob@example.com")),
        array("title" => "bar", "author" => array("name" => "Tom", "email" => "tom@example.com")),
        array("title" => "baz"),
        array("title" => "biz", "author" => array("name" => "Mark", "email" => "mark@example.com")),
    );
    
What if we wanted to extract all author names from this data set. In traditional procedural programming, you'd likely have a number of loops and conditionals. With monads, it becomes quite simple.

First, we define a function to return a particular index of an array:

    function index($key) {
        return function($array) use ($key) {
            return isset($array[$key]) ? $array[$key] : null;
        };
    }
    
Basically, this just creates a callback which will return a particular array key if it exists. With this, we have everything we need to get the list of authors.

    $postMonad = new ListMonad($posts);
    $names = $postMonad
        ->bind(Maybe::UNIT)
        ->bind(index("author"))
        ->bind(index("name"))
        ->extract();
        
Follow through and see what happens!

For CakePHP
===========
Added MonadController which has useful functions.
* retrieve session functions
* retrieve configure functions
* retrieve post/get parameters functions
* the view can retrieve viewVars

Example code:

    App::uses('MonadController', 'Monaca.Lib');
    
    class SomeController extends MonadController {
      public function index() {
        // you can retrieve session params in optional ways
        $aSession = $this->getSession('a');
        $bSession = $this->getSessionOrElse('b', 'default value');
        $cSession = $this->getSessionOrCall('c', function() { LogError('No session c'); return 'default'; });
        $dSession = $this->getSessionOrThrow('d', new Exception('Session d should not empty'));
        
        // you can retrieve configure params in optional ways
        // Also you can use getConfigOrElse, getConfigOrCall, getConfigOrThrow 
        $aConfig = $this->getConfig('a');
        
        // you can retrieve post/get params in optional ways
        // Also you can use getPostOrElse, getPostOrCall, getPostOrThrow, getQueryOrElse, getQueryOrCall, getQueryOrThrow
        $aPost = $this->getPost('a');
        $aGet = $this->getQuery('a');
        
        // you can retrieve post or get params.
        // Also: getInputOrElse, getInputOrCall, getInputOrThrow
        $aInput = $this->getInput('a');
        
        $this->set('foo', 'bar');
        $this->render('index');
      }
    }

// In view, you can retrieve viewVars in optional ways
// also: getOrCall, getOrThrow

    <h1>Example</h1>
    <ul>
      <li><?php echo $this->getOrElse('foo', 'bar'); ?></li>
    </ul>

If your Controller cannot extend MonadController, you can implement these functions by using components and view.

    App::uses('MonadComponent', 'Monaca.Controller/Component');
    App::uses('MonadView', 'Monaca.View');
    
    class FooController extends Controller {
      $components = array('Monaca.Monad');  // add Monaca.Monad
      
      public function __constructor($request = null, $response = null) {
        parent::__construct($request, $response);
        
        // Specify viewClass somewhere you want to use MonadView.
        $this->viewClass = 'Monaca.Monad';
      }
    }
