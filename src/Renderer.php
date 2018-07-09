<?php

namespace Viscle;

/**
 * Description of Renderer
 *
 * @author Vitor Meneguetti
 */
class Renderer
{

    /**
     * Generate HTML, with the visual lifecycle, through json parsed data
     * 
     * @param string $data
     * @return string
     */
    public function render(string $data)
    {
        ob_start();
        include 'template/default.php';
        return ob_get_clean();
    }

}
