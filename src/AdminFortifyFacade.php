<?php

namespace NagSamayam\AdminFortify;

use Illuminate\Support\Facades\Facade;

/**
 * @see \NagSamayam\AdminFortify\AdminFortify
 */
class AdminFortifyFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'skeleton';
    }
}
