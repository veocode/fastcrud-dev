<?php

namespace Veocode\FastCRUD;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Veocode\FastCRUD\Skeleton\SkeletonClass
 */
class FastCRUDFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'fastcrud';
    }
}
