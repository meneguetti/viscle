<?php

namespace Viscle;

use Viscle\Filter\FilterInterface;

/**
 * Description of Viscle
 *
 * @author Vitor Meneguetti
 */
class Viscle
{

    public static function capture(?FilterInterface $filter = null)
    {

        ini_set('xdebug.trace_format', 1);

        //if no filter was passed, then instantiate a standard filter
        if (empty($filter)) {
            $filter = new \Viscle\Filter\Standard();
        }

        //set the filter
        $filter->set();

        xdebug_start_trace(__DIR__ . DIRECTORY_SEPARATOR . 'trace');
    }

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
