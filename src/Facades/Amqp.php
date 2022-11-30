<?php

namespace Brodud\Amqp\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @author Björn Schmitt <code@bjoern.io>
 * @see Brodud\Amqp\Amqp
 */
class Amqp extends Facade
{

    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Amqp';
    }
}
