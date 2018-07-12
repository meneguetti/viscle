<?php

namespace Viscle\Filter;

/**
 * Description of Filter
 *
 * @author Vitor Meneguetti
 */
class NamespaceWhitelist implements FilterInterface
{

    public $functionName = ' '; //yes, a blank space
    public $classFunctionName = ' '; //yes, a blank space

    /**
     * @var array Classes in an array of strings
     */
    public $classes = [''];

    public function set()
    {
        array_unshift($this->classes, $this->classFunctionName);
        
        //function like (call_user_func, strtr) will be the first in this array
        array_unshift($this->classes, $this->functionName);
        
        xdebug_set_filter(
                XDEBUG_FILTER_TRACING,
                XDEBUG_NAMESPACE_WHITELIST,
                $this->classes
        );
    }

}
