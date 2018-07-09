<?php

namespace VisualLifecycle;

class A
{

    public function __construct()
    {
        
    }

    public function perform()
    {
        $b = new B();
        $e = new E();
        
        return true;
    }

}
