<?php

namespace Viscle\Filter;

/**
 * Description of Filter
 *
 * @author Vitor Meneguetti
 */
class NamespaceBlacklist implements FilterInterface
{

    public $functionName = '';
    public $classFunctionName = '';

    /**
     * @var array Classes in an array of strings
     */
    public $classes = [''];

    public function set()
    {

        array_unshift($this->classes, $this->classFunctionName);
        array_unshift($this->classes, $this->functionName);
        
        xdebug_set_filter(
                XDEBUG_FILTER_TRACING,
                XDEBUG_NAMESPACE_BLACKLIST,
                $this->classes
        );
    }

}
