<?php

namespace VisualLifecycle;

class B
{

    public function __construct()
    {
        (new C())->perform();
        (new D())->perform();
    }

}
