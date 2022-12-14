# du-disk/laravel-amqp-delay-message
AMQP wrapper for Laravel and Lumen to publish and consume messages especially from RabbitMQ

[![Build Status](https://travis-ci.org/du-disk/laravel-amqp-delay-message.svg?branch=master)](https://travis-ci.org/du-disk/laravel-amqp-delay-message)
[![Latest Stable Version](https://poser.pugx.org/du-disk/laravel-amqp-delay-message/v/stable.svg)](https://packagist.org/packages/du-disk/laravel-amqp-delay-message)
[![License](https://poser.pugx.org/du-disk/laravel-amqp-delay-message/license.svg)](https://packagist.org/packages/du-disk/laravel-amqp-delay-message)

## Features
  - Advanced queue configuration
  - Add message to queues easily
  - Listen queues with useful options
  - Delay Message with x-delay headers

## Installation

### Composer

Add the following to your require part within the composer.json: 

```js
"du-disk/laravel-amqp-delay-message": "2.*" (Laravel >= 5.5)
"du-disk/laravel-amqp-delay-message": "1.*" (Laravel < 5.5)
```
```batch
$ php composer update
```

or

```
$ php composer require du-disk/laravel-amqp-delay-message
```

## Integration

### Lumen

Create a **config** folder in the root directory of your Lumen application and copy the content
from **vendor/du-disk/laravel-amqp-delay-message/config/amqp.php** to **config/amqp.php**.

Adjust the properties to your needs.

```php
return [

    'use' => 'production',

    'properties' => [

        'production' => [
            'host'                => 'localhost',
            'port'                => 5672,
            'username'            => 'username',
            'password'            => 'password',
            'vhost'               => '/',
            'exchange'            => 'amq.topic',
            'exchange_type'       => 'topic',
            'consumer_tag'        => 'consumer',
            'ssl_options'         => [], // See https://secure.php.net/manual/en/context.ssl.php
            'connect_options'     => [], // See https://github.com/php-amqplib/php-amqplib/blob/master/PhpAmqpLib/Connection/AMQPSSLConnection.php
            'queue_properties'    => ['x-ha-policy' => ['S', 'all']],
            'exchange_properties' => [],
            'timeout'             => 0
        ],

    ],

];
```

Register the Lumen Service Provider in **bootstrap/app.php**:

```php
/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
*/

//...

$app->configure('amqp');
$app->register(Brodud\Amqp\LumenServiceProvider::class);

//...
```

Add Facade Support for Lumen 5.2+

```php
//...
$app->withFacades(true, [
    'Brodud\Amqp\Facades\Amqp' => 'Amqp',
]);
//...
```


### Laravel

Open **config/app.php** and add the service provider and alias:

```php
'Brodud\Amqp\AmqpServiceProvider',
```

```php
'Amqp' => 'Brodud\Amqp\Facades\Amqp',
```


## Publishing a message

### Push message with routing key

```php
    Amqp::publish('routing-key', 'message');
```

### Push message with routing key and create queue

```php	
    Amqp::publish('routing-key', 'message' , ['queue' => 'queue-name']);
```

### Push message with routing key and overwrite properties

```php	
    Amqp::publish('routing-key', 'message' , ['exchange' => 'amq.direct']);
```


## Consuming messages

### Consume messages, acknowledge and stop when no message is left

```php
Amqp::consume('queue-name', function ($message, $resolver) {
    		
   var_dump($message->body);

   $resolver->acknowledge($message);

   $resolver->stopWhenProcessed();
        
});
```

### Consume messages forever

```php
Amqp::consume('queue-name', function ($message, $resolver) {
    		
   var_dump($message->body);

   $resolver->acknowledge($message);
        
});
```

### Consume messages, with custom settings

```php
Amqp::consume('queue-name', function ($message, $resolver) {
    		
   var_dump($message->body);

   $resolver->acknowledge($message);
      
}, [
	'timeout' => 2,
	'vhost'   => 'vhost3'
]);
```

## Fanout example

### Publishing a message

```php
\Amqp::publish('', 'message' , [
    'exchange_type' => 'fanout',
    'exchange' => 'amq.fanout',
]);
```

### Consuming messages

```php
\Amqp::consume('', function ($message, $resolver) {
    var_dump($message->body);
    $resolver->acknowledge($message);
}, [
    'routing' => '',
    'exchange' => 'amq.fanout',
    'exchange_type' => 'fanout',
    'queue_force_declare' => true,
    'queue_exclusive' => true,
    'persistent' => true // required if you want to listen forever
]);
```

## Credits

* Some concepts were used from https://github.com/mookofe/tail


## License

This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
