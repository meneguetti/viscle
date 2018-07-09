<?php

namespace Viscle;

/**
 * Description of Viscle
 *
 * @author Vitor Meneguetti
 */
class Viscle
{

    public static function render()
    {
        //stop xdebug trace
        $filename = xdebug_stop_trace();
        
        //parse data generated from xdebug
        $data = (new Parser)->parse($filename);
        
        //render json parsed data
        $html = (new Renderer)->render($data);
        
        return $html;
    }

    
}
