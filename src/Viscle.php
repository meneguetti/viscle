<?php

namespace Viscle;

/**
 * Description of Viscle
 *
 * @author Vitor Meneguetti
 */
class Viscle
{

    public static function capture()
    {
        ini_set('xdebug.trace_format', 1);
//        xdebug_set_filter(XDEBUG_FILTER_TRACING, XDEBUG_NAMESPACE_WHITELIST, [" ", "App"]);
        xdebug_set_filter( XDEBUG_FILTER_TRACING, XDEBUG_NAMESPACE_BLACKLIST, ["", "", "Composer\\", "Illuminate\\", "Symfony\\", "Reflection", "Dotenv", "Barryvdh", "DebugBar", "Swift"]);
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
