<?php

namespace Magmi\Integration\Inc;

use Magmi\Inc\Properties;
use Magmi\Integration\Inc\Magmi_ProductImport_DataPump;
use Magento\Framework\App\ProductMetadataInterface;

class Magmi_DataPumpFactory
{
    protected static $_factoryprops = null;

    public static function getDataPumpInstance($pumptype)
    {
        if (self::$_factoryprops == null) {
            self::$_factoryprops = new Properties();
            self::$_factoryprops->load(dirname(__FILE__) . DIRSEP . "pumpfactory.ini");
        }
        $pumpinfo = self::$_factoryprops->get("DATAPUMPS", $pumptype, "");
        $arr = explode("::", $pumpinfo);
        if (count($arr) == 2) {
            $pumpfile = $arr[0];
            $pumpclass = $arr[1];

            try {
                //require_once(dirname(__FILE__) . DIRSEP . "$pumpfile.php");
                $pumpinst = new $pumpclass();
            } catch (Exception $e) {
                $pumpinst = null;
            }
        } else {
            echo "Invalid Pump Type";
        }
        return $pumpinst;
    }
}
