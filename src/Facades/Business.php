<?php

namespace Fintech\Business\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Fintech\Business\Business
 */
class Business extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Fintech\Business\Business::class;
    }
}
