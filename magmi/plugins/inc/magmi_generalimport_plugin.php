<?php

namespace Magmi\Plugins\Inc;

use Magmi\Plugins\Inc\Magmi_Plugin;


abstract class Magmi_GeneralImportPlugin extends Magmi_Plugin
{
    public function beforeImport()
    {
        return true;
    }

    public function afterImport()
    {
        return true;
    }
}
