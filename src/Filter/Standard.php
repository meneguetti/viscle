<?php

namespace Viscle\Filter;

/**
 * Description of Default
 *
 * @author Vitor Meneguetti
 */
class Standard implements FilterInterface
{

    //classes/namespaces that will be escaped by default if any class/namespace wasn't declared
    protected $classesDefault = [
        '', //functions without class
        '', //functions of classes
        'Barryvdh',
        'Composer\\',
        'DebugBar',
        'Dotenv',
        'Illuminate\\',
        'Reflection',
        'Symfony\\',
        'Swift'
    ];

    public function set()
    {
        xdebug_set_filter(
                XDEBUG_FILTER_TRACING,
                XDEBUG_NAMESPACE_BLACKLIST,
                $this->classesDefault
        );
    }

}
